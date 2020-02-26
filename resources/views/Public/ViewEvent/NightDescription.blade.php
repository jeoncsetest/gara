@extends('Public.ViewEvent.Layouts.master')
@section('title')
Descrizione Serata
@endsection
@section('styles')
{!!HTML::style(config('attendize.cdn_url_static_assets').'/assets/stylesheet/frontend.css')!!}
@endsection

@section('content')
  @include('Public.ViewEvent.Partials.NightDescriptionSection')
@stop

@section('scripts')
<script src = "https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
<script>
function showPopupIscrizione(){
$('#popup-iscrizione').fadeIn("slow");
		      return false;
}
</script>

@endsection
