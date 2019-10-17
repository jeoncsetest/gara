@extends('en.Emails.Layouts.Master')

@section('message_content')
Ciao,<br><br>

Il tuo ordine per l'evento <b>{{$order->event->title}}</b> ha avuto successo.<br><br>


I tuoi biglietti sono allegati in questa email. È inoltre possibile visualizzare i dettagli dell'ordine e scaricare i biglietti all'indirizzo: {{route('showOrderDetails', ['order_reference' => $order->order_reference])}}


<h3>Dettagli ordine</h3>
Riferimento ordine: <b>{{$order->order_reference}}</b><br>
Nome ordine: <b>{{$order->full_name}}</b><br>
Data ordine: <b>{{$order->created_at->format(config('attendize.default_datetime_format'))}}</b><br>
Emai ordine: <b>{{$order->email}}</b><br>

<h3>Dettaglio ordine</h3>
<div style="padding:10px; background: #F9F9F9; border: 1px solid #f1f1f1;">
    <table style="width:100%; margin:10px;">
        <tr>
            <td>
                <b>Ticket</b>
            </td>
            <td>
                <b>Quantità.</b>
            </td>
            <td>
                <b>Prezzo</b>
            </td>
            <td>
                <b>Tassa</b>
            </td>
            <td>
                <b>Totale</b>
            </td>
        </tr>
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
        <tr>
            <td>
            </td>
            <td>
            </td>
            <td>
            </td>
            <td>
                <b>Totale parziale</b>
            </td>
            <td colspan="2">
                {{$orderService->getOrderTotalWithBookingFee(true)}}
            </td>
        </tr>
        @if($order->event->organiser->charge_tax == 1)
        <tr>
            <td>
            </td>
            <td>
            </td>
            <td>
            </td>
            <td>
                <b>{{$order->event->organiser->tax_name}}</b>
            </td>
            <td colspan="2">
                {{$orderService->getTaxAmount(true)}}
            </td>
        </tr>
        @endif
        <tr>
            <td>
            </td>
            <td>
            </td>
            <td>
            </td>
            <td>
                <b>Totale</b>
            </td>
            <td colspan="2">
                {{$orderService->getGrandTotal(true)}}
            </td>
        </tr>
    </table>

    <br><br>
</div>
<br><br>
Grazie.
@stop
