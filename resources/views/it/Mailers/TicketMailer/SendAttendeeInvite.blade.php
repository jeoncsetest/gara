@extends('en.Emails.Layouts.Master')

@section('message_content')
Ciao, {{$attendee->first_name}},<br><br>

Sei stato invitato all'evento  <b>{{$attendee->order->event->title}}</b>.<br/>
Il tuo biglietto per l'evento è allegato a questa email.

<br><br>
Grazie.
@stop
