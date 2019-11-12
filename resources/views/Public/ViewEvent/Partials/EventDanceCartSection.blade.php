<div class="breadcrumbs-wrap no-title">
   <div class="container">
     <ul class="breadcrumbs">
       <li>Home</a></li>
       <li>Gare</li>
       <li>{{ $event->title }}</li>
       <li>Riepilogo</li>
     </ul>
   </div>
   <div>
     <p><h1 style="text-align:center">Riepilogo iscrizioni gare</h1></p>
   </div>

     <div id="content" class="page-content-wrap">
<div class="container">

@if(Cart::count()>0)
{!! Form::open(['url' => route('postValidateCartItems', ['event_id' => $event->id]), 'class' => 'ajax gf',  'enctype'=>'multipart/form-data', 'id'=>'cartCheckoutForm']) !!}
<div class="content-element">
<div class="row" id="div_event_dance_cart">
	<table class="table-type-1" id="competition_table">
    <thead>
          <tr>
          <th scope="col"></th>
          <th scope="col">Descrizione</th>
          <th scope="col">tipo</th>
          <th scope="col">Livello</th>
          <th scope="col">Categoria</th>
          <th scope="col">Prezzo</th>
          <th scope="col">Scegli file Mp3</th>
          <th scope="col">{{trans("Competition.participant")}}</th>
          <th scope="col"></th>
          </tr>
      </thead>

      <tbody>
        @foreach(Cart::content() as $row)
        <tr id="{{$row->rowId}}">
        <td>
          {!! Form::hidden('cartIds[]', $row->id) !!}
          <input name="competition_type_{{$row->options->competition_id}}" type="hidden" value="{{$row->options->type}}">
                <input name="competitionId_{{$row->id}}" type="hidden" value="{{($row->options->has('competition_id') ? $row->options->competition_id : '')}}">
         <!-- <label class="form-control" id="identificativo">{{$row->id}}</label>-->
          <button type="button" class="btn btn-danger" onclick="showPopupRemoveItem('{{$row->rowId}}', {{$row->id}}, '{{trans('Competition.delete_cart_item_confirmation', ['competitionTitle' => ($row->options->has('competition_title') ? $row->options->competition_title : '')])}}')" ><i class="fas fa-times-circle"></i></button>
          <div id="popup-removeCart_{{$row->id}}" class="popup var3">
          <div class="popup-inner">
            <button type="button" class="close-popup"></button>
            <h4 class="title">Eliminare dal carrello</h4>
            <span>
              {{trans('Competition.delete_cart_item_confirmation', ['competitionTitle' => ($row->options->has('competition_title') ? $row->options->competition_title : '')])}}
            </span>
            <button type="button" name="{{$row->rowId}}" class="btn btn-primary" id="remove_cart_item" >{{trans("Competition.confirm")}}</button>
          </div>
          </div>


               <input name="qty_{{$row->id}}" type="hidden" value="{{$row->qty}}">




        </td>
        <td>  <label class="form-control" id="description">{{($row->options->has('competition_title') ? $row->options->competition_title : '')}}</label></td>
        <td>  <label class="form-control" id="typedance">{{($row->options->has('type') ? $row->options->type : '')}}</label></td>
        <td>  <label class="form-control" id="description">{{($row->options->has('level') ? $row->options->level : '')}}</label></td>
        <td>   <label class="form-control" id="Category" >{{($row->options->has('category') ? $row->options->category : '')}}</label>
        <td>   <label class="form-control" id="price_" >{{money($row->price, $event->currency)}} </label></td>
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
              @if(Session::has('school'))
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
              @else
              <div class="form-group more-options">
                <table>
                <tr>
                <td>
                    <label class="form-control" id="description">{{Session::get('surname')}} {{Session::get('name')}}</label>
                    <input type="hidden" name='participants_{{$row->id}}[]'  value='{{$row->options->student_id}}'>
                  </td>
                </tr>
                </table>
                </div>
              @endif
            @elseif($row->options->type == trans("Competition.competition_type_double_abbr"))
              @if(Session::has('school'))
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
                @else

                <div class="form-group more-options">
                <table id="dyn_participants_{{$row->id}}">
                <tr>
                <td>
                    <label class="form-control" id="description">{{Session::get('surname')}} {{Session::get('name')}}</label>
                    <input type="hidden" name='participants_{{$row->id}}[]'  value='{{$row->options->student_id}}'>
                  </td>
                </tr>
                </table>
                </div>
                  <button type="button" class="btn btn-danger" onclick="showAddBallerino('{{$row->rowId}}', {{$row->id}}, '{{trans('Competition.delete_cart_item_confirmation', ['competitionTitle' => ($row->options->has('competition_title') ? $row->options->competition_title : '')])}}')" >Aggiungi</button>
                  <button type="button" class="btn btn-danger" onclick="remove_participant({{$row->id}}, 'false')">elimina</button>
                @endif
            @elseif($row->options->type == trans("Competition.competition_type_group_abbr"))
              @if(Session::has('school'))
                <div class="form-group more-options" >
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
                  @else
                  <div class="form-group more-options">
                  <table id="dyn_participants_{{$row->id}}">
                <tr>
                  <td>
                    <label class="form-control" id="description">{{Session::get('surname')}} {{Session::get('name')}}</label>
                    <input type="hidden" name='participants_{{$row->id}}[]'  value='{{$row->options->student_id}}'>
                  </td>
                </tr>
                </table>
                </div>
                @endif
                @if(Session::has('school'))
                  <button type="button" class="btn btn-primary" onclick="add_participant({{$row->id}})"><i class="fas fa-plus-circle"></i></button>
                  <button type="button" class="btn btn-danger" onclick="remove_participant({{$row->id}}, 'true')"><i class="fas fa-times-circle"></i></button>
                @else
                  <button type="button" class="btn btn-danger" onclick="showAddBallerino('{{$row->rowId}}', {{$row->id}}, '{{trans('Competition.delete_cart_item_confirmation', ['competitionTitle' => ($row->options->has('competition_title') ? $row->options->competition_title : '')])}}')" >Aggiungi ballerino</button>
                  <button type="button" class="btn btn-danger" onclick="remove_participant({{$row->id}}, 'false')"><i class="fas fa-times-circle"></i></button>
                @endif
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
          <!--
              {!!Form::submit(trans("Public_ViewEvent.register"), ['class' => 'btn btn-lg btn-primary pull-right'])!!}
              -->
          </td>
        </tr>
      </tfoot>
  </table>
  <div id="popup-ballerino" class="popup var3">
  <div class="popup-inner">
    <button type="button" class="close-popup"></button>
    <h4 class="title">Aggiungi nuovo ballerino</h4>
    <table>
      <tr>
        <td>{!! Form::label('first_name', trans("User.first_name"), ['class' => 'control-label required']) !!}</td>
        <td>
          <input type=text name='name' id='name' >
          <input type=hidden name='item_id' id='item_id' >
          <input type=hidden name='item_rowId' id='item_rowId' >
        </td>
      </tr>
      <tr>
      <td>{!! Form::label('last_name', trans("User.last_name"), ['class' => 'control-label required']) !!}</td>
        <td><input type=text name='surname' id='surname'></td>
      </tr>
      <tr>
      <td> {!! Form::label('fiscal_code', trans("User.fiscal_code"), ['class' => 'control-label required']) !!}</td>
        <td><input type=text name='fiscal_code' id='fiscal_code'></td>
      </tr>
      <tr>
      <td> {!! Form::label('birth_date', trans("User.birth_date"), ['class' => 'control-label required']) !!}</td>
        <td><input type=date name='birth_date' id='birth_date'></td>
      </tr>
      <tr>
      <td> {!! Form::label('birth_place', trans("User.birth_place"), ['class' => 'control-label required']) !!}</td>
        <td><input type=text name='birth_place' id='birth_place'></td>
      </tr>
    </table>
    <button type="button" name="{{$row->rowId}}" class="btn btn-primary" id="add_nuovo_ballerino" >aggiungi ballerino</button>
  </div>
  </div>
  </div>
  <span> &nbsp; &nbsp;</span>

  <div style="" class="align-center">
              <button class="align-center btn btn-primary btn-lg active" type="submit">
                {{trans("Public_ViewEvent.register")}}
              </button>
              </div>
{!! Form::close() !!}
@endif
</div>
</div>
<span> &nbsp; &nbsp;</span>
<span> &nbsp; &nbsp;</span>
</div>
