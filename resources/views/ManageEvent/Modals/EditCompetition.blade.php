<div role="dialog"  class="modal fade " style="display: none;">
    {!! Form::model($competition, ['url' => route('postEditCompetition', ['competition_id' => $competition->id, 'event_id' => $event->id]), 'class' => 'ajax']) !!}
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header text-center">
                <button type="button" class="close" data-dismiss="modal">×</button>
                <h3 class="modal-title">
                    <i class="ico-ticket"></i>
                    @lang("ManageEvent.edit_competition", ["title"=>$competition->title])</h3>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    {!! Form::label('title', trans("ManageEvent.competition_title"), array('class'=>'control-label required')) !!}
                    {!!  Form::text('title', null,['class'=>'form-control',
                                'required' => 'required', 'placeholder'=>'E.g: General Admission']) !!}
                </div>
                <div class="row">
                    <div class="col-sm-6">
                    	<div class="form-group">
                            {!! Form::label('type', trans("Competition.competition_type"), array('class'=>'control-label required')) !!}
                            {!! Form::select('type', array('S' => trans("Competition.competition_type_single"),
                             'D' => trans("Competition.competition_type_double"), 'G' => trans("Competition.competition_type_group"))) !!}
                        </div>
                    </div>
                    </div>
                    <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            {!! Form::label('price', trans("ManageEvent.ticket_price"), array('class'=>'control-label required')) !!}
                            {!!  Form::text('price', null,
                                        array(
                                        'class'=>'form-control',
                                        'required' => 'required',
                                        'placeholder'=>trans("ManageEvent.price_placeholder")
                                        ))  !!}
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            {!! Form::label('max_competitors', trans("Competition.max_competitors"), array('class'=>' control-label')) !!}
                            {!!  Form::text('max_competitors', null,
                                        array(
                                        'class'=>'form-control',
                                        'required' => 'required',
                                        'placeholder'=>trans("Competition.max_competitors_placeholder")
                                        )
                                        )  !!}
                        </div>
                    </div>
                </div>

                <div class="form-group more-options">
                    {!! Form::label('level', trans("Competition.competition_level"), array('class'=>'control-label')) !!}
                    <select>
                    @foreach ($competition->levels as $iter)
                                                            <option value="{{ $iter->id }}">{{ $iter->level }}</option>">
                                                        @endforeach
                                                        </select>
                </div>
                <div class="form-group more-options">
                    {!! Form::label('category', trans("Competition.competition_category"), array('class'=>'control-label')) !!}
                    <select>
                    @foreach ($competition->categories as $iter)
                                                            <option value="{{ $iter->id }}">{{ $iter->category }}</option>">
                                                        @endforeach
                                                        </select>
                </div>
                <div class="form-group more-options">
                                <div class="form-group">
                                    <div class="custom-checkbox">
                                        {!! Form::checkbox('mp3_upload', null, null, ['id' => 'mp3_upload']) !!}
                                        {!! Form::label('mp3_upload', trans("Competition.competition_mp3"), 
                                            array(
                                                'class'=>' control-label'
                                                )) !!}
                                    </div>

                                </div>
                            </div>

                <a href="javascript:void(0);" class="show-more-options">
                    @lang("ManageEvent.more_options")
                </a>
            </div> <!-- /end modal body-->
            <div class="modal-footer">
                {!! Form::button(trans("basic.cancel"), ['class'=>"btn modal-close btn-danger",'data-dismiss'=>'modal']) !!}
                {!! Form::submit(trans("Competition.save_competition"), ['class'=>"btn btn-success"]) !!}
            </div>
        </div><!-- /end modal content-->
       {!! Form::close() !!}
    </div>
</div>
