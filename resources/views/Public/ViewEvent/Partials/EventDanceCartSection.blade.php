<div class="container">

@if(Cart::count()>0)
{!! Form::open(['url' => route('postValidateCartItems', ['event_id' => $event->id]), 'class' => 'ajax gf',  'enctype'=>'multipart/form-data']) !!}
<div class="row" id="div_event_dance_cart">
	<table class="table table-striped table-light" id="competition_table">
      <thead>
          <tr>
              <th>Identificativo</th>
              <th>Descrizione di ballo</th>
              <th>tipo</th>
              <th>Livello</th>
              <th>Categoria</th>
              <th>Prezzo</th>
              <th>Scegli file Mp3</th>  
              <th>participant</th>  
              <th></th>                      
          </tr>
      </thead>

      <tbody>
        @foreach(Cart::content() as $row)  
        <tr id="{{$row->rowId}}">
        <td>  
          {!! Form::hidden('cartIds[]', $row->id) !!}
          <input name="competition_type_{{$row->options->competition_id}}" type="hidden" value="{{$row->options->type}}">
                <input name="competitionId_{{$row->id}}" type="hidden" value="{{($row->options->has('competition_id') ? $row->options->competition_id : '')}}">
          <label class="form-control" id="identificativo">{{$row->id}}</label></td>
        <td>  <label class="form-control" id="description">{{($row->options->has('competition_title') ? $row->options->competition_title : '')}}</label></td>
        <td>  <label class="form-control" id="typedance">{{($row->options->has('type') ? $row->options->type : '')}}</label></td>
        <td>  <label class="form-control" id="description">{{($row->options->has('level') ? $row->options->level : '')}}</label></td>
        <td>   <label class="form-control" id="Category" >{{($row->options->has('category') ? $row->options->category : '')}}</label>
        <td>   <label class="form-control" id="price" >{{money($row->price, $event->currency)}} </label></td>
        <td>
            <div class="input-group">
            <div class="custom-file">
              <input type="hidden" name="mp3_file_name_{{$row->id}}" value="mp3-{{$event->id}}-{{$row->options->competition_id}}-{{$row->id}}">
              <input type="file" name="mp3-{{$event->id}}-{{$row->options->competition_id}}-{{$row->id}}" id="mp3-{{$event->id}}-{{$row->options->competition_id}}-{{$row->id}}" > 
            </div>
          </div>
        </td>
        <td>
        @if($row->options->has('type'))
            @if($row->options->type == trans("Competition.competition_type_single_abbr"))
              <div class="form-group more-options">
              <table>
              <tr>
                <td>   
                  <div class="ui-widget">
                    <select name='participants_{{$row->id}}[]' class="combobox">
                    @foreach ($students as $iter)
                      <option value="{{ $iter->id }}">{{ $iter->name }} {{ $iter->surname }}</option>>
                    @endforeach
                    </select>
                  </div>
                </td>
              </tr>
              </table>
              </div>
            @elseif($row->options->type == trans("Competition.competition_type_double_abbr"))
            <div class="form-group more-options">
              {!! Form::label('participant', trans("Competition.participant"), array('class'=>'control-label')) !!}
              <table>
              <tr>
                <td>   
                  <div class="ui-widget">
                    <select name='participants_{{$row->id}}[]' class="combobox">
                    @foreach ($students as $iter)
                      <option value="{{ $iter->id }}">{{ $iter->name }} {{ $iter->surname }}</option>>
                    @endforeach
                    </select>
                  </div>
                </td>   
              </tr>
              <tr>
                <td>
                <div class="ui-widget">
                    <select name='participants_{{$row->id}}[]' class="combobox">
                    @foreach ($students as $iter)
                      <option value="{{ $iter->id }}">{{ $iter->name }} {{ $iter->surname }}</option>>
                    @endforeach
                    </select>
                  </div>
                </td>
              </tr>
              </table>
              </div>
            @elseif($row->options->type == trans("Competition.competition_type_group_abbr"))
            <div class="form-group more-options" >
              {!! Form::label('participant', trans("Competition.participant"), array('class'=>'control-label')) !!}
              <table id="dyn_participants_{{$row->id}}">
              <tr>
                <td>   
                  <div class="ui-widget">
                    <select name='participants_{{$row->id}}[]' class="combobox">
                    @foreach ($students as $iter)
                      <option value="{{ $iter->id }}">{{ $iter->name }} {{ $iter->surname }}</option>>
                    @endforeach
                    </select>
                  </div>
                </td>
              </tr>
              <tr>
              <td>
              <div class="ui-widget">
                  <select name='participants_{{$row->id}}[]' class="combobox">
                  @foreach ($students as $iter)
                    <option value="{{ $iter->id }}">{{ $iter->name }} {{ $iter->surname }}</option>>
                  @endforeach
                  </select>
                </div>
              </td>
              </tr>
              </table>
              <div  class="ui-widget">
              <button type="button" class="btn btn-primary" onclick="add_participant({{$row->id}})">add</button>
              <button type="button" class="btn btn-danger" onclick="remove_participant({{$row->id}})">remove</button>
              </div>
              <div  class="ui-widget">
              {!! Form::label('participant', trans("Competition.group_name"), array('class'=>'control-label')) !!}
              <input name="grp_name_{{$row->id}}" type="text" value="">
              </div>
               
                <!--<div  class="ui-widget"><a href="#" onclick="add_participant({{$row->id}}, count(participants_{{$row->id}})">add</a></div>-->
              </div>
              
            @endif
          @endif
        </td>
        <td>  
          <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#exampleModal" data-cartrowid="{{$row->rowId}}" data-cartitem="{{trans('Competition.delete_cart_item_confirmation', ['competitionTitle' => ($row->options->has('competition_title') ? $row->options->competition_title : '')])}}">Elimina</button>
            <div class="modal " id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
              <div class="modal-dialog" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">{{trans("Competition.cofirmation_popup_title")}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  <div class="modal-body">
                  <span>
                    {{trans('Competition.delete_cart_item_confirmation', ['competitionTitle' => ($row->options->has('competition_title') ? $row->options->competition_title : '')])}}
                  </span>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{trans("Competition.close")}}</button>
                    <button type="button" class="btn btn-primary" id="remove_cart_item" data-dismiss="modal" >{{trans("Competition.confirm")}}</button>
                  </div>
                </div>
              </div>
            </div>    
               <input name="qty_{{$row->id}}" type="hidden" value="{{$row->qty}}">
      </tr>
        @endforeach
      </tbody>
      
      <tfoot>
        <tr>
          <td>&nbsp;</td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td>Total</td>
          <td id="cartTotal">{{money(Cart::subtotal(), $event->currency)}}</td>
        </tr>
        <tr>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td>
              {!!Form::submit(trans("Public_ViewEvent.register"), ['class' => 'btn btn-lg btn-primary pull-right'])!!}
          </td>
        </tr>
      </tfoot>
  </table>
  </div>
{!! Form::close() !!}
@endif
</div>