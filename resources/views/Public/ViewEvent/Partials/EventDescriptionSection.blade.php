<div class="breadcrumbs-wrap no-title">

   <div class="container">

     <ul class="breadcrumbs">
       <li>Home</a></li>
       <li>Gare</li>
       <li>{{ $event->title }}</li>
     </ul>
   </div>
 </div>
<span> &nbsp; &nbsp;</span>
 <!-- - - - - - - - - - - - - - Content - - - - - - - - - - - - - - - - -->

      <div class="container">

        <div class="content-element">

          <div class="content-element5">
            <div class="entry-box single-post">

              <div class="entry">

                <div class="content-element4">
                  <div class="entry-body">

                    <h1 class="entry-title">{{ $event->title }} </h1>
                    <div class="our-info vr-type">
                      <div class="info-item">
                        <i class="licon-clock3"></i>
                        <div class="wrapper">
                          <span>{{date ('d-m-Y h:m', strtotime ($event->start_date)) }} &nbsp; </span>
                        </div>
                      </div>
                      <div class="info-item">
                        <i class="licon-map-marker"></i>
                        <div class="wrapper">
                          <span>{{$event->venue_name}}</span>
                        </div>
                      </div>

                    </div>
                    <div class="content-element5">
                      <div class="icons-box style-1 flex-row justify-content-between item-col-3">

                        <!-- - - - - - - - - - - - - - Icon Box Item - - - - - - - - - - - - - - - - -->
                        <div class="icons-wrap">

                          <div class="icons-item">
                            <div class="item-box">
                              <i><img src=" {{ asset($event->images->first()['image_path']) }}" alt=""></i>
                              </div>
                          </div>

                        </div>

                        <!-- - - - - - - - - - - - - - Icon Box Item - - - - - - - - - - - - - - - - -->
                        <div class="icons-wrap">

                          <div class="icons-item">
                            <div class="item-box">
                             <h6 class="event-title">Dettagli</h6>
                                 <ul class="custom-list">
                                  <li><p >
                                          <a href = "{{route('showAgreement', array('event_id'=>$event->id))}}" class="btn btn-big btn-style-4">Regolamento</a>
                                      </p></li>
                                        <li><p >
                                      <div class="align-rigth">
                                    <!--    {{route('showEventDescription', array('event_desc_id'=>$event->id))}} -->
                                                 <a href="{{route('showEventPage', array('event_id'=>$event->id))}}" class="btn btn-big btn-style-4"><i class="fas fa-users"></i>PUBBLICO</a>
                                               </div>
                                               </p>
                                             </li>
                                               <li><p >
                                               <div class="align-rigth">
                                                          <a href="{{route('showSubscriptionPage', array('event_id'=>$event->id))}}" class="btn btn-big btn-style-6">ISCRIZIONE <i class="fas fa-medal"></i></a>
                                                        </div>
                                                        <li><p >
                                 </ul>
                              </div>
                          </div>
                        </div>

                        <!-- - - - - - - - - - - - - - Icon Box Item - - - - - - - - - - - - - - - - -->
                        <div class="icons-wrap">

                          <div class="icons-item">
                            <div class="item-box">
                              <div class="google-maps content" style="width=100%" >
                          <iframe frameborder="0" style="border:0;" src="https://maps.google.com/maps?f=q&amp;source=s_q&amp;hl=en&amp;geocode=&amp;q={{$event->map_address}}&amp;aq=0&amp;oq={{$event->map_address}}&amp;sll=28.659344,-81.187888&amp;sspn=0.128789,0.264187&amp;ie=UTF8&amp;hq={{$event->map_address}}&amp;t=m&amp;z=15&amp;iwloc=A&amp;output=embed"></iframe>
                      </div>
                            </div>
                          </div>
                          <div class="share-wrap">

                            <span class="share-title">Condividi:</span>
                            <ul class="social-icons var2 share">
                              @if($event->social_show_facebook)
                              <li><a href="https://www.facebook.com/sharer/sharer.php?={{$event->event_url}}" class="sh-facebook"><i class="icon-facebook"></i></a></li>
                              @endif
                              @if($event->social_show_twitter)
                              <li><a href="http://twitter.com/intent/tweet?text=Check out: {{$event->event_url}} {{{Str::words(strip_tags($event->description), 20)}}}" class="sh-twitter"><i class="icon-twitter"></i></a></li>
                              @endif

                              <li><a href="#" class="sh-instagram"><i class="icon-instagram-5"></i></a></li>

                             @if($event->social_show_email)
                              <li><a href="mailto:?subject=Check This Out&body={{urlencode($event->event_url)}}" class="sh-mail"><i class="icon-mail"></i></a></li>
                             @endif
                            </ul>



                          </div>
                        </div>

                      </div>
                    </div>

                  </div>
                </div>

              </div>

            </div>
          </div>

        </div>

      </div>



    <!-- - - - - - - - - - - - - end Content - - - - - - - - - - - - - - - -->
