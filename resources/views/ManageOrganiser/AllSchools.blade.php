@extends('Shared.Layouts.Master')

@section('title')
@parent
@lang("Attendee.event_attendees")
@stop


@section('page_title')
<i class="ico-building"></i>
@lang("basic.schools")
@stop



@section('menu')
@include('ManageOrganiser.Partials.Sidebar')
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
                <li><a href="{{route('showExportSchools', ['organiser_id'=>$organiser->id,'export_as'=>'xlsx'])}}">@lang("File_format.Excel_xlsx")</a></li>
                <li><a href="{{route('showExportSchools', ['organiser_id'=>$organiser->id,'export_as'=>'xls'])}}">@lang("File_format.Excel_xls")</a></li>
                <li><a href="{{route('showExportSchools', ['organiser_id'=>$organiser->id,'export_as'=>'csv'])}}">@lang("File_format.csv")</a></li>
                <li><a href="{{route('showExportSchools', ['organiser_id'=>$organiser->id,'export_as'=>'html'])}}">@lang("File_format.html")</a></li>
            </ul>
        </div>
    </div>
</div>
<div class="col-md-3">
   {!! Form::open(array('url' => route('showAllSchools', ['organiser_id'=>$organiser->id,'sort_by'=>$sort_by]), 'method' => 'get')) !!}
    <div class="input-group">
        <input name='organiserId' type="hidden" value='{{$organiser->id}}'>
        <input name="q" value="{{$q}}" placeholder="@lang("Competition.search_schools")" type="text" class="form-control" />
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
        @if($schools->count())
        <div class="panel">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>
                               {!!Html::sortable_link(trans("User.name"), $sort_by, 'name', $sort_order, ['q' => $q ,'organiserId' => $organiser->id, 'page' => $schools->currentPage()])!!}
                            </th>
                            <th>
                               {!!Html::sortable_link(trans("User.email"), $sort_by, 'email', $sort_order, ['q' => $q ,'organiserId' => $organiser->id, 'page' => $schools->currentPage()])!!}
                            </th>
                            <th>
                            {{trans("User.phone")}}
                            </th>
                            <th>
                               {!!Html::sortable_link(trans("User.eps"), $sort_by, 'eps', $sort_order, ['q' => $q ,'organiserId' => $organiser->id, 'page' => $schools->currentPage()])!!}
                            </th>
                            <th>
                               {!!Html::sortable_link(trans("User.place"), $sort_by, 'place', $sort_order, ['q' => $q ,'organiserId' => $organiser->id, 'page' => $schools->currentPage()])!!}
                            </th>
                            <th>
                               {!!Html::sortable_link(trans("User.city"), $sort_by, 'city', $sort_order, ['q' => $q ,'organiserId' => $organiser->id, 'page' => $schools->currentPage()])!!}
                            </th>
                            <th>
                               {{trans("User.address")}}
                            </th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($schools as $school)
                        <tr>
                            <td>{{$school->name}}</td>
                            <td>{{$school->email}}</td>
                            <td>{{$school->phone}}</td>
                            <td>{{$school->eps}}</td>
                            <td>{{$school->place}}</td>
                            <td>{{$school->city}}</td>
                            <td>{{$school->address}}</td>
                            <td class="text-center">
                                <a                                   
                                    href="{{route('showAllStudents', ['organiser_id'=>$organiser->id, 'organiserId' => $organiser->id, 'school_eps'=>$school->eps])}}"
                                    class="btn btn-xs btn-primary"
                                    > @lang("Basic.students")
                                </a>
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
        @include('ManageOrganiser.Partials.NormalBlankSlate')
        @endif

        @endif
    </div>
    <div class="col-md-12">
        {!!$schools->appends(['sort_by' => $sort_by, 'sort_order' => $sort_order, 'q' => $q,'organiserId' => $organiser->id])->render()!!}
    </div>
</div>    <!--/End attendees table-->

@stop


