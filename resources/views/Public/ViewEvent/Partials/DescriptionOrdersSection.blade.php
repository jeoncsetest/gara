<div class="breadcrumbs-wrap no-title">
   <div class="container">
     <ul class="breadcrumbs">
       <li>Home</a></li>
       <li>Gare</li>
       <li>Dettaglio Ordine</li>
     </ul>
   </div>
   </div>

 <div class="container">


 <div id="content" class="page-content-wrap">

<div class="content-element">
<div class="content-element3">

       <h5>Informazione Ordine</h5>
       <div class="content-element4">

         <div class="content-element4">
           <div class="table-type-2">
             <table>
               <tr>
                 <th>Numero Ordine</th>
                 <th>Nome</th>
                 <th>Cognome</th>
                 <th>Totale</th>
                 <th>Data Acquisto</th>


               </tr>

               @foreach($orders as $order)
              
               <tr>
                      <td>{{$order->order_reference}}</td>
                      <td>{{$order->first_name}}</td>
                      <td>{{$order->last_name}}</td>
                      <@if($order->cart_amount == 0)
                      <td>{{$order->amount}}</td>
                      @else
                      <td>{{$order->cart_amount}}</td>
                
                          @endif
                         
                         
                      <td>  {{$order->created_at}}</td>
                      @endforeach  
           </tr>
             </table>

           </div>
         </div>

       </div>
            </div>

   </div>
   </div>
</div>
