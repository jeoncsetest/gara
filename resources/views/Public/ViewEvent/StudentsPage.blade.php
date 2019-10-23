@extends('Public.ViewEvent.Layouts.master')


@section('head')
    @include('Public.ViewEvent.Partials.GoogleTagManager')
@endsection

@section('title')
gestione ballerini
@endsection

@section('styles')
{!!HTML::style(config('attendize.cdn_url_static_assets').'/assets/stylesheet/frontend.css')!!}
@endsection

@section('content')
    @include('Public.ViewEvent.Partials.StudentsSection')
@endsection('content')

@section('scripts')

@include("Shared.Partials.LangScript")
        {!!HTML::script(config('attendize.cdn_url_static_assets').'/assets/javascript/frontend.js')!!}

<script src = "https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
@endsection
