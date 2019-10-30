@extends('en.Emails.Layouts.Master')

@section('message_content')

<p>Ciao</p>
<p>
  Sei stato aggiunto a {{ config('attendize.app_name') }} account di {{$inviter->first_name.' '.$inviter->last_name}}.
</p>

<p>
  È possibile accedere utilizzando i seguenti dettagli.<br><br>

    Username: <b>{{$user->email}}</b> <br>
    Password: <b>{{$temp_password}}</b>
</p>

<p>
  È possibile modificare la password temporanea dopo aver effettuato l'accesso.
</p>

<div style="padding: 5px; border: 1px solid #ccc;" >
   {{route('login')}}
</div>
<br><br>
<p>
  In caso di domande, rispondi a questa email.
</p>
<p>
    Grazie
</p>

@stop

@section('footer')


@stop
