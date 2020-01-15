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
 @if ($errors->any())
      <div class="alert alert-danger">
        <ul>
          @foreach ($errors->all() as $error)
          <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
      @endif
@if(Cart::count()>0)
{!! Form::open(['url' => route('postValidateCartItems', ['event_id' => $event->id]), 'class' => 'ajax gf',  'enctype'=>'multipart/form-data', 'id'=>'cartCheckoutForm']) !!}
<div class="content-element">
<div class="row" id="div_event_dance_cart">
  <div class="table-responsive">
	<table class="table table-borderless table-responsive" id="competition_table">
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
          <button style="border: 0px" type="button" class="btn btn-danger btn-sm" onclick="showPopupRemoveItem('{{$row->rowId}}', {{$row->id}}, '{{trans('Competition.delete_cart_item_confirmation', ['competitionTitle' => ($row->options->has('competition_title') ? $row->options->competition_title : '')])}}')" ><i class="fas fa-times-circle"></i></button>
          <div id="popup-removeCart_{{$row->id}}" class="popup var3">
          <div class="popup-inner">
            <button type="button" class="close-popup"></button>
            <h4 class="title">Eliminare dal carrello</h4>
            <span>
              {{trans('Competition.delete_cart_item_confirmation', ['competitionTitle' => ($row->options->has('competition_title') ? $row->options->competition_title : '')])}}
            </span>
            <button type="button" name="{{$row->rowId}}" class="btn btn-sm" id="remove_cart_item" >{{trans("Competition.confirm")}}</button>
          </div>
          </div>


               <input name="qty_{{$row->id}}" type="hidden" value="{{$row->qty}}">




        </td>
        <td>  <label id="description">{{($row->options->has('competition_title') ? $row->options->competition_title : '')}}</label></td>
        <td>  <label  id="typedance">{{($row->options->has('type') ? $row->options->type : '')}}</label></td>
        <td>  <label  id="description">{{($row->options->has('level') ? $row->options->level : '')}}</label></td>
        <td>   <label  id="Category" >{{($row->options->has('category') ? $row->options->category : '')}}</label>
        <td>   <label  id="price_" >{{money($row->price, $event->currency)}} </label></td>
        <td>
            @if($row->options->has('mp3_upload') && $row->options->mp3_upload == 1)
            <div class="input-group">
            <div class="custom-file">
            {!! Form::open(['url' => route('postRemoveMp3', ['event_id' => $event->id]), 'class' => 'ajax gf',  'enctype'=>'multipart/form-data', 'id'=>'formRemovedMp3']) !!}
           
            {!! Form::close() !!}
            </div>
          </div>
          <div class="input-group">
            <div class="custom-file">
              {!! Form::open(['url' => route('postUploadMp3', ['event_id' => $event->id]), 'class' => 'ajax gf formUploadMp3',  'enctype'=>'multipart/form-data', 'id'=>'formUploadMp3'.$row->rowId]) !!}
              <?php
              $obj = json_decode($row->name);

              ?>
              @if(empty($obj->{'mp3'}))
             <div id="mp3Name{{ $row->rowId }}"></div>
              <button id="upload{{ $row->rowId }}" type="button" class="btn  btn-sm text-white"  data-toggle="modal" data-target="#popUpModal{{ $row->rowId }}" style="margin-bottom: 50%">Caricare</button>
              <button type="button" style="display: none;margin-bottom: 50%" id="btnUpdateMp3{{ $row->rowId }}"  class="btn-sm btn-danger text-white"  data-toggle="modal" data-target="#popUpModal{{ $row->rowId }}" >Modifica</button>
              
               <!-- Modal -->
               <div class="modal fade" id="popUpModal{{ $row->rowId }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title" id="exampleModalLabel">Caricare</h5>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>


                    <div class="modal-body"  >
                     <input   type="file" name="mp3_file_{{ $row->rowId }}" id="mp3_file_1{{ $row->rowId }}">
                     <input type="hidden" name='item_row_id' value="{{  $row->rowId }}" id="item_row_id-{{ $row->rowId  }}">

                   </div>
                   <div class="modal-footer">

                    <button type="button"  class="btn btn-sm" data-dismiss="modal" disabled="disabled" id="btn_mp3_file_{{ $row->rowId }}" onsu onclick="uploadMp3('{{  $row->rowId }}')" >Invia</button>
                  </div>

                </div>
              </div>
            </div>
              @else
              <p id="mp3Name{{ $row->rowId }}">{{ str_limit($obj->{'mp3'},10) }}</p>
              <button style="margin-bottom: 50%" type="button" id="btnUpdateMp3{{ $row->rowId }}"    class="  btn-sm btn-danger"  data-toggle="modal" data-target="#popUpModal{{ $row->rowId }}" >Modifica</button>
              <button type="button" class="btn btn-danger btn-sm"  data-toggle="modal" data-target="#popUpModal{{ $row->rowId }}" style="display: none;margin-bottom: 50%" id="btn{{ $row->rowId }}">Modifica</button>
              <!-- Modal -->
              <div class="modal fade" id="popUpModal{{ $row->rowId }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title" id="exampleModalLabel">Modifica</h5>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>

                      
                      <div class="modal-body">
                        <input type="file"  name="mp3_file_{{ $row->rowId }}" id="mp3_file_{{ $row->rowId }}"  class="form-control"  required accept=".mp3">
                       <input type="hidden" name="item_row_id" value="{{ $row->rowId }}" id="item_row_id-{{ $row->rowId }}">
                     </div>
                     <div class="modal-footer">
                     
                    <button type="button"  data-dismiss="modal" disabled="disabled"  onclick="updateMp3('{{ $row->rowId }}',)" class="btn btn-sm" id="btn_mp3_file_{{ $row->rowId }}">Salva</button>
                    </div>
                   
                  </div>
                </div>
              </div>
              

             @endif
             {!! Form::close() !!}
            </div>
          </div>
          @endif
        </td>
        <td>
        @if($row->options->has('type'))
          @if(Session::has('school'))
            <div class="form-group more-options">
            <table id="dyn_participants_{{$row->id}}">
            <?php
              $obj = json_decode($row->name);
              if(!empty($obj->{'participants'})){
                  foreach($obj->{'participants'} as $participant){
                    echo "<tr id='ballerino_".  $row->rowId . '_' . $participant->{'id'} ."'><td>";
                    echo "<input type='hidden' name='participants_" .$row->id. '[]' .'value=' .$participant->{'id'}.'>';
                    echo "<label class='form-control' id='description'>" . $participant->{'surname'} . ' '. $participant->{'name'} .'</label>';
                    echo "</td><td><button type='button' id='btnRemoveBallerinoDalCarello" .$row->rowId . "' onclick=removeBallerinoDalCarello('" . $row->rowId .   "'," .$participant->{'id'}. ") class='btn btn-sm btn-danger' style='border:0px'><i class='fas fa-times-circle'></i></button>";    
                    echo '</td></tr>';
                  }
              }
            ?>
            <tr>
              <td>
                <div class="ui-widget" >
                 <?php

                  
                  if(($row->options->type == 'S' &&  count($obj->participants) > 0) || ($row->options->type == 'D' &&  count($obj->participants) >=2)){
                  $checkType= true;
                  }
                  else{
                    $checkType= false;
                  }
                  ?>
                  <select class="form-control option{{$row->rowId}}"  onchange="addBallerino('{{$row->rowId}}', '{{$row->id}}')"  name='participants_{{$row->id}}[]' id='participants_{{$row->id}}' @if($checkType==true) style="display: none" @endif>
                    <option selected disabled>Seleziona scuola.....</option>
                  @foreach ($students as $iter)
                    <option value="{{ $iter->id }}">{{ $iter->name }} {{ $iter->surname }}</option>>
                  @endforeach
                  </select>
                  
                </div>
              </td>
            </tr>
              <tr>
             
            {{--   @if($row->options->type !== 'S' || $row->options->type !== 'D') --}}
              <td>
             {{--  <button type="button" style="border: 0px" class="btn btn-success" onclick="addBallerino('{{$row->rowId}}', '{{$row->id}}')" >Aggiungi</button> --}}
              </td>
            {{--   @endif --}}
            </tr>

            </table>
            </div>
          @else
          <div class="form-group more-options">
          <table class="table table-borderless table-responsive" id="dyn_participants_{{$row->id}}">
            <?php
              $obj = json_decode($row->name);
              if(!empty($obj->{'participants'})){
                  foreach($obj->{'participants'} as $participant){
                    echo "<tr id='ballerino_'".  $row->rowId . '_' . $participant->{'id'} ."><td>";
                    echo "<input type='hidden' name='participants_" .$row->id. '[]' .'value=' .$participant->{'id'}.'>';
                    echo "<label class='form-control' id='description'>" . $participant->{'surname'}. ' ' . $participant->{'name'} .'</label>';
                    echo "</td><td><button type='button' id='btnRemoveBallerinoDalCarello" .$row->rowId . "' onclick=removeBallerinoDalCarello(" . $row->rowId .   "'," .$participant->{'id'}. ") class='btn btn-sm btn-danger' style='border:0px'><i class='fas fa-times-circle'></i></button>";    
                    echo '</td></tr>';
                  }
              }
            ?>
            <tr>
              <td>

              <button type="button" class="btn btn-danger" onclick="showAddBallerino('{{$row->rowId}}', {{$row->id}}, '{{trans('Competition.delete_cart_item_confirmation', ['competitionTitle' => ($row->options->has('competition_title') ? $row->options->competition_title : '')])}}')" >Aggiungi</button>
              </td>
            </tr>
          
            </table>
            </div>
          @endif
        @endif
        </td>

      </tr>
        @endforeach
      </tbody>

   
  </table>
  <p class="text-center">Total: <b>{{money(Cart::subtotal(), $event->currency)}}</b></p>
  </div>
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
    <button type="button" name="{{$row->rowId}}" class="btn btn-sm" id="add_nuovo_ballerino" >aggiungi ballerino</button>
  </div>
  </div>
  </div>
  <span> &nbsp; &nbsp;</span>

  <div style="" class="align-center">
              <button class="btn btn-sm text-white" type="submit">
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
<div id="wait" style="display:none;width:69px;height:89px;border:1px solid black;position:absolute;top:50%;left:50%;padding:2px;"><img src=" {{ asset('/assets/images/ajax-loader.gif') }} "width="64" height="64" /><br>Loading..</div>

