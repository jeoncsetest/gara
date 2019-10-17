<?php

namespace App\Http\Controllers;

use App\Events\OrderCompletedEvent;
use App\Models\Account;
use App\Models\AccountPaymentGateway;
use App\Models\Affiliate;
use App\Models\Attendee;
use App\Models\Event;
use App\Models\EventStats;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\PaymentGateway;
use App\Models\QuestionAnswer;
use App\Models\ReservedTickets;
use App\Models\reservedCompetitions;
use App\Models\Competition;
use App\Models\Subscription;
use App\Models\Student;
use App\Models\Participant;
use App\Models\Ticket;
use App\Services\Order as OrderService;
use Carbon\Carbon;
use Cookie;
use DB;
use Illuminate\Http\Request;
use Log;
use Omnipay;
use PDF;
use PhpSpec\Exception\Exception;
use Validator;
use Auth;
use Cart;
use Illuminate\Support\Facades\Session;
class EventCheckoutController extends Controller
{
    /**
     * Is the checkout in an embedded Iframe?
     *
     * @var bool
     */
    protected $is_embedded;

    /**
     * EventCheckoutController constructor.
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        /*
         * See if the checkout is being called from an embedded iframe.
         */
        $this->is_embedded = $request->get('is_embedded') == '1';
    }

    /**
     * Validate a ticket request. If successful reserve the tickets and redirect to checkout
     *
     * @param Request $request
     * @param $event_id
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function postValidateTickets(Request $request, $event_id)
    {
        /*
         * Order expires after X min
         */
        $session_usr_name = $request->session()->get('name');
        Log::debug('session_usr_name :' .$session_usr_name);
        if (empty(Auth::user()) || empty($session_usr_name)) {
            Auth::logout();
            Session::flush();
            return redirect()->to('/eventList?logged_out=yup');
        }
        /*else{
            $account = Account::find(Auth::user()->account_id);
            if ($account->account_type == config('attendize.default_account_type')) {
                return redirect()->route('showSelectOrganiser');
            }elseif($account->account_type == config('attendize.simple_account_type')){
                return redirect()->route('showEventListPage');
            }
        }*/
        Log::debug('stripe token' .$request->get('stripeToken'));
        $order_expires_time = Carbon::now()->addMinutes(config('attendize.checkout_timeout_after'));

        $event = Event::findOrFail($event_id);

        if (!$request->has('tickets')) {
            return response()->json([
                'status'  => 'error',
                'message' => 'No tickets selected',
            ]);
        }

        $ticket_ids = $request->get('tickets');

        /*
         * Remove any tickets the user has reserved
         */
        ReservedTickets::where('session_id', '=', session()->getId())->delete();

        /*
         * Go though the selected tickets and check if they're available
         * , tot up the price and reserve them to prevent over selling.
         */

        $validation_rules = [];
        $validation_messages = [];
        $tickets = [];
        $order_total = 0;
        $total_ticket_quantity = 0;
        $booking_fee = 0;
        $organiser_booking_fee = 0;
        $quantity_available_validation_rules = [];

        foreach ($ticket_ids as $ticket_id) {
            $current_ticket_quantity = (int)$request->get('ticket_' . $ticket_id);

            if ($current_ticket_quantity < 1) {
                continue;
            }

            $total_ticket_quantity = $total_ticket_quantity + $current_ticket_quantity;
            $ticket = Ticket::find($ticket_id);
            $ticket_quantity_remaining = $ticket->quantity_remaining;
            $max_per_person = min($ticket_quantity_remaining, $ticket->max_per_person);

            $quantity_available_validation_rules['ticket_' . $ticket_id] = [
                'numeric',
                'min:' . $ticket->min_per_person,
                'max:' . $max_per_person
            ];

            $quantity_available_validation_messages = [
                'ticket_' . $ticket_id . '.max' => 'The maximum number of tickets you can register is ' . $ticket_quantity_remaining,
                'ticket_' . $ticket_id . '.min' => 'You must select at least ' . $ticket->min_per_person . ' tickets.',
            ];

            $validator = Validator::make(['ticket_' . $ticket_id => (int)$request->get('ticket_' . $ticket_id)],
                $quantity_available_validation_rules, $quantity_available_validation_messages);

            if ($validator->fails()) {
                return response()->json([
                    'status'   => 'error',
                    'messages' => $validator->messages()->toArray(),
                ]);
            }

            $order_total = $order_total + ($current_ticket_quantity * $ticket->price);
            $booking_fee = $booking_fee + ($current_ticket_quantity * $ticket->booking_fee);
            $organiser_booking_fee = $organiser_booking_fee + ($current_ticket_quantity * $ticket->organiser_booking_fee);

            $tickets[] = [
                'ticket'                => $ticket,
                'qty'                   => $current_ticket_quantity,
                'price'                 => ($current_ticket_quantity * $ticket->price),
                'booking_fee'           => ($current_ticket_quantity * $ticket->booking_fee),
                'organiser_booking_fee' => ($current_ticket_quantity * $ticket->organiser_booking_fee),
                'full_price'            => $ticket->price + $ticket->total_booking_fee,
            ];

            /*
             * Reserve the tickets for X amount of minutes
             */
            $reservedTickets = new ReservedTickets();
            $reservedTickets->ticket_id = $ticket_id;
            $reservedTickets->event_id = $event_id;
            $reservedTickets->quantity_reserved = $current_ticket_quantity;
            $reservedTickets->expires = $order_expires_time;
            $reservedTickets->session_id = session()->getId();
            $reservedTickets->save();

            for ($i = 0; $i < $current_ticket_quantity; $i++) {
                /*
                 * Create our validation rules here
                 */
                $validation_rules['ticket_holder_first_name.' . $i . '.' . $ticket_id] = ['required'];
                $validation_rules['ticket_holder_last_name.' . $i . '.' . $ticket_id] = ['required'];
                $validation_rules['ticket_holder_email.' . $i . '.' . $ticket_id] = ['required', 'email'];

                $validation_messages['ticket_holder_first_name.' . $i . '.' . $ticket_id . '.required'] = 'Ticket holder ' . ($i + 1) . '\'s first name is required';
                $validation_messages['ticket_holder_last_name.' . $i . '.' . $ticket_id . '.required'] = 'Ticket holder ' . ($i + 1) . '\'s last name is required';
                $validation_messages['ticket_holder_email.' . $i . '.' . $ticket_id . '.required'] = 'Ticket holder ' . ($i + 1) . '\'s email is required';
                $validation_messages['ticket_holder_email.' . $i . '.' . $ticket_id . '.email'] = 'Ticket holder ' . ($i + 1) . '\'s email appears to be invalid';

                /*
                 * Validation rules for custom questions
                 */
                foreach ($ticket->questions as $question) {
                    if ($question->is_required && $question->is_enabled) {
                        $validation_rules['ticket_holder_questions.' . $ticket_id . '.' . $i . '.' . $question->id] = ['required'];
                        $validation_messages['ticket_holder_questions.' . $ticket_id . '.' . $i . '.' . $question->id . '.required'] = "This question is required";
                    }
                }
            }
        }

        if (empty($tickets)) {
            return response()->json([
                'status'  => 'error',
                'message' => 'No tickets selected.',
            ]);
        }

        if (config('attendize.enable_dummy_payment_gateway') == TRUE) {
            $activeAccountPaymentGateway = new AccountPaymentGateway();
            $activeAccountPaymentGateway->fill(['payment_gateway_id' => config('attendize.payment_gateway_dummy')]);
            $paymentGateway = $activeAccountPaymentGateway;
        } else {
            $activeAccountPaymentGateway = $event->account->getGateway($event->account->payment_gateway_id);
            //if no payment gateway configured and no offline pay, don't go to the next step and show user error
            if (empty($activeAccountPaymentGateway) && !$event->enable_offline_payments) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'No payment gateway configured',
                ]);
            }
            $paymentGateway = $activeAccountPaymentGateway ? $activeAccountPaymentGateway->payment_gateway : false;
        }

        /*
         * The 'ticket_order_{event_id}' session stores everything we need to complete the transaction.
         */
        session()->put('ticket_order_' . $event->id, [
            'validation_rules'        => $validation_rules,
            'validation_messages'     => $validation_messages,
            'event_id'                => $event->id,
            'tickets'                 => $tickets,
            'total_ticket_quantity'   => $total_ticket_quantity,
            'order_started'           => time(),
            'expires'                 => $order_expires_time,
            'reserved_tickets_id'     => $reservedTickets->id,
            'order_total'             => $order_total,
            'booking_fee'             => $booking_fee,
            'organiser_booking_fee'   => $organiser_booking_fee,
            'total_booking_fee'       => $booking_fee + $organiser_booking_fee,
            'order_requires_payment'  => (ceil($order_total) == 0) ? false : true,
            'account_id'              => $event->account->id,
            'affiliate_referral'      => Cookie::get('affiliate_' . $event_id),
            'account_payment_gateway' => $activeAccountPaymentGateway,
            'payment_gateway'         => $paymentGateway
        ]);

        /*
         * If we're this far assume everything is OK and redirect them
         * to the the checkout page.
         */
        Log::debug('stripe token' .$request->get('stripeToken'));
        /*
        if ($request->ajax()) {
            return response()->json([
                'status'      => 'success',
                'redirectUrl' => route('showEventCheckout', [
                        'event_id'    => $event_id,
                        'is_embedded' => $this->is_embedded,
                    ]) . '#order_form',
            ]);
        }*/

        return redirect()->route('showEventCheckout', [
            'event_id'    => $event_id,
            'is_embedded' => $this->is_embedded,
            '#order_form',]);

        /*
         * Maybe display something prettier than this?
         */
        exit('Please enable Javascript in your browser.');
    }

    /**
     * Validate a competition request. If successful reserve the competitions and redirect to checkout
     *
     * @param Request $request
     * @param $event_id
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function postValidateCartItems(Request $request, $event_id)
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

        $user = Auth::user();
        /*
         * Order expires after X min
         */
        $order_expires_time = Carbon::now()->addMinutes(config('attendize.checkout_timeout_after'));

        $event = Event::findOrFail($event_id);
        
        $cartIds = $request->get('cartIds');
        Log::debug('num cart items :' . count($cartIds));
        if (!$request->has('cartIds')) {
            return response()->json([
                'status'  => 'error',
                'message' => 'No competition selected',
            ]);
        }

        /*
         * Remove any competitions the user has reserved
         */
        ReservedCompetitions::where('session_id', '=', session()->getId())->delete();

        /*
         * Go though the selected competitions and check if they're available
         * , tot up the price and reserve them to prevent over selling.
         */

        $validation_rules = [];
        $validation_messages = [];

        /** new validations for competition type, mp3, participants **/
        $competition_validation_rules = [];
        $competition_validation_messages = [];


        $competitions = [];
        $order_total = 0;
        $total_competition_quantity = 0;
        $booking_fee = 0;
        $organiser_booking_fee = 0;
        $quantity_available_validation_rules = [];
        $min_per_person = 1;


        

        if(Cart::count()>0){
            foreach(Cart::content() as $cartItem){
            $cardId = $cartItem->id;
            
            /* start upload mp3 */
            $filename = '';
            $fileNameInput = $request->get('mp3_file_name_' .$cardId);
            Log::debug('$filename :' .$fileNameInput);
            Log::debug('file' .$request->file($fileNameInput));
            if ($request->hasFile($fileNameInput)) {
                Log::debug('file' .$request->file($fileNameInput));
                $path = public_path() . '/' . config('attendize.audio_mp3_path');
                $filename = 'mp3_file-' . md5(time() . $event_id) . '.' . strtolower($request->file($fileNameInput)->getClientOriginalExtension());
                Log::debug(' $filename :' .$filename);
                $file_full_path = $path . '/' . $filename;
                Log::debug(' $file_full_path :' .$file_full_path);
                
                $request->file($fileNameInput)->move($path, $filename);
    
                
                \Storage::put(config('attendize.audio_mp3_path') . '/' . $filename, file_get_contents($file_full_path));
    
                /*
                $eventImage = EventImage::createNew();
                $eventImage->image_path = config('attendize.audio_mp3_path') . '/' . $filename;
                $eventImage->event_id = $event->id;
                $eventImage->save();*/
            }
            /* start upload mp3 */

            /* foreach ($cartIds as $cardId) {*/
            Log::debug('item id:' .$cardId);
            $current_competition_quantity = (int)$request->get('qty_'.$cardId);
            Log::debug('qty: ' .$current_competition_quantity);
            if ($current_competition_quantity < 1) {
                continue;
            }
            $participants = $request->get('participants_' . $cardId);

            $groupName = $request->get('grp_name_' . $cardId);

            $rowId = $cartItem->rowId;
            Log::debug('row id of the cart item: ' .$rowId .' total participants : '. count( $participants));
            Log::debug('first participants : '. $participants[0]);

            $total_competition_quantity = $total_competition_quantity + $current_competition_quantity;
            /** prendere competition id dal carello  */
            $comptId = (int)$request->get('competitionId_'.$cardId); ;
            $competition = Competition::find($comptId);
            $competition_quantity_remaining = $competition->max_competitors-$competition->total_subscription;
            /*$max_per_person = min($competition_quantity_remaining, $competition->max_competitors);*/

            $quantity_available_validation_rules['qty_'.$cardId] = [
                'numeric',
                'min:' . $min_per_person,
                'max:' . $competition_quantity_remaining
            ];

            $quantity_available_validation_messages = [
                'qty_'.$cardId . '.max' => 'The maximum number of competitions you can register is ' . $competition_quantity_remaining,
                'qty_'.$cardId . '.min' => 'You must select at least ' . $competition->min_per_person . ' competitions.',
            ];

            $validator = Validator::make(['qty_' . $cardId => (int)$request->get('qty_' . $cardId)],
                $quantity_available_validation_rules, $quantity_available_validation_messages);

            if ($validator->fails()) {
                return response()->json([
                    'status'   => 'error',
                    'messages' => $validator->messages()->toArray(),
                ]);
            }

           
            $participants_validation_rules =[
                'participants_' . $cardId => ['required'],
            ];   

            $participants_validation_messages = [
              'participants_' . $cardId .'.required' => trans("particpants required"),
            ];
            Log::debug('step 1');
            /*
            $validator = Validator::make([ 'participants_' . $cardId => (int)$request->get('participants_' . $cardId)],
                $participants_validation_rules, $participants_validation_messages);
            
            if ($validator->fails()) {
                return response()->json([
                    'status'   => 'error',
                    'messages' => $validator->messages()->toArray(),
                ]);
            }
            */


            $order_total = $order_total + ($current_competition_quantity * $competition->price);
            /*$booking_fee = $booking_fee + ($current_competition_quantity * $competition->booking_fee);*/
            /*$organiser_booking_fee = $organiser_booking_fee + ($current_competition_quantity * $competition->organiser_booking_fee);*/

            $competitions[] = [
                'competition'           => $competition,
                'filename'              =>$filename,
                'groupName'             => $groupName,
                'cardId'                => $cardId,
                'participants'          =>$participants,
                'qty'                   => $current_competition_quantity,
                'price'                 => ($current_competition_quantity * $competition->price),
                'booking_fee'           => $booking_fee,
                'organiser_booking_fee' => $organiser_booking_fee,
                'full_price'            => $competition->price,
            ];

            /*
             * Reserve the competitions for X amount of minutes
             */
            $reservedCompetitions = new ReservedCompetitions();
            $reservedCompetitions->competition_id = $comptId;
            $reservedCompetitions->event_id = $event_id;
            $reservedCompetitions->user_id = $user->id;
            $reservedCompetitions->quantity_reserved = $current_competition_quantity;
            $reservedCompetitions->expires = $order_expires_time;
            $reservedCompetitions->session_id = session()->getId();
            $reservedCompetitions->save();

            for ($i = 0; $i < $current_competition_quantity; $i++) {
                /*
                 * Create our validation rules here
                 */
                /*
                $validation_rules['competition_holder_first_name.' . $i . '.' . $competition_id] = ['required'];
                $validation_rules['competition_holder_last_name.' . $i . '.' . $competition_id] = ['required'];
                $validation_rules['competition_holder_email.' . $i . '.' . $competition_id] = ['required', 'email'];

                $validation_messages['competition_holder_first_name.' . $i . '.' . $competition_id . '.required'] = 'Competition holder ' . ($i + 1) . '\'s first name is required';
                $validation_messages['competition_holder_last_name.' . $i . '.' . $competition_id . '.required'] = 'Competition holder ' . ($i + 1) . '\'s last name is required';
                $validation_messages['competition_holder_email.' . $i . '.' . $competition_id . '.required'] = 'Competition holder ' . ($i + 1) . '\'s email is required';
                $validation_messages['competition_holder_email.' . $i . '.' . $competition_id . '.email'] = 'Competition holder ' . ($i + 1) . '\'s email appears to be invalid';
                */


                
                /*
                 * Validation rules for custom questions
                 */
                /*
                foreach ($competition->questions as $question) {
                    if ($question->is_required && $question->is_enabled) {
                        $validation_rules['competition_holder_questions.' . $competition_id . '.' . $i . '.' . $question->id] = ['required'];
                        $validation_messages['competition_holder_questions.' . $competition_id . '.' . $i . '.' . $question->id . '.required'] = "This question is required";
                    }
                }
                */
            }
        }
    }

        if (empty($competitions)) {
            return response()->json([
                'status'  => 'error',
                'message' => 'No competitions selected.',
            ]);
        }

        if (config('attendize.enable_dummy_payment_gateway') == TRUE) {
            $activeAccountPaymentGateway = new AccountPaymentGateway();
            $activeAccountPaymentGateway->fill(['payment_gateway_id' => config('attendize.payment_gateway_dummy')]);
            $paymentGateway = $activeAccountPaymentGateway;
        } else {
            $activeAccountPaymentGateway = $event->account->getGateway($event->account->payment_gateway_id);
            //if no payment gateway configured and no offline pay, don't go to the next step and show user error
            if (empty($activeAccountPaymentGateway) && !$event->enable_offline_payments) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'No payment gateway configured',
                ]);
            }
            $paymentGateway = $activeAccountPaymentGateway ? $activeAccountPaymentGateway->payment_gateway : false;
        }

        /*
         * The 'competition_order_{event_id}' session stores everything we need to complete the transaction.
         */

        session()->put('competition_order_' . $event->id, [
            'validation_rules'        => $validation_rules,
            'validation_messages'     => $validation_messages,
            'event_id'                => $event->id,
            'competitions'                 => $competitions,
            'total_competition_quantity'   => $total_competition_quantity,
            'order_started'           => time(),
            'expires'                 => $order_expires_time,
            'reserved_competitions_id'     => $reservedCompetitions->id,
            'order_total'             => $order_total,
            'booking_fee'             => $booking_fee,
            'organiser_booking_fee'   => $organiser_booking_fee,
            'total_booking_fee'       => $booking_fee + $organiser_booking_fee,
            'order_requires_payment'  => (ceil($order_total) == 0) ? false : true,
            'account_id'              => $event->account->id,
            'affiliate_referral'      => Cookie::get('affiliate_' . $event_id),
            'account_payment_gateway' => $activeAccountPaymentGateway,
            'payment_gateway'         => $paymentGateway
        ]);

        /*
         * If we're this far assume everything is OK and redirect them
         * to the the checkout page.
         */
        /*
        if ($request->ajax()) {

            return response()->json([
                'status'      => 'success',
                'redirectUrl' => route('showEventCheckout', [
                        'event_id'    => $event_id,
                        'is_embedded' => $this->is_embedded,
                    ]) . '#order_form',
            ]);
        }
        */
        return redirect()->route('showEventSubscriptionCheckout', [
            'event_id'    => $event_id,
            'is_embedded' => $this->is_embedded,
            '#order_form',]);


        /*
         * Maybe display something prettier than this?
         */
        exit('Please enable Javascript in your browser.');
    }


    /**
     * Show the checkout page
     *
     * @param Request $request
     * @param $event_id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function showEventCheckout(Request $request, $event_id)
    {
        Log::debug('soweventCheckout for event: ' .$event_id);
        $order_session = session()->get('ticket_order_' . $event_id);

        if (!$order_session || $order_session['expires'] < Carbon::now()) {
            $route_name = $this->is_embedded ? 'showEmbeddedEventPage' : 'showEventPage';
            Log::debug('$route_name:' .$route_name);
            return redirect()->route($route_name, ['event_id' => $event_id]);
        }

        $secondsToExpire = Carbon::now()->diffInSeconds($order_session['expires']);

        $event = Event::findorFail($order_session['event_id']);

        $orderService = new OrderService($order_session['order_total'], $order_session['total_booking_fee'], $event);
        $orderService->calculateFinalCosts();

        $data = $order_session + [
                'event'           => $event,
                'secondsToExpire' => $secondsToExpire,
                'is_embedded'     => $this->is_embedded,
                'orderService'    => $orderService
                ];

        if ($this->is_embedded) {
            return view('Public.ViewEvent.Embedded.EventPageCheckout', $data);
        }
        Log::debug('EventPageCheckout routing');
        return view('Public.ViewEvent.EventPageCheckout', $data);
    }

    /**
     * Show the checkout page
     *
     * @param Request $request
     * @param $event_id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function showEventSubscriptionCheckout(Request $request, $event_id)
    {
        Log::debug('soweventCheckout for event: ' .$event_id);
        $order_session = session()->get('competition_order_' . $event_id);
        /*$competitions = session()->get('competitions');*/

        if (!$order_session || $order_session['expires'] < Carbon::now()) {
            $route_name = 'showSubscriptionPage';
            Log::debug('$route_name:' .$route_name);
            return redirect()->route($route_name, ['event_id' => $event_id]);
        }
        
        
        /*if(empty($competitions)){
            Log::debug('ciupa: ' .$order_session['order_total']);
        }*/
        $secondsToExpire = Carbon::now()->diffInSeconds($order_session['expires']);

        $event = Event::findorFail($order_session['event_id']);
        Log::debug('event found from order_session');
        $orderService = new OrderService($order_session['order_total'], $order_session['total_booking_fee'], $event);
        $orderService->calculateFinalCosts();
        Log::debug('after calculating final costs');
        $data = $order_session + [
                'event'           => $event,
                'secondsToExpire' => $secondsToExpire,
                'is_embedded'     => $this->is_embedded,
                'orderService'    => $orderService
                ];

        /*
        if ($this->is_embedded) {
            return view('Public.ViewEvent.Embedded.EventPageCheckout', $data);
        }*/
        Log::debug('before opening the checlout page');
        
        return view('Public.ViewEvent.EventSubscriptionPageCheckout', $data);
    }

    /**
     * Create the order, handle payment, update stats, fire off email jobs then redirect user
     *
     * @param Request $request
     * @param $event_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function postCreateOrder(Request $request, $event_id)
    {
        $session_usr_name = $request->session()->get('name');
        Log::debug('session_usr_name :' .$session_usr_name);
        if (empty(Auth::user()) || empty($session_usr_name)) {
            Auth::logout();
            Session::flush();
            return redirect()->to('/eventList?logged_out=yup');
        }else{
            $account = Account::find(Auth::user()->account_id);
            if ($account->account_type == config('attendize.default_account_type')) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'please create a ticketing account',
                ]);
            }elseif($account->account_type == config('attendize.simple_account_type')){
                return response()->json([
                    'status'  => 'error',
                    'message' => 'please create a ticketing account',
                ]);
            }
        }
        Log::debug('stripe token' .$request->get('stripeToken'));
        //If there's no session kill the request and redirect back to the event homepage.
        if (!session()->get('ticket_order_' . $event_id)) {
            return response()->json([
                'status'      => 'error',
                'message'     => 'Your session has expired.',
                'redirectUrl' => route('showEventPage', [
                    'event_id' => $event_id,
                ])
            ]);
        }

        $event = Event::findOrFail($event_id);
        $order = new Order();
        $ticket_order = session()->get('ticket_order_' . $event_id);

        $validation_rules = $ticket_order['validation_rules'];
        $validation_messages = $ticket_order['validation_messages'];

        $order->rules = $order->rules + $validation_rules;
        $order->messages = $order->messages + $validation_messages;

        if ($request->has('is_business') && $request->get('is_business')) {
            // Dynamic validation on the new business fields, only gets validated if business selected
            $businessRules = [
                'business_name' => 'required',
                'business_tax_number' => 'required',
                'business_address_line1' => 'required',
                'business_address_city' => 'required',
                'business_address_code' => 'required',
            ];

            $businessMessages = [
                'business_name.required' => 'Please enter a valid business name',
                'business_tax_number.required' => 'Please enter a valid business tax number',
                'business_address_line1.required' => 'Please enter a valid street address',
                'business_address_city.required' => 'Please enter a valid city',
                'business_address_code.required' => 'Please enter a valid code',
            ];

            $order->rules = $order->rules + $businessRules;
            $order->messages = $order->messages + $businessMessages;
        }

        if (!$order->validate($request->all())) {
            return response()->json([
                'status'   => 'error',
                'messages' => $order->errors(),
            ]);
        }

        //Add the request data to a session in case payment is required off-site
        Log::debug('stripe token' .$request->get('stripeToken'));
        session()->push('ticket_order_' . $event_id . '.request_data', $request->except(['card-number', 'card-cvc']));
        Log::debug('stripe token' .$request->get('stripeToken'));
        $orderRequiresPayment = $ticket_order['order_requires_payment'];

        if ($orderRequiresPayment && $request->get('pay_offline') && $event->enable_offline_payments) {
            return $this->completeOrder($event_id);
        }

        if (!$orderRequiresPayment) {
            return $this->completeOrder($event_id);
        }

        try {
            //more transation data being put in here.
            $transaction_data = [];
            if (config('attendize.enable_dummy_payment_gateway') == TRUE) {
                $formData = config('attendize.fake_card_data');
                $transaction_data = [
                    'card' => $formData
                ];

                $gateway = Omnipay::create('Dummy');
                $gateway->initialize();

            } else {
                Log::debug('stripe token' .$request->get('stripeToken'));
                $gateway = Omnipay::create($ticket_order['payment_gateway']->name);
                Log::debug('stripe token' .$request->get('stripeToken'));
                Log::debug('$ticket_order[payment_gateway]->name:' .$ticket_order['payment_gateway']->name);
                $gateway->initialize($ticket_order['account_payment_gateway']->config + [
                        'testMode' => config('attendize.enable_test_payments'),
                    ]);
                Log::debug('stripe token' .$request->get('stripeToken'));
            }

            $orderService = new OrderService($ticket_order['order_total'], $ticket_order['total_booking_fee'], $event);
            $orderService->calculateFinalCosts();

            $transaction_data += [
                    'amount'      => $orderService->getGrandTotal(),
                    'currency'    => $event->currency->code,
                    'description' => 'Order for customer: ' . $request->get('order_email'),
            ];

            //TODO: class with an interface that builds the transaction data.
            switch ($ticket_order['payment_gateway']->id) {
                case config('attendize.payment_gateway_dummy'):
                    $token = uniqid();
                    $transaction_data += [
                        'token'         => $token,
                        'receipt_email' => $request->get('order_email'),
                        'card' => $formData
                    ];
                    break;
                case config('attendize.payment_gateway_paypal'):

                    $transaction_data += [
                        'cancelUrl' => route('showEventCheckoutPaymentReturn', [
                            'event_id'             => $event_id,
                            'is_payment_cancelled' => 1
                        ]),
                        'returnUrl' => route('showEventCheckoutPaymentReturn', [
                            'event_id'              => $event_id,
                            'is_payment_successful' => 1
                        ]),
                        'brandName' => isset($ticket_order['account_payment_gateway']->config['brandingName'])
                            ? $ticket_order['account_payment_gateway']->config['brandingName']
                            : $event->organiser->name
                    ];
                    break;
                case config('attendize.payment_gateway_stripe'):
                    $token = $request->get('stripeToken');
                    $transaction_data += [
                        'token'         => $token,
                        'receipt_email' => $request->get('order_email'),
                    ];
                    break;
                default:
                    Log::error('No payment gateway configured.');
                    return response()->json([
                        'status'  => 'error',
                        'message' => 'No payment gateway configured.'
                    ]);
                    break;
            }
            Log::debug('transaction data token:' .$transaction_data['token'] 
            .'transaction data amount:' .$transaction_data['amount'] 
            .'transaction data user_email:' .$transaction_data['receipt_email'] 
            .'transaction data currency:' .$transaction_data['currency'] 
            .'transaction data description:' .$transaction_data['description'] 
           /* .'transaction data source:' .$transaction_data['source']  */
        );
            $transaction = $gateway->purchase($transaction_data);
            Log::debug('transaction gateway ok');
            $response = $transaction->send();
            Log::debug('transaction executed');
            if ($response->isSuccessful()) {
                Log::debug('transaction ok');
                session()->push('ticket_order_' . $event_id . '.transaction_id',
                    $response->getTransactionReference());

                return $this->completeOrder($event_id);

            } elseif ($response->isRedirect()) {

                /*
                 * As we're going off-site for payment we need to store some data in a session so it's available
                 * when we return
                 */
                session()->push('ticket_order_' . $event_id . '.transaction_data', $transaction_data);
                Log::info("Redirect url: " . $response->getRedirectUrl());

                $return = [
                    'status'       => 'success',
                    'redirectUrl'  => $response->getRedirectUrl(),
                    'message'      => 'Redirecting to ' . $ticket_order['payment_gateway']->provider_name
                ];

                // GET method requests should not have redirectData on the JSON return string
                if($response->getRedirectMethod() == 'POST') {
                    $return['redirectData'] = $response->getRedirectData();
                }

                return response()->json($return);

            } else {
                // display error to customer
                return response()->json([
                    'status'  => 'error',
                    'message' => $response->getMessage(),
                ]);
            }
        } catch (\Exeption $e) {
            Log::error($e);
            $error = 'Sorry, there was an error processing your payment. Please try again.';
        }

        if ($error) {
            return response()->json([
                'status'  => 'error',
                'message' => $error,
            ]);
        }

    }

    public function postSubscriptionCreateOrder(Request $request, $event_id)
    {
        //If there's no session kill the request and redirect back to the event homepage.
        /*
        if (!session()->get('ticket_order_' . $event_id)) {
            return response()->json([
                'status'      => 'error',
                'message'     => 'Your session has expired.',
                'redirectUrl' => route('showSubscriptionPage', [
                    'event_id' => $event_id,
                ])
            ]);
        }*/

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

        if (!session()->get('competition_order_' . $event_id)) {
            return redirect()->route('showSubscriptionPage', [
                'event_id'    => $event_id,
                'is_embedded' => $this->is_embedded]);
        }


        $event = Event::findOrFail($event_id);
        $order = new Order();
        $competition_order = session()->get('competition_order_' . $event_id);

        $validation_rules = $competition_order['validation_rules'];
        $validation_messages = $competition_order['validation_messages'];

        /*
        $order->rules = $order->rules + $validation_rules;
        $order->messages = $order->messages + $validation_messages;
        */
        
        $order->rules = $validation_rules;
        $order->messages = $validation_messages;

        if ($request->has('is_business') && $request->get('is_business')) {
            Log::debug('business validation included');
            // Dynamic validation on the new business fields, only gets validated if business selected
            $businessRules = [
                'business_name' => 'required',
                'business_tax_number' => 'required',
                'business_address_line1' => 'required',
                'business_address_city' => 'required',
                'business_address_code' => 'required',
            ];

            $businessMessages = [
                'business_name.required' => 'Please enter a valid business name',
                'business_tax_number.required' => 'Please enter a valid business tax number',
                'business_address_line1.required' => 'Please enter a valid street address',
                'business_address_city.required' => 'Please enter a valid city',
                'business_address_code.required' => 'Please enter a valid code',
            ];

           /* $order->rules = $order->rules + $businessRules;
            $order->messages = $order->messages + $businessMessages;*/

            $order->rules = $businessRules;
            $order->messages = $businessMessages;
        }
        Log::debug('before validation');
        if (!$order->validate($request->all())) {
            return response()->json([
                'status'   => 'error',
                'messages' => $order->errors(),
            ]);
        }
        Log::debug('after validation');
        //Add the request data to a session in case payment is required off-site
        session()->push('competition_order_' . $event_id . '.request_data', $request->except(['card-number', 'card-cvc']));

        $orderRequiresPayment = $competition_order['order_requires_payment'];
        Log::debug('$orderRequiresPayment :' .$orderRequiresPayment);

        if ($orderRequiresPayment && $request->get('pay_offline') && $event->enable_offline_payments) {
            Log::debug('pay_offline : true');
            return $this->completeSubscriptionOrder($event_id);
        }

        if (!$orderRequiresPayment) {
            Log::debug('payment not required : true');
            return $this->completeSubscriptionOrder($event_id);
        }

        try {
            //more transation data being put in here.
            $transaction_data = [];
            if (config('attendize.enable_dummy_payment_gateway') == TRUE) {
                Log::debug('dummy payment : true');
                $formData = config('attendize.fake_card_data');
                $transaction_data = [
                    'card' => $formData
                ];

                $gateway = Omnipay::create('Dummy');
                $gateway->initialize();

            } else {
                Log::debug('payment with payment gateway : true');
                $gateway = Omnipay::create($competition_order['payment_gateway']->name);
                Log::debug('$competition_order[payment_gateway]->name:' .$competition_order['payment_gateway']->name);
                $gateway->initialize($competition_order['account_payment_gateway']->config + [
                        'testMode' => config('attendize.enable_test_payments'),
                    ]);
            }

            $orderService = new OrderService($competition_order['order_total'], $competition_order['total_booking_fee'], $event);
            Log::debug('order total : ' .$competition_order['order_total']);
            $orderService->calculateFinalCosts();
            Log::debug('grand total: ' .$orderService->getGrandTotal());
            $user_email = Auth::user()->email;
            $transaction_data += [
                    'amount'      => $orderService->getGrandTotal(),
                    'currency'    => $event->currency->code,
                    'description' => 'Order for customer: ' . $user_email,
            ];
            //TODO: class with an interface that builds the transaction data.
            switch ($competition_order['payment_gateway']->id) {
                case config('attendize.payment_gateway_dummy'):
                    Log::debug('dummy gateway: ' .config('attendize.payment_gateway_dummy'));
                    $token = uniqid();
                    $transaction_data += [
                        'token'         => $token,
                        'receipt_email' => $request->get('order_email'),
                        'card' => $formData
                    ];
                    break;
                case config('attendize.payment_gateway_paypal'):
                    Log::debug('gateway paypal: ' .config('attendize.payment_gateway_paypal'));
                    $transaction_data += [
                        'cancelUrl' => route('showEventCheckoutPaymentReturn', [
                            'event_id'             => $event_id,
                            'is_payment_cancelled' => 1
                        ]),
                        'returnUrl' => route('showEventCheckoutPaymentReturn', [
                            'event_id'              => $event_id,
                            'is_payment_successful' => 1
                        ]),
                        'brandName' => isset($competition_order['account_payment_gateway']->config['brandingName'])
                            ? $competition_order['account_payment_gateway']->config['brandingName']
                            : $event->organiser->name
                    ];
                    break;
                case config('attendize.payment_gateway_stripe'):
                    Log::debug('gateway stripe: ' .config('attendize.payment_gateway_stripe'));
                    $token = $request->get('stripeToken');
                    Log::debug('user_email: ' .$user_email);
                    $user_email = 'jeoncsetest@gmail.com';
                    Log::debug('user_email modified to : ' .$user_email);
                    $transaction_data += [
                        'token'         => $token,
                        'receipt_email' => $user_email,
                    ];
                    break;
                default:
                    Log::error('No payment gateway configured.');
                    return response()->json([
                        'status'  => 'error',
                        'message' => 'No payment gateway configured.'
                    ]);
                    break;
            }
            Log::debug('transaction data token:' .$transaction_data['token'] 
            .'transaction data amount:' .$transaction_data['amount'] 
            .'transaction data user_email:' .$transaction_data['receipt_email'] 
            .'transaction data currency:' .$transaction_data['currency'] 
            .'transaction data description:' .$transaction_data['description'] 
        /*    .'transaction data source:' .$transaction_data['source'] */
         );
            $transaction = $gateway->purchase($transaction_data);
            Log::debug('transaction purcahse object instatiated');
            $response = $transaction->send();
            Log::debug('transaction has been send');
            if ($response->isSuccessful()) {
                Log::debug('transaction $response->isSuccessful(): '.$response->isSuccessful());
                Log::debug('transaction id: '.'competition_order_' . $event_id . '.transaction_id::' .$response->getTransactionReference());
                session()->push('competition_order_' . $event_id . '.transaction_id',
                    $response->getTransactionReference());

                return $this->completeSubscriptionOrder($event_id);

            } elseif ($response->isRedirect()) {
                Log::debug('transaction $response->isRedirect() :' .$response->isRedirect());
                /*
                 * As we're going off-site for payment we need to store some data in a session so it's available
                 * when we return
                 */
                session()->push('competition_order_' . $event_id . '.transaction_data', $transaction_data);
                Log::info("Redirect url: " . $response->getRedirectUrl());

                $return = [
                    'status'       => 'success',
                    'redirectUrl'  => $response->getRedirectUrl(),
                    'message'      => 'Redirecting to ' . $competition_order['payment_gateway']->provider_name
                ];

                // GET method requests should not have redirectData on the JSON return string
                if($response->getRedirectMethod() == 'POST') {
                    $return['redirectData'] = $response->getRedirectData();
                }

                return response()->json($return);

            } else {
                // display error to customer
                return response()->json([
                    'status'  => 'error',
                    'message' => $response->getMessage(),
                ]);
            }
        } catch (\Exeption $e) {
            Log::error($e);
            $error = 'Sorry, there was an error processing your payment. Please try again.';
        }

        if ($error) {
            return response()->json([
                'status'  => 'error',
                'message' => $error,
            ]);
        }

    }


    /**
     * Attempt to complete a user's payment when they return from
     * an off-site gateway
     *
     * @param Request $request
     * @param $event_id
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function showEventCheckoutPaymentReturn(Request $request, $event_id)
    {

        if ($request->get('is_payment_cancelled') == '1') {
            session()->flash('message', trans('Event.payment_cancelled'));
            return response()->redirectToRoute('showEventCheckout', [
                'event_id'             => $event_id,
                'is_payment_cancelled' => 1,
            ]);
        }

        $ticket_order = session()->get('ticket_order_' . $event_id);
        $gateway = Omnipay::create($ticket_order['payment_gateway']->name);

        $gateway->initialize($ticket_order['account_payment_gateway']->config + [
                'testMode' => config('attendize.enable_test_payments'),
            ]);

        $transaction = $gateway->completePurchase($ticket_order['transaction_data'][0]);

        $response = $transaction->send();

        if ($response->isSuccessful()) {
            session()->push('ticket_order_' . $event_id . '.transaction_id', $response->getTransactionReference());
            return $this->completeOrder($event_id, false);
        } else {
            session()->flash('message', $response->getMessage());
            return response()->redirectToRoute('showEventCheckout', [
                'event_id'          => $event_id,
                'is_payment_failed' => 1,
            ]);
        }

    }

    /**
     * Complete an order
     *
     * @param $event_id
     * @param bool|true $return_json
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function completeOrder($event_id, $return_json = true)
    {
/*
        if (empty(Auth::user())) {
            Log::debug('redirect to login page');
      
            return redirect()->to('/loginSimple');
        }else{
            $account = Account::find(Auth::user()->account_id);
           
            if ($account->account_type == config('attendize.default_account_type')) {
                return redirect()->route('showSelectOrganiser');
            }elseif($account->account_type == config('attendize.ticket_account_type')){
                return redirect()->route('showEventListPage');
            }
        }
		*/
		$user = Auth::user();

        DB::beginTransaction();

        try {

            $order = new Order();
            $ticket_order = session()->get('ticket_order_' . $event_id);
            $request_data = $ticket_order['request_data'][0];
            $event = Event::findOrFail($ticket_order['event_id']);
            $attendee_increment = 1;
            $ticket_questions = isset($request_data['ticket_holder_questions']) ? $request_data['ticket_holder_questions'] : [];

            /*
             * Create the order
             */
            if (isset($ticket_order['transaction_id'])) {
                $order->transaction_id = $ticket_order['transaction_id'][0];
            }
            if ($ticket_order['order_requires_payment'] && !isset($request_data['pay_offline'])) {
                $order->payment_gateway_id = $ticket_order['payment_gateway']->id;
            }

            /*
            $order->first_name = sanitise($request_data['order_first_name']);
            $order->last_name = sanitise($request_data['order_last_name']);
            $order->email = sanitise($request_data['order_email']);
            */
            $order->user_id = $user->id;
            $order->first_name = sanitise($user->first_name);
            $order->last_name = sanitise($user->last_name);
            $order->email = sanitise($user->email);
            $order->order_status_id = isset($request_data['pay_offline']) ? config('attendize.order_awaiting_payment') : config('attendize.order_complete');
            $order->amount = $ticket_order['order_total'];
            $order->booking_fee = $ticket_order['booking_fee'];
            $order->organiser_booking_fee = $ticket_order['organiser_booking_fee'];
            $order->discount = 0.00;
            $order->account_id = $event->account->id;
            $order->event_id = $ticket_order['event_id'];
            $order->is_payment_received = isset($request_data['pay_offline']) ? 0 : 1;

            // Business details is selected, we need to save the business details
            if (isset($request_data['is_business']) && (bool)$request_data['is_business']) {
                $order->is_business = $request_data['is_business'];
                $order->business_name = sanitise($request_data['business_name']);
                $order->business_tax_number = sanitise($request_data['business_tax_number']);
                $order->business_address_line_one = sanitise($request_data['business_address_line1']);
                $order->business_address_line_two  = sanitise($request_data['business_address_line2']);
                $order->business_address_state_province  = sanitise($request_data['business_address_state']);
                $order->business_address_city = sanitise($request_data['business_address_city']);
                $order->business_address_code = sanitise($request_data['business_address_code']);

            }

            // Calculating grand total including tax
            $orderService = new OrderService($ticket_order['order_total'], $ticket_order['total_booking_fee'], $event);
            $orderService->calculateFinalCosts();

            $order->taxamt = $orderService->getTaxAmount();
            $order->save();

            /*
             * Update the event sales volume
             */
            $event->increment('sales_volume', $orderService->getGrandTotal());
            $event->increment('organiser_fees_volume', $order->organiser_booking_fee);

            /*
             * Update affiliates stats stats
             */
            if ($ticket_order['affiliate_referral']) {
                $affiliate = Affiliate::where('name', '=', $ticket_order['affiliate_referral'])
                    ->where('event_id', '=', $event_id)->first();
                $affiliate->increment('sales_volume', $order->amount + $order->organiser_booking_fee);
                $affiliate->increment('tickets_sold', $ticket_order['total_ticket_quantity']);
            }

            /*
             * Update the event stats
             */
            $event_stats = EventStats::updateOrCreate([
                'event_id' => $event_id,
                'date'     => DB::raw('CURRENT_DATE'),
            ]);
            $event_stats->increment('tickets_sold', $ticket_order['total_ticket_quantity']);

            if ($ticket_order['order_requires_payment']) {
                $event_stats->increment('sales_volume', $order->amount);
                $event_stats->increment('organiser_fees_volume', $order->organiser_booking_fee);
            }

            /*
             * Add the attendees
             */
            foreach ($ticket_order['tickets'] as $attendee_details) {

                /*
                 * Update ticket's quantity sold
                 */
                $ticket = Ticket::findOrFail($attendee_details['ticket']['id']);

                /*
                 * Update some ticket info
                 */
                $ticket->increment('quantity_sold', $attendee_details['qty']);
                $ticket->increment('sales_volume', ($attendee_details['ticket']['price'] * $attendee_details['qty']));
                $ticket->increment('organiser_fees_volume',
                    ($attendee_details['ticket']['organiser_booking_fee'] * $attendee_details['qty']));


                /*
                 * Insert order items (for use in generating invoices)
                 */
                $orderItem = new OrderItem();
                $orderItem->title = $attendee_details['ticket']['title'];
                $orderItem->quantity = $attendee_details['qty'];
                $orderItem->order_id = $order->id;
                $orderItem->unit_price = $attendee_details['ticket']['price'];
                $orderItem->unit_booking_fee = $attendee_details['ticket']['booking_fee'] + $attendee_details['ticket']['organiser_booking_fee'];
                $orderItem->save();

                /*
                 * Create the attendees
                 */
                for ($i = 0; $i < $attendee_details['qty']; $i++) {

                    $attendee = new Attendee();
                    $attendee->first_name = strip_tags($request_data["ticket_holder_first_name"][$i][$attendee_details['ticket']['id']]);
                    $attendee->last_name = strip_tags($request_data["ticket_holder_last_name"][$i][$attendee_details['ticket']['id']]);
                    $attendee->email = $request_data["ticket_holder_email"][$i][$attendee_details['ticket']['id']];
                    $attendee->event_id = $event_id;
                    $attendee->order_id = $order->id;
                    $attendee->ticket_id = $attendee_details['ticket']['id'];
                    $attendee->account_id = $event->account->id;
                    $attendee->reference_index = $attendee_increment;
                    $attendee->save();


                    /*
                     * Save the attendee's questions
                     */
                    foreach ($attendee_details['ticket']->questions as $question) {


                        $ticket_answer = isset($ticket_questions[$attendee_details['ticket']->id][$i][$question->id]) ? $ticket_questions[$attendee_details['ticket']->id][$i][$question->id] : null;

                        if (is_null($ticket_answer)) {
                            continue;
                        }

                        /*
                         * If there are multiple answers to a question then join them with a comma
                         * and treat them as a single answer.
                         */
                        $ticket_answer = is_array($ticket_answer) ? implode(', ', $ticket_answer) : $ticket_answer;

                        if (!empty($ticket_answer)) {
                            QuestionAnswer::create([
                                'answer_text' => $ticket_answer,
                                'attendee_id' => $attendee->id,
                                'event_id'    => $event->id,
                                'account_id'  => $event->account->id,
                                'question_id' => $question->id
                            ]);

                        }
                    }


                    /* Keep track of total number of attendees */
                    $attendee_increment++;
                }
            }

        } catch (Exception $e) {

            Log::error($e);
            DB::rollBack();

            return response()->json([
                'status'  => 'error',
                'message' => 'Whoops! There was a problem processing your order. Please try again.'
            ]);

        }
        //save the order to the database
        DB::commit();
        //forget the order in the session
        session()->forget('ticket_order_' . $event->id);

        /*
         * Remove any tickets the user has reserved after they have been ordered for the user
         */
        ReservedTickets::where('session_id', '=', session()->getId())->delete();

        // Queue up some tasks - Emails to be sent, PDFs etc.
        Log::info('Firing the event');
        event(new OrderCompletedEvent($order));


        if ($return_json) {
            return response()->json([
                'status'      => 'success',
                'redirectUrl' => route('showOrderDetails', [
                    'is_embedded'     => $this->is_embedded,
                    'order_reference' => $order->order_reference,
                ]),
            ]);
        }

        return response()->redirectToRoute('showOrderDetails', [
            'is_embedded'     => $this->is_embedded,
            'order_reference' => $order->order_reference,
        ]);


    }

     /**
     * Complete an cart order
     *
     * @param $event_id
     * @param bool|true $return_json
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function completeSubscriptionOrder($event_id, $return_json = true)
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
		
		$user = Auth::user();

        Log::debug('########################begin transaction########################');
        DB::beginTransaction();
        try {

            $order = new Order();
            $competition_order = session()->get('competition_order_' . $event_id);
            $request_data = $competition_order['request_data'][0];
            $event = Event::findOrFail($competition_order['event_id']);
            $participants_increment = 1;

            /*
             * Create the order
             */
            if (isset($competition_order['transaction_id'])) {
				Log::debug('transaction id:' .$competition_order['transaction_id'][0]);
                $order->transaction_id = $competition_order['transaction_id'][0];
            }
            if ($competition_order['order_requires_payment'] && !isset($request_data['pay_offline'])) {
				Log::debug($competition_order['payment_gateway']->id .':' .$competition_order['payment_gateway']->id);
                $order->payment_gateway_id = $competition_order['payment_gateway']->id;
            }
            $order->user_id = $user->id;
            $order->first_name = sanitise($user->first_name);
            $order->last_name = sanitise($user->last_name);
            $order->email = sanitise($user->email);
            $order->order_status_id = isset($request_data['pay_offline']) ? config('attendize.order_awaiting_payment') : config('attendize.order_complete');
            $order->amount = 0.00;
            $order->order_type = 'SUBSCRIPTION';
			$order->cart_amount = $competition_order['order_total'];
            $order->booking_fee = $competition_order['booking_fee'];
            $order->organiser_booking_fee = $competition_order['organiser_booking_fee'];
            $order->discount = 0.00;
            $order->account_id = $event->account->id;
            $order->event_id = $competition_order['event_id'];
            $order->is_payment_received = isset($request_data['pay_offline']) ? 0 : 1;

            // Business details is selected, we need to save the business details
            if (isset($request_data['is_business']) && (bool)$request_data['is_business']) {
                Log::debug('business data');
                $order->is_business = $request_data['is_business'];
                $order->business_name = sanitise($request_data['business_name']);
                $order->business_tax_number = sanitise($request_data['business_tax_number']);
                $order->business_address_line_one = sanitise($request_data['business_address_line1']);
                $order->business_address_line_two  = sanitise($request_data['business_address_line2']);
                $order->business_address_state_province  = sanitise($request_data['business_address_state']);
                $order->business_address_city = sanitise($request_data['business_address_city']);
                $order->business_address_code = sanitise($request_data['business_address_code']);
            }

            // Calculating grand total including tax
            $orderService = new OrderService($competition_order['order_total'], $competition_order['total_booking_fee'], $event);
            $orderService->calculateFinalCosts();

            $order->taxamt = $orderService->getTaxAmount();
            $order->save();

            /*
             * Update the event sales volume
             */
            $event->increment('cart_sales_volume', $orderService->getGrandTotal());
            $event->increment('cart_organiser_fees_volume', $order->organiser_booking_fee);

            /*
             * Update affiliates stats stats
             */
            if ($competition_order['affiliate_referral']) {
                $affiliate = Affiliate::where('name', '=', $competition_order['affiliate_referral'])
                    ->where('event_id', '=', $event_id)->first();
                $affiliate->increment('sales_volume', $order->amount + $order->organiser_booking_fee);
                $affiliate->increment('cart_items_sold', $competition_order['total_competition_quantity']);
            }

            /*
             * Update the event stats
             */
			 /*
            $event_stats = EventStats::updateOrCreate([
                'event_id' => $event_id,
                'date'     => DB::raw('CURRENT_DATE'),
            ]);
            $event_stats->increment('competitions_sold', $competition_order['total_competition_quantity']);

            if ($competition_order['order_requires_payment']) {
                $event_stats->increment('sales_volume', $order->amount);
                $event_stats->increment('organiser_fees_volume', $order->organiser_booking_fee);
            }
			*/

            /*
             * Add the attendees
             */

             Log::debug('step 1');
            if(Cart::count()>0){
                foreach(Cart::content() as $row){
                    Log::debug('step 2');
                    $competition = Competition::findOrFail($row->options->competition_id);

                    /*
                 * Update some competition info
                 */
                Log::debug('step 3');
                $competition->increment('total_subscription', $row->qty);
                $competition->increment('sales_volume', ($row->price * $row->qty));
                $competition->increment('organiser_fees_volume',(0 * $row->qty));
                $competition->save;
                Log::debug('step 4');

                /*
                 * Insert order items (for use in generating invoices)
                 */
                
                $orderItem = new OrderItem();
                $orderItem->title = $competition->title;
                $orderItem->quantity = $row->qty;
                $orderItem->order_id = $order->id;
                $orderItem->unit_price = $row->price;
                Log::debug('step 5');
                /*$orderItem->unit_booking_fee = $competitor_details['competition']['booking_fee'] + $competitor_details['competition']['organiser_booking_fee'];*/
                $orderItem->save();

                /*
                 * Create a subscription
                 */
                $subscription = new Subscription();
                $subscription->user_id = $user->id;
                $subscription->event_id = $event_id;
                $subscription->order_id = $order->id;
                $subscription->competition_id = $competition->id;
                $subscription->account_id = $event->account->id;
                $subscription->reference_index = $participants_increment;
                $subscription->save();

                
                /*
                 * add participant
                 */
                Log::debug('step 6');
                $participants = [];
                $filename = '';
                foreach ($competition_order['competitions'] as $attendee_details) {

                    /*
                     * Update ticket's quantity sold
                     */
         
                    if($row->id == $attendee_details['cardId']){
                        $participants = $attendee_details['participants'];
                        $filename = $attendee_details['filename'];  
                        $groupName =  $attendee_details['groupName'];
                    }
                }
                
                Log::debug('step 7 : ' .count($participants));
                foreach ($participants as $participant) {
                    Log::debug('step 8 participant :' .$participant);
                    $student = Student::findOrFail((int)$participant);
                    $participant = new Participant();
                    $participant->student_id = $student->id;
                    $participant->subscription_id = $subscription->id;
                    $participant->save();
                }
                $subscription->mp3_path = $filename;
                $subscription->group_name = $groupName;
                $subscription->category = ($row->options->has('category') ? $row->options->category : '');
                $subscription->level = ($row->options->has('level') ? $row->options->level : '');
                $subscription->save();
                $participants_increment++;

/*
            foreach ($competition_order['competitions'] as $competitor_details) {

                $competition = Competition::findOrFail($competitor_details['competition']['id']);


                $competition->increment('total_subscription', $competitor_details['qty']);
                $competition->increment('sales_volume', ($competitor_details['competition']['price'] * $competitor_details['qty']));
                $competition->increment('organiser_fees_volume',
                ($competitor_details['competition']['organiser_booking_fee'] * $competitor_details['qty']));



                $orderItem = new OrderItem();
                $orderItem->title = $competitor_details['competition']['title'];
                $orderItem->quantity = $competitor_details['qty'];
                $orderItem->order_id = $order->id;
                $orderItem->unit_price = $competitor_details['competition']['price'];
                $orderItem->unit_booking_fee = $competitor_details['competition']['booking_fee'] + $competitor_details['competition']['organiser_booking_fee'];
                $orderItem->save();

  
                for ($i = 0; $i < $competitor_details['qty']; $i++) {

                    $subscription = new Subscription();
                    $subscription->email = $user->email[$i][$competitor_details['competition']['id']];
                    $subscription->event_id = $event_id;
                    $subscription->order_id = $order->id;
                    $subscription->competition_id = $competitor_details['competition']['id'];
                    $subscription->account_id = $event->account->id;
                    $subscription->reference_index = $participants_increment;
                    $subscription->save();

                    $participants_increment++;
                }

            }*/

        }
     }
    } catch (Exception $e) {

            Log::error($e);
            DB::rollBack();

            return response()->json([
                'status'  => 'error',
                'message' => 'Whoops! There was a problem processing your order. Please try again.'
            ]);

        }
        //save the order to the database
        DB::commit();
        Cart::destroy();
        Log::debug('########################end transaction########################');
        //forget the order in the session
        session()->forget('competition_order_' . $event->id);

        /*
         * Remove any competitions the user has reserved after they have been ordered for the user
         */
        ReservedTickets::where('session_id', '=', session()->getId())->delete();

        // Queue up some tasks - Emails to be sent, PDFs etc.
        Log::info('Firing the event');
        event(new OrderCompletedEvent($order));


        if ($return_json) {
            return response()->json([
                'status'      => 'success',
                'redirectUrl' => route('showOrderDetails', [
                    'is_embedded'     => $this->is_embedded,
                    'order_reference' => $order->order_reference,
                ]),
            ]);
        }

        return response()->redirectToRoute('showOrderDetails', [
            'is_embedded'     => $this->is_embedded,
            'order_reference' => $order->order_reference,
        ]);
    }

    /**
     * Show the order details page
     *
     * @param Request $request
     * @param $order_reference
     * @return \Illuminate\View\View
     */
    public function showOrderDetails(Request $request, $order_reference)
    {
        $order = Order::where('order_reference', '=', $order_reference)->first();

        if (!$order) {
            abort(404);
        }

        $orderService = new OrderService($order->amount, $order->organiser_booking_fee, $order->event);
        $orderService->calculateFinalCosts();

        $data = [
            'order'        => $order,
            'orderService' => $orderService,
            'event'        => $order->event,
            'tickets'      => $order->event->competitions,
            'is_embedded'  => $this->is_embedded,
        ];

        if ($this->is_embedded) {
            return view('Public.ViewEvent.Embedded.EventPageViewOrder', $data);
        }

        return view('Public.ViewEvent.EventPageViewOrder', $data);
    }

    /**
     * Shows the tickets for an order - either HTML or PDF
     *
     * @param Request $request
     * @param $order_reference
     * @return \Illuminate\View\View
     */
    public function showOrderTickets(Request $request, $order_reference)
    {
        $order = Order::where('order_reference', '=', $order_reference)->first();

        if (!$order) {
            abort(404);
        }
        $images = [];
        $imgs = $order->event->images;
        foreach ($imgs as $img) {
            $images[] = base64_encode(file_get_contents(public_path($img->image_path)));
        }

        $data = [
            'order'     => $order,
            'event'     => $order->event,
            'tickets'   => $order->event->tickets,
            'attendees' => $order->attendees,
            'css'       => file_get_contents(public_path('assets/stylesheet/ticket.css')),
            'image'     => base64_encode(file_get_contents(public_path($order->event->organiser->full_logo_path))),
            'images'    => $images,
        ];

        if ($request->get('download') == '1') {
            return PDF::html('Public.ViewEvent.Partials.PDFTicket', $data, 'Tickets');
        }
        return view('Public.ViewEvent.Partials.PDFTicket', $data);
    }

     /**
     * Shows the tickets for an order - either HTML or PDF
     *
     * @param Request $request
     * @param $order_reference
     * @return \Illuminate\View\View
     */
    public function showSubscriptions(Request $request, $order_reference)
    {
        $order = Order::where('order_reference', '=', $order_reference)->first();

        if (!$order) {
            abort(404);
        }
        $images = [];
        $imgs = $order->event->images;
        foreach ($imgs as $img) {
            $images[] = base64_encode(file_get_contents(public_path($img->image_path)));
        }

        $data = [
            'order'     => $order,
            'event'     => $order->event,
            'competitions'   => $order->event->competitions,
            'attendees' => $order->attendees,
            'css'       => file_get_contents(public_path('assets/stylesheet/ticket.css')),
            'image'     => base64_encode(file_get_contents(public_path($order->event->organiser->full_logo_path))),
            'images'    => $images,
        ];

        if ($request->get('download') == '1') {
            return PDF::html('Public.ViewEvent.Partials.PDFTicket', $data, 'Competitions');
        }
        return view('Public.ViewEvent.Partials.PDFTicket', $data);
    }

}

