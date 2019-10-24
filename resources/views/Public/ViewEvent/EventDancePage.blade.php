@extends('Public.ViewEvent.Layouts.master')
@section('title')
Evento
@endsection
@section('styles')
{!!HTML::style(config('attendize.cdn_url_static_assets').'/assets/stylesheet/frontend.css')!!}
@endsection
@section('content')

    
    @include('Public.ViewEvent.Partials.eventDancePageSection')
@endsection
