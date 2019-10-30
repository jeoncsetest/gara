@extends('en.Emails.Layouts.Master')

@section('message_content')

<p>Ciao {{$first_name}}</p>
<p>
  Grazie per esserti registrato a {{ config('attendize.app_name') }}. Siamo entusiasti di averti con noi.
</p>

<p>
  Puoi creare il tuo primo evento e confermare l'e-mail utilizzando il link in basso.
</p>

<div style="padding: 5px; border: 1px solid #ccc;">
   {{route('confirmEmail', ['confirmation_code' => $confirmation_code])}}
</div>
<br><br>
<p>
    In caso di domande, commenti o suggerimenti, non esitare a rispondere a questa email.
</p>
<p>
   Grazie
</p>

@stop

@section('footer')


@stop
