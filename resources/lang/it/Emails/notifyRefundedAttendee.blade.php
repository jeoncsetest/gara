@extends('en.Emails.Layouts.Master')

@section('message_content')

    <p>Ciao,</p>
    <p>
        Hai ricevuto un rimborso per conto del tuo biglietto cancellato per <b>{{{$attendee->event->title}}}</b>.
        <b>{{{ $refund_amount }}} è stato rimborsato al beneficiario originale, dovresti vedere il pagamento tra qualche giorno.</b>
    </p>

    <p>
        Puoi contattare <b>{{{ $attendee->event->organiser->name }}}</b> direttamente all'indirizzo <a href='mailto:{{{$attendee->event->organiser->email}}}'>{{{$attendee->event->organiser->email}}}</a> o rispondendo a questa email se hai bisogno di ulteriori informazioni.
    </p>
@stop

@section('footer')

@stop
