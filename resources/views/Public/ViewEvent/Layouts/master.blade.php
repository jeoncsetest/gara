<!doctype html>
<html lang="en">
<head>
  <!-- Google Web Fonts
  ================================================== -->

  <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700%7CPrata" rel="stylesheet">


  <!-- Basic Page Needs
  ================================================== -->
<title> @yield('title')</title>

  <!--meta info
  <meta charset="utf-8">
  <meta name="author" content="">
  <meta name="keywords" content="">
  <meta name="description" content="">
-->
  <!-- Mobile Specific Metas
  ================================================== -->
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <!-- Vendor CSS
  ============================================ -->


  <link rel="stylesheet" href="{{ asset('bootstrap/4.3.1/font/demo-files/demo.css') }}">
  <link rel="stylesheet" href="{{ asset('bootstrap/4.3.1/plugins/fancybox/jquery.fancybox.css') }}">
  <link rel="stylesheet" href="{{ asset('bootstrap/4.3.1/plugins/revolution/css/settings.css') }}">
  <link rel="stylesheet" href="{{ asset('bootstrap/4.3.1/plugins/revolution/css/layers.css') }}">
  <link rel="stylesheet" href="{{ asset('bootstrap/4.3.1/plugins/revolution/css/navigation.css') }}">
  <link rel="stylesheet" href="{{ asset('fontawesome/css/all.css') }}">

  <!-- CSS theme files
  ============================================ -->
  <link rel="stylesheet" href="{{ asset('bootstrap/4.3.1/css/bootstrap-grid.min.css') }}">
  <link rel="stylesheet" href="{{ asset('bootstrap/4.3.1/css/fontello.css') }}">
  <link rel="stylesheet" href="{{ asset('bootstrap/4.3.1/css/owl.carousel.css') }}">
  <link rel="stylesheet" href="{{ asset('bootstrap/4.3.1/css/style.css') }}">
  <link rel="stylesheet" href="{{ asset('bootstrap/4.3.1/css/responsive.css') }}">
  <link rel="shortcut icon" href="{{ asset('bootstrap/4.3.1/images/favicon.png') }}" >
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
  <body>

    @include('Public.ViewEvent.Partials.header')


  @yield('content')


<!-- JS Libs & Plugins
 ============================================ -->
 <script src="{{ asset('bootstrap/4.3.1/js/libs/jquery.modernizr.js') }}"></script>
 <script src="{{ asset('bootstrap/4.3.1/js/libs/jquery-2.2.4.min.js') }}"></script>
 <script src="{{ asset('bootstrap/4.3.1/js/libs/jquery-ui.min.js') }}"></script>
 <script src="{{ asset('bootstrap/4.3.1/js/libs/retina.min.js') }}"></script>
 <script src="https://maps.googleapis.com/maps/api/js?libraries=places&amp;key=AIzaSyBN4XjYeIQbUspEkxCV2dhVPSoScBkIoic"></script>
 <script src="{{ asset('bootstrap/4.3.1/plugins/jquery.scrollTo.min.js') }}"></script>
 <script src="{{ asset('bootstrap/4.3.1/plugins/jquery.localScroll.min.js') }}"></script>
 <script src="{{ asset('bootstrap/4.3.1/plugins/instafeed.min.js') }}"></script>
 <script src="{{ asset('bootstrap/4.3.1/plugins/fancybox/jquery.fancybox.min.js') }}"></script>
 <script src="{{ asset('bootstrap/4.3.1/plugins/mad.customselect.js') }}"></script>
 <script src="{{ asset('bootstrap/4.3.1/plugins/revolution/js/jquery.themepunch.tools.min.js?ver=5.0') }}"></script>
 <script src="{{ asset('bootstrap/4.3.1/plugins/revolution/js/jquery.themepunch.revolution.min.js?ver=5.0') }}"></script>
 <script src="{{ asset('bootstrap/4.3.1/plugins/jquery.queryloader2.min.js') }}"></script>
 <script src="{{ asset('bootstrap/4.3.1/plugins/owl.carousel.min.js') }}"></script>

 <!-- JS theme files
 ============================================ -->
 <script src="{{ asset('bootstrap/4.3.1/js/plugins.js') }}"></script>
 <script src="{{ asset('bootstrap/4.3.1/js/script.js') }}"></script>


 <script type="text/javascript" src="{{ asset('bootstrap/4.3.1/plugins/revolution/js/extensions/revolution.extension.slideanims.min.js') }}"></script>
 <script type="text/javascript" src="{{ asset('bootstrap/4.3.1/plugins/revolution/js/extensions/revolution.extension.layeranimation.min.js') }}"></script>
 <script type="text/javascript" src="{{ asset('bootstrap/4.3.1/plugins/revolution/js/extensions/revolution.extension.navigation.min.js') }}"></script>

 @yield('scripts')
   @include('Public.ViewEvent.Partials.foot')

    </body>
    </html>
