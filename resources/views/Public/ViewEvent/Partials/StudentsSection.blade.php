<div class="breadcrumbs-wrap">
  <div class="container">
        <h1 class="page-title">I miei ballerini</h1>
  </div>
<span> &nbsp; &nbsp;</span>

<div class="content" class ="page-content-wrap">
  <span> &nbsp; </span>
<div class="container">
  <div class="form-group text-center">
    <a class="btn btn-lg btn-primary pull-right" href = "{{route('showAddStudent')}}">aggiungi ballerino</a>
  </div>
</div>
<br>
<!--
	<h1>
    Carello <span class='section_head_carello' id='section_head_carello'>{{Cart::count()}}</span>

	</h1>-->
   <div class="content-element">
  <div class="row" id="div_event_dance_cart">
	<table class="table-type-1" id="student_table">
    <thead>
      <tr>
        <th scope="col"> </th>
        <th scope="col">@lang("User.first_name")</th>
        <th scope="col">@lang("User.last_name")</th>
        <th scope="col">@lang("User.phone")</th>
        <th scope="col">@lang("User.birth_date")</th>
        <th scope="col">@lang("User.fiscal_code")</th>
        <th scope="col">@lang("User.email")</th>
      </tr>
    </thead>
    <tbody>
	 @foreach($students as $student)


    <tr id="{{$student->id}}">
        <th scope="row">1</th>
    <td>
			<label class="form-control" id="description_{{$student->id}}">{{$student->name}}</label>
		</td>
        <td>  <label class="form-control" id="typedance"><i>{{$student->surname}}</i></label></td>
        <td>  <label class="form-control" id="typedance">{{$student->phone}}</label></td>
        <td>  <label class="form-control" id="typedance">{{$student->birth_date}}</label></td>
        <td>  <label class="form-control" id="typedance">{{$student->fiscal_code}}</label></td>
        <td>  <label class="form-control" id="typedance">{{$student->email}}</label></td>
         </tr>
        {!! Form::hidden('is_embedded', $is_embedded) !!}
                                @endforeach
    </tbody>
  </table>
  <div>

  </div>
</div>
</div>
<span> &nbsp; &nbsp;</span>
</div>
