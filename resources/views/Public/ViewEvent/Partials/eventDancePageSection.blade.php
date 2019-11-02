<!-- - - - - - - - - - - - - - Breadcrumbs - - - - - - - - - - - - - - - - -->

<div class="breadcrumbs-wrap">

  <div class="container">

    <h1 class="page-title">Gare</h1>

    <ul class="breadcrumbs">

      <li>Home</li>
      <li>Gare</li>

    </ul>

  </div>

</div>

<!-- - - - - - - - - - - - - end Breadcrumbs - - - - - - - - - - - - - - - -->




<!------- content -------->

    <div id="content" class="page-content-wrap">
      <div class="container">
        <div class="content-element8">

    <!--   <div class="row">
          <div class="col-lg-10"> -->
    @foreach($events->chunk(3) as $eventChunk)

            <div class="entry-box list-type">

@foreach ($eventChunk as $event)

          <!-- - - - - - - - - - - - - - Entry - - - - - - - - - - - - - - - - -->
          <div class="entry">

            <!-- - - - - - - - - - - - - - Entry attachment - - - - - - - - - - - - - - - - -->
            <div class="thumbnail-attachment">
            <img src="{{ asset($event->images->first()['image_path']) }}" alt=" ... ">
            </div>

            <!-- - - - - - - - - - - - - - Entry body - - - - - - - - - - - - - - - - -->
            <div class="entry-body"  align="center">

              <h3 class="entry-title">{{ $event->title }} </h3>
              <div class="our-info">

                <div class="info-item">
                  <i class="licon-clock3"></i>
                  <div class="wrapper">
                    <span><h6> {{date ('d-m-Y', strtotime ($event->start_date)) }} </h6></span>
                  </div>
                </div>
                <div class="info-item">
                  <i class="licon-map-marker"></i>
                  <div class="wrapper">
                    <span><h6> {{$event->venue_name}}</h6></span>
                  </div>
                </div>

              </div>

              <h1>
              <a href="{{route('showEventDescription', array('event_desc_id'=>$event->id))}}" class="btn btn-big"> Partecipa </a>
            </h1>
                </div>
            </div>
	@endforeach
          </div>

@endforeach


    </div>

            </div>
                </div>
                    </div>


    <!-- - - - - - - - - - - - - end Content - - - - - - - - - - - - - - - -->
