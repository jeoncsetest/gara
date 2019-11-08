<!DOCTYPE html>
<html lang="en">
    <head>

        <title>{{{$event->title}}}</title>


        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0" />
        <link rel="canonical" href="{{$event->event_url}}" />
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <link rel="stylesheet" href="{{ asset('fontawesome/css/all.css') }}">
        <link rel="shortcut icon" href="{{ asset('bootstrap/4.3.1/images/favicon.ico') }}" >

        
        <!-- Open Graph data -->
        <meta property="og:title" content="{{{$event->title}}}" />
        <meta property="og:type" content="article" />
        <meta property="og:url" content="{{$event->event_url}}?utm_source=fb" />
        @if($event->images->count())
        <meta property="og:image" content="{{config('attendize.cdn_url_user_assets').'/'.$event->images->first()['image_path']}}" />
        @endif
        <meta property="og:description" content="{{Str::words(strip_tags(Markdown::parse($event->description))), 20}}" />
        <meta property="og:site_name" content="Attendize.com" />
        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
          <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
        <![endif]-->
        @yield('head')

       {!!HTML::style(config('attendize.cdn_url_static_assets').'/assets/stylesheet/frontend.css')!!}

        <!--Bootstrap placeholder fix-->
        <style>
            ::-webkit-input-placeholder { /* WebKit browsers */
                color:    #ccc !important;
            }
            :-moz-placeholder { /* Mozilla Firefox 4 to 18 */
                color:    #ccc !important;
                opacity:  1;
            }
            ::-moz-placeholder { /* Mozilla Firefox 19+ */
                color:    #ccc !important;
                opacity:  1;
            }
            :-ms-input-placeholder { /* Internet Explorer 10+ */
                color:    #ccc !important;
            }

            input, select {
                color: #999 !important;
            }

            .btn {
                color: #fff !important;
            }

        </style>
        @if ($event->bg_type == 'color' || Input::get('bg_color_preview'))
            <style>body {background-color: {{(Input::get('bg_color_preview') ? '#'.Input::get('bg_color_preview') : $event->bg_color)}} !important; }</style>
        @endif

        @if (($event->bg_type == 'image' || $event->bg_type == 'custom_image' || Input::get('bg_img_preview')) && !Input::get('bg_color_preview'))
            <style>
                body {
                    background-image: url({{asset('/bootstrap/4.3.1/images/1816x980_bg1.jpg')}}); no-repeat center center fixed;
                    background-size: cover;
                }
            </style>
        @endif
        <!-- Facebook Pixel Code -->
        <script>
        !function(f,b,e,v,n,t,s)
        {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
        n.callMethod.apply(n,arguments):n.queue.push(arguments)};
        if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
        n.queue=[];t=b.createElement(e);t.async=!0;
        t.src=v;s=b.getElementsByTagName(e)[0];
        s.parentNode.insertBefore(t,s)}(window, document,'script',
        'https://connect.facebook.net/en_US/fbevents.js');
        fbq('init', '714281029071098');
        fbq('track', 'PageView');
        </script>
        <noscript><img height="1" width="1" style="display:none"
        src="https://www.facebook.com/tr?id=714281029071098&ev=PageView&noscript=1"
        /></noscript>
        <!-- End Facebook Pixel Code -->    
    </head>
    <body class="attendize">
        <div id="event_page_wrap" vocab="http://schema.org/" typeof="Event">
            @yield('content')

            {{-- Push for sticky footer--}}
            @stack('footer')
        </div>

        {{-- Sticky Footer--}}
        @yield('footer')

        <a href="#intro" style="display:none;" class="totop"><i class="ico-angle-up"></i>
            <span style="font-size:11px;">@lang("basic.TOP")</span></a>

        @include("Shared.Partials.LangScript")
        {!!HTML::script(config('attendize.cdn_url_static_assets').'/assets/javascript/frontend.js')!!}


        @if(isset($secondsToExpire))
        <script>if($('#countdown')) {setCountdown($('#countdown'), {{$secondsToExpire}});}</script>
        @endif
        @include('Shared.Partials.GlobalFooterJS')
    </body>
</html>
