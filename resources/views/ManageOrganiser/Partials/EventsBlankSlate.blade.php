@extends('Shared.Layouts.BlankSlate')

@section('blankslate-icon-class')
    ico-ticket
@stop

@section('blankslate-title')
    @if(!empty($organiser->account->organiser_type) && $organiser->account->organiser_type =='night')
        @lang("Event.no_nights_yet")
    @else
        @lang("Event.no_events_yet")
    @endif       
@stop

@section('blankslate-text')
    @if(!empty($organiser->account->organiser_type) && $organiser->account->organiser_type =='night')
        @lang("Event.no_nights_yet_text")
    @else
        @lang("Event.no_events_yet_text")
    @endif 
@stop

@section('blankslate-body')
    @if(!empty($organiser->account->organiser_type) && $organiser->account->organiser_type =='night')
        <button data-invoke="modal" data-modal-id="CreateEvent" data-href="{{route('showCreateNight', ['organiser_id' => $organiser->id])}}" href='javascript:void(0);'  class="btn btn-success mt5 btn-lg" type="button">
        <i class="ico-ticket"></i>
        @lang("Event.create_night")
        </button>
    @else
        <button data-invoke="modal" data-modal-id="CreateEvent" data-href="{{route('showCreateEvent', ['organiser_id' => $organiser->id])}}" href='javascript:void(0);'  class="btn btn-success mt5 btn-lg" type="button">
        <i class="ico-ticket"></i>
        @lang("Event.create_event")
        </button>
    @endif 
@stop


