<html>
    <title>DanceClick</title>
    <head>This a simple page</head>
    <body>
        <div>
            <table>

            @foreach ($events as $event)
                <tr>
                    <td>{{ $event->title }}</td>
                    <td>{{ $event->start_date }}</td>
                    <td>{{ $event->location }}</td>
                    <td>{{ $event->description }}</td>
                </tr>
                <tr>
                    <td>
                        <a href = "{{route('showEventPage', array('event_id'=>$event->id))}}">ticket</a>
                    <!--
                        <div class="col-md-9">
                            
                            <div class="btn-toolbar" role="toolbar">
                                <div class="btn-group btn-group-responsive">
                                    <button data-modal-id='CreateCompetition'
                                            data-href="{{route('showEventPage', array('event_id'=>$event->id))}}"
                                            class='loadModal btn btn-success' type="button"><i class="ico-competition"></i> ticket
                                    </button>
                                </div>
                            </div>
                            -->
                        </div>
                     </td> 
                    <td><a href = "{{route('showSubscriptionPage', array('event_id'=>$event->id))}}">subscribe</a></td>
                </tr>
             @endforeach

            </table>
        </div>
    </body>
</html>

