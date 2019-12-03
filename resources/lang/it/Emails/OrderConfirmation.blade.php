@extends('en.Emails.Layouts.Master')

@section('message_content')
Ciao,<br><br>

Il tuo ordine per l'evento <b>{{$order->event->title}}</b> ha avuto successo.<br><br>


I tuoi biglietti sono allegati a questa email. È inoltre possibile visualizzare i dettagli dell'ordine e scaricare i biglietti all'indirizzo: {{route('showOrderDetails', ['order_reference' => $order->order_reference])}}


<h3>Dettagli ordine</h3>
Riferimento ordine: <b>{{$order->order_reference}}</b><br>
Nome ordine: <b>{{$order->full_name}}</b><br>
Data ordine: <b>{{$order->created_at->format(config('attendize.default_datetime_format'))}}</b><br>
Emai ordine: <b>{{$order->email}}</b><br>

<h3>Ordina articoli</h3>
<div style="padding:10px; background: #F9F9F9; border: 1px solid #f1f1f1;">
    <table style="width:100%; margin:10px;">
    <thead>
                            <tr>
                                <th>
                                    @lang("Public_ViewEvent.ticket")
                                </th>
                                @if(!empty($order->subscriptions) && $order->subscriptions->count()>0)
                                    <th>
                                        @lang("Public_ViewEvent.students")
                                    </th>
                                @endif
                                <th>
                                    @lang("Public_ViewEvent.quantity_full")
                                </th>
                                <th>
                                    @lang("Public_ViewEvent.price")
                                </th>
                                @if(empty($order->subscriptions) || $order->subscriptions->count()==0)
                                    <th>
                                        @lang("Public_ViewEvent.booking_fee")
                                    </th>
                                @endif
                                <th>
                                    @lang("Public_ViewEvent.total")
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(empty($order->subscriptions) || $order->subscriptions->count()==0)
                                @foreach($order->orderItems as $order_item)
                                    <tr>
                                        <td>
                                            {{$order_item->title}}
                                        </td>
                                        <td>
                                            {{$order_item->quantity}}                                  
                                        </td>
                                        <td>
                                            @if((int)ceil($order_item->unit_price) == 0)
                                            FREE
                                            @else
                                        {{money($order_item->unit_price, $order->event->currency)}}
                                            @endif
                                        </td>
                                        <td>
                                            @if((int)ceil($order_item->unit_price) == 0)
                                                -
                                            @else
                                                {{money($order_item->unit_booking_fee, $order->event->currency)}}
                                            @endif
                                        </td>
                                        <td>
                                            @if((int)ceil($order_item->unit_price) == 0)
                                                FREE
                                            @else
                                                {{money(($order_item->unit_price + $order_item->unit_booking_fee) * ($order_item->quantity), $order->event->currency)}}
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                @foreach($order->subscriptions as $subscription)
                                    <tr>
                                        <td>
                                            {{$subscription->competition->title}}
                                        </td>
                                        <td>
                                            <table>
                                                @foreach($subscription->participants as $participant)
                                                    <tr>
                                                        <td>
                                                            {{$participant->Student->surname}}  {{$participant->Student->name}}
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </table>
                                        </td>
                                        <td>1</td>
                                        <td>
                                            @if((int)ceil($subscription->competition->price) == 0)
                                                FREE
                                            @else
                                                {{money($subscription->competition->price, $order->event->currency)}}
                                            @endif
                                        </td>
                                        <td>
                                            @if((int)ceil($subscription->competition->price) == 0)
                                                FREE
                                            @else
                                                {{money(($subscription->competition->price) * 1, $order->event->currency)}}
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            @endif                            
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td>
                                    <b>@lang("Public_ViewEvent.sub_total")</b>
                                </td>
                                <td colspan="2">
                                    {{ $orderService->getOrderTotalWithBookingFee(true) }}
                                </td>
                            </tr>
                            @if($order->event->organiser->charge_tax)
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td>
                                        <strong>{{$order->event->organiser->tax_name}}</strong><em>({{$order->event->organiser->tax_value}}%)</em>
                                    </td>
                                    <td colspan="2">
                                        {{ $orderService->getTaxAmount(true) }}
                                    </td>
                                </tr>
                            @endif
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td>
                                    <b>Total</b>
                                </td>
                                <td colspan="2">
                                   {{ $orderService->getGrandTotal(true) }}
                                </td>
                            </tr>
                        </tbody>
    </table>

    <br><br>
</div>
<br><br>
Grazie
@stop
