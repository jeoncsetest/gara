 <div class="container">
        <table>

            @foreach ($events as $event)
            
                  <div class="row">
                    <div class="col-md-4">
                      <div class="card mb-4 shadow-sm">
                        <button type="button" class="btn btn-sm btn-outline-secondary">
                        <img src="{{ asset($event->images->first()['image_path']) }}" class="header-img" alt="...">
                        <svg class="bd-placeholder-img card-img-top" width="100%" height="225" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="xMidYMid slice" focusable="false" role="img" aria-label="Placeholder: Evento"><title>Gara del 2020</title><rect width="100%" height="100%" fill="#55595c"/><text x="50%" y="50%" fill="#eceeef" dy=".3em">Evento</text></svg>
                        </button>
                        <div class="card-body">
                          <p class="card-text">{{ $event->title }}</p>
                          <p class="card-text">{{ $event->start_date }}</p>
                          <div class="d-flex justify-content-between align-items-center">
                              <a href="{{route('showEventPage', array('event_id'=>$event->id))}}" class="btn btn-primary btn-lg active" role="button" aria-pressed="true">Ticket</a>
                              <a href="{{route('showSubscriptionPage', array('event_id'=>$event->id))}}" class="btn btn-primary btn-lg active" role="button" aria-pressed="true">Iscrizione</a>
                          </div>
                        </div>
                      </div>
                    </div>
                </td>
        @endforeach
    </div>
