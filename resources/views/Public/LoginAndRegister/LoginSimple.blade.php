@extends('Shared.Layouts.MasterDanceWithoutMenus')
@section('title')
@section('title', trans("User.login"))
@section('content')
{!! Form::open(array('url' => route("loginSimple"))) !!}
        <div class="col-md-4 col-md-offset-4">
            <div class="panel">
                <div class="panel-body">
                <div class="logo">
                        {!!HTML::image('assets/images/logo-dark.png')!!}
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
                    <!--<span>@lang("User.dont_have_account_button", ["url"=> route('showSignupSimple', ['signupType'=>'STUDENT'])])</span>-->
                    Non hai un account? <a  data-toggle="modal" data-target="#exampleModal" href="#"><span>Iscriviti</span></a>
     
                    
                <div class="modal " id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
              <div class="modal-dialog" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <h3 class="modal-title" id="exampleModalLabel">{{trans("Competition.cofirmation_popup_title")}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                    <br>
                  </div>
                  <div class="modal-body">
                  <span>
                  <h1>Come vuoi Iscriverti?</h1>
                  <br>
                  <a href="{{route('showSignupSimple', array('signupType'=>'TICKET'))}}" class="btn btn-primary btn-lg active" role="button" aria-pressed="true"><i class="fas fa-users"></i> Pubblico</a>
                  <a href="{{route('showSignupSimple', array('signupType'=>'STUDENT'))}}" class="btn btn-primary btn-lg active" role="button" aria-pressed="true"><i class="fas fa-users"></i> Ballerino</a>
                  <a href="{{route('showSignupSimple', array('signupType'=>'SCHOOL'))}}" class="btn btn-primary btn-lg active" role="button" aria-pressed="true"><i class="fas fa-users"></i> Scuola</a>
                             
                  </span>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{trans("Competition.close")}}</button>
                    <button type="button" class="btn btn-primary" id="remove_cart_item" data-dismiss="modal" >{{trans("Competition.confirm")}}</button>
                  </div>
                </div>
              </div>
            </div>   
                    </div>

                </div>
            </div>
        </div>
    {!! Form::close() !!}
@endsection
   

