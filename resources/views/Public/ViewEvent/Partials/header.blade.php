<div class="loader"></div>

<!--cookie-->
<!-- <div class="cookie">
        <div class="container">
          <div class="clearfix">
            <span>Please note this website requires cookies in order to function correctly, they do not store any specific information about you personally.</span>
            <div class="f-right"><a href="#" class="button button-type-3 button-orange">Accept Cookies</a><a href="#" class="button button-type-3 button-grey-light">Read More</a></div>
          </div>
        </div>
      </div>-->

<!-- - - - - - - - - - - - - - Wrapper - - - - - - - - - - - - - - - - -->

<div id="wrapper" class="wrapper-container wide">

  <!-- - - - - - - - - - - - - Mobile Menu - - - - - - - - - - - - - - -->

  <nav id="mobile-advanced" class="mobile-advanced"></nav>

  <!-- - - - - - - - - - - - - - Header - - - - - - - - - - - - - - - - -->

  <header id="header" class="header style-3 sticky-header">

    <!-- searchform -->

    <div class="searchform-wrap">
      <div class="vc-child h-inherit">

        <form class="search-form">
          <button type="submit" class="search-button"></button>
          <div class="wrapper">
            <input type="text" name="search" placeholder="Start typing...">
          </div>
        </form>

        <button class="close-search-form"></button>

      </div>
    </div>

    <!-- top-header -->

    <div class="top-header">

      <div class="flex-row align-items-center justify-content-between">

        <!-- logo -->

        <div class="logo-wrap">

          <a href="{{ asset('/homepage') }}" class="logo"><img src="{{ asset('/bootstrap/4.3.1/images/logo3.png')}}" alt=""></a>

        </div>

        <!-- - - - - - - - - - - - / Mobile Menu - - - - - - - - - - - - - -->

        <!--main menu-->

        <div class="menu-holder">

          <div class="menu-wrap">

            <div class="nav-item">

              <!-- - - - - - - - - - - - - - Navigation - - - - - - - - - - - - - - - - -->

              <nav id="main-navigation" class="main-navigation">
                <ul id="menu" class="clearfix">
                  <li class="current"><a href="#">Home</a>
                    <!--sub menu-->
                    <div class="sub-menu-wrap">
                      <ul>
                      <li class="sub"><a href="#">Header Layouts</a>
                          <!--sub menu-->
                          <div class="sub-menu-wrap sub-menu-inner">
                            <ul>
                              <li><a href="index.html">Header 1</a></li>
                              <li><a href="home_2.html">Header 2</a></li>
                              <li><a href="home_3.html">Header 3</a></li>
                              <li><a href="home_4.html">Header 4</a></li>
                              <li><a href="home_5.html">Header 5</a></li>
                            </ul>
                          </div>
                        </li>
                                              </ul>
                    </div>
                  </li>
                      <li><a href="#">Shop</a>
                    <!--sub menu-->
                    <div class="sub-menu-wrap">
                      <ul>
                        <li><a href="shop_category.html">Category Page</a></li>
                        <li><a href="shop_single.html">Single Product Page</a></li>
                        <li><a href="shop_cart.html">Cart</a></li>
                        <li><a href="shop_checkout.html">Checkout</a></li>
                        <li><a href="shop_account.html">My Account</a></li>
                      </ul>
                    </div>
                  </li>
                </ul>
              </nav>

              <!-- - - - - - - - - - - - - end Navigation - - - - - - - - - - - - - - - -->

            </div>

            <!-- search button -->
            <div class="search-holder"><button type="button" class="search-button"></button></div>
            <!-- account button -->
            <button type="button" class="account popup-btn-login"></button>

            <a href="#" class="btn btn-big btn-style-3 popup-btn-sign">Join Free</a>

          </div>

        </div>

      </div>

    </div>

  </header>

  <!-- - - - - - - - - - - - - end Header - - - - - - - - - - - - - - - -->


<!--
<nav class="navbar navbar-expand-lg navbar-dark fixed-top bg-dark">
<a class="navbar-brand" href=" {{ asset('/homepage') }} ">
<img src=" {{ asset('/bootstrap/logo1.png') }} " width="120" height="30" class="d-inline-block align-top" alt="">
</a>
<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
  <span class="navbar-toggler-icon"></span>
</button>
<div class="collapse navbar-collapse" id="navbarSupportedContent">
  <ul class="navbar-nav mr-auto">
    <li class="nav-item active">
      <a class="nav-link" href=" {{ asset('/homepage') }} ">Home <span class="sr-only">(current)</span></a>
    </li>
  </ul>

  @if(!Session::has('name'))
<form class="form-inline my-2 my-lg-0">
      <a href="\loginSimple" role="button">
    <button class="btn btn-outline-success my-2 my-sm-0" type="button">Accedi</button></a>
  </form>
@endif

@if(Session::has('error'))
<div class="alert alert-danger">
{{ Session::get('error')}}
</div>
@endif

@if(Session::has('name'))

 <div class="dropdown">
 <div>
  <h1 style="color:white">{{Session::get('surname')}} {{Session::get('name')}}</h1>
</div>
  <button class="btn btn-secondary dropdown-toggle btn-outline-success my-2 my-sm-0" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
    My menu
  </button>
  <div class="dropdown-menu my-2 my-lg-0" aria-labelledby="dropdownMenuButton" >

      <button class="btn btn-outline-success my-2 my-sm-0" type="button">my profile</button>

      <button class="btn btn-outline-success my-2 my-sm-0" type="button">my orders</button>

      <button class="btn btn-outline-success my-2 my-sm-0" type="button">
      <a href="\logoutSimple">logout me</a></button>

  </div>
</div>
@endif
</div>
</nav>
-->


<!--
<nav class="navbar navbar-expand-lg navbar-dark fixed-top bg-dark">
  <a class="navbar-brand" href=" {{ asset('/homepage') }} ">
  <img src=" {{ asset('/bootstrap/logo1.png') }} " width="120" height="40" style="padding-top:5%" class="d-inline-block" alt="">
</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto">
      <li class="nav-item active">
        <a class="nav-link" href=" {{ asset('/homepage') }} "><span class= 'btn-outline-success'>Home</span> <span class="sr-only">(current)</span></a>
      </li>
    </ul>


    @if(!Session::has('name'))
  <form class="form-inline my-2 my-lg-0">
        <a href="\loginSimple" role="button">
      <button class="btn btn-outline-success my-2 my-sm-0" type="button">Accedi</button></a>
    </form>
@endif

@if(Session::has('error'))
<div class="alert alert-danger">
  {{ Session::get('error')}}
</div>
@endif

@if(Session::has('name'))
<h3 class= 'btn-outline-success' style="padding-right: 3%;">{{Session::get('surname')}} {{Session::get('name')}}</h3>
<@if(Session::has('account_type') && Session::get('account_type') == 'SIMPLE')
<span style="padding-right: 1%;">

        <a href="#" class=" btn-outline-success my-2 my-sm-0 ico-cart mr5 section_head_carello"  aria-haspopup="true" aria-expanded="false">
        <!--<span class="ico-cart mr5 section_head_carello"> {{Cart::count()}}</span>-->
<!--    </a>
    <i class=" btn-outline-success my-2 my-sm-0 "> {{Cart::count()}}</i>

      </span>
    @endif
   <div class="dropdown">




    <button class="btn btn-secondary dropdown-toggle btn-outline-success my-2 my-sm-0" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
      My menu
    </button>
    <div class="dropdown-menu my-2 my-lg-0" aria-labelledby="dropdownMenuButton" >

        <button class="btn btn-outline-success my-2 my-sm-0" type="button">my profile</button>

        <button class="btn btn-outline-success my-2 my-sm-0" type="button">my orders</button>
        <button type="button" class="btn btn-default" aria-label="Left Align">
        <p>Shopping-cart icon: <span class="glyphicon glyphicon-shopping-cart"></span></p>
</button>

        <button class="btn btn-outline-success my-2 my-sm-0" type="button">
        <a href="\logoutSimple">logout me</a></button>

    </div>
  </div>
  @endif
</div>
</nav>
-->
