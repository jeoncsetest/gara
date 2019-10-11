
@extends('Public.ViewEvent.Layouts.master')
@section('title')
Carellooooooooooooooooo
@endsection
@section('content')
@include('Public.ViewEvent.Partials.EventDanceCartSection')
@endsection
@section('scripts')
<script src = "https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
<script>
function add_participant(rowId){ 
    html = '';
    participantName = "participants_"+rowId + "[]";
    html +=  '<tr><td><div class="ui-widget">' +
                  "<select name='participants_" + rowId + "[]'"  + ' class="combobox">' +
                  @foreach ($students as $iter)
                    "<option value='" + "{{ $iter->id }}'" + '>' + '{{ $iter->name }}' + ' ' +  '{{ $iter->surname }}' + '</option>>'
                  @endforeach
                  + '</select>'
                + '</td></tr></div>';
    $('#dyn_participants_' + rowId).append(html);
}

function remove_participant(rowId){
        var rowCount = $('#dyn_participants_' + rowId + ' tr').length;
        console.info('count row :' + rowCount);
        if(rowCount > 2){
            $('#dyn_participants_' + rowId + ' tr:last').remove();
        }
}

/*
$('#exampleModal').on('show.bs.modal', function (event) {
  var button = $(event.relatedTarget) // Button that triggered the modal
  var recipient = button.data('whatever') // Extract info from data-* attributes
  var competition_title = button.data('cartitem');
  // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
  // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
  var modal = $(this)
  modal.find('.modal-title').text(competition_title);
  //modal.find('.modal-body input').val(recipient);
})
*/
$(document).on('click', '#remove_cart_item', function(){
        /**chiamata ajax per eliminare item dal carello */
        rowTobeEliminated = $(this).closest("tr").attr('id');
         
        $.ajaxSetup({
            headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
        });

        $.ajax({
            type:'POST',
            url: '{{route('removeFromCart', ['event_id' => $event->id])}}',
            data:{rowIdCart:rowTobeEliminated},
            success:function(data){
              $("#section_head_carello").text(data.cartCount);
              console.info('total:' + data.total);
              $("#cartTotal").text(data.total);
              $('#' + rowTobeEliminated).closest("tr").remove();
                //alert(data.message);
            }
        });         
});
</script>
@endsection

