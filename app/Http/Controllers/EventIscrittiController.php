<?php

namespace App\Http\Controllers;

use App\Jobs\GenerateTicket;
use App\Jobs\SendAttendeeInvite;
use App\Jobs\SendAttendeeTicket;
use App\Jobs\SendMessageToAttendees;
use App\Models\Attendee;
use App\Models\Event;
use App\Models\EventStats;
use App\Models\Message;
use App\Models\Order;
use App\Models\OrderItem;
use App\Services\Order as OrderService;
use App\Models\Ticket;
use Auth;
use Config;
use DB;
use Excel;
use Illuminate\Http\Request;
use Log;
use Mail;
use Omnipay\Omnipay;
use PDF;
use Validator;

class EventIscrittiController extends MyBaseController
{
    /**
     * Show the subscriptions list
     *
     * @param Request $request
     * @param $event_id
     * @return View
     */
    public function showIscritti(Request $request, $event_id)
    {
        $allowed_sorts = ['first_name', 'email', 'ticket_id', 'order_reference'];

        $searchQuery = $request->get('q');
        $sort_order = $request->get('sort_order') == 'asc' ? 'asc' : 'desc';
        $sort_by = (in_array($request->get('sort_by'), $allowed_sorts) ? $request->get('sort_by') : 'created_at');

        $event = Event::scope()->find($event_id);

        Log::debug('iscritti' .$event->subscriptions()->count() .'        $searchQuery ' .$searchQuery);
        if ($searchQuery) {
            $subscriptions = $event->subscriptions()
                ->join('orders', 'orders.id', '=', 'subscriptions.order_id')
                ->join('competitions', 'competitions.id', '=', 'subscriptions.competition_id')
                ->join('events', 'events.id', '=', 'subscriptions.event_id')
                ->join('participants', 'participants.subscription_id', '=', 'subscriptions.id')
                ->join('students', 'students.id', '=', 'participants.student_id')
                ->where('subscriptions.event_id', '=', $event_id)
                ->where(function ($query) use ($searchQuery) {
                    $query->where('orders.order_reference', 'like', $searchQuery . '%')
                        ->orWhere('students.name', 'like', $searchQuery . '%')
                        ->orWhere('students.email', 'like', $searchQuery . '%')
                        ->orWhere('competitions.title', 'like', $searchQuery . '%')
                        ->orWhere('students.surname', 'like', $searchQuery . '%');
                })
                ->orderBy($sort_by, $sort_order)
                ->select('subscriptions.*','students.email as bal_email', 'students.name as bal_name',
                 'students.surname as bal_surname',
                 'students.phone as bal_phone', 'students.fiscal_code as bal_fiscalCode','orders.order_reference')
                ->paginate();
        } else {
            $subscriptions = $event->subscriptions()
                ->join('orders', 'orders.id', '=', 'subscriptions.order_id')
                ->join('competitions', 'competitions.id', '=', 'subscriptions.competition_id')
                ->join('events', 'events.id', '=', 'subscriptions.event_id')
                ->join('participants', 'participants.subscription_id', '=', 'subscriptions.id')
                ->join('students', 'students.id', '=', 'participants.student_id')
                ->where('subscriptions.event_id', '=', $event_id)
                ->orderBy($sort_by, $sort_order)
                ->select('subscriptions.*', 'students.email as bal_email', 'students.name as bal_name',
                 'students.surname as bal_surname','students.phone as bal_phone',
                  'students.fiscal_code as bal_fiscalCode', 'orders.order_reference')
                ->paginate();
        }

        $data = [
            'subscriptions'  => $subscriptions,
            'event'      => $event,
            'sort_by'    => $sort_by,
            'sort_order' => $sort_order,
            'q'          => $searchQuery ? $searchQuery : '',
        ];

        return view('ManageEvent.Iscritti', $data);
    }

    /**
     * Downloads an export of attendees
     *
     * @param $event_id
     * @param string $export_as (xlsx, xls, csv, html)
     */
    public function showExportSubscriptions($event_id, $export_as = 'xls')
    {

        Excel::create('subscriptions-as-of-' . date('d-m-Y-g.i.a'), function ($excel) use ($event_id) {

            $excel->setTitle('Subscriptions List');

            // Chain the setters
            $excel->setCreator(config('attendize.app_name'))
                ->setCompany(config('attendize.app_name'));

            $excel->sheet('subscriptions_sheet_1', function ($sheet) use ($event_id) {
                DB::connection();
                $data = DB::table('subscriptions')
                    ->where('subscriptions.event_id', '=', $event_id)
                    ->where('subscriptions.is_cancelled', '=', 0)
                    ->where('subscriptions.account_id', '=', Auth::user()->account_id)
                    ->join('events', 'events.id', '=', 'subscriptions.event_id')
                    ->join('orders', 'orders.id', '=', 'subscriptions.order_id')
                    ->join('competitions', 'competitions.id', '=', 'subscriptions.competition_id')
                    ->join('participants', 'participants.subscription_id', '=', 'subscriptions.id')
                    ->join('students', 'students.id', '=', 'participants.student_id')
                    ->select([
                        'students.name',
                        'students.surname',
                        'students.email',
			            'students.phone',
                        'students.fiscal_code',
                        'competitions.title',
                        'subscriptions.group_name',
                        'subscriptions.level',
                        'subscriptions.category',
                         DB::raw("(CASE WHEN competitions.type='D' THEN 'Doppio'  WHEN competitions.type='G' THEN 'Gruppo' WHEN competitions.type='S' THEN 'Single' END) AS competitions_type"),
                        'orders.created_at',
                        DB::raw("(CASE WHEN subscriptions.has_arrived THEN 'YES' ELSE 'NO' END) AS has_arrived"),
                        'subscriptions.arrival_time',
                    ])->get();

                $data = array_map(function($object) {
                    return (array)$object;
                }, $data->toArray());

                $sheet->fromArray($data);
                $sheet->row(1, [
                    'First Name',
                    'Last Name',
                    'Email',
		            'Telefono',
                    'Codice Fiscale',
                    'Gara',
                    'Nome Gruppo',
                    'Livello',
                    'Categoria',
                    'Tipo',
                    'Purchase Date',
                    'Has Arrived',
                    'Arrival Time',
                ]);

                // Set gray background on first row
                $sheet->row(1, function ($row) {
                    $row->setBackground('#f5f5f5');
                });
            });
        })->export($export_as);
    }

    

    /**
     * Shows the 'Cancel Attendee' modal
     *
     * @param Request $request
     * @param $event_id
     * @param $attendee_id
     * @return View
     */
    public function showCancelSubscription(Request $request, $event_id, $attendee_id)
    {
        $attendee = Attendee::scope()->findOrFail($attendee_id);

        $data = [
            'attendee' => $attendee,
            'event'    => $attendee->event,
            'tickets'  => $attendee->event->tickets->pluck('title', 'id'),
        ];

        return view('ManageEvent.Modals.CancelAttendee', $data);
    }

    /**
     * Cancels an attendee
     *
     * @param Request $request
     * @param $event_id
     * @param $attendee_id
     * @return mixed
     */
    public function postCancelSubscription(Request $request, $event_id, $attendee_id)
    {
        $subscription = Subscription::scope()->findOrFail($attendee_id);
        $error_message = false; //Prevent "variable doesn't exist" error message

        if ($subscription->is_cancelled) {
            return response()->json([
                'status'  => 'success',
                'message' => trans("Controllers.attendee_already_cancelled"),
            ]);
        }

        $subscription->competition->decrement('total_subscription');
        $subscription->competition->decrement('sales_volume', $subscription->competition->price);
        $subscription->competition->event->decrement('sales_volume', $subscription->competition->price);
        $subscription->is_cancelled = 1;
        $attendee->save();

        $eventStats = EventStats::where('event_id', $subscription->event_id)->where('date', $subscription->created_at->format('Y-m-d'))->first();
        if($eventStats){
            $eventStats->decrement('tickets_sold',  1);
            $eventStats->decrement('sales_volume',  $subscription->competition->price);
        }

        $data = [
            'subscription'   => $subscription,
            'email_logo' => $subscription->event->organiser->full_logo_path,
        ];

        if ($request->get('notify_attendee') == '1') {
            Mail::send('Emails.notifyCancelledAttendee', $data, function ($message) use ($subscription) {
                $message->to($subscription->order->email, $subscription->order->full_name)
                    ->from(config('attendize.outgoing_email_noreply'), $subscription->event->organiser->name)
                    ->replyTo($subscription->event->organiser->email, $subscription->event->organiser->name)
                    ->subject(trans("Email.your_ticket_cancelled"));
            });
        }

        if ($request->get('refund_attendee') == '1') {

            try {
                // This does not account for an increased/decreased ticket price
                // after the original purchase.
                $refund_amount = $subscription->competition->price;
                $data['refund_amount'] = $refund_amount;

                $gateway = Omnipay::create($subscription->order->payment_gateway->name);

                // Only works for stripe
                $gateway->initialize($subscription->order->account->getGateway($subscription->order->payment_gateway->id)->config);

                $request = $gateway->refund([
                    'transactionReference' => $subscription->order->transaction_id,
                    'amount'               => $refund_amount,
                    'refundApplicationFee' => false,
                ]);

                $response = $request->send();

                if ($response->isSuccessful()) {

                    // Update the attendee and their order
                    $subscription->is_refunded = 1;
                    $subscription->order->is_partially_refunded = 1;
                    $subscription->order->amount_refunded += $refund_amount;

                    $subscription->order->save();
                    $subscription->save();

                    // Let the user know that they have received a refund.
                    Mail::send('Emails.notifyRefundedAttendee', $data, function ($message) use ($subscription) {
                        $message->to($subscription->email, $subscription->full_name)
                            ->from(config('attendize.outgoing_email_noreply'), $subscription->event->organiser->name)
                            ->replyTo($subscription->event->organiser->email, $subscription->event->organiser->name)
                            ->subject(trans("Email.refund_from_name", ["name"=>$subscription->event->organiser->name]));
                    });
                } else {
                    $error_message = $response->getMessage();
                }

            } catch (\Exception $e) {
                \Log::error($e);
                $error_message = trans("Controllers.refund_exception");

            }
        }

        if ($error_message) {
            return response()->json([
                'status'  => 'error',
                'message' => $error_message,
            ]);
        }

        session()->flash('message', trans("Controllers.successfully_cancelled_attendee"));

        return response()->json([
            'status'      => 'success',
            'id'          => $subscription->id,
            'redirectUrl' => '',
        ]);
    }
}


