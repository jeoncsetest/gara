<div class="breadcrumbs-wrap no-title">
 <div class="container">
   <ul class="breadcrumbs">
     <li><a href="{{ url('/') }}" style="text-decoration: none">Home</a></li>
     <li><a href="{{ url('/eventList') }}" style="text-decoration: none">Gare</a></li>
     <li><a href="{{ url('/'.$event->id.'/showEventDescription') }}" style="text-decoration: none">{{ $event->title }}</a></li>
     <li>Iscriviti</li>
   </ul>
 </div>


 <div class="container">
  <div class="isotope-nav" id = "filters">
    <div class="form-group text-center">

      <p><h1>  Seleziona la tua gara </h1></p>

      @foreach($disciplines as $discipline)
      <a href="{{route('showSubscriptionPage', array('event_id'=>$event->id, 'discipline_id'=>$discipline->id))}}" class="btn btn-lg active" role="button" aria-pressed="true">{{$discipline->discipline_name}}</a>

      @endforeach
      <a href="{{route('showSubscriptionPage', array('event_id'=>$event->id))}}" class="btn btn-lg active" role="button" aria-pressed="true">Tutti</a>
    </div>
  </div>
</div>
<span> &nbsp; &nbsp;</span>

<div class="content-element">
  <div class="container">
  <div class="row" id="div_event_dance_cart">
   <table class="table  table-borderless table-type-1" id="competition_table">
    <thead>
      <tr>
        <!--  <th scope="col"> </th> -->
        <th scope="col">@lang("Competition.competition_description")</th>
        <th scope="col">@lang("Competition.competition_type")</th>
        <th scope="col">@lang("Competition.price")</th>
        <th scope="col">@lang("Competition.competition_level")</th>
        <th scope="col">@lang("Competition.competition_category")</th>
        <th scope="col"> </th>
      </tr>
    </thead>
    <tbody>
      @foreach($competitions as $competition)
      <!--{!! Form::open(['url' => route('postAddSubscriptionToCart', ['event_id' => $event->id]), 'class' => 'ajax subscriptionForm', 'name'=>'subscriptionForm']) !!}-->

      <tr id="{{$competition->id}}">
        <!--    <th scope="row">1</th> -->
        <td>
         <label id="description_{{$competition->id}}">{{$competition->title}}</label>
         <input name="price" id="price_{{$competition->id}}" type="hidden" value="{{$competition->price}}"/>
       </td>
       <td>  <label class="form-control" id="typedance">{{$competition->type}}</label></td>
       <td><span title='{{money($competition->price, $event->currency)}} @lang("Public_ViewEvent.competition_price")'>{{money($competition->price, $event->currency)}} </span></td>
       <td>
         <div class="mad-custom-select">

          <select name='level' id="level_{{$competition->id}}">
            @foreach ($competition->levels as $iter)
            <option value="{{ $iter->level }}">{{ $iter->level }}</option>
            @endforeach
          </select>
        </div>
      </td>
      <td>
        <div class="mad-custom-select">
          <select name='category' id="category_{{$competition->id}}">
            @foreach ($competition->categories as $iter)
            <option value="{{ $iter->category }}">{{ $iter->category }}</option>
            @endforeach
          </select>
        </div>
      </td>
                                                               <!-- {!! Form::hidden('type', $competition->type) !!}
                                                                {!! Form::hidden('title', $competition->title) !!}-->
                                                                <td>
                                                                  {!! Form::hidden('competition_id', $competition->id) !!}
                                                                  <input type="hidden" name="type" id="type_{{$competition->id}}" value="{{$competition->type}}">
                                                                  <input type="hidden" name="title" id="title_{{$competition->id}}" value="{{$competition->title}}">
                                                                  <?php
                                                                  $gara_incarello = null;
                                                                  foreach(Cart::content() as $row){
                                                                    if($row->options->has('competition_id') && $row->options->competition_id==$competition->id){
                                                                      $gara_incarello = 'true';
                                                                      break;
                                                                    }
                                                                  }
                                                                  if(empty($gara_incarello)){
                                                                    echo "<button class='add_cart_item btn btn-small btn-style-6'  id='add_cart_item_{$competition->id}'> <i class='fas fa-cart-plus'></i> </button>";
                                                                  }else{
                                                                    echo "<button class='add_cart_item btn btn-small btn-style-1'  id='add_cart_item_{$competition->id}'> <i class='fas fa-cart-plus'></i> </button>";
                                                                  }
                                                                  ?>



                                                                  <!-- {{trans("Public_ViewEvent.add_to_cart")}} -->
                                                                  <!--{!!Form::button(trans("Public_ViewEvent.add_to_cart"), ['class' => 'btn btn-lg btn-primary pull-right'])!!}-->
                                                                </td>
                                                              </tr>

                                                              {!! Form::hidden('is_embedded', $is_embedded) !!}
                                                              <!--   {!! Form::close() !!}-->
                                                              @endforeach
                                                              <tr><td colspan=6><span style="color:white">.</span></td></tr>
                                                              <tr><td colspan=6><span style="color:white">.</span></td></tr>

                                                              <tr><td colspan=6>
                                                                <span>
                                                                  <button class="btn  btn-lg active text-white" type="submit">
                                                                    <a href= "{{route('showCart', ['event_id' => $event->id])}}" class="text-white" style="text-decoration: none">{{trans("Public_ViewEvent.proceed")}}</a>
                                                                  </span></td></tr>
                                                                  <tr><td colspan=6><span style="color:white">.</span></td></tr>
                                                                  <tr><td colspan=6><span style="color:white">.</span></td></tr>
                                                                  <tr><td colspan=6><span style="color:white">.</span></td></tr>
                                                                  <tr><td colspan=6><span style="color:white">.</span></td></tr>
                                                                  <tr><td colspan=6><span style="color:white">.</span></td></tr>
                                                                  <tr><td colspan=6><span style="color:white">.</span></td></tr>
                                                                  <tr><td colspan=6><span style="color:white">.</span></td></tr>
                                                                  <tr><td colspan=6><span style="color:white">.</span></td></tr>
                                                                  <tr><td colspan=6><span style="color:white">.</span></td></tr>
                                                                  <tr><td colspan=6><span style="color:white">.</span></td></tr>
                                                                  <tr><td colspan=6><span style="color:white">.</span></td></tr>
                                                                  <tr><td colspan=6><span style="color:white">.</span></td></tr>
                                                                  <tr><td colspan=6><span style="color:white">.</span></td></tr>
                                                                  <tr><td colspan=6><span style="color:white">.</span></td></tr>
                                                                  <tr><td colspan=6><span style="color:white">.</span></td></tr>
                                                                  <tr><td colspan=6><span style="color:white">.</span></td></tr>
                                                                  <tr><td colspan=6><span style="color:white">.</span></td></tr>
                                                                  <tr><td colspan=6><span style="color:white">.</span></td></tr>
                                                                  <tr><td colspan=6><span style="color:white">.</span></td></tr>
                                                                  <tr><td colspan=6><span style="color:white">.</span></td></tr>
                                                                  <tr><td colspan=6><span style="color:white">.</span></td></tr>
                                                                  <tr><td colspan=6><span style="color:white">.</span></td></tr>
                                                                  <tr><td colspan=6><span style="color:white">.</span></td></tr>
                                                                  <tr><td colspan=6><span style="color:white">.</span></td></tr>
                                                                  <tr><td colspan=6><span style="color:white">.</span></td></tr>
                                                                  <tr><td colspan=6><span style="color:white">.</span></td></tr>
                                                                  <tr><td colspan=6><span style="color:white">.</span></td></tr>
                                                                  <tr><td colspan=6><span style="color:white">.</span></td></tr>

                                                                </tbody>
                                                                <span> &nbsp; &nbsp;</span>
                                                                <span> &nbsp; &nbsp;</span>
                                                              </table>
                                                              <span> &nbsp; &nbsp;</span>
                                                              <span> &nbsp; &nbsp;</span>
                                                            </div>
                                                          </div>
                                                            <span> &nbsp; &nbsp;</span>
                                                          </div>
                                                        </div>
<!--
>>>>>>> 9a557f1918cfcf4eb3cc55f9b7f65bcadc802e39
     <div class="align-center">
          <button class="btn btn-primary btn-lg active" type="submit">
       <a href = "{{route('showCart', ['event_id' => $event->id])}}">{{trans("Public_ViewEvent.proceed")}}</a>
       </button>
     </div>-->
   </div>
