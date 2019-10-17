Ciao {{{$attendee->first_name}}},<br><br>

Abbiamo allegato i tuoi biglietti a questa email.<br><br>

È possibile visualizzare le informazioni sull'ordine e scaricare i biglietti all'indirizzo {{route('showOrderDetails', ['order_reference' => $attendee->order->order_reference])}} in qualsiasi momento.<br><br>

Il riferimento dell'ordine è <b>{{$attendee->order->order_reference}}</b>.<br>

Grazie.<br>
