
<div class="container">
  <div class="form-group text-center">
    <p>  Seleziona la tua gara </p>
    <a href="{{route('showSubscriptionPage', array('event_id'=>$event->id, 'eventType'=>'S'))}}" class="btn btn-primary btn-lg active" role="button" aria-pressed="true">Assolo</a>
    <a href="{{route('showSubscriptionPage', array('event_id'=>$event->id, 'eventType'=>'D'))}}" class="btn btn-primary btn-lg active" role="button" aria-pressed="true">Coppia</a>
    <a href="{{route('showSubscriptionPage', array('event_id'=>$event->id, 'eventType'=>'G'))}}" class="btn btn-primary btn-lg active" role="button" aria-pressed="true">Gruppo</a>
    <a href="{{route('showSubscriptionPage', array('event_id'=>$event->id))}}" class="btn btn-primary btn-lg active" role="button" aria-pressed="true">Tutti</a>
  </div>

</div>
<!--
	<h1>
    Carello <span class='section_head_carello' id='section_head_carello'>{{Cart::count()}}</span>
		
	</h1>-->
  <div class="row" id="div_event_dance_cart">
	<table class="table table-striped" id="competition_table">
    <thead>
      <tr>
        <th scope="col"> </th>
        <th scope="col">@lang("Competition.competition_description")</th>
        <th scope="col">@lang("Competition.competition_type")</th>
	      <th scope="col">@lang("Competition.price")</th>
        <th scope="col">@lang("Competition.competition_category")</th>
        <th scope="col">@lang("Competition.competition_level")</th>
        <th scope="col"> </th>
      </tr>
    </thead>
    <tbody>
	 @foreach($competitions as $competition)
     <!--{!! Form::open(['url' => route('postAddSubscriptionToCart', ['event_id' => $event->id]), 'class' => 'ajax subscriptionForm', 'name'=>'subscriptionForm']) !!}-->
     								
    <tr id="{{$competition->id}}">
        <th scope="row">1</th>
    <td> 
			<label class="form-control" id="description_{{$competition->id}}">{{$competition->title}}</label>
			<input name="price" id="price_{{$competition->id}}" type="hidden" value="{{$competition->price}}"/>
		</td>
        <td>  <label class="form-control" id="typedance">{{$competition->type}}</label></td>
		<td><span title='{{money($competition->price, $event->currency)}} @lang("Public_ViewEvent.competition_price")'>{{money($competition->price, $event->currency)}} </span></td>
        <td>   
          <div class="form-group more-options">                                       
            <select name='category' id="category_{{$competition->id}}">
                @foreach ($competition->categories as $iter)
                  <option value="{{ $iter->category }}">{{ $iter->category }}</option>">
                @endforeach
            </select>
          </div>
        </td>
              <td>   
             <div class="form-group more-options">
                                                        
                                                        <select name='level' id="level_{{$competition->id}}">
                                                        @foreach ($competition->levels as $iter)
                                                                                                <option value="{{ $iter->level }}">{{ $iter->level }}</option>">
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
                  <button id="add_cart_item">{{trans("Public_ViewEvent.add_to_cart")}}</button>
                  <!--{!!Form::button(trans("Public_ViewEvent.add_to_cart"), ['class' => 'btn btn-lg btn-primary pull-right'])!!}</td>-->

      </tr>
        {!! Form::hidden('is_embedded', $is_embedded) !!}
                             <!--   {!! Form::close() !!}-->
                                @endforeach
    </tbody>
  </table>
  <div>
  <p class="float-right">
       <button class="btn btn-lg btn-primary btn-block" type="submit">
	   <a class="btn btn-lg btn-primary pull-right" href = "{{route('showCart', ['event_id' => $event->id])}}">{{trans("Public_ViewEvent.proceed")}}</a>
	   </button>
     </p>
  </div>
</div>
