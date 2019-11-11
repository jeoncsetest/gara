
@extends('Public.ViewEvent.Layouts.master')
@section('title')
Carello
@endsection
@section('styles')
{!!HTML::style(config('attendize.cdn_url_static_assets').'/assets/stylesheet/frontend.css')!!}
@endsection
@section('content')
@include('Public.ViewEvent.Partials.EventDanceCartSection')
@endsection
@section('scripts')
<script src = "https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>

<script>
/*
	$('.popup-btn-removeCart').on('click', function() {
		      $('#popup-removeCart').fadeIn("slow");
		      return false;
        });
        */

function add_participant(rowId){ 
    html = '';
    participantName = "participants_"+rowId + "[]";
    html +=  '<tr><td>';
    @if(Session::has('school'))
      html += '<div class="ui-widget">' +
      "<select name='participants_" + rowId + "[]'"  + ' class="combobox">';
      @foreach ($students as $iter)
      html += "<option value='" + "{{ $iter->id }}'" + '>' + '{{ $iter->name }}' + ' ' +  '{{ $iter->surname }}' + '</option>>'
      @endforeach
      html +=  '</select></div>'
    @else
      html += '<div>' +
      "<input type=text name='participantName_" + rowId + "[]'"  + '>' +
      "<input type=text name='participantSurname_" + rowId + "[]'"  + '>'+
      "<input type=date name='participantDOB_" + rowId + "[]'"  + '>' +
      "<input type=text name='participantFiscalCode_" + rowId + "[]'"  + '>';
      html +=  '</div>'
    @endif
    html += '</td></tr>';
    $('#dyn_participants_' + rowId).append(html);
}

function remove_participant(rowId, isSchool){
        var rowCount = $('#dyn_participants_' + rowId + ' tr').length;
        console.info('count row :' + rowCount);
        if((isSchool == 'true' && rowCount > 2) || rowCount > 1){
            $('#dyn_participants_' + rowId + ' tr:last').remove();
        }
}

function showPopupRemoveItem(rowId, id, message){
$('#popup-removeCart_' + id).fadeIn("slow");
		      return false;
}

function showAddBallerino(rowId, id, message){
  $('#popup-ballerino #item_rowId').val(rowId);
  $('#popup-ballerino #item_id').val(id);
  $('#popup-ballerino').fadeIn("slow");
		      return false;
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
        rowTobeEliminated = $(this).attr('name');
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
              $(".section_head_carello").text(data.cartCount);
              console.info('total:' + data.total);
              $("#cartTotal").text(data.total);
              $('#' + rowTobeEliminated).closest("tr").remove();
              if(data.cartCount == 0 || data.cartCount == '0'){
                location.reload();
              }
                //alert(data.message);
            }
        });         
});


$(document).on('click', '#add_nuovo_ballerino', function(){
        /**chiamata ajax per eliminare item dal carello */
        name = $('#name').val();
        surname = $('#surname').val();
        birth_date = $('#birth_date').val();
        birth_place = $('#birth_place').val();
        fiscal_code = $('#fiscal_code').val();

        rowId = $('#popup-ballerino #item_id').val();
  
        
        if((name.trim()) && (surname.trim()) && (birth_date.trim()) && (birth_place.trim())  && (fiscal_code.trim())){
          $.ajaxSetup({
            headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
        });

        $.ajax({
            type:'POST',
            url: "{{route('postAddBallerino')}}",
            data:{name:name, surname:surname, birth_date:birth_date, birth_place:birth_place, fiscal_code:fiscal_code},
            success:function(data){
              if(data.status=='error'){
                alert(data.message);
              }else{
                html = '';
                participantName = "participants_"+rowId + "[]";
                html +=  '<tr><td>';
                html += '<div>' +
                  "<label class='form-control'>" + surname + "  " + name + "</label>" +
                  "<input type=hidden name='participants_" + rowId + "[]'" + " value='" + data.student_id + "'>";
                html +=  '</div>'
  
                html += '</td></tr>';
                $('#dyn_participants_' + rowId).append(html);
                $('#popup-ballerino').fadeOut("slow");
              }
              
            }
        });   
        }else{
          alert('tutti i campi sono obbligatori');
        }
         
});

</script>
@endsection

