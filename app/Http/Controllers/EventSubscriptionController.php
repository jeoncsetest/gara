<?php

namespace App\Http\Controllers;
use App\Attendize\Utils;
use App\Models\Affiliate;
use App\Models\Event;
use App\Models\EventAccessCodes;
use App\Models\EventStats;
use Carbon\Carbon;
use DB;
use Auth;
use Cookie;
use DateTime;
use Illuminate\Http\Request;
use Mail;
use Validator;
use Log;
use Cart;
use App\Models\Account;
use App\Models\Competition;
use App\Models\User;
use App\Models\Student;
use Illuminate\Support\Facades\Session;


class EventSubscriptionController extends Controller
{
    /**
     * add subscription from cart
     *
     * @param Request $request
     * @param $event_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function postAddSubscriptionToCart(Request $request, $event_id)
    {
        $session_usr_name = $request->session()->get('name');
        Log::debug('session_usr_name :' .$session_usr_name);
        if (empty(Auth::user()) || empty($session_usr_name)) {
            Auth::logout();
            Session::flush();
            return redirect()->to('/eventList?logged_out=yup');
            Log::debug('redirect to login page');
           /* return new RedirectResponse(route('loginSimple'));*/
            return redirect()->to('/loginSimple');
        }else{
            $account = Account::find(Auth::user()->account_id);
            if ($account->account_type == config('attendize.default_account_type')) {
                return redirect()->route('showSelectOrganiser');
            }elseif($account->account_type == config('attendize.ticket_account_type')){
                return redirect()->route('showEventListPage');
            }
        }
        Log::debug('event_id : '  .$event_id);
        $current_event_id = $request->session()->get('current_event_id');
        if (!empty($current_event_id) && $current_event_id != $event_id) {
            return response()->json([
                'status'  => 'error',
                'cartCount' => Cart::count(),
                'message' => "finire prima iscrizione del'evento " .$event_id,
            ]);
        }
       /* $event = Event::scope()->findOrFail($event_id);*/
       $user = User::find(Auth::user()->id);
       $student_id = null;
       if(!empty($user->Student)){
        $student_id = $user->Student->id;
       }
        Log::debug('event_id : '  .$event_id);
        $competition_id = $request->get('competition_id');
        $competitionToAdd = Competition::find($competition_id);
        Log::debug('competition_id : '  .$competition_id);
        $category = $request->get('category');
        Log::debug('category : '  .$category);
        $type = $request->get('type');
        Log::debug('type : '  .$type);
        $level = $request->get('level');
        Log::debug('level : '  .$level);
        $title = $request->get('title');
        /** check if the competitor is already registered for this competiton**/
        /** if the competitor is alredy registered for this competition show an error message with red color **/
       
       /*
        return response()->json([
            'status'  => 'error',
            'message' => trans("Controllers.fill_email_and_password"),
        ]);
        */
        $cartCount =  ('' .Cart::count()) + 1;
        $cartId = $competition_id . '-' .$cartCount;
        $name = new \stdClass();
        $name->mp3 = '';
        $name->participants = [];
        Cart::add($cartCount, json_encode($name), 1, $request->get('price'), 1,
         ['competition_id'=>$competition_id,'event_id' => $event_id,
          'competition_title' => $title, 'student_id' => $student_id,
          'type' => $type,'category' => $category,'level' => $level, 'mp3_upload' =>$competitionToAdd->mp3_upload ]);
          
        $nrd = DB::delete('DELETE FROM shoppingcart WHERE IDENTIFIER =' .Auth::user()->id);
        Log::debug('cart reomved -> num row :' .$nrd);
        Cart::store(Auth::user()->id);

        $request->session()->put('current_event_id', $event_id);
        /*
        $rules = [
            'social_share_text'      => ['max:3000'],
            'social_show_facebook'   => ['boolean'],
            'social_show_twitter'    => ['boolean'],
            'social_show_linkedin'   => ['boolean'],
            'social_show_email'      => ['boolean'],
            'social_show_googleplus' => ['boolean'],
        ];

        $messages = [
            'social_share_text.max' => 'Please keep the text under 3000 characters.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return response()->json([
                'status'   => 'error',
                'messages' => $validator->messages()->toArray(),
            ]);
        }
        

        $event->social_share_text = $request->get('social_share_text');competitions
        $event->social_show_facebook = $request->get('social_show_facebook');
        $event->social_show_linkedin = $request->get('social_show_linkedin');
        $event->social_show_twitter = $request->get('social_show_twitter');
        $event->social_show_email = $request->get('social_show_email');
        $event->social_show_googleplus = $request->get('social_show_googleplus');
        $event->social_show_whatsapp = $request->get('social_show_whatsapp');
        $event->save();
        */

        return response()->json([
            'status'  => 'success',
            'cartCount' => Cart::count(),
            'message' => trans("Competition.cart_item_added_successfully", ['competitionTitle' =>$title]),
        ]);

    }

    /**
     * remove subscription from cart
     *
     * @param Request $request
     * @param $event_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function postRemoveSubscriptionFromCart(Request $request)
    {
        $session_usr_name = $request->session()->get('name');
        Log::debug('session_usr_name :' .$session_usr_name);
        if (empty(Auth::user()) || empty($session_usr_name)) {
            Auth::logout();
            Session::flush();
            return redirect()->to('/eventList?logged_out=yup');
        }
        $rowIdCart = $request->get('rowIdCart');
        $cart = Cart::get($rowIdCart);
        
        Cart::remove($rowIdCart);
        $nrd = DB::delete('DELETE FROM shoppingcart WHERE IDENTIFIER =' .Auth::user()->id);
        Log::debug('cart reomved -> num row :' .$nrd);
    
        Cart::store(Auth::user()->id);
        $eventId = $request->get('event_id');
        Log::debug('event_id :' .$eventId);
        $event = Event::findOrFail($eventId);
        Log::debug('event_id :' .$event->description);

        $currency = $event->currency;
        Log::debug('$currency :' .$currency);
        $moneyee = money(Cart::subtotal(), $currency);
        if( Cart::count() == 0){
            $request->session()->forget('current_event_id'); 
        }
        return response()->json([
            'status'  => 'success',
            'cartCount' => Cart::count(),
            'total' => $moneyee,
            'message' => trans("Competition.cart_item_removed_successfully", ['competitionTitle' =>($cart->options->has('competition_title') ? $cart->options->competition_title : '')]),
        ]);

    }

 /**
     * eliminare ballerino al carello
     *
     * @param Request $request
     * @param $event_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function postRemoveBallerinoDalCarello(Request $request)
    {
        $session_usr_name = $request->session()->get('name');
        Log::debug('session_usr_name :' .$session_usr_name);
        if (empty(Auth::user()) || empty($session_usr_name)) {
            Auth::logout();
            Session::flush();
            return redirect()->to('/eventList?logged_out=yup');
        }
        $rowIdCart = $request->get('item_row_id');
        $idBallerino = $request->get('idBallerino');
        Log::debug('rowIdCart :' .$rowIdCart);
        Log::debug('idBallerino :' .$idBallerino);
        if(empty($idBallerino) || empty($rowIdCart)){
            return response()->json([
                'status'  => 'error',
                'message' => 'Sì è verificato un errore',
            ]);
    
        }

        $cart = Cart::get($rowIdCart);
        if(empty($cart)){
            return response()->json([
                'status'  => 'error',
                'message' => 'Sì è verificato un errore',
            ]);
    
        }

        $data = json_decode($cart->name, true);
        Log::debug($data);

      
        $i=0;
        
        foreach($data['participants'] as $key => $element) {
            if ($element["id"] == $idBallerino) {
                unset($data['participants'][$key]);
           }
        }

        Cart::update($rowIdCart, ['name' => json_encode($data)]);
    
        $nrd = DB::delete('DELETE FROM shoppingcart WHERE IDENTIFIER =' .Auth::user()->id);
        Cart::store(Auth::user()->id);
        return response()->json([
            'status'  => 'success',
            'message' => trans("Competition.cart_item_removed_successfully", ['competitionTitle' =>($cart->options->has('competition_title') ? $cart->options->competition_title : '')]),
        ]);

    }

    /**
     * aggiungere ballerino al carello
     *
     * @param Request $request
     * @param $event_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function postaddBallerinoAlCarello(Request $request)
    {
        $session_usr_name = $request->session()->get('name');
        Log::debug('session_usr_name :' .$session_usr_name);
        if (empty(Auth::user()) || empty($session_usr_name)) {
            Auth::logout();
            Session::flush();
            return redirect()->to('/eventList?logged_out=yup');
        }
        $rowIdCart = $request->get('item_row_id');
        $idBallerino = $request->get('idBallerino');
        Log::debug('rowIdCart :' .$rowIdCart);
        Log::debug('idBallerino :' .$idBallerino);
        if(empty($idBallerino) || empty($rowIdCart)){
            return response()->json([
                'status'  => 'error',
                'message' => 'Sì è verificato un errore',
            ]);
    
        }

        $ballerino = Student::find($idBallerino);
        $cart = Cart::get($rowIdCart);
        if(empty($ballerino) || empty($cart)){
            return response()->json([
                'status'  => 'error',
                'message' => 'Sì è verificato un errore',
            ]);
    
        }

        $data = json_decode($cart->name, true);
        Log::debug($data);

        
        $type = $cart->options->has('type') ? $cart->options->type : '';
        Log::debug('type : '.$type);
        Log::debug('count:' .count($data['participants']));
        if(($type == 'S' && count($data['participants'])>0) || ($type == 'D' && count($data['participants']) >2)){
            return response()->json([
                'status'  => 'error',
                'message' => 'non puoì aggiungere altro ballerino per questa gara',
            ]);
        }
       
        foreach($data['participants'] as $key => $element) {
            if ($element["id"] == $idBallerino) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'il ballerino è già associato',
                ]);
           }
        }
 
        $participant = new \stdClass();
        $participant->id = $ballerino->id;
        $participant->name = $ballerino->name;;
        $participant->surname = $ballerino->surname;;
        $participants = $participant;
       
        

        $data['participants'][] =  json_decode(json_encode($participant), true); 
      

        Cart::update($rowIdCart, ['name' => json_encode($data)]);
    
        $nrd = DB::delete('DELETE FROM shoppingcart WHERE IDENTIFIER =' .Auth::user()->id);
        Cart::store(Auth::user()->id);
        
        $getData = json_decode($cart->name, true);
        Log::debug($data);

        
        $getType = $cart->options->has('type') ? $cart->options->type : '';
        Log::debug('type : '.$getType);
        Log::debug('count:' .count($getData['participants']));
        if(($getType == 'S' && count($getData['participants'])>0) || ($getType == 'D' && count($getData['participants']) >=2)){
           
              $check=0;
           
        }
        else{
            $check=1;
        }
        return response()->json([
            'status'  => 'success',
            'studentId' => $ballerino->id,
            'studentName' => $ballerino->name,
            'studentSurname' => $ballerino->surname,
            'message' => trans("Competition.cart_item_removed_successfully", ['competitionTitle' =>($cart->options->has('competition_title') ? $cart->options->competition_title : '')]),
            'check'=>$check
        ]);

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
    public function showEventHome(Request $request, $event_id, $slug = '', $preview = false)
    {
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
     * Show the homepage for subscritpion
     *
     * @param Request $request
     * @param $event_id
     * @param string $subs_slug
     * @param bool $preview
     * @return mixed
     */
    public function showSubscriptionPage(Request $request, $event_id, $slug = '', $preview = false)
    {
        if (empty(Auth::user())) {
            Log::debug('redirect to login page');
           /* return new RedirectResponse(route('loginSimple'));*/
            return redirect()->to('/loginSimple');
        }else{
            $account = Account::find(Auth::user()->account_id);
            if ($account->account_type == config('attendize.default_account_type')) {
                return redirect()->route('showSelectOrganiser');
            }elseif($account->account_type == config('attendize.ticket_account_type')){
                return redirect()->route('showEventListPage');
            }
        }
        $event = Event::findOrFail($event_id);
        $disciplineId = $request->get('discipline_id');
        $competitions = null;
        if(empty($disciplineId)){
            $competitions = $event->competitions()
            ->orderBy('id', 'asc')->get();
        }else{
            $competitions = $event->competitions()
            ->where('competitions.discipline_id', '=', $disciplineId)
            ->orderBy('id', 'asc')->get();
        }
       
        Log::debug('$disciplineId: ' .$disciplineId);
        if (!Utils::userOwns($event) && !$event->is_live) {
            return view('Public.ViewEvent.EventNotLivePage');
        }

        $data = [
            'event' => $event,
            'competitions' => $competitions,
            'disciplines' => DB::table('disciplines')
            ->join('competitions', 'disciplines.id', '=', 'competitions.discipline_id')
            ->distinct()->select('disciplines.id', 'disciplines.discipline_name', 'disciplines.discipline_desc')->where('competitions.event_id', '=', $event_id)
            ->get(),
            'is_embedded' => 0
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
       
        return view('Public.ViewEvent.EventDanceSubsrciptionPage', $data);
    }


     /**
     * Show the homepage for subscritpion
     *
     * @param Request $request
     * @param $event_id
     * @param string $subs_slug
     * @param bool $preview
     * @return mixed
     */
    public function showStudentsPage(Request $request, $event_id, $slug = '', $preview = false)
    {
        if (empty(Auth::user())) {
            Log::debug('redirect to login page');
           /* return new RedirectResponse(route('loginSimple'));*/
            return redirect()->to('/loginSimple');
        }else{
            $account = Account::find(Auth::user()->account_id);
            if ($account->account_type == config('attendize.default_account_type')) {
                return redirect()->route('showSelectOrganiser');
            }elseif($account->account_type == config('attendize.ticket_account_type')){
                return redirect()->route('showEventListPage');
            }
        }
        $event = Event::findOrFail($event_id);
        $eventType = $request->get('eventType');
        Log::debug('$eventType: ' .$eventType);
        if (!Utils::userOwns($event) && !$event->is_live) {
            return view('Public.ViewEvent.EventNotLivePage');
        }

        $data = [
            'event' => $event,
            'competitions' => $event->competitions()
            ->where('competitions.type', 'like', $eventType . '%')
            ->orderBy('id', 'asc')->get(),
            'is_embedded' => 0
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

        return view('Public.ViewEvent.StudentsPage', $data);
    }


    /**
     * Show the homepage for subscritpion
     *
     * @param Request $request
     * @param $event_id
     * @param string $subs_slug
     * @param bool $preview
     * @return mixed
     */
    public function showCart(Request $request)
    {
        Log::debug('school:'  .session()->get('school'));

        $event = Event::findOrFail($request->get('event_id'));
        if (empty(Auth::user())) {
            Log::debug('redirect to login page');
           /* return new RedirectResponse(route('loginSimple'));*/
            return redirect()->to('/loginSimple');
        }else{
            $account = Account::find(Auth::user()->account_id);
            if ($account->account_type == config('attendize.default_account_type')) {
                return redirect()->route('showSelectOrganiser');
            }elseif($account->account_type == config('attendize.ticket_account_type')){
                return redirect()->route('showEventListPage');
            }
        }

        $user = Auth::user();
        $school = Auth::user()->school;
        $data = null;
        if(empty($school)){
            $students = DB::table('students')
                    ->get();
            Log::debug('total:' . $students->count());
                    $data = [
                        'students' => $students,
                        'event'   => $event
                    ];
        }else{
                session()->put('school', $school->name);
              
                Log::debug('total students of the school :' .$school->name .' are ' .$school->students->count());
                $data = [
                'students' => $school->students,
                'event'   => $event
            ];
        }
        session()->put('name', Auth::user()->first_name );
        Log::debug('name:'  .session()->get('name'));
        session()->put('surname', Auth::user()->last_name);
        session()->put('account_type', $account->account_type);
        return view('Public.ViewEvent.EventSubscriptionCartPage', $data);
    }
    
    /**
     * Show preview of event homepage / used for backend previewing
     *
     * @param $event_id
     * @return mixed
     * 
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
                ->get();

                $data = [
                    'events' => $events
                ];
        foreach ($events as $e1) {
            echo $e1->title;
            echo $e1->start_date;
            $mytime2 = Carbon::createFromFormat('Y-m-d H:i:s', $e1->start_date);
            echo $mytime2->format('l');
        }

        $datetime = DateTime::createFromFormat('YmdHi', '201308131830');
        echo $datetime->format('D');
        Log::debug('mi porta su EventDancePage');
        return view('Public.ViewEvent.EventDancePage', $data);
    }

/**
     * upload mpo3
     */
    public function postUploadMp3(Request $request)
    {
        
        Log::debug('entered');
        $data = null;
        $event_id = $request->get('event_id');
        $itemRowId = $request->get('item_row_id');
        Log::debug('event-ID:' .$event_id .' item_row_id:' . $itemRowId);

        $filename = '';
            $fileNameInput = 'mp3_file_' .$itemRowId;
            Log::debug('$filename :' .$fileNameInput);
            if ($request->hasFile($fileNameInput)) {
                Log::debug('file' .$request->file($fileNameInput));
                $path = public_path() . '/' . config('attendize.audio_mp3_path');
                $filename = 'mp3_file-' . md5(time() . $event_id) . '.' . strtolower($request->file($fileNameInput)->getClientOriginalExtension());
                Log::debug(' $filename :' .$filename);
                $file_full_path = $path . '/' . $filename;
                Log::debug(' $file_full_path :' .$file_full_path);
                
                $request->file($fileNameInput)->move($path, $filename);
    
                
                \Storage::put(config('attendize.audio_mp3_path') . '/' . $filename, file_get_contents($file_full_path));
    
                $cart = Cart::get($itemRowId);

                $data = json_decode($cart->name, true);
                Log::debug($data);

                $data['mp3'] = $filename;

                Cart::update($itemRowId, ['name' => json_encode($data)]);
               
                $nrd = DB::delete('DELETE FROM shoppingcart WHERE IDENTIFIER =' .Auth::user()->id);
                Cart::store(Auth::user()->id);
            }

        return response()->json([
            'status'  => 'success',
            'itemRowId' => $itemRowId,
            'message' => trans("Competition.mp3_uploaded_successfully"),
            'mp3'=>$data['mp3']
        ]);
    }


      public function RemoveMp3($id)
    {
        Log::debug('remove mp3 uploaded');
        $itemRowId = $id;
        Log::debug('itemRowId : ' . $itemRowId);
        $cart = Cart::get($itemRowId);

        $data = json_decode($cart->name, true);
        Log::debug($data);

        $data['mp3'] = "";

        Cart::update($itemRowId, ['name' => json_encode($data)]);
       
        $nrd = DB::delete('DELETE FROM shoppingcart WHERE IDENTIFIER =' .Auth::user()->id);
        Cart::store(Auth::user()->id);

        return response()->json([
            'status'  => 'success',
            'itemRowId' => $itemRowId,
            'message' => trans("Competition.mp3_uploaded_successfully"),
        ]);
    }
    /**
     * upload mpo3
     */
    public function postRemoveMp3(Request $request)
    {
        Log::debug('remove mp3 uploaded');
        $itemRowId = $request->get('item_row_id');
        Log::debug('itemRowId : ' . $itemRowId);
        $cart = Cart::get($itemRowId);

        $data = json_decode($cart->name, true);
        Log::debug($data);

        $data['mp3'] = "";

        Cart::update($itemRowId, ['name' => json_encode($data)]);
       
        $nrd = DB::delete('DELETE FROM shoppingcart WHERE IDENTIFIER =' .Auth::user()->id);
        Cart::store(Auth::user()->id);

        return response()->json([
            'status'  => 'success',
            'itemRowId' => $itemRowId,
            'message' => trans("Competition.mp3_uploaded_successfully"),
        ]);
    }
             /**
     * @param $event_id
     * @param $subscription_id
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function downloadMp3(Request $request, $event_id, $subscription_id, $slug = '', $preview = false)
    {
        Log::info(' mp3 path :' .$subscription_id);
        Config::set('queue.default', 'sync');
        $subscription = Subscription::findOrFail($subscription_id);
        Log::info(' mp3 path :' .$subscription->mp3_path);
        
   
        $mp3_file = $subscription->mp3_path;
       /* $pdf_file = 'user_content/event_pdfs/event_pdf-fa180de9a92f290576835ed9c271d884.pdf';*/


        return response()->download($mp3_file);
    }
}
