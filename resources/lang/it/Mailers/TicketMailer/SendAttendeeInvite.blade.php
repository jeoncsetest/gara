@extends('en.Emails.Layouts.Master')

@section('message_content')
Ciao {{$attendee->first_name}},<br><br>

Sei stato invitato alla seguente gara: <b>{{$attendee->order->event->title}}</b>.<br/>
I biglietti sono stati allegati a questa mail.

<br><br>
Grazie.
@stop
