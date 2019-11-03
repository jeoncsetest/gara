

    <!-- - - - - - - - - - - - - - Content - - - - - - - - - - - - - - - - -->

    <div id="content">

      <!-- Info box -->
      <div class="info-boxes style-2 flex-row no-gutters item-col-4">

        <div class="info-box" data-bg="{{ asset('/bootstrap/4.3.1/images/480x338_bg1.jpg') }}">

          <h3 class="box-title"><a href="{{ route('showEventListPage') }}">Gare</a></h3>
          <p> <span> &nbsp; &nbsp;</span> <br> </p>
          <a href="{{ route('showEventListPage') }}" class="btn btn-style-2">Visualizza</a>

        </div>

        <div class="info-box" data-bg="{{ asset('/bootstrap/4.3.1/images/480x338_bg4.jpg') }}">

          <h3 class="box-title">Eventi</h3>

        </div>

        <div class="info-box" data-bg="{{ asset('/bootstrap/4.3.1/images/480x338_bg3.jpg') }}">

          <h3 class="box-title">Lezioni</h3>

        </div>

        <div class="info-box" data-bg="{{ asset('/bootstrap/4.3.1/images/480x338_bg2.jpg') }}">

           <h3 class="box-title">Serate</h3>

        </div>

      </div>

      <!-- Page section -->

    <div id="popup-sign" class="popup var3">

      <div class="popup-inner">

        <button type="button" class="close-popup"></button>

        <h4 class="title">Iscriviti</h4>
       <p>Hai già un account? <a href="#" class="link-text var2 popup-btn-login">Login </a></p>
    <!--     <a href="#" class="btn fb-btn btn-big">Sign Up With Facebook</a>
        <p class="content-element2">O</p> -->

        <form class="content-element2">

                  <a href="{{route('showSignupSimple', array('signupType'=>'TICKET'))}}" class="btn btn-primary btn-lg active" role="button" aria-pressed="true"><i class="fas fa-users"></i> Pubblico</a>
                  <br> <span> &nbsp;</span>
                  <a href="{{route('showSignupSimple', array('signupType'=>'STUDENT'))}}" class="btn btn-primary btn-lg active" role="button" aria-pressed="true"><i class="fas fa-users"></i> Ballerino</a>
                  <br><span> &nbsp;</span>
                  <a href="{{route('showSignupSimple', array('signupType'=>'SCHOOL'))}}" class="btn btn-primary btn-lg active" role="button" aria-pressed="true"><i class="fas fa-users"></i> Scuola</a>

        </form>
      </div>

    </div>

    <div id="popup-login" class="popup var3">

      <div class="popup-inner">

        <button type="button" class="close-popup"></button>

        <h4 class="title">Login</h4>
    <!--    <p>Non hai ancora un account?<a href="#" class="link-text var2 popup-btn-sign">Iscriviti</a></p>
        <a href="#" class="btn fb-btn btn-big"> Login con Facebook</a>
        <p class="content-element2">OR</p> -->
        <form  method="post" class="content-element1">
          <input name="email" id="myemail" type="text" placeholder="Enter Your Email Address">
          <input name="password" id="mypassword" type="password" placeholder="Password">
          <button type="button" id="loginBtn" class="btn btn-style-3 btn-big">Login</button>
          <div class="input-wrapper">
            <input type="checkbox" id="checkbox11" name="checkbox">
            <label for="checkbox">Ricordami</label>
          </div>

        </form>

        <p class="text-size-small"><a href="#" class="link-text">Password dimenticata?</a></p>

      </div>

    </div>

  <!--  -- - - - - - - - - - - - end Wrapper - - - - - - - - - - - - - - -->
</div>
