<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title> @yield('title')</title>

    @yield('styles')
    <link rel="stylesheet" href="{{ asset('bootstrap/4.3.1/css/bootstrap.css') }}">
        <link rel="stylesheet" href="{{ asset('bootstrap/4.3.1/css/jumbotron.css') }}">
        <link rel="stylesheet" href="{{ asset('font-awesome/css/font-awesome.min.css') }}">
    
  <script src="{{asset('bootstrap/4.3.1/js/jquery.js')}}"></script>
  <script src="{{asset('bootstrap/4.3.1/js/bootstrap.js')}}"></script>
  </head>
  <body>
      @include('Public.ViewEvent.Partials.header')
      @include('Public.ViewEvent.Partials.head')
<div class="jumbotron">
  @yield('content')
</div>

    @yield('scripts')
      @include('Public.ViewEvent.Partials.foot')
  </body>
</html>
