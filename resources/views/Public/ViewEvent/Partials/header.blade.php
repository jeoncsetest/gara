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

<!--    <div class="searchform-wrap">
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
-->
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

      <!--   <nav id="main-navigation" class="main-navigation">
                <ul id="menu" class="clearfix">
                      <li><a href="#">Shop</a>
                    <!--sub menu-->
                <!--    <div class="sub-menu-wrap">
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
-->
              <!-- - - - - - - - - - - - - end Navigation - - - - - - - - - - - - - - - -->

            </div>

            <!-- account button -->
            @if(!Session::has('name'))

          <!--  <button type="button" class="account popup-btn-login"> </button>  -->
            <a href="http://localhost/gara/gara_github/public/loginSimple" role="button" class="btn btn-big btn-style-3 popup-btn-sign">Accedi</a>
              @endif

              @if(Session::has('error'))
              <div class="alert alert-danger">
                {{ Session::get('error')}}
              </div>
              @endif

              @if(Session::has('name'))
              <nav id="main-navigation" class="main-navigation">
                     <ul id="menu" class="clearfix">
                           <li><p> {{Session::get('surname')}} {{Session::get('name')}}
              <@if(Session::has('account_type') && Session::get('account_type') == 'SIMPLE')</p>
              </li>

          <li>
                      <a href="#" class="fas fa-shopping-cart"  aria-haspopup="true" aria-expanded="false">   </a>
                      <!--<span class="ico-cart mr5 section_head_carello"> {{Cart::count()}}</span>-->
                  </li>  <li>   <i class=" text-size-small"> <span class=" section_head_carello">{{Cart::count()}} </span> </i>
                  </li>
                  @endif
                  <li><a href="#">Menu</a>
                                        <!--sub menu-->
                                        <div class="sub-menu-wrap">
                                          <ul>
                                            <li><a href="shop_category.html">Profilo</a></li>
                                            <li><a href="shop_single.html">Ordini</a></li>
                                            <li><a href="\logout">Logout</a></li>
                                          </ul>
                                        </div>
                                      </li>

                <!--   <button class="btn btn-big btn-style-3" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">   </button>-->

              <!--     <div class="dropdown-menu my-2 my-lg-0" aria-labelledby="dropdownMenuButton" >

                       <button class="btn btn-big btn-style-3" type="button">my profile</button>

                       <button class="btn btn-big btn-style-3" type="button">my orders</button>
                       <button type="button" class="btn btn-default" aria-label="Left Align">
                       <p>Shopping-cart icon: <span class="glyphicon glyphicon-shopping-cart"></span></p>
               </button>

                       <button class="btn btn-big btn-style-3" type="button">
                       <a href="\logoutSimple">logout </a></button>

                   </div>-->
                 </ul>
             </nav>

  @endif
          </div>

        </div>

      </div>

    </div>

  </header>

  <!-- - - - - - - - - - - - - end Header - - - - - - - - - - - - - - - -->
