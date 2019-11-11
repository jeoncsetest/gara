@extends('Public.ViewEvent.Layouts.master')


@section('head')
    @include('Public.ViewEvent.Partials.GoogleTagManager')
@endsection

@section('title')
Iscriviti
@endsection

@section('styles')
{!!HTML::style(config('attendize.cdn_url_static_assets').'/assets/stylesheet/frontend.css')!!}
@endsection

@section('content')
    @include('Public.ViewEvent.Partials.EventDanceSubscriptionsSection')
@endsection('content')

@section('scripts')

@include("Shared.Partials.LangScript")
        {!!HTML::script(config('attendize.cdn_url_static_assets').'/assets/javascript/frontend.js')!!}

<script src = "https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
<script>
function tempAlert(msg,duration)
{
 //var el = document.createElement("div");
 var el = document.createElement("div");
 el.setAttribute("style","position:absolute;top:10%;left:30%;background-color:white;");
 el.innerHTML = msg;
 setTimeout(function(){
  el.parentNode.removeChild(el);
 },duration);
 document.body.appendChild(el);
}
/**add to cart */
$(document).on('click', '.add_cart_item', function(){
        /**chiamata ajax per eliminare item dal carello */

        var rowTobeAdded = $(this).closest("tr").attr('id');
        console.info('row id:' + rowTobeAdded);

        var type = $("#competition_table #type_" + rowTobeAdded).val();
        console.info('type:' + type );
        var category = $("#competition_table #category_" + rowTobeAdded+ ' option:selected').text();
        console.info('category:' + category );
        var level = $("#competition_table #level_" + rowTobeAdded + ' option:selected').text();
        console.info('level:' + level );
        var title = $("#competition_table #title_" + rowTobeAdded).val();
        console.info('title:' + title );
        var price = $("#competition_table #price_" + rowTobeAdded).val();
        console.info('price:' + price );

        $.ajaxSetup({
            headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
        });

        $.ajax({
            type:'POST',
            url: '/{{$event->id}}/addToCart',
            data:{competition_id:rowTobeAdded, type : type, level:level, category : category,competition_id : rowTobeAdded, title : title,price : price},
            success:function(data){
                tempAlert(data.message,1000);
                $(".section_head_carello").text(' ' + data.cartCount);
                console.info('row id:' + rowTobeAdded);
                $("#add_cart_item_" + rowTobeAdded).removeClass( "btn-style-6" ).addClass( "btn-style-1" );
                if(data.cartCount == 1 || data.cartCount == '1'){
                location.reload();
              }
               // alert(data.message);

            }
        });
});
</script>
@endsection
