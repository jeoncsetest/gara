<nav class="navbar navbar-expand-lg navbar-dark fixed-top bg-dark">
  <a class="navbar-brand" href=" {{ asset('/homepage') }} ">
  <img src=" {{ asset('/bootstrap/logo.png') }} " width="120" height="30" class="d-inline-block align-top" alt="">
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
