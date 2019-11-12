@extends('Shared.Layouts.Master')

@section('title')
@parent
@lang("Attendee.event_attendees")
@stop


@section('page_title')
<i class="ico-users"></i>
@lang("basic.subscriptions")
@stop

@section('top_nav')
@include('ManageEvent.Partials.TopNav')
@stop

@section('menu')
@include('ManageEvent.Partials.Sidebar')
@stop


@section('head')

@stop

@section('page_header')

<div class="col-md-9">
    <div class="btn-toolbar" role="toolbar">
        <div class="btn-group btn-group-responsive">
            <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown">
                <i class="ico-users"></i> @lang("ManageEvent.export") <span class="caret"></span>
            </button>
            <ul class="dropdown-menu" role="menu">
                <li><a href="{{route('showExportSubscriptions', ['event_id'=>$event->id,'export_as'=>'xlsx'])}}">@lang("File_format.Excel_xlsx")</a></li>
                <li><a href="{{route('showExportSubscriptions', ['event_id'=>$event->id,'export_as'=>'xls'])}}">@lang("File_format.Excel_xls")</a></li>
                <li><a href="{{route('showExportSubscriptions', ['event_id'=>$event->id,'export_as'=>'csv'])}}">@lang("File_format.csv")</a></li>
                <li><a href="{{route('showExportSubscriptions', ['event_id'=>$event->id,'export_as'=>'html'])}}">@lang("File_format.html")</a></li>
            </ul>
        </div>
    </div>
</div>
<div class="col-md-3">
   {!! Form::open(array('url' => route('showIscritti', ['event_id'=>$event->id,'sort_by'=>$sort_by]), 'method' => 'get')) !!}
    <div class="input-group">
        <input name="q" value="{{$q or ''}}" placeholder="@lang("Attendee.search_attendees")" type="text" class="form-control" />
        <span class="input-group-btn">
            <button class="btn btn-default" type="submit"><i class="ico-search"></i></button>
        </span>
    </div>
   {!! Form::close() !!}
</div>
@stop


@section('content')

<!--Start Attendees table-->
<div class="row">
    <div class="col-md-12">
        @if($subscriptions->count())
        <div class="panel">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>
                               {!!Html::sortable_link(trans("basic.dancer"), $sort_by, 'students.surname', $sort_order, ['q' => $q , 'page' => $subscriptions->currentPage()])!!}
                            </th>
                            <th>
                               {!!Html::sortable_link(trans("Attendee.email"), $sort_by, 'students.email', $sort_order, ['q' => $q , 'page' => $subscriptions->currentPage()])!!}
                            </th>
                            <th>
                               {!!Html::sortable_link(trans("basic.competition"), $sort_by, 'subscriptions.competition_id', $sort_order, ['q' => $q , 'page' => $subscriptions->currentPage()])!!}
                            </th>
                            <th>
                               {!!Html::sortable_link(trans("basic.ref_order"), $sort_by, 'orders.order_reference', $sort_order, ['q' => $q , 'page' => $subscriptions->currentPage()])!!}
                            </th>
                            <th>
                               {!!Html::sortable_link(trans("basic.ref_competition"), $sort_by, 'subscriptions.private_reference_number', $sort_order, ['q' => $q , 'page' => $subscriptions->currentPage()])!!}
                            </th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($subscriptions as $subscription)
                        <tr class="attendee_{{$subscription->id}} {{$subscription->is_cancelled ? 'danger' : ''}}">
                            <td>{{$subscription->bal_surname}} {{$subscription->bal_name}}</td>
                            <td>
                                <a data-modal-id="MessageAttendee" href="javascript:void(0);" class="loadModal"
                                    data-href="{{route('showMessageAttendee', ['attendee_id'=>$subscription->id])}}"
                                    > {{$subscription->bal_email}}</a>
                            </td>
                            <td>
                                {{{$subscription->competition->title}}}
                            </td>
                            <td>
                                <!--<a href="javascript:void(0);" data-modal-id="view-order-{{ $subscription->order->id }}" data-href="{{route('showManageOrder', ['order_id'=>$subscription->order->id])}}" title="View Order #{{$subscription->order->order_reference}}" class="loadModal">-->
                                    {{$subscription->order->order_reference}}
                                <!--</a>-->
                            </td>
                            <td>
                                <!--<a href="javascript:void(0);" data-modal-id="view-order-{{ $subscription->order->id }}" data-href="{{route('showManageOrder', ['order_id'=>$subscription->order->id])}}" title="View Order #{{$subscription->order->order_reference}}" class="loadModal">-->
                                    {{$subscription->private_reference_number}}
                                <!--</a>-->
                            </td>
                            <td class="text-center">
                                @if(!empty($subscription->mp3_path))
                                <a                                   
                                    href="{{route('downloadMp3', ['event_id'=>$event->id, 'subscription_id'=>$subscription->id])}}"
                                    class="btn btn-xs btn-primary"
                                    > @lang("basic.download_mp3")</a>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @else

        @if(!empty($q))
        @include('Shared.Partials.NoSearchResults')
        @else
        @include('ManageEvent.Partials.AttendeesBlankSlate')
        @endif

        @endif
    </div>
    <div class="col-md-12">
        {!!$subscriptions->appends(['sort_by' => $sort_by, 'sort_order' => $sort_order, 'q' => $q])->render()!!}
    </div>
</div>    <!--/End attendees table-->

@stop


