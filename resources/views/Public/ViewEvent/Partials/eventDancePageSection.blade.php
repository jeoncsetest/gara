<div class="breadcrumbs-wrap">
      <div class="container">
        <h1 class="page-title">Gare</h1>
        <ul class="breadcrumbs">
          <li>Home</li>
          <li>Gare</li>

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
                    <span>{{$event->venue_name}}</span>
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
