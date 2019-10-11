@extends('Public.ViewEvent.Layouts.master')
@section('title')
Ticket Cart
@endsection

@section('content')
    @include('Public.ViewEvent.Partials.EventHeaderSection')
    @include('Public.ViewEvent.Partials.EventTicketsSection')
    @include('Public.ViewEvent.Partials.EventDescriptionSection')
    @include('Public.ViewEvent.Partials.EventShareSection')
    @include('Public.ViewEvent.Partials.EventMapSection')
    @include('Public.ViewEvent.Partials.EventOrganiserSection')
    @include('Public.ViewEvent.Partials.EventFooterSection')
@stop

@section('scripts')
<script src = "https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
@endsection


