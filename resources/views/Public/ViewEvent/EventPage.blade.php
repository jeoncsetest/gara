@extends('Public.ViewEvent.Layouts.master')
@section('head')
    @include('Public.ViewEvent.Partials.GoogleTagManager')
@endsection
@section('title')
Ticket Cart
@endsection
@section('styles')
{!!HTML::style(config('attendize.cdn_url_static_assets').'/assets/stylesheet/frontend.css')!!}
@endsection

@section('content')

  @include('Public.ViewEvent.Partials.EventHeaderSection')
    @include('Public.ViewEvent.Partials.EventTicketsSection')
    @include('Public.ViewEvent.Partials.EventDescriptionSection')
    @include('Public.ViewEvent.Partials.EventShareSection')
    @include('Public.ViewEvent.Partials.EventMapSection')
@stop

@section('scripts')
<script src = "https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
@endsection


