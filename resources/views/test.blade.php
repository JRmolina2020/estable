<!DOCTYPE html>
<html>
<head>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
 <title>Factura de venta</title>
</head>
<body>

 @foreach($company as $company)
 <center>
<strong>{{$company->name}}<br></strong>
<p><strong>Nit.</strong>{{$company->nit}}<br>
{{$company->representative}}<br>
{{$company->direction}}-{{$company->city}}<br>
{{$company->phone}}
</p>
 </center>
 @endforeach
<strong>Datos cliente</strong>
<table class="table">
  <thead class="">
    <tr>
      <th>Fac</th>
      <th>Fecha</th>
      <th>Nit</th>
      <th>Cliente</th>
      <th>Tel</th>
    </tr>
  </thead>
  <tbody>
    @foreach($facture2 as $facture2)
    <tr>
      <td>{{$facture2->id}}</td>
      <td>{{$facture2->date_facture}}</td>
      <td>{{$facture2->nit}}</td>
      <td>{{$facture2->fullname}}</td>
      <td>{{$facture2->phone}}</td>
    </tr>
    @endforeach
  </tbody>
 </table> 
 <br>
<table class="table">
  <thead class="">
    <tr>
      <th>Producto</th>
      <th>Cantidad</th>
      <th>Precio</th>
      <th>Subtotal</th>
      <th>Descuento</th>
      <th>Total</th>
    </tr>
  </thead>
  <tbody>
    @foreach($detail as $detail)
    <tr>
      <td>{{$detail->name}}</td>
      <td>{{$detail->quantity}}</td>
      <td>{{number_format($detail->price, 2, ',')}}</td>
      <td>{{number_format($detail->sub, 2, ',')}}</td>
      <td>{{number_format($detail->disc, 2, ',')}}</td>
      <td>{{number_format($detail->tot, 2, ',')}}</td>
    </tr>
    @endforeach
  </tbody>
 </table>
 <table class="table">
   <thead class="thead-dark">
     <tr>
       <th>Subtot</th>
       <th>Desc</th>
       <th>Total</th>
       <th>Pago</th>
     </tr>
   </thead>
   <tbody>
    @foreach($facture as $facture)
     <tr>
       <td>{{number_format($facture->sub, 2, ',')}}</td>
       <td>{{number_format($facture->disc, 2, ',')}}</td>
       <td>{{number_format($facture->tot, 2, ',')}}</td>
       @if($facture->type_sale =='1') 
       <td>Efectivo</td>
       @else    
       <td>{{$facture->type_sale}}</td>
       @endif
     </tr>
     @endforeach
   </tbody>
  </table>
</body>
</html> 