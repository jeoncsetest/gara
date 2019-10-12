@extends('it.Emails.Layouts.Master')

@section('message_content')
    <div>
        Ciao,<br><br>
        Per reimpostare la password, completare questo form: {{ route('password.reset', ['token' => $token]) }}.
        <br><br><br>
        Grazie,<br>
        Team Dancematik
    </div>
@stop
