@extends('en.Emails.Layouts.Master')

@section('message_content')

<p>Ciao,</p>
<p>
  Il tuo biglietto per l'evento <b>{{{$attendee->event->title}}}</b> è stato cancellato.
</p>

<p>
  Puoi contattare <b>{{{$attendee->event->organiser->name}}}</b> directly at <a href='mailto:{{{$attendee->event->organiser->email}}}'>{{{$attendee->event->organiser->email}}}</a> o rispondendo a questa email se hai bisogno di ulteriori informazioni.
</p>
@stop

@section('footer')

@stop
