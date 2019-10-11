@extends('Public.ViewEvent.Layouts.master')
@section('title')
Login simple
@endsection

@section('content')
{!! Form::open(array('url' => route("loginSimple"))) !!}
    <div class="row">
        <div class="col-md-4 col-md-offset-4">
            <div class="panel">
                <div class="panel-body">
                    <div class="logo">
                        
                    </div>

                    @if(Session::has('failed'))
                        <h4 class="text-danger mt0">@lang("basic.whoops")! </h4>
                        <ul class="list-group">
                            <li class="list-group-item">@lang("User.login_fail_msg")</li>
                        </ul>
                    @endif

                    <div class="form-group">
                        {!! Form::label('email', trans("User.email"), ['class' => 'control-label']) !!}
                        {!! Form::text('email', null, ['class' => 'form-control', 'autofocus' => true]) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('password', trans("User.password"), ['class' => 'control-label']) !!}
                        (<a class="forgotPassword" href="{{route('forgotPassword')}}" tabindex="-1">@lang("User.forgot_password?")</a>)
                        {!! Form::password('password',  ['class' => 'form-control']) !!}
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-block btn-success">@lang("User.login")</button>
                    </div>

               
                    <div class="signup">
                        <span>@lang("User.dont_have_account_button", ["url"=> route('showSignupSimple', ['signupType'=>'STUDENT'])])</span>
                    </div>

                </div>
            </div>
        </div>
    </div>
    {!! Form::close() !!}
@endsection
   

