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
               <th>eps</th>
               <th>Telefono</th>
               <th>Indirizzo</th>

             </tr>
             <tr>
                    <td> </td>
                    <td>  </td>
                    <td> </td>
                    <td>   </td>
                    <td>  </td>
                    <td>   </td>
                    <td>   </td>
         </tr>
           </table>

         </div>
       </div>
   @endif

   @if(Session::has('ticket'))
   <div class="content-element4">
     <div class="table-type-2">
       <table>
         <tr>
           <th>Nome scuola</th>
           <th>Nome</th>
           <th>Cognome</th>
           <th>mail</th>
           <th>eps</th>
           <th>Telefono</th>
           <th>Indirizzo</th>

         </tr>
         <tr>
                <td> </td>
                <td>  </td>
                <td> </td>
                <td>   </td>
                <td>  </td>
                <td>   </td>
                <td>   </td>
     </tr>
       </table>

     </div>
   </div>
  @endif

  @if(Session::has('student'))
  <div class="content-element4">
    <div class="table-type-2">
      <table>
        <tr>
          <th>Nome scuola</th>
          <th>Nome</th>
          <th>Cognome</th>
          <th>mail</th>
          <th>eps</th>
          <th>Telefono</th>
          <th>Indirizzo</th>

        </tr>
        <tr>
               <td> </td>
               <td>  </td>
               <td> </td>
               <td>   </td>
               <td>  </td>
               <td>   </td>
               <td>   </td>
    </tr>
      </table>

    </div>
  </div>
 @endif

   </div>
</div>
