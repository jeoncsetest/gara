@extends('Public.ViewEvent.Layouts.master')

@section('title')
Homepage
@endsection
@section('content')
  @include('Public.ViewEvent.Partials.head')
    @include('Public.ViewEvent.Partials.homePageSection')
@endsection('content')
@section('scripts')
<script>
/**login */
$(document).on('click', '#loginBtn', function(){
        /**chiamata ajax per login */
        var email = $('#myemail').val();
        console.info('email:' + email );
        var password = $('#mypassword').val();
        console.info('password:' + password );
        $.ajaxSetup({
            headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
        });


        $.ajax({
            type:'POST',
            url: "{{route('logoutSimple')}}",
            data:{email:email, password : password, ajaxCall:'yes'},
            success:function(data){
                console.info(data);
            }
        });

        $.ajax({
            type:'POST',
            url: "{{route('loginWithLogoutSimple')}}",
            data:{email:email, password : password, ajaxCall:'yes'},
            success:function(data){
              if(data.status == 'success'){
                  location.href = data.redirectUrl;
              }else{
                  alert(data.message);
              }
               // alert(data.message);

            }
        });
});
</script>
@endsection
