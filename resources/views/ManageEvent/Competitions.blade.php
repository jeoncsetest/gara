@extends('Shared.Layouts.Master')

@section('title')
    @parent
    @lang("Competition.event_competitions")
@stop

@section('top_nav')
    @include('ManageEvent.Partials.TopNav')
@stop

@section('page_title')
    <i class="ico-competition mr5"></i>
    @lang("Competition.event_competitions")
@stop

@section('head')
    <script>
        $(function () {
            $('.sortable').sortable({
                handle: '.sortHandle',
                forcePlaceholderSize: true,
                placeholderClass: 'col-md-4 col-sm-6 col-xs-12',
            }).bind('sortupdate', function (e, ui) {

                var data = $('.sortable .competition').map(function () {
                    return $(this).data('competition-id');
                }).get();

                $.ajax({
                    type: 'POST',
                    url: '{{ route('postUpdateCompetitionsOrder' ,['event_id' => $event->id]) }}',
                    dataType: 'json',
                    data: {competition_ids: data},
                    success: function (data) {
                        showMessage(data.message);
                    },
                    error: function (data) {
                        showMessage(lang("whoops2"));
                    }
                });
            });
        });
    </script>
@stop

@section('menu')
    @include('ManageEvent.Partials.Sidebar')
@stop

@section('page_header')
    <div class="col-md-9">
        <!-- Toolbar -->
        <div class="btn-toolbar" role="toolbar">
            <div class="btn-group btn-group-responsive">
                <button data-modal-id='CreateCompetition'
                        data-href="{{route('showCreateCompetition', array('event_id'=>$event->id))}}"
                        class='loadModal btn btn-success' type="button"><i class="ico-competition"></i> @lang("Competition.create_competition")
                </button>
            </div>
        </div>
        <!--/ Toolbar -->
    </div>
    <div class="col-md-3">
        {!! Form::open(array('url' => route('showEventCompetitions', ['event_id'=>$event->id,'sort_by'=>$sort_by]), 'method' => 'get')) !!}
        <div class="input-group">
            <input name='q' value="{{$q or ''}}" placeholder="@lang("Competition.search_competitions")" type="text" class="form-control">
        <span class="input-group-btn">
            <button class="btn btn-default" type="submit"><i class="ico-search"></i></button>
        </span>
            {!!Form::hidden('sort_by', $sort_by)!!}
        </div>
        {!! Form::close() !!}
    </div>
@stop

@section('content')
    @if($competitions->count())
        <div class="row">
            <div class="col-md-3 col-xs-6">
                <div class='order_options'>
                    <span class="event_count">@lang("Competition.n_competitions", ["num"=>$competitions->count()])</span>
                </div>
            </div>
            <div class="col-md-2 col-xs-6 col-md-offset-7">
                <div class='order_options'>
                    {!! Form::select('sort_by_select', $allowed_sorts, $sort_by, ['class' => 'form-control pull right']) !!}
                </div>
            </div>
        </div>
    @endif
    <!--Start competition table-->
    <div class="row sortable">
        @if($competitions->count())

            @foreach($competitions as $competition)
                <div id="competition_{{$competition->id}}" class="col-md-4 col-sm-6 col-xs-12">
                    <div class="panel panel-success competition" data-competition-id="{{$competition->id}}">
                        <div style="cursor: pointer;" data-modal-id='competition-{{ $competition->id }}'
                             data-href="{{ route('showEditCompetition', ['event_id' => $event->id, 'competition_id' => $competition->id]) }}"
                             class="panel-heading loadModal">
                            <h3 class="panel-title">
                                {{$competition->title}}
                                <span class="pull-right">
                                    {{ money($competition->price, $event->currency) }}
                                </span>
                            </h3>
                        </div>
                        <div class='panel-body'>
                            <ul class="nav nav-section nav-justified mt5 mb5">
                                <li>
                                    <div class="section">
                                        <h4 class="nm">{{ $competition->total_subscription }}</h4>

                                        <p class="nm text-muted">@lang("Competition.total_subscrition")</p>
                                    </div>
                                </li>
                                <li>
                                    <div class="section">
                                        <h4 class="nm">
                                            {{ ($competition->max_competitors === null) ? '∞' : $competition->max_competitors -$competition->total_subscription }}
                                        </h4>

                                        <p class="nm text-muted">@lang("Competition.remaining")</p>
                                    </div>
                                </li>
                                <li>
                                    <div class="section">
                                        <h4 class="nm hint--top"
                                            title="{{money($competition->sales_volume, $event->currency)}} + {{money($competition->organiser_fees_volume, $event->currency)}} @lang("Order.organiser_booking_fees")">
                                            {{money($competition->total_subscription * $competition->price, $event->currency)}}
                                            <sub title="@lang("Competition.doesnt_account_for_refunds").">*</sub>
                                        </h4>
                                        <p class="nm text-muted">@lang("Competition.revenue")</p>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            @endforeach
        @else
            @if($q)
                @include('Shared.Partials.NoSearchResults')
            @else
                @include('ManageEvent.Partials.CompetitionsBlankSlate
                ')
            @endif
        @endif
    </div><!--/ end competition table-->
    <div class="row">
        <div class="col-md-12">
           <!-- {!! $competitions->appends(['q' => $q, 'sort_by' => $sort_by])->render() !!}->
        </div>
    </div>
@stop
