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

              </div>

            <!-- account button -->
            @if(!Session::has('name'))

          <!--  <button type="button" class="account popup-btn-login"> </button>  -->
            <a href="{{ asset('/loginSimple')}}" role="button" class="btn btn-big btn-style-3 popup-btn-sign">Accedi</a>
              @endif

              @if(Session::has('error'))
              <div class="alert alert-danger">
                {{ Session::get('error')}}
              </div>
              @endif

              @if(Session::has('name'))
              <nav id="main-navigation" class="main-navigation">
                     <ul id="menu" class="clearfix">
                           <li><p> <font color="white">{{Session::get('surname')}} {{Session::get('name')}}
              @if(Session::has('account_type') && Session::get('account_type') == 'SIMPLE')</font></p>
              </li>

          <li>
                      @if(Session::has('current_event_id'))
                        <a href= "/showCart?event_id={{Session::get('current_event_id')}}" class="fas fa-shopping-cart"
                          aria-haspopup="true" aria-expanded="false"> |
                      @else
                        <a href= "#" class="fas fa-shopping-cart"  aria-haspopup="true" aria-expanded="false"> |                      
                      @endif
                     
                      <i class=" text-size-small "><span class="section_head_carello"> {{Cart::count()}}</span> </i> </a>
                      <!--<span class="ico-cart mr5 section_head_carello"> {{Cart::count()}}</span>
                  </li>    <i class=" text-size-small "> {{Cart::count()}}</i>-->

                  @endif
                  <li><a href="#">Menu</a>
                                        <!--sub menu-->
                                        <div class="sub-menu-wrap">
                                          <ul>
                                            <li><a href="{{ asset('/profileMenu')}} ">Profilo</a></li>
                                            @if(Session::has('school'))
                                              <li><a href="{{ asset('/showStudentsPage')}}">Ballerini</a></li>
                                            @endif
                                            <li><a href="{{route('descriptionOrders')}}">Ordini</a></li>
                                            <li><a href="{{ asset('/logout')}} ">Logout</a></li>
                                          </ul>
                                        </div>
                                      </li>

               </ul>
             </nav>

  @endif
          </div>

        </div>

      </div>

    </div>

  </header>

  <!-- - - - - - - - - - - - - end Header - - - - - - - - - - - - - - - -->
