@extends('Shared.Layouts.MasterDanceWithoutMenus')

@section('title')
    @lang("User.sign_up")
@stop

@section('content')
    <div class="row">
        <div class="col-md-7 col-md-offset-2">
            {!! Form::open(array('url' => route("showSignupSimple"), 'class' => 'panel')) !!}
            <div class="panel-body">
                <div class="logo">
                   {!! HTML::image('assets/images/logo-dark.png') !!}
                </div>
                <h2>@lang("User.sign_up") - {{$signupType}}
                </h2>

                @if(Input::get('first_run'))
                    <div class="alert alert-info">
                        @lang("User.sign_up_first_run")
                    </div>
                @endif

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group {{ ($errors->has('first_name')) ? 'has-error' : '' }}">
                            {!! Form::label('first_name', trans("User.first_name"), ['class' => 'control-label required']) !!}
                            {!! Form::text('first_name', null, ['class' => 'form-control']) !!}
                            @if($errors->has('first_name'))
                                <p class="help-block">{{ $errors->first('first_name') }}</p>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group {{ ($errors->has('last_name')) ? 'has-error' : '' }}">
                            {!! Form::label('last_name', trans("User.last_name"), ['class' => 'control-label']) !!}
                            {!! Form::text('last_name', null, ['class' => 'form-control']) !!}
                            @if($errors->has('last_name'))
                                <p class="help-block">{{ $errors->first('last_name') }}</p>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="form-group {{ ($errors->has('email')) ? 'has-error' : '' }}">
                    {!! Form::label('email', trans("User.email"), ['class' => 'control-label required']) !!}
                    {!! Form::text('email', null, ['class' => 'form-control']) !!}
                    @if($errors->has('email'))
                        <p class="help-block">{{ $errors->first('email') }}</p>
                    @endif
                </div>
                <div class="form-group {{ ($errors->has('password')) ? 'has-error' : '' }}">
                    {!! Form::label('password', trans("User.password"), ['class' => 'control-label required']) !!}
                    {!! Form::password('password',  ['class' => 'form-control']) !!}
                    @if($errors->has('password'))
                        <p class="help-block">{{ $errors->first('password') }}</p>
                    @endif
                </div>
                <div class="form-group {{ ($errors->has('password_confirmation')) ? 'has-error' : '' }}">
                    {!! Form::label('password_confirmation', 'Password again', ['class' => 'control-label required']) !!}
                    {!! Form::password('password_confirmation',  ['class' => 'form-control']) !!}
                    @if($errors->has('password_confirmation'))
                        <p class="help-block">{{ $errors->first('password_confirmation') }}</p>
                    @endif
                </div>

                @if(Utils::isAttendize())
                <div class="form-group {{ ($errors->has('terms_agreed')) ? 'has-error' : '' }}">
                    <div class="checkbox custom-checkbox">
                        {!! Form::checkbox('terms_agreed', Input::old('terms_agreed'), false, ['id' => 'terms_agreed']) !!}
                        {!! Form::rawLabel('terms_agreed', trans("User.terms_and_conditions", ["url"=>route('termsAndConditions')])) !!}
                        @if ($errors->has('terms_agreed'))
                            <p class="help-block">{{ $errors->first('terms_agreed') }}</p>
                        @endif
                    </div>
                </div>
                @endif
                
                <input type="hidden" name="signupType" value="{{$signupType}}">
                @if($signupType == 'STUDENT')
                <div class="form-group {{ ($errors->has('fiscal_code')) ? 'has-error' : '' }}">
                    {!! Form::label('fiscal_code', trans("User.fiscal_code"), ['class' => 'control-label required']) !!}
                    {!! Form::text('fiscal_code', null, ['class' => 'form-control']) !!}
                    @if($errors->has('fiscal_code'))
                        <p class="help-block">{{ $errors->first('fiscal_code') }}</p>
                    @endif
                </div>
                <div class="form-group {{ ($errors->has('school_eps')) ? 'has-error' : '' }}">
                    {!! Form::label('school_eps', trans("User.school_eps"), ['class' => 'control-label required']) !!}
                    {!! Form::text('school_eps', null, ['class' => 'form-control']) !!}
                    @if($errors->has('school_eps'))
                        <p class="help-block">{{ $errors->first('school_eps') }}</p>
                    @endif
                </div>
                <div class="form-group {{ ($errors->has('phone')) ? 'has-error' : '' }}">
                    {!! Form::label('phone', trans("User.phone"), ['class' => 'control-label required']) !!}
                    {!! Form::text('phone', null, ['class' => 'form-control']) !!}
                    @if($errors->has('phone'))
                        <p class="help-block">{{ $errors->first('phone') }}</p>
                    @endif
                </div>
                <div class="form-group {{ ($errors->has('birth_date')) ? 'has-error' : '' }}">
                    {!! Form::label('birth_date', trans("User.birth_date"), ['class' => 'control-label required']) !!}
                    {!! Form::date('birth_date', null, ['class' => 'form-control']) !!}
                    @if($errors->has('birth_date'))
                        <p class="help-block">{{ $errors->first('birth_date') }}</p>
                    @endif
                </div>
                <div class="form-group {{ ($errors->has('birth_place')) ? 'has-error' : '' }}">
                    {!! Form::label('birth_place', trans("User.birth_place"), ['class' => 'control-label required']) !!}
                    {!! Form::text('birth_place', null, ['class' => 'form-control']) !!}
                    @if($errors->has('birth_place'))
                        <p class="help-block">{{ $errors->first('birth_place') }}</p>
                    @endif
                </div>

                @elseif($signupType == 'SCHOOL')
                <div class="form-group {{ ($errors->has('name')) ? 'has-error' : '' }}">
                    {!! Form::label('name', trans("User.name"), ['class' => 'control-label required']) !!}
                    {!! Form::text('name', null, ['class' => 'form-control']) !!}
                    @if($errors->has('name'))
                        <p class="help-block">{{ $errors->first('name') }}</p>
                    @endif
                </div>
                <div class="form-group {{ ($errors->has('eps')) ? 'has-error' : '' }}">
                    {!! Form::label('eps', trans("User.eps"), ['class' => 'control-label required']) !!}
                    {!! Form::text('eps', null, ['class' => 'form-control']) !!}
                    @if($errors->has('eps'))
                        <p class="help-block">{{ $errors->first('eps') }}</p>
                    @endif
                </div>
                <div class="form-group {{ ($errors->has('phone')) ? 'has-error' : '' }}">
                    {!! Form::label('phone', trans("User.phone"), ['class' => 'control-label required']) !!}
                    {!! Form::text('phone', null, ['class' => 'form-control']) !!}
                    @if($errors->has('phone'))
                        <p class="help-block">{{ $errors->first('phone') }}</p>
                    @endif
                </div>
                <div class="form-group {{ ($errors->has('place')) ? 'has-error' : '' }}">
                    {!! Form::label('place', trans("User.place"), ['class' => 'control-label required']) !!}
                    {!! Form::text('place', null, ['class' => 'form-control']) !!}
                    @if($errors->has('place'))
                        <p class="help-block">{{ $errors->first('place') }}</p>
                    @endif
                </div>
                <div class="form-group {{ ($errors->has('address')) ? 'has-error' : '' }}">
                    {!! Form::label('address', trans("User.address"), ['class' => 'control-label required']) !!}
                    {!! Form::text('address', null, ['class' => 'form-control']) !!}
                    @if($errors->has('address'))
                        <p class="help-block">{{ $errors->first('address') }}</p>
                    @endif
                </div>
                <div class="form-group {{ ($errors->has('city')) ? 'has-error' : '' }}">
                    {!! Form::label('city', trans("User.city"), ['class' => 'control-label required']) !!}
                    {!! Form::text('city', null, ['class' => 'form-control']) !!}
                    @if($errors->has('city'))
                        <p class="help-block">{{ $errors->first('city') }}</p>
                    @endif
                </div>
                @endif
             

                <div class="form-group ">
                   {!! Form::submit(trans("User.sign_up"), array('class'=>"btn btn-block btn-success")) !!}
                </div>
                    <div class="signupSimple">
                        <span>{!! @trans("User.already_have_account", ["url"=>route("loginSimple")]) !!}</span>
                    </div>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
@stop
