<div class="breadcrumbs-wrap no-title">
   <div class="container">
     <ul class="breadcrumbs">
       <li>Home</a></li>
       <li>Gare</li>
       <li>{{ $event->title }}</li>
       <li>Iscriviti</li>
     </ul>
   </div>


<div class="container">
    <div class="isotope-nav" id = "filters">
  <div class="form-group text-center">

    <p><h1>  Seleziona la tua gara </h1></p>

    @foreach($disciplines as $discipline)
    <a href="{{route('showSubscriptionPage', array('event_id'=>$event->id, 'discipline_id'=>$discipline->id))}}" class="btn btn-primary btn-lg active" role="button" aria-pressed="true">{{$discipline->discipline_name}}</a>

    @endforeach
    <a href="{{route('showSubscriptionPage', array('event_id'=>$event->id))}}" class="btn btn-primary btn-lg active" role="button" aria-pressed="true">Tutti</a>
  </div>
</div>
 </div>
 <span> &nbsp; &nbsp;</span>

 <div class="content-element">
    <div class="row" id="div_event_dance_cart">
      	<table class="table-type-1" id="competition_table">
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
			<label class="form-control" id="description_{{$competition->id}}">{{$competition->title}}</label>
			<input name="price" id="price_{{$competition->id}}" type="hidden" value="{{$competition->price}}"/>
		</td>
        <td>  <label class="form-control" id="typedance">{{$competition->type}}</label></td>
		<td><span title='{{money($competition->price, $event->currency)}} @lang("Public_ViewEvent.competition_price")'>{{money($competition->price, $event->currency)}} </span></td>
    <td>
   <div class="mad-custom-select">

                                              <select name='level' id="level_{{$competition->id}}">
                                              @foreach ($competition->levels as $iter)
                                                                                      <option value="{{ $iter->level }}">{{ $iter->level }}</option>">
                                                                                  @endforeach
                                                                                  </select>
                                          </div>
  </td>
        <td>
          <div class="mad-custom-select">
                  <select name='category' id="category_{{$competition->id}}">
                @foreach ($competition->categories as $iter)
                  <option value="{{ $iter->category }}">{{ $iter->category }}</option>">
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
                  <button class='btn btn-small btn-style-6' id="add_cart_item"> <i class="fas fa-cart-plus"></i> </button>
                  <!-- {{trans("Public_ViewEvent.add_to_cart")}} -->
                  <!--{!!Form::button(trans("Public_ViewEvent.add_to_cart"), ['class' => 'btn btn-lg btn-primary pull-right'])!!}-->
         </td>
      </tr>
        {!! Form::hidden('is_embedded', $is_embedded) !!}
                             <!--   {!! Form::close() !!}-->
                                @endforeach
                                <span> &nbsp; &nbsp;</span>
                                <span> &nbsp; &nbsp;</span>
    </tbody>
    <span> &nbsp; &nbsp;</span>
    <span> &nbsp; &nbsp;</span>
  </table>
  <span> &nbsp; &nbsp;</span>
  <span> &nbsp; &nbsp;</span>
</div>
<span> &nbsp; &nbsp;</span>
    </div>



     <div class="align-center">
          <button class="btn btn-primary btn-lg active" type="submit">
       <a href = "{{route('showCart', ['event_id' => $event->id])}}">{{trans("Public_ViewEvent.proceed")}}</a>
       </button>
       </div>
</div>
