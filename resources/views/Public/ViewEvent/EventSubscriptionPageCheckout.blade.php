@extends('Public.ViewEvent.Layouts.EventPage')

@section('head')
    <script type="text/javascript" src="https://js.stripe.com/v2/"></script>
@stop

@section('content')
    @include('Public.ViewEvent.Partials.EventSubscriptionCreateOrderSection')
    <script>var OrderExpires = {{strtotime($expires)}};</script>
@stop

