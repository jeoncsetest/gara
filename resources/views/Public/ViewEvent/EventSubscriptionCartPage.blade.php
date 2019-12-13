
@extends('Public.ViewEvent.Layouts.master')
@section('title')
Carrello
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
  $(document).ready(function(){
  $(document).ajaxStart(function(){
    $("#wait").css("display", "block");
  });
  $(document).ajaxComplete(function(){
    $("#wait").css("display", "none");
  });
});
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

function uploadMp3(rowId){
  /*alert('chiama mp3 upload :' + rowId + $('#formUploadMp3'+rowId).attr('action'));*/
      var form = $('#formUploadMp3'+rowId)[0];
        var formData = new FormData(form);
        $.ajaxSetup({
            headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
        });
        $.ajax({
            url: $('#formUploadMp3'+rowId).attr('action'),
            type: 'post',
            processData: false,
            contentType: false,
            data: formData,
           /* async: false,*/
            success: function(data) {
              if (data.status == 'success'){
                /*alert('success : ' + data.itemRowId);*/
                $('#btn_mp3_file_'+rowId).remove();
                $('#mp3_file_'+rowId).remove();
                html = "<button type='button' id='btnRemoveMp3" + rowId + "' onclick=removeMp3('" + rowId  + "') class='btn btn-danger' ><i class='fas fa-times-circle'></i></button>";    
                $('#formUploadMp3' + rowId).append(html);
              }else{
                alert('error : ' + data.message);
              } 
            },
            error: function() {
                alert('There has been an error, please alert us immediately');
            }
        });
}

function removeMp3(rowId){
  /*alert('chiama mp3 upload rowId:' + rowId );*/
        $.ajaxSetup({
            headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
        });
        event.preventDefault();
        $.ajax({
            url: '{{route('postRemoveMp3', ['event_id' => $event->id])}}',
            type: 'post',
            data:{item_row_id:rowId},
            success: function(data) {
              if (data.status == 'success'){
                /*alert('success : ' + data.itemRowId);*/
                $('#btnRemoveMp3'+rowId).remove();
               
                html =  "<input type='file'" + " name='mp3_file_"+ rowId  +"' id='mp3_file_"+ rowId +"'>";
                html += "<button type='button' disabled='disabled' id='btn_mp3_file_" + rowId + "' onclick=uploadMp3('" + rowId  + "') class='btn btn-danger btnUploadMp3' ><i class='fas fa-file-upload btnUploadMp3'></i></button>";    
                $('#formUploadMp3' + rowId).append(html);
              }else{
                alert('error : ' + data.message);
              } 
            },
            error: function() {
                alert('There has been an error, please alert us immediately');
            }
        });
}

function addBallerino(rowIdDelCarello, rowId ){
  
  idBalerino = $( "#participants_" + rowId + " option:selected" ).val();
  /*alert(idBalerino);*/
  addBallerinoAlCarello(rowIdDelCarello, rowId , idBalerino);
}

function addBallerinoAlCarello(rowIdDelCarello, rowId , id_ballerino){
  /*alert('addBallerinoAlCarello rowId:' + rowIdDelCarello );*/
        $.ajaxSetup({
            headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
        });
        event.preventDefault();
        $.ajax({
            url: "{{route('postaddBallerinoAlCarello', ['event_id' => $event->id])}}",
            type: 'post',
            data:{item_row_id:rowIdDelCarello, idBallerino:id_ballerino},
            success: function(data) {
              if (data.status == 'success'){
                /*alert('success : ' + data.studentId);*/
                html = "<tr id= " + 'ballerino_' +  rowIdDelCarello + '_' + data.studentId +"><td>";
                html += "<input type='hidden' name='participants_"  + rowId + '[]'  + 'value=' + data.studentId + '>';
                html += "<label class='form-control' id='description'>" + data.studentName + ' ' + data.studentSurname  + '</label>';
                html += "</td><td><button type='button' id='btnRemoveBallerinoDalCarello" + rowIdDelCarello + "' onclick=removeBallerinoDalCarello('" + rowIdDelCarello +   "'," + data.studentId + ") class='btn btn-danger' ><i class='fas fa-times-circle'></i></button>";    
                /*html += "</td><td><button type='button' id='btnRemoveBallerinoDalCarello" + rowIdDelCarello +  "' onclick=removeBallerinoDalCarello(" . $row->rowId .   "'," .$participant->{'id'}. ") class='btn btn-danger' ><i class='fas fa-times-circle'></i></button>";    */
                html += "</td></tr>";
                $('#dyn_participants_' + rowId).prepend(html);
              }else{
                alert('error : ' + data.message);
              } 
            },
            error: function() {
                alert('There has been an error, please alert us immediately');
            }
        });
}


function removeBallerinoDalCarello(rowIdDelCarello , id_ballerino){
  /*alert('addBallerinoAlCarello rowId:' + rowIdDelCarello );*/
        $.ajaxSetup({
            headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
        });
        event.preventDefault();
        $.ajax({
            url: "{{route('postRemoveBallerinoDalCarello', ['event_id' => $event->id])}}",
            type: 'post',
            data:{item_row_id:rowIdDelCarello, idBallerino:id_ballerino},
            success: function(data) {
              if (data.status == 'success'){
                $('#ballerino_' +  rowIdDelCarello + '_' + id_ballerino).remove();
              }else{
                alert('error : ' + data.message);
              } 
            },
            error: function() {
                alert('There has been an error, please alert us immediately');
            }
        });
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



$(document).on('change', 'input:file', function(){
  if ($(this).val()) {

                    $('#btn_mp3_file_' + $(this).closest("tr").attr('id')).attr('disabled',false);
                    // or, as has been pointed out elsewhere:
                    // $('input:submit').removeAttr('disabled'); 
                } 
});
/*
$(document).ready(
    function(){
        $('input:file').change(
            function(){
                if ($(this).val()) {
                  alert('#btn_mp3_file_' + $(this).closest("tr").attr('id'));
                    $('#btn_mp3_file_' + $(this).closest("tr").attr('id')).attr('disabled',false);
                    // or, as has been pointed out elsewhere:
                    // $('input:submit').removeAttr('disabled'); 
                } 
            }
            );
    });*/

$(document).on('click', '#add_nuovo_ballerino', function(){
        /**chiamata ajax per eliminare item dal carello */
        name = $('#name').val();
        surname = $('#surname').val();
        birth_date = $('#birth_date').val();
        birth_place = $('#birth_place').val();
        fiscal_code = $('#fiscal_code').val();

        rowId = $('#popup-ballerino #item_id').val();
        rowIdDelCarello = $('#popup-ballerino #item_rowId').val();
  
        
        if((name.trim()) && (surname.trim()) && (birth_date.trim()) && (birth_place.trim())  && (fiscal_code.trim())){
          $.ajaxSetup({
            headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
        });

        $.ajax({
            type:'POST',
            url: "{{route('postAddBallerino')}}",
            data:{name:name, surname:surname, birth_date:birth_date, birth_place:birth_place, fiscal_code:fiscal_code, item_rowId : rowIdDelCarello},
            success:function(data){
              if(data.status=='error'){
                if(data.studentExists == 'true'){
                  var r = confirm(data.message);
                  if (r == true) {
                    addBallerinoAlCarello(rowIdDelCarello, rowId, data.studentId);
                    $('#popup-ballerino').fadeOut("slow");
                  }
                }else{
                  alert('Sì è verificato un errore');
                }
              }else{
                addBallerinoAlCarello(rowIdDelCarello, rowId, data.studentId);
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

