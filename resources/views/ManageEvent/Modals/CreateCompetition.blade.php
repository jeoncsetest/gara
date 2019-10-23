<script>
$('.refrsh-closeModal').on('click', function() {
    window.location.reload();
});
var count_dyn_field_cat = 1;

dynamic_field_cat(count_dyn_field_cat);

function dynamic_field_cat(number)
{
 html = '<tr>';
       html += '<td><input type="text" name="category[]" class="form-control" /></td>';
       if(number > 1)
       {
           html += '<td><button type="button" name="remove_cat" id="remove_cat" class="btn btn-danger remove">Remove</button></td></tr>';
           $('#dyn_field_category').append(html);
       }
       else
       {   
           html += '<td><button type="button" name="add_cat" id="add_cat" class="btn btn-success">Add</button></td></tr>';
           $('#dyn_field_category').html(html);
       }
}

$(document).on('click', '#add_cat', function(){
 count_dyn_field_cat++;
 dynamic_field_cat(count_dyn_field_cat);
});

$(document).on('click', '#remove_cat', function(){
 count_dyn_field_cat--;
 $(this).closest("tr").remove();
});

/* start levels */
var count_dyn_field_level = 1;

dynamic_field_level(count_dyn_field_level);

function dynamic_field_level(number)
{
 html = '<tr>';
       html += '<td><input type="text" name="level[]" class="form-control" /></td>';
       if(number > 1)
       {
           html += '<td><button type="button" name="remove_level" id="remove_level" class="btn btn-danger remove">Remove</button></td></tr>';
           $('#dyn_field_level').append(html);
       }
       else
       {   
           html += '<td><button type="button" name="add_level" id="add_level" class="btn btn-success">Add</button></td></tr>';
           $('#dyn_field_level').html(html);
       }
}

$(document).on('click', '#add_level', function(){
    count_dyn_field_level++;
dynamic_field_level(count_dyn_field_level);
});

$(document).on('click', '#remove_level', function(){
    count_dyn_field_level--;
 $(this).closest("tr").remove();
});
/* end levels */
</script>
<div role="dialog"  class="modal fade" style="display: none;">
   {!! Form::open(array('url' => route('postCreateCompetition', array('event_id' => $event->id)), 'class' => 'ajax')) !!}
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header text-center">
                <button type="button" id="modal1" class="close refrsh-closeModal" data-dismiss="modal">×</button>
                <h3 class="modal-title">
                    <i class="ico-ticket"></i>
                    @lang("Competition.create_competition")</h3>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            {!! Form::label('title', trans("Competition.competition_title"), array('class'=>'control-label required')) !!}
                            {!!  Form::text('title', Input::old('title'),
                                        array(
                                        'class'=>'form-control',
                                        'required' => 'required',
                                        'placeholder'=>trans("Competition.competition_title_placeholder")
                                        ))  !!}
                        </div>
                        <div class="form-group">       
                        {!! Form::label('type', trans("Competition.discipline"), array('class'=>'control-label required')) !!}                                
                            <select name='discipline_id' id="discipline_id">
                                @foreach ($disciplines as $discipline)
                                <option value="{{ $discipline->id}}">{{ $discipline->discipline_name }}</option>">
                                @endforeach
                            </select>
                        </div>
						<div class="form-group">
                            {!! Form::label('type', trans("Competition.competition_type"), array('class'=>'control-label required')) !!}
                            {!!  Form::select('type', array('S' => trans("Competition.competition_type_single"),
                             'D' => trans("Competition.competition_type_double"), 'G' => trans("Competition.competition_type_group")),
                                        array(
                                        'class'=>'form-control',
                                        'required' => 'required',
                                        'placeholder'=>trans("Competition.competition_type_placeholder")
                                        ))  !!}
                        </div>
                        <div class="form-group">
                            {!! Form::label('level', trans("Competition.competition_level"), array('class'=>'control-label required')) !!}
                           
                           <!-- {!!  Form::text('level', Input::old('level'),
                                        array(
                                        'class'=>'form-control',
                                        'required' => 'required',
                                        'placeholder'=>trans("Competition.competition_level_placeholder")
                                        ))  !!}-->
                            <table class="table table-bordered table-striped" id="user_table">
                            <tbody id='dyn_field_level'>
                            </tbody>
                            </table>
                        </div>
                        <!--
                        <div class="form-group">
                            {!! Form::label('category', trans("Competition.competition_category"), array('class'=>'control-label required')) !!}
                            {!!  Form::text('category', Input::old('category'),
                                        array(
                                        'class'=>'form-control',
                                        'required' => 'required',
                                        'placeholder'=>trans("Competition.competition_category_
                                        placeholder")
                                        ))  !!}
                        </div>-->
                        <div class="form-group">
                            {!! Form::label('category', trans("Competition.competition_category"), array('class'=>'control-label required')) !!}
                            <table class="table table-bordered table-striped" id="user_table">
                            <tbody id='dyn_field_category'>
                            </tbody>
                            </table>
                        </div>
                        <div class="row more-options">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <div class="custom-checkbox">
                                        {!! Form::checkbox('mp3_upload', 1, false, ['id' => 'mp3_upload']) !!}
                                        {!! Form::label('mp3_upload', trans("Competition.competition_mp3"), 
                                            array(
                                                'class'=>' control-label'
                                                )) !!}
                                    </div>

                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    {!! Form::label('price', trans("Competition.price"), array('class'=>'control-label required')) !!}
                                    {!!  Form::text('price', Input::old('price'),
                                                array(
                                                'class'=>'form-control',
                                                'required' => 'required',
                                                'placeholder'=>trans("Competition.price_placeholder")
                                                ))  !!}


                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="form-group">
                                    {!! Form::label('max_competitors', trans("Competition.max_competitors"), array('class'=>' control-label')) !!}
                                    {!!  Form::text('max_competitors', Input::old('max_competitors'),
                                                array(
                                                'class'=>'form-control',
                                                'required' => 'required|numeric',
                                                'placeholder'=>trans("Competition.max_competitors_placeholder")
                                                )
                                                )  !!}
                                </div>
                            </div>

                        </div>
               
                    </div>
                    <div class="col-md-12">
                        <a href="javascript:void(0);" class="show-more-options">
                            @lang("ManageEvent.more_options")
                        </a>
                    </div>

                </div>

            </div> <!-- /end modal body-->
            <div class="modal-footer">
               {!! Form::button(trans("basic.cancel"), ['class'=>"btn modal-close btn-danger refrsh-closeModal",'data-dismiss'=>'modal', 'id'=>'cancButtonMod']) !!}
               {!! Form::submit(trans("Competition.create_competition"), ['class'=>"btn btn-success"]) !!}
            </div>
        </div><!-- /end modal content-->
       {!! Form::close() !!}
    </div>
</div>

