    <div class="row">
        <div class="col-md-7 col-md-offset-2">
            {!! Form::open(array('url' => route("postAddStudent"), 'class' => 'panel')) !!}
            <div class="panel-body">
                <h2 style="text-align:center">Iscrivere studenti
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

                <div class="form-group {{ ($errors->has('fiscal_code')) ? 'has-error' : '' }}">
                    {!! Form::label('fiscal_code', trans("User.fiscal_code"), ['class' => 'control-label required']) !!}
                    {!! Form::text('fiscal_code', null, ['class' => 'form-control']) !!}
                    @if($errors->has('fiscal_code'))
                        <p class="help-block">{{ $errors->first('fiscal_code') }}</p>
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
                <div class="form-group ">
                   {!! Form::submit(trans("User.add_student"), array('class'=>"btn btn-block btn-success")) !!}
                </div>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
