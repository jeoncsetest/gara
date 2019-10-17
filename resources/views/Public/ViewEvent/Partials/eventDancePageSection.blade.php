 <div class="container">
          @foreach($events->chunk(3) as $eventChunk)
            
                  <div class="row">
		@foreach ($eventChunk as $event)
                     <div class="col-sm-6 col-md-4">
                      <div class="card mb-4 shadow-sm"> 
                        <button type="button" class="btn btn-sm btn-outline-secondary">
                        <img src="{{ asset($event->images->first()['image_path']) }}"  class="img-fluid img-thumbnail" alt="Responsive image" alt="...">
                        <!--<svg class="bd-placeholder-img card-img-top" width="100%" height="225" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="xMidYMid slice" focusable="false" role="img" aria-label="Placeholder: Evento"><title>Gara del 2020</title><rect width="100%" height="100%" fill="#55595c"/><text x="50%" y="50%" fill="#eceeef" dy=".3em">Evento</text></svg>-->
                        </button>
                        <div class="card-body">
                        	<h1>  <p class="card-text">{{ $event->title }}</p></h1>
                       		<h5>  <p class="card-text">{{ $event->start_date }}</p></h5>
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
