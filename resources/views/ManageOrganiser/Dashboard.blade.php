@extends('Shared.Layouts.Master')

@section('title')
    @parent
    @lang("Organiser.dashboard")
@endsection

@section('top_nav')
    @include('ManageOrganiser.Partials.TopNav')
@stop
@section('page_title')
    @lang("Organiser.organiser_name_dashboard", ["name"=>$organiser->name])
@stop

@section('menu')
    @include('ManageOrganiser.Partials.Sidebar')
@stop

@section('head')

    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.css" integrity="sha256-szHusaozbQctTn4FX+3l5E0A5zoxz7+ne4fr8NgWJlw=" crossorigin="anonymous" />
    <script src="//cdnjs.cloudflare.com/ajax/libs/raphael/2.1.4/raphael-min.js" integrity="sha256-Gk+dzc4kV2rqAZMkyy3gcfW6Xd66BhGYjVWa/FjPu+s=" crossorigin="anonymous"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.min.js" integrity="sha256-0rg2VtfJo3VUij/UY9X0HJP7NET6tgAY98aMOfwP0P8=" crossorigin="anonymous"></script>

    {!! HTML::script('https://maps.googleapis.com/maps/api/js?libraries=places&key='.config("attendize.google_maps_geocoding_key")) !!}
    {!! HTML::script('vendor/geocomplete/jquery.geocomplete.min.js')!!}
    {!! HTML::script('vendor/moment/moment.js')!!}
    {!! HTML::script('vendor/fullcalendar/dist/fullcalendar.min.js')!!}
    <?php
    if(Lang::locale()!="en")
        echo HTML::script('vendor/fullcalendar/dist/lang/'.Lang::locale().'.js');
    ?>
    {!! HTML::style('vendor/fullcalendar/dist/fullcalendar.css')!!}

    <script>
        $(function() {
            $('#calendar').fullCalendar({
                locale: '{{ Lang::locale() }}',
                events: {!! $calendar_events !!},
                header: {
                    left:   'prev,',
                    center: 'title',
                    right:  'next'
                },
                dayClick: function(date, jsEvent, view) {

                }
            });
        });
    </script>
@stop

@section('content')
    <div class="row">
        <div class="col-sm-6">
            <div class="stat-box">
                <h3>
                @if(!empty($organiser->account->organiser_type) && $organiser->account->organiser_type =='night')
                    {{$organiser->nights->count()}}
                @else
                    {{$organiser->events->count()}}
                @endif                    
                </h3>
            <span>
                @if(!empty($organiser->account->organiser_type) && $organiser->account->organiser_type =='night')
                    @lang("Organiser.nights")
                @else
                    @lang("Organiser.events")
                @endif  
            </span>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="stat-box">
                <h3>
                    {{$organiser->attendees->count()}}
                </h3>
            <span>
                @lang("Organiser.tickets_sold")
            </span>
            </div>
        </div>
    </div>

    <div class="row">

        <div class="col-md-8">

            <h4 style="margin-bottom: 25px;margin-top: 20px;">@lang("Organiser.event_calendar")</h4>
            <!--<div id="calendar"></div>-->


            <h4 style="margin-bottom: 25px;margin-top: 20px;">@lang("Public_ViewOrganiser.upcoming_events")</h4>
            @if(!empty($organiser->account->organiser_type) && $organiser->account->organiser_type =='night')
                @if($upcoming_nights->count())
                    @foreach($upcoming_nights as $event)
                        @include('ManageOrganiser.Partials.EventPanel')
                    @endforeach
                @else
                    <div class="alert alert-success alert-lg">
                        @lang("Organiser.no_upcoming_nights") <a href="#"
                                                        data-href="{{route('showCreateNight', ['organiser_id' => $organiser->id])}}"
                                                        class=" loadModal">@lang("Organiser.no_upcoming_nights_click")</a>
                    </div>
                @endif
            @else
                @if($upcoming_events->count())
                    @foreach($upcoming_events as $event)
                        @include('ManageOrganiser.Partials.EventPanel')
                    @endforeach
                @else
                    <div class="alert alert-success alert-lg">
                        @lang("Organiser.no_upcoming_events") <a href="#"
                                                        data-href="{{route('showCreateEvent', ['organiser_id' => $organiser->id])}}"
                                                        class=" loadModal">@lang("Organiser.no_upcoming_events_click")</a>
                    </div>
                @endif
            @endif
        </div>
        <div class="col-md-4">
            <h4 style="margin-bottom: 25px;margin-top: 20px;">@lang("Order.recent_orders")</h4>
            @if($organiser->orders->count())
                <ul class="list-group">
                    @foreach($organiser->orders()->orderBy('created_at', 'desc')->take(5)->get() as $order)
                        <li class="list-group-item">
                            <h6 class="ellipsis">
                                <a href="{{ route('showEventDashboard', ['event_id' => $order->event->id]) }}">
                                    {{ $order->event->title }}
                                </a>
                            </h6>
                            <p class="list-group-text">
                                <a href="{{ route('showEventOrders', ['event_id' => $order->event_id, 'q' => $order->order_reference]) }}">
                                    <b>#{{ $order->order_reference }}</b></a> -
                                <a href="{{ route('showEventAttendees', ['event_id'=>$order->event->id,'q'=>$order->order_reference]) }}">
                                    <strong>{{ $order->full_name }}</strong>
                                </a> {{ @trans("Order.registered") }}
                                    {{ $order->attendees()->withTrashed()->count() }} {{ @trans("Order.tickets") }}
                            </p>
                            <h6>
                                {{ $order->created_at->diffForHumans() }} &bull; <span
                                        style="color: green;">{{ $order->event->currency_symbol }}{{ $order->amount }}</span>
                            </h6>
                        </li>
                    @endforeach
                    @else
                        <div class="alert alert-success alert-lg">
                            @lang("Order.no_recent_orders")
                        </div>
                    @endif
                </ul>

        </div>
    </div>
@stop
