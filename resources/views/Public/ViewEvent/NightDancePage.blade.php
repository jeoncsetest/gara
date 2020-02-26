@extends('Public.ViewEvent.Layouts.master')
@section('title')
Serate
@endsection

@section('styles')
{!!HTML::style(config('attendize.cdn_url_static_assets').'/assets/stylesheet/frontend.css')!!}
@endsection

@section('content')

    @include('Public.ViewEvent.Partials.NightDancePageSection')
@endsection
