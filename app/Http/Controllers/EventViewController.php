<?php

namespace App\Http\Controllers;

use App\Attendize\Utils;
use App\Models\Affiliate;
use App\Models\Event;
use App\Models\EventAccessCodes;
use App\Models\EventStats;
use Carbon\Carbon;
use App\Models\Subscription;
use DB;
use Auth;
use Cookie;
use DateTime;
use Illuminate\Http\Request;
use Mail;
use Validator;
use Log;
use App\Models\Account;
use Config;

class EventViewController extends Controller
{
    /**
     * Show the homepage for an event
     *
     * @param Request $request
     * @param $event_id
     * @param string $slug
     * @param bool $preview
     * @return mixed
     */
    public function showEventHome(Request $request, $event_id, $slug = '', $preview = false)
    {
        
        if (empty(Auth::user())) {
            Log::debug('redirect to login page');
           
            return redirect()->to('/loginSimple');
        }
        /*else{
            $account = Account::find(Auth::user()->account_id);
            if($account->account_type == config('attendize.simple_account_type')){
                return redirect()->route('showEventListPage');
            }
        }
        */
        $event = Event::findOrFail($event_id);

        if (!Utils::userOwns($event) && !$event->is_live) {
            return view('Public.ViewEvent.EventNotLivePage');
        }

        $data = [
            'event' => $event,
            'tickets' => $event->tickets()->orderBy('sort_order', 'asc')->get(),
            'is_embedded' => 0,
        ];
        /*
         * Don't record stats if we're previewing the event page from the backend or if we own the event.
         */
        if (!$preview && !Auth::check()) {
            $event_stats = new EventStats();
            $event_stats->updateViewCount($event_id);
        }

        /*
         * See if there is an affiliate referral in the URL
         */
        if ($affiliate_ref = $request->get('ref')) {
            $affiliate_ref = preg_replace("/\W|_/", '', $affiliate_ref);

            if ($affiliate_ref) {
                $affiliate = Affiliate::firstOrNew([
                    'name'       => $request->get('ref'),
                    'event_id'   => $event_id,
                    'account_id' => $event->account_id,
                ]);

                ++$affiliate->visits;

                $affiliate->save();

                Cookie::queue('affiliate_' . $event_id, $affiliate_ref, 60 * 24 * 60);
            }
        }

        return view('Public.ViewEvent.EventPage', $data);
    }


    
     /**
     * Show the homepage for an event
     *
     * @param Request $request
     * @param $event_id
     * @param string $slug
     * @param bool $preview
     * @return mixed
     */
    public function showEventDescription(Request $request, $event_id, $slug = '', $preview = false)
    {
        $event = Event::findOrFail($event_id);
        $data = [
            'event' => $event,
            'is_embedded' => 0,
        ];
        return view('Public.ViewEvent.EventDescriptionGara', $data);
    }


      
     /**
     * Show the homepage for an event
     *
     * @param Request $request
     * @param $event_id
     * @param string $slug
     * @param bool $preview
     * @return mixed
     */
    public function showNightDescription(Request $request, $event_id, $slug = '', $preview = false)
    {
        $event = Event::findOrFail($event_id);
        $data = [
            'event' => $event,
            'is_embedded' => 0,
        ];
        return view('Public.ViewEvent.NightDescription', $data);
    }

   
    /**
     * Show preview of event homepage / used for backend previewing
     *
     * @param $event_id
     * @return mixed
     */
    public function showEventHomePreview($event_id)
    {
        return showEventHome($event_id, true);
    }

    /**
     * Sends a message to the organiser
     *
     * @param Request $request
     * @param $event_id
     * @return mixed
     */
    public function postContactOrganiser(Request $request, $event_id)
    {
        $rules = [
            'name'    => 'required',
            'email'   => ['required', 'email'],
            'message' => ['required'],
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'status'   => 'error',
                'messages' => $validator->messages()->toArray(),
            ]);
        }

        $event = Event::findOrFail($event_id);

        $data = [
            'sender_name'     => $request->get('name'),
            'sender_email'    => $request->get('email'),
            'message_content' => strip_tags($request->get('message')),
            'event'           => $event,
        ];

        Mail::send('Emails.messageReceived', $data, function ($message) use ($event, $data) {
            $message->to($event->organiser->email, $event->organiser->name)
                ->from(config('attendize.outgoing_email_noreply'), $data['sender_name'])
                ->replyTo($data['sender_email'], $data['sender_name'])
                ->subject(trans("Email.message_regarding_event", ["event"=>$event->title]));
        });

        return response()->json([
            'status'  => 'success',
            'message' => trans("Controllers.message_successfully_sent"),
        ]);
    }

    public function showCalendarIcs(Request $request, $event_id)
    {
        $event = Event::findOrFail($event_id);

        $icsContent = $event->getIcsForEvent();

        return response()->make($icsContent, 200, [
            'Content-Type' => 'application/octet-stream',
            'Content-Disposition' => 'attachment; filename="event.ics'
        ]);
    }

    /**
     * @param Request $request
     * @param $event_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function postShowHiddenTickets(Request $request, $event_id)
    {
        $event = Event::findOrFail($event_id);

        $accessCode = strtoupper(strip_tags($request->get('access_code')));
        if (!$accessCode) {
            return response()->json([
                'status' => 'error',
                'message' => trans('AccessCodes.valid_code_required'),
            ]);
        }

        $unlockedHiddenTickets = $event->tickets()
            ->where('is_hidden', true)
            ->orderBy('sort_order', 'asc')
            ->get()
            ->filter(function($ticket) use ($accessCode) {
                // Only return the hidden tickets that match the access code
                return ($ticket->event_access_codes()->where('code', $accessCode)->get()->count() > 0);
            });

        if ($unlockedHiddenTickets->count() === 0) {
            return response()->json([
                'status' => 'error',
                'message' => trans('AccessCodes.no_tickets_matched'),
            ]);
        }

        // Bump usage count
        EventAccessCodes::logUsage($event_id, $accessCode);

        return view('Public.ViewEvent.Partials.EventHiddenTicketsSelection', [
            'event' => $event,
            'tickets' => $unlockedHiddenTickets,
            'is_embedded' => 0,
        ]);
    }

    /**
     * Show the homepage
     *
     * @param Request $request
     * @param $event_id
     * @param string $slug
     * @param bool $preview
     * @return mixed
     */
    public function showEventListHome(Request $request)
    {
        $mytime = Carbon::now();
        $eventDay = '';
        $mytime2 = $mytime->toDateTimeString('Y-m-d H:i:s');
        $events = DB::table('events')
                ->whereDate('end_date', '>=', $mytime)
                ->Where('is_live', '=', 1)
                ->Where('is_night', '=', 'N')
                ->get();

        $eventsList =[];
        foreach ($events as $e1) {
            $event = Event::findOrFail($e1->id);
            $eventsList[] = $event;
            /*
            echo $e1->title;
            echo $e1->start_date;*/
            $mytime2 = Carbon::createFromFormat('Y-m-d H:i:s', $e1->start_date);
            /*echo $mytime2->format('l');*/
        }
        $data = [
            'events' => collect($eventsList)
        ];

        $datetime = DateTime::createFromFormat('YmdHi', '201308131830');
        /*echo $datetime->format('D');*/
        /*Log::debug('mi porta evet view controller su EventDancePage');*/
        return view('Public.ViewEvent.EventDancePage', $data);
    }

    
    /**
     * Show the homepage
     *
     * @param Request $request
     * @param $event_id
     * @param string $slug
     * @param bool $preview
     * @return mixed
     */
    public function showNightListHome(Request $request)
    {
        $mytime = Carbon::now();
        $eventDay = '';
        $mytime2 = $mytime->toDateTimeString('Y-m-d H:i:s');
        $events = DB::table('events')
                ->whereDate('end_date', '>=', $mytime)
                ->Where('is_live', '=', 1)
                ->Where('is_night', '=', 'Y')
                ->get();

        $eventsList =[];
        foreach ($events as $e1) {
            $event = Event::findOrFail($e1->id);
            $eventsList[] = $event;
            /*
            echo $e1->title;
            echo $e1->start_date;*/
            $mytime2 = Carbon::createFromFormat('Y-m-d H:i:s', $e1->start_date);
            /*echo $mytime2->format('l');*/
        }
        $data = [
            'events' => collect($eventsList)
        ];

        $datetime = DateTime::createFromFormat('YmdHi', '201308131830');
        /*echo $datetime->format('D');*/
        /*Log::debug('mi porta evet view controller su EventDancePage');*/
        return view('Public.ViewEvent.NightDancePage', $data);
    }

        /**
     * @param $event_id
     * @param $attendee_id
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function showAgreement(Request $request, $event_id, $slug = '', $preview = false)
    {
        Config::set('queue.default', 'sync');
        $event = Event::findOrFail($event_id);
        Log::info($event_id . ' count pdf :' .$event->pdfs()->count());
        
   
        $pdf_file = $event->pdfs()->first()->pdf_path;
       /* $pdf_file = 'user_content/event_pdfs/event_pdf-fa180de9a92f290576835ed9c271d884.pdf';*/


        return response()->download($pdf_file);
    }

         /**
     * @param $event_id
     * @param $attendee_id
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function downloadMp3(Request $request, $event_id, $slug = '', $preview = false)
    {
        $subscription_id = $request->get('subscription_id');
        Config::set('queue.default', 'sync');
        $subscription = Subscription::findOrFail($subscription_id);
        Log::info(' mp3 path :' .$subscription->mp3_path);
        $mp3_file = $subscription->mp3_path;
        if(empty($mp3_file)){
            return response()->json([
                'status' => 'error',
                'message' => trans('AccessCodes.no_tickets_matched'),
            ]);
        }
       /* $pdf_file = 'user_content/event_pdfs/event_pdf-fa180de9a92f290576835ed9c271d884.pdf';*/
        return response()->download('user_content/audio_mp3/' .$mp3_file);
    }
    

}
