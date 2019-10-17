<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title> @yield('title')</title>

    @yield('styles')
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ asset('bootstrap/4.3.1/css/bootstrap.css') }}">
        <link rel="stylesheet" href="{{ asset('bootstrap/4.3.1/css/jumbotron.css') }}">
        <link rel="stylesheet" href="{{ asset('font-awesome/css/font-awesome.min.css') }}">
        <link rel="stylesheet" href="{{ asset('fontawesome/css/all.css') }}">
        

  <script src="{{asset('bootstrap/4.3.1/js/jquery.js')}}"></script>
  <script src="{{asset('bootstrap/4.3.1/js/bootstrap.js')}}"></script>

 <style>
    .jumbotron {
      padding-top: 5.5rem;
      background-image: url({{asset('/assets/images/sfondo.jpg')}});
      background-color: #cccccc;
      background-attachment: fixed;
 background-position: center;
 background-repeat: no-repeat;
 background-size: cover;
    }
    </style>

  </head>
  <body>
      @include('Public.ViewEvent.Partials.header')
      
<div class="jumbotron">
  @yield('content')
</div>

    @yield('scripts')
      @include('Public.ViewEvent.Partials.foot')
  </body>
</html>
