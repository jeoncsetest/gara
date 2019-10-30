

    <!-- - - - - - - - - - - - - - Content - - - - - - - - - - - - - - - - -->

    <div id="content">

      <!-- Info box -->
      <div class="info-boxes style-2 flex-row no-gutters item-col-4">

        <div class="info-box" data-bg="{{ asset('/bootstrap/4.3.1/images/480x338_bg1.jpg') }}">

          <h3 class="box-title"><a href="{{ route('showEventListPage') }}">Gare</a></h3>
          <p> <h3> <br> Dancepass <br> Tutta la danza che vuoi. </h3></p>
          <a href="{{ route('showEventListPage') }}" class="btn btn-style-2">Visualizza</a>

        </div>

        <div class="info-box" data-bg="{{ asset('/bootstrap/4.3.1/images/480x338_bg2.jpg') }}">

          <h3 class="box-title">Eventi</h3>
  <!--        <p>Aenean auctor wisi et urna. Aliquam <br> erat volutpat. Duis ac turpis. Donec sit <br> amet eros. Lorem ipsum dolor sit amet.</p>
          <a href="#" class="btn btn-style-2">Visualizza </a>
-->
        </div>

        <div class="info-box" data-bg="{{ asset('/bootstrap/4.3.1/images/480x338_bg3.jpg') }}">

          <h3 class="box-title">Lezioni</h3>
  <!--        <p>Aenean auctor wisi et urna. Aliquam <br> erat volutpat. Duis ac turpis. Donec sit <br> amet eros. Lorem ipsum dolor sit amet.</p>
          <a href="#" class="btn btn-style-2">Visualizza</a>
-->
        </div>

        <div class="info-box" data-bg="{{ asset('/bootstrap/4.3.1/images/480x338_bg4.jpg') }}">

           <h3 class="box-title">Serate</h3>
<!--        <p>Aenean auctor wisi et urna. Aliquam <br> erat volutpat. Duis ac turpis. Donec sit <br> amet eros. Lorem ipsum dolor sit amet.</p>
          <a href="#" class="btn btn-style-2">Visualizza</a>
-->
        </div>

      </div>

      <!-- Page section -->

    <div id="popup-sign" class="popup var3">

      <div class="popup-inner">

        <button type="button" class="close-popup"></button>

        <h4 class="title">Sign Up For Free</h4>
        <p>Already have an account? <a href="#" class="link-text var2 popup-btn-login">Login Here</a></p>
        <a href="#" class="btn fb-btn btn-big">Sign Up With Facebook</a>
        <p class="content-element2">OR</p>

        <form class="content-element2">

                  <a href="{{route('showSignupSimple', array('signupType'=>'TICKET'))}}" class="btn btn-primary btn-lg active" role="button" aria-pressed="true"><i class="fas fa-users"></i> Pubblico</a>
                  <br>
                  <a href="{{route('showSignupSimple', array('signupType'=>'STUDENT'))}}" class="btn btn-primary btn-lg active" role="button" aria-pressed="true"><i class="fas fa-users"></i> Ballerino</a>
                  <br>
                  <a href="{{route('showSignupSimple', array('signupType'=>'SCHOOL'))}}" class="btn btn-primary btn-lg active" role="button" aria-pressed="true"><i class="fas fa-users"></i> Scuola</a>


        </form>

        <p class="text-size-small">By signing up you agree to <a href="#" class="link-text">Terms of Service</a></p>

      </div>

    </div>

    <div id="popup-login" class="popup var3">

      <div class="popup-inner">

        <button type="button" class="close-popup"></button>

        <h4 class="title">Login</h4>
        <p>Don't have an account yet?<a href="#" class="link-text var2 popup-btn-sign">JOIN FOR FREE</a></p>
        <a href="#" class="btn fb-btn btn-big"> Login With Facebook</a>
        <p class="content-element2">OR</p>
        <form  method="post" class="content-element1">
          <input name="email" id="myemail" type="text" placeholder="Enter Your Email Address">
          <input name="password" id="mypassword" type="password" placeholder="Password">
          <button type="button" id="loginBtn" class="btn btn-style-3 btn-big">Login</button

          <div class="input-wrapper">
            <input type="checkbox" id="checkbox11" name="checkbox">
            <label for="checkbox">Remember me</label>
          </div>

        </form>

        <p class="text-size-small"><a href="#" class="link-text">Forgot your password?</a></p>

      </div>

    </div>

    -- - - - - - - - - - - - end Wrapper - - - - - - - - - - - - - - -->
</div>
