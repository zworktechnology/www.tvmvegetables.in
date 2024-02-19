<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
<meta name="description" content="Zwork Technology - POS System - Vegtable Shop">
<meta name="author" content="Zwork Technology">

<title>Zwork Technology - POS - Shop Billing - Custom Software</title>
<link rel="stylesheet" href="{{ asset('assets/backend/css/bootstrap.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/backend/css/style.css') }}">


<div class="content">

   <div class="card" style="text-transform: uppercase;">
      <div class="card-body">
         <div style="background-color: #fff;">

            <div class="row py-2">
               <div class="col-lg-12  col-sm-12 col-12">
                  <img src="{{ asset('assets/backend/img/spmheader.png') }}">
               </div>
            </div>
            <h6 class="py-1" style="font-size:15px;color: black; font-weight:500">Customer : {{ $customer_upper }}</h6>
            <div class="row">
               <div class="col-lg-10  col-sm-8 col-8">
               <span style="font-size:11px" >Bill No.  &nbsp;<span style="font-weight:600"># {{ $SalesData->bill_no}}</span></span>
               </div>
               <div class="col-lg-2  col-sm-4 col-4">
               <span style="font-size:11px" >Date: &nbsp;<span style="font-weight:600">{{ date('d-m-Y', strtotime($SalesData->date))}}</span></span>
               </div>
            </div>

                  <table style="width: 100%;line-height: inherit;text-align: left;overflow: auto;margin:15px auto;">
                     <tr class="heading " style="background:#eee; border: 1px solid #E9ECEF;">
                        <td style="padding: 3px;vertical-align: middle;font-weight: 600;color: black;font-size: 11px;border: 1px solid #E9ECEF;">
                        Product Name
                        </td>
                        <td style="padding: 3px;vertical-align: middle;font-weight: 600;color: black;font-size: 11px;border: 1px solid #E9ECEF;">
                        Bag / Kg
                        </td>
                        <td style="padding: 3px;vertical-align: middle;font-weight: 600;color: black;font-size: 11px;border: 1px solid #E9ECEF;">
                        Count
                        </td>
                        <td style="padding: 3px;vertical-align: middle;font-weight: 600;color: black;font-size: 11px;border: 1px solid #E9ECEF;">
                        Price / Count
                        </td>
                        <td style="padding: 3px;vertical-align: middle;font-weight: 600;color: black;font-size: 11px;border: 1px solid #E9ECEF;">
                        Amount
                        </td>
                     </tr>
                     @foreach ($SalesProduct_darta as $index => $SalesProduct_darta_arr)
                           @if ($SalesProduct_darta_arr->sales_id == $SalesData->id)
                     <tr class="details" style="border-bottom:1px solid #E9ECEF;">
                        <td style="padding: 7px;vertical-align: top; vertical-align: inherit;vertical-align: inherit;font-size: 11px;color:#000;font-weight: 600;border: 1px solid #E9ECEF;">
                        @foreach ($productlist as $products)
                                    @if ($products->id == $SalesProduct_darta_arr->productlist_id)
                                    {{ $products->name }}
                                    @endif
                                 @endforeach
                        </td>
                        <td style="padding: 7px;vertical-align: top; vertical-align: inherit;vertical-align: inherit;font-size: 11px;color:#000;font-weight: 600;border: 1px solid #E9ECEF;">
                        {{ $SalesProduct_darta_arr->bagorkg }}
                        </td>
                        <td style="padding: 7px;vertical-align: top;vertical-align: inherit;vertical-align: inherit;font-size: 11px;color:#000;font-weight: 600;border: 1px solid #E9ECEF;">
                        {{ $SalesProduct_darta_arr->count }}
                        </td>
                        <td style="padding: 7px;vertical-align: top; vertical-align: inherit;vertical-align: inherit;font-size: 11px;color:#000;font-weight: 600;border: 1px solid #E9ECEF;">
                        {{ $SalesProduct_darta_arr->price_per_kg }}
                        </td>
                        <td style="padding: 7px;vertical-align: top; vertical-align: inherit;vertical-align: inherit;font-size: 11px;color:#000;font-weight: 600;border: 1px solid #E9ECEF;">
                        {{ $SalesProduct_darta_arr->total_price }}
                        </td>
                     </tr>
                     @endif
                        @endforeach
                  </table>



               @if ($SalesData->extra_cost)
                  <table style="width: 100%;line-height: inherit;text-align: left;overflow: auto;margin:15px auto;">
                     <tr class="heading " style="background:#eee;">
                        <td style="padding: 3px;vertical-align: middle;font-weight: 800;color: black;font-size: 11px;border: 1px solid #E9ECEF; ">ExtraCost Note</td>
                        <td style="padding: 3px;vertical-align: middle;font-weight: 800;color: black;font-size: 11px;border: 1px solid #E9ECEF; ">Cost</td>
                     </tr>
                     <tr class="details" style="border-bottom:1px solid #E9ECEF ;">
                        <td style="padding: 7px;vertical-align: top; vertical-align: inherit;vertical-align: inherit;font-size: 11px;color:#000;font-weight: 600;border: 1px solid #E9ECEF;">{{ $SalesData->note }}</td>
                        <td style="padding: 7px;vertical-align: top; vertical-align: inherit;vertical-align: inherit;font-size: 11px;color:#000;font-weight: 600;border: 1px solid #E9ECEF;">{{ $SalesData->extra_cost }}</td>
                     </tr>
                  </table>
               @endif


                  <div class="row">
                        <div class="col-lg-7  col-sm-5 col-3"></div>
                        <div class="col-lg-5  col-sm-7 col-9">
                           <div class="total-order w-100 max-widthauto">
                              <ul>
                                 <li>
                                    <h4 style="font-size: 11px;color:blue;font-weight: 600; padding: 2px;">Extra Charge</h4>
                                    <h5 style="font-size: 11px;color:blue;font-weight: 600; padding: 2px;">₹ <span  class="">{{ $SalesData->extra_cost}}</span></h5>
                                 </li>
                                 <li>
                                    <h4 style="font-size: 11px;color:green;font-weight: 600; padding: 2px;">Gross Amount</h4>
                                    <h5 style="font-size: 11px;color:green;font-weight: 600; padding: 2px;">₹ <span  class="">{{ $SalesData->gross_amount}}</span></h5>
                                 </li>
                                 <li class="">
                                    <h4 style="font-size: 11px;color:blue;font-weight: 600; padding: 2px;">Old Balance</h4>
                                    <h5 style="font-size: 11px;color:blue;font-weight: 600; padding: 2px;">₹ <span  class="">{{ $SalesData->old_balance}}</span></h5>
                                 </li>
                                 <li class="">
                                    <h4 style="font-size: 11px;color:red;font-weight: 600; padding: 2px;">Grand Total</h4>
                                    <h5 style="font-size: 11px;color:red;font-weight: 600; padding: 2px;">₹ <span  class="">{{ $SalesData->grand_total}}</span></h5>
                                 </li>
                                 <li>
                                    <h4 style="font-size: 11px;color:blue;font-weight: 600; padding: 2px;">Paid Amount</h4>
                                    <h5 style="font-size: 11px;color:blue;font-weight: 600; padding: 2px;">₹ <span  class="">{{ $SalesData->paid_amount}}</span></h5>
                                 </li>
                                 <li class="">
                                    <h4 style="font-size: 11px;color:red;font-weight: 600; padding: 2px;">Nett Balance</h4>
                                    <h5 style="font-size: 11px;color:red;font-weight: 600; padding: 2px;">₹ <span  class="">{{ $SalesData->balance_amount}}</span></h5>
                                 </li>
                              </ul>
                           </div>
                        </div>
                  </div>


         </div>


      </div>
   </div>
</div>
<script>
        //setTimeout(window.close, 7000);
        window.onload=function(){self.print();}
        window.onafterprint = function() {
            history.go(-1);
        };
    </script>
