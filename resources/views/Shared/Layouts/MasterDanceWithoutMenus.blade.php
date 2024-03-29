<html>
    <head>

        <title>@yield('title')</title>

        @include('Shared/Layouts/ViewJavascript')

        @include('Shared.Partials.GlobalMeta')

        <!--JS-->
       {!! HTML::script('vendor/jquery/dist/jquery.min.js') !!}
        <!--/JS-->

        <!--Style-->
       {!!HTML::style('assets/stylesheet/application.css')!!}
        <!--/Style-->
        <link rel="stylesheet" href="{{ asset('bootstrap/4.3.1/css/style.css') }}">

        @yield('head')

        <style>

            body {
                padding-top: 5.5rem;
                background-image: url("http://localhost/gara/gara_github/public/bootstrap/4.3.1/images/1816x980_bg1.jpg") ;
                background-color: #cccccc;
                background-attachment: fixed;
                background-position: center;
                background-repeat: no-repeat;
                background-size: cover;
            }

            h2 {
                text-align: center;
                margin-bottom: 31px;
                text-transform: uppercase;
                letter-spacing: 4px;
                font-size: 23px;
            }
            .panel {
                background-color: #ffffff;
                background-color: rgba(255,255,255,.95);
                padding: 15px 30px ;
                border: none;
                color: #333;
                box-shadow: 0 0 5px 0 rgba(0,0,0,.2);
                margin-top: 40px;
            }

            .panel a {
                color: #333;
                font-weight: 600;
            }

            .logo {
                text-align: center;
                margin-bottom: 20px;
            }

            .logo img {
                width: 200px;
            }

            .signup {
                margin-top: 10px;
            }

            .forgotPassword {
                font-size: 12px;
                color: #ccc;
            }
        </style>
    </head>
    <body>
        <section id="main" role="main">
            <section class="popup-inner">
                @yield('content')
            </section>

        </section>
        <div style="text-align: center; color: white" >
        </div>

        @include("Shared.Partials.LangScript")
        {!!HTML::script('assets/javascript/backend.js')!!}

    </body>

</html>
