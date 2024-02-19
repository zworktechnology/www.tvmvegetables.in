@extends('layout.backend.auth')

@section('content')


<div class="content">
      <button  onclick="printDiv('printableArea')"  class="btn-success btn-sm" ><i class="fa fa-print"></i> Print</button>
      <a href="{{ route('purchase.index') }}"><button  class="btn-danger btn-sm" style="color:white"> back</button> </a>

      <div  id="printableArea">

   <div class="card">
      <div class="card-body">

         <div style="background-color: #fff;">

            <div class="row py-2">
               <div class="col-lg-12  col-sm-12 col-12">
               <img src="{{ asset('assets/backend/img/spmheader.png') }}">
               </div>
            </div>
               <h4 class="py-1" style="font-size:15px;color: black; font-weight:800">{{ $supplier_upper }}</h4>
               <div class="row">
                  <div class="col-lg-10  col-sm-8 col-8">
                     <span style="font-size:11px" >Bill No.  &nbsp;<span style="font-weight:600"># {{ $PurchaseData->bill_no}}</span></span>
                  </div>
                  <div class="col-lg-2  col-sm-4 col-4">
                     <span style="font-size:11px" >Date: &nbsp;<span style="font-weight:600">{{ date('d-m-Y', strtotime($PurchaseData->date))}}</span></span>
                  </div>
               </div>

                  <table style="width: 100%;line-height: inherit;text-align: left;overflow: auto;margin:15px auto;">
                     <tr class="heading border" style="background:#eee;">
                        <td class=" border" style="padding: 3px;vertical-align: middle;font-weight: 800;color: black;font-size: 11px;">
                        Product Name
                        </td>
                        <td class=" border" style="padding: 3px;vertical-align: middle;font-weight: 800;color: black;font-size: 11px;">
                        Bag / Kg
                        </td>
                        <td class=" border" style="padding: 3px;vertical-align: middle;font-weight: 800;color: black;font-size: 11px;">
                        Count
                        </td>
                        <td class=" border" style="padding: 3px;vertical-align: middle;font-weight: 800;color: black;font-size: 11px;">
                        Price / Count
                        </td>
                        <td style="padding: 3px;vertical-align: middle;font-weight: 800;color: black;font-size: 11px;">
                        Amount
                        </td>
                     </tr>
                     @foreach ($PurchaseProducts as $index => $PurchaseProducts_array)
                           @if ($PurchaseProducts_array->purchase_id == $PurchaseData->id)
                     <tr class="details border" style="border-bottom:1px solid #E9ECEF ;">
                        <td class=" border" style="padding: 7px;vertical-align: top; vertical-align: inherit;vertical-align: inherit;font-size: 11px;color:#000;font-weight: 600;">
                        @foreach ($productlist as $products)
                                    @if ($products->id == $PurchaseProducts_array->productlist_id)
                                    {{ $products->name }}
                                    @endif
                                 @endforeach
                        </td>
                        <td class=" border" style="padding: 7px;vertical-align: top; vertical-align: inherit;vertical-align: inherit;font-size: 11px;color:#000;font-weight: 600;">
                        {{ $PurchaseProducts_array->bagorkg }}
                        </td>
                        <td class=" border" style="padding: 7px;vertical-align: top;vertical-align: inherit;vertical-align: inherit;font-size: 11px;color:#000;font-weight: 600;">
                        {{ $PurchaseProducts_array->count }}
                        </td>
                        <td class=" border" style="padding: 7px;vertical-align: top; vertical-align: inherit;vertical-align: inherit;font-size: 11px;color:#000;font-weight: 600;">
                        {{ $PurchaseProducts_array->price_per_kg }}
                        </td>
                        <td style="padding: 7px;vertical-align: top; vertical-align: inherit;vertical-align: inherit;font-size: 11px;color:#000;font-weight: 600;">
                        {{ $PurchaseProducts_array->total_price }}
                        </td>
                     </tr>
                     @endif
                        @endforeach
                        <tr class="details border" style="border-bottom:1px solid #E9ECEF ;">
                        <td  style="padding: 7px;vertical-align: top; vertical-align: inherit;vertical-align: inherit;font-size: 11px;color:#000;font-weight: 600;"></td>
                        <td style="padding: 7px;vertical-align: top; vertical-align: inherit;vertical-align: inherit;font-weight: 800;color: black;font-size: 11px;">COMMISSION</td>
                        <td class=" border" style="padding: 7px;vertical-align: top; vertical-align: inherit;vertical-align: inherit;font-size: 11px;color:#000;font-weight: 600;">{{$PurchaseData->commission_percent}} %</td>
                        <td style="padding: 7px;vertical-align: top; vertical-align: inherit;vertical-align: inherit;font-size: 11px;color:#000;font-weight: 600;">{{$PurchaseData->commission_amount}}</td>
                     </tr>
                     @foreach ($Purchaseextracosts as $index => $Purchaseextracosts_arr)
                     <tr class="details border" style="border-bottom:1px solid #E9ECEF ;">
                        <td style="padding: 7px;vertical-align: top; vertical-align: inherit;vertical-align: inherit;font-size: 11px;color:#000;font-weight: 600;"></td>
                        <td style="padding: 7px;vertical-align: top; vertical-align: inherit;vertical-align: inherit;font-weight: 800;color: black;font-size: 11px;">EXTRACOST</td>
                        <td  class=" border"style="padding: 7px;vertical-align: top; vertical-align: inherit;vertical-align: inherit;font-size: 11px;color:#000;font-weight: 600;">{{ $Purchaseextracosts_arr->extracost_note }}</td>
                        <td style="padding: 7px;vertical-align: top; vertical-align: inherit;vertical-align: inherit;font-size: 11px;color:#000;font-weight: 600;">{{ $Purchaseextracosts_arr->extracost }}</td>
                     </tr>
                     @endforeach
                     <tr class="details border" style="border-bottom:1px solid #E9ECEF ;">
                        <td style="padding: 7px;vertical-align: top; vertical-align: inherit;vertical-align: inherit;font-size: 11px;color:#000;font-weight: 600;"></td>
                        <td style="padding: 7px;vertical-align: top; vertical-align: inherit;vertical-align: inherit;font-size: 11px;color:#000;font-weight: 600;"></td>
                        <td class=" border" style="padding: 7px;vertical-align: top; vertical-align: inherit;vertical-align: inherit;font-weight: 800;color: black;font-size: 11px;">TOTAL</td>
                        <td class=" border" style="padding: 7px;vertical-align: top; vertical-align: inherit;vertical-align: inherit;font-size: 11px;color:#000;font-weight: 600;">{{$PurchaseData->tot_comm_extracost}}</td>
                        <td style="padding: 7px;vertical-align: top; vertical-align: inherit;vertical-align: inherit;font-size: 11px;color:#000;font-weight: 600;">{{$PurchaseData->total_amount}}</td>
                     </tr>
                     <tr class="details ">
                        <td style="padding: 7px;vertical-align: top; vertical-align: inherit;vertical-align: inherit;font-size: 11px;color:#000;font-weight: 600;"></td>
                        <td style="padding: 7px;vertical-align: top; vertical-align: inherit;vertical-align: inherit;font-size: 11px;color:#000;font-weight: 600;"></td>
                        <td style="padding: 7px;vertical-align: top; vertical-align: inherit;vertical-align: inherit;font-weight: 800;color: black;font-size: 11px;"></td>
                        <td style="padding: 7px;vertical-align: top; vertical-align: inherit;vertical-align: inherit;font-weight: 800;color: blue;font-size: 11px;">GROSS AMOUNT</td>
                        <td style="padding: 7px;vertical-align: top; vertical-align: inherit;vertical-align: inherit;font-size: 11px;color:blue;font-weight: 600;">{{ $PurchaseData->gross_amount}}</td>
                     </tr>
                     <tr class="details " >
                        <td style="padding: 7px;vertical-align: top; vertical-align: inherit;vertical-align: inherit;font-size: 11px;color:#000;font-weight: 600;"></td>
                        <td style="padding: 7px;vertical-align: top; vertical-align: inherit;vertical-align: inherit;font-size: 11px;color:#000;font-weight: 600;"></td>
                        <td style="padding: 7px;vertical-align: top; vertical-align: inherit;vertical-align: inherit;font-weight: 800;color: black;font-size: 11px;"></td>
                        <td style="padding: 7px;vertical-align: top; vertical-align: inherit;vertical-align: inherit;font-weight: 800;color: red;font-size: 11px;">OLD BALANCE</td>
                        <td style="padding: 7px;vertical-align: top; vertical-align: inherit;vertical-align: inherit;font-size: 11px;color:red;font-weight: 600;">{{ $PurchaseData->old_balance}}</td>
                     </tr>
                     <tr class="details " >
                        <td style="padding: 7px;vertical-align: top; vertical-align: inherit;vertical-align: inherit;font-size: 11px;color:#000;font-weight: 600;"></td>
                        <td style="padding: 7px;vertical-align: top; vertical-align: inherit;vertical-align: inherit;font-size: 11px;color:#000;font-weight: 600;"></td>
                        <td style="padding: 7px;vertical-align: top; vertical-align: inherit;vertical-align: inherit;font-weight: 800;color: black;font-size: 11px;"></td>
                        <td style="padding: 7px;vertical-align: top; vertical-align: inherit;vertical-align: inherit;font-weight: 800;color: blue;font-size: 11px;">GRAND TOTAL</td>
                        <td style="padding: 7px;vertical-align: top; vertical-align: inherit;vertical-align: inherit;font-size: 11px;color:blue;font-weight: 600;">{{ $PurchaseData->grand_total}}</td>
                     </tr>
                     <tr class="details ">
                        <td style="padding: 7px;vertical-align: top; vertical-align: inherit;vertical-align: inherit;font-size: 11px;color:#000;font-weight: 600;"></td>
                        <td style="padding: 7px;vertical-align: top; vertical-align: inherit;vertical-align: inherit;font-size: 11px;color:#000;font-weight: 600;"></td>
                        <td style="padding: 7px;vertical-align: top; vertical-align: inherit;vertical-align: inherit;font-weight: 800;color: black;font-size: 11px;"></td>
                        <td  style="padding: 7px;vertical-align: top; vertical-align: inherit;vertical-align: inherit;font-weight: 800;color: green;font-size: 11px;">PAID AMOUNT</td>
                        <td style="padding: 7px;vertical-align: top; vertical-align: inherit;vertical-align: inherit;font-size: 11px;color:green;font-weight: 600;">{{ $PurchaseData->paid_amount}}</td>
                     </tr>
                     <tr class="details " >
                        <td style="padding: 7px;vertical-align: top; vertical-align: inherit;vertical-align: inherit;font-size: 11px;color:#000;font-weight: 600;"></td>
                        <td style="padding: 7px;vertical-align: top; vertical-align: inherit;vertical-align: inherit;font-size: 11px;color:#000;font-weight: 600;"></td>
                        <td style="padding: 7px;vertical-align: top; vertical-align: inherit;vertical-align: inherit;font-weight: 800;color: black;font-size: 11px;"></td>
                        <td  style="padding: 7px;vertical-align: top; vertical-align: inherit;vertical-align: inherit;font-weight: 800;color: red;font-size: 11px;">NETT BALANCE</td>
                        <td style="padding: 7px;vertical-align: top; vertical-align: inherit;vertical-align: inherit;font-size: 11px;color:red;font-weight: 600;">{{ $PurchaseData->balance_amount}}</td>
                     </tr>
                  </table>



         </div>


      </div>
   </div>
</div>

</div>

@endsection
