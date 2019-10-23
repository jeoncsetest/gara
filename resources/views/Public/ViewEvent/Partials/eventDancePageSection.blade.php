<div class="breadcrumbs-wrap">

      <div class="container">

        <h1 class="page-title">Gare</h1>

        <ul class="breadcrumbs">

          <li><a href="index.html">Home</a></li>
          <li>Events List</li>

        </ul>

      </div>



<!------- content -------->

    <div id="content" class="page-content-wrap">

  <div class="container">


      <div class="tribe-events-bar">

        <div class="row">
          <div class="col-lg-10">
    @foreach($events->chunk(3) as $eventChunk)

            <div class="entry-box list-type">

@foreach ($eventChunk as $event)

          <!-- - - - - - - - - - - - - - Entry - - - - - - - - - - - - - - - - -->
          <div class="entry">



            <!-- - - - - - - - - - - - - - Entry attachment - - - - - - - - - - - - - - - - -->
            <div class="thumbnail-attachment">
              <a href="#"><img src="{{ asset($event->images->first()['image_path']) }}" alt=" ... "></a>
            </div>

            <!-- - - - - - - - - - - - - - Entry body - - - - - - - - - - - - - - - - -->
            <div class="entry-body">

              <h5 class="entry-title"><a href="#">{{ $event->title }} </a></h5>
              <div class="our-info">

                <div class="info-item">
                  <i class="licon-clock3"></i>
                  <div class="wrapper">
                    <span> {{date ('d-m-Y h:m', strtotime ($event->start_date)) }}</span>
                  </div>
                </div>
                <div class="info-item">
                  <i class="licon-map-marker"></i>
                  <div class="wrapper">
                    <span>{{ $event->title }}</span>
                  </div>
                </div>

              </div>

              <a href="{{route('showEventDescription', array('event_desc_id'=>$event->id))}}" class="btn btn-small"> Partecipa </a>

                </div>
            </div>
	@endforeach
          </div>
    </div>
@endforeach


    </div>
        </div>
            </div>
                </div>
                    </div>


    <!-- - - - - - - - - - - - - end Content - - - - - - - - - - - - - - - -->

<!--
 <div class="container">
          @foreach($events->chunk(3) as $eventChunk)

                  <div class="row">
		@foreach ($eventChunk as $event)
                     <div class="col-sm-6 col-md-4">
                      <div class="card mb-4 shadow-sm">
                        <button type="button" class="btn btn-sm btn-outline-secondary" onclick="window.location.href='{{route('showEventDescription', array('event_desc_id'=>$event->id))}}'">
                        <img src="{{ asset($event->images->first()['image_path']) }}"  class="img-fluid img-thumbnail" alt="Responsive image" alt="...">
                        </button>
                        <div class="card-body">
                        	<h1>  <p class="card-text">{{ $event->title }}</p></h1>
                          <p class="card-text"> <h5>  {{date ('d-m-Y h:m', strtotime ($event->start_date)) }}</h5></p>
                          <div class="d-flex justify-content-between align-items-center">
                              <a href="{{route('showEventPage', array('event_id'=>$event->id))}}" class="btn btn-primary btn-lg active" role="button" aria-pressed="true"><i class="fas fa-users"></i> Pubblico</a>
                             <a href="{{route('showSubscriptionPage', array('event_id'=>$event->id))}}" class="btn btn-secondary btn-lg active" role="button" aria-pressed="true">Iscrizione <i class="fas fa-trophy"></i></a>
                          </div>
                        </div>
                      </div>
</div>
     		@endforeach

                    </div>
@endforeach
    </div>
