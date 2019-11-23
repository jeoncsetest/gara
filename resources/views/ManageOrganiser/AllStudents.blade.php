@extends('Shared.Layouts.Master')

@section('title')
@parent
@lang("Attendee.event_attendees")
@stop


@section('page_title')
<i class="ico-building"></i>
@lang("basic.students")
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
                <li><a href="{{route('showExportStudents', ['organiser_id'=>$organiser->id,'export_as'=>'xlsx'])}}">@lang("File_format.Excel_xlsx")</a></li>
                <li><a href="{{route('showExportStudents', ['organiser_id'=>$organiser->id,'export_as'=>'xls'])}}">@lang("File_format.Excel_xls")</a></li>
                <li><a href="{{route('showExportStudents', ['organiser_id'=>$organiser->id,'export_as'=>'csv'])}}">@lang("File_format.csv")</a></li>
                <li><a href="{{route('showExportStudents', ['organiser_id'=>$organiser->id,'export_as'=>'html'])}}">@lang("File_format.html")</a></li>
            </ul>
        </div>
    </div>
</div>
<div class="col-md-3">
   {!! Form::open(array('url' => route('showAllStudents', ['organiser_id'=>$organiser->id, 'sort_by'=>$sort_by]), 'method' => 'get')) !!}
    <div class="input-group">
        <input name='organiserId' type="hidden" value='{{$organiser->id}}'>
        <input name='school_eps' type="hidden" value='{{$school_eps}}'>
        <input name="q" value="{{$q}}" placeholder="@lang("Competition.search_students")" type="text" class="form-control" />
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
        @if($students->count())
        <div class="panel">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>
                               {!!Html::sortable_link(trans("User.first_name"), $sort_by, 'name', $sort_order, ['q' => $q ,'organiserId' => $organiser->id, 'school_eps' => $school_eps, 'page' => $students->currentPage()])!!}
                            </th>
                            <th>
                               {!!Html::sortable_link(trans("User.last_name"), $sort_by, 'surname', $sort_order, ['q' => $q ,'organiserId' => $organiser->id, 'school_eps' => $school_eps, 'page' => $students->currentPage()])!!}
                            </th>
                            <th>
                               {!!Html::sortable_link(trans("User.email"), $sort_by, 'email', $sort_order, ['q' => $q ,'organiserId' => $organiser->id, 'school_eps' => $school_eps, 'page' => $students->currentPage()])!!}
                            </th>
                            <th>
                               {!!Html::sortable_link(trans("User.school_eps"), $sort_by, 'eps', $sort_order, ['q' => $q ,'organiserId' => $organiser->id, 'school_eps' => $school_eps, 'page' => $students->currentPage()])!!}
                            </th>
                            <th>
                               {!!Html::sortable_link(trans("User.birth_date"), $sort_by, 'birth_date', $sort_order, ['q' => $q ,'organiserId' => $organiser->id, 'school_eps' => $school_eps, 'page' => $students->currentPage()])!!}
                            </th>
                            <th>
                               {!!Html::sortable_link(trans("User.birth_place"), $sort_by, 'birth_place', $sort_order, ['q' => $q ,'organiserId' => $organiser->id, 'school_eps' => $school_eps, 'page' => $students->currentPage()])!!}
                            </th>
                            <th>
                               {!!Html::sortable_link(trans("User.phone"), $sort_by, 'phone', $sort_order, ['q' => $q ,'organiserId' => $organiser->id, 'school_eps' => $school_eps, 'page' => $students->currentPage()])!!}
                            </th>
                            <th>
                               {!!Html::sortable_link(trans("User.fiscal_code"), $sort_by, 'fiscal_code', $sort_order, ['q' => $q ,'organiserId' => $organiser->id, 'school_eps' => $school_eps, 'page' => $students->currentPage()])!!}
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($students as $student)
                        <tr>
                            <td>{{$student->name}}</td>
                            <td>{{$student->surname}}</td>
                            <td>{{$student->email}}</td>
                            <td>{{$student->school_eps}}</td>
                            <td>{{$student->birth_date}}</td>
                            <td>{{$student->birth_place}}</td>
                            <td>{{$student->phone}}</td>
                            <td>{{$student->fiscal_code}}</td>
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
        {!!$students->appends(['sort_by' => $sort_by, 'sort_order' => $sort_order, 'q' => $q, 'school_eps' => $school_eps,'organiserId' => $organiser->id])->render()!!}
    </div>
</div>    <!--/End attendees table-->

@stop


