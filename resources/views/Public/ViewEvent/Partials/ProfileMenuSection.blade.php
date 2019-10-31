<div class="breadcrumbs-wrap no-title">
   <div class="container">
     <ul class="breadcrumbs">
       <li>Home</a></li>
       <li>Gare</li>
       <li>Profilo</li>
     </ul>
   </div>
   </div>

 <div class="container">

 <div id="content" class="page-content-wrap">

       <h5>I miei dati:</h5>

         @if(Session::has('school'))
       <div class="content-element4">
         <div class="table-type-2">
           <table>
             <tr>
               <th>Nome scuola</th>
               <th>Nome</th>
               <th>Cognome</th>
               <th>mail</th>
               <th>Telefono</th>
               <th>eps</th>
               <th>Indirizzo</th>

             </tr>
             <tr>
                    <td>{{$user->School->name}}</td>
                    <td>{{$user->first_name}}</td>
                    <td>{{$user->last_name}}</td>
                    <td>{{$user->school->email}}</td>
                    <td>{{$user->school->phone}}</td>
                    <td>{{$user->school->eps}}</td>
                    <td>{{$user->school->address}}</td>
         </tr>
           </table>

         </div>
       </div>
  @else
   <div class="content-element4">
     <div class="table-type-2">
       <table>
         <tr>
           <th>Nome</th>
           <th>Cognome</th>
           <th>mail</th>
           <th>Telefono</th>
         </tr>
         <tr>
                <td>{{$user->first_name}}</td>
                <td>{{$user->last_name}}</td>
                <td>{{$user->email}}</td>
                <td>{{$user->phone}}</td>
     </tr>
       </table>

     </div>
   </div>
  @endif
</div>
</div>
