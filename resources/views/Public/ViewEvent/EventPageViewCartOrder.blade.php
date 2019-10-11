@extends('Public.ViewEvent.Layouts.EventPage')

@section('content')

    @include('Public.ViewEvent.Partials.EventHeaderSection')
    @include('Public.ViewEvent.Partials.EventShareSection')
    @include('Public.ViewEvent.Partials.EventViewCartOrderSection')
    @include('Public.ViewEvent.Partials.EventFooterSection')
    
@stop
