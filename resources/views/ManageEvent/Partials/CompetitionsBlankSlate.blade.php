@extends('Shared.Layouts.BlankSlate')

@section('blankslate-icon-class')
    ico-ticket
@stop

@section('blankslate-title')
    @lang("Competition.no_competitions_yet")
@stop

@section('blankslate-text')
    @lang("Competition.no_competitions_yet_text")
@stop

@section('blankslate-body')
    <button data-invoke="modal" data-modal-id='CreateCompetition' data-href="{{route('showCreateCompetition', array('event_id'=>$event->id))}}" href='javascript:void(0);'  class=' btn btn-success mt5 btn-lg' type="button" >
        <i class="ico-ticket"></i>
        @lang("Competition.create_competition")
    </button>
@stop
