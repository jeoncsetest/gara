@extends('Shared.Layouts.Master')

@section('title')
@parent
@lang("Attendee.event_attendees")
@stop


@section('page_title')
<i class="ico-gift"></i>
@lang("basic.coupons")
@stop



@section('menu')
@include('ManageOrganiser.Partials.Sidebar')
@stop


@section('head')

@stop

@section('page_header')

<div class="col-md-3">
   {!! Form::open(array('url' => route('showOrganiserCoupons', ['organiser_id'=>$organiser->id,'sort_by'=>$sort_by]), 'method' => 'get')) !!}
    <div class="input-group">
        <input name='organiserId' type="hidden" value='{{$organiser->id}}'>
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
        @if($coupons->count())
        <div class="panel">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>
                               {!!Html::sortable_link(trans("Basic.code"), $sort_by, 'code', $sort_order, ['q' => $q ,'organiserId' => $organiser->id, 'page' => $coupons->currentPage()])!!}
                            </th>
                            <th>
                               {!!Html::sortable_link(trans("Basic.type"), $sort_by, 'type', $sort_order, ['q' => $q ,'organiserId' => $organiser->id, 'page' => $coupons->currentPage()])!!}
                            </th>
                            <th>
                               {!!Html::sortable_link(trans("Basic.value"), $sort_by, 'value', $sort_order, ['q' => $q ,'organiserId' => $organiser->id, 'page' => $coupons->currentPage()])!!}
                            </th>
                            <th>
                               {{trans("Basic.end_date")}}
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($coupons as $coupon)
                        <tr>
                            <td>{{$coupon->code}}</td>
                            <td>{{$coupon->type}}</td>
                            <td>{{$coupon->value}}</td>
                            <td>{{$coupon->end_date}}</td>
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
        {!!$coupons->appends(['sort_by' => $sort_by, 'sort_order' => $sort_order, 'q' => $q,'organiserId' => $organiser->id])->render()!!}
    </div>
</div>    <!--/End attendees table-->

@stop


