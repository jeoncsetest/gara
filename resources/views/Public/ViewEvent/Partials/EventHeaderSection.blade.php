@if(!$event->is_live)
<section id="goLiveBar">
    <div class="container">
        @if(!$event->is_live)

        {{ @trans("ManageEvent.event_not_live") }}
        <a href="{{ route('MakeEventLive' , ['event_id' => $event->id]) }}"
           style="background-color: green; border-color: green;"
        class="btn btn-success btn-xs">{{ @trans("ManageEvent.publish_it") }}</a>
        @endif
    </div>
</section>
@endif
<!--
<section id="organiserHead" class="container-fluid">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div onclick="window.location='{{$event->event_url}}#organiser'" class="event_organizer">
                    <b>{{$event->organiser->name}}</b> @lang("Public_ViewEvent.presents")
                </div>
            </div>
        </div>
    </div>
</section>
-->

<!---   link  -->

<div class="breadcrumbs-wrap no-title">

   <div class="container">

     <ul class="breadcrumbs">
       <li>Home</a></li>
       <li>Gare</li>
       <li>{{ $event->title }}</li>
       <li>Biglietto</li>
     </ul>
   </div>
 </div>
<span> &nbsp; &nbsp;</span>
 <!-- - - - - - - - - - - - - - Content - - - - - - - - - - - - - - - - -->


  <div class="container">
    <div class="row">
        <div class="col-md-12">
            <h1 property="name">{{$event->title}}</h1>

        </div>
    </div>
</div>
<span> &nbsp; &nbsp;</span>
<span> &nbsp; &nbsp;</span>
