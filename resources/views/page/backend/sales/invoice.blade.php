@extends('layout.backend.auth')

@section('content')

   <div class="content">
      <div class="page-header">
         <div class="page-title">
            <h4>Update Sales Invoice</h4>
         </div>
      </div>

      <div class="card">
         <div class="card-body">
         <form autocomplete="off" method="POST" action="{{ route('sales.invoice_update', ['unique_key' => $SalesData->unique_key]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="row">
               <div class="col-lg-3 col-sm-6 col-12">
                  <div class="form-group">
                     <label style="font-size:15px;padding-top: 5px;padding-bottom: 2px;">Bill No<span style="color: red;">*</span></label>
                     <input type="text" name="sales_billno"disabled placeholder="Bill No" id="sales_billno" value="{{ $SalesData->bill_no }}" style="background-color: #e9ecef;" >
                  </div>
               </div>
            </div>
            <div class="row">
               <div class="col-lg-3 col-sm-6 col-12">
                  <div class="form-group">
                     <label style="font-size:15px;padding-top: 5px;padding-bottom: 2px;">From Branch<span style="color: red;">*</span></label>
                     <select class="select sales_branch_id" name="sales_branch_id" id="sales_branch_id" disabled>
                        <option value="" disabled selected hiddden>Select Branch</option>
                           @foreach ($branch as $branches)
                              <option value="{{ $branches->id }}"@if ($branches->id === $SalesData->branch_id) selected='selected' @endif>{{ $branches->name }}</option>
                           @endforeach
                     </select>
                  </div>
               </div>
            
               <div class="col-lg-3 col-sm-6 col-12">
                  <div class="form-group">
                     <label style="font-size:15px;padding-top: 5px;padding-bottom: 2px;">To Customer<span style="color: red;">*</span> </label>
                     <select class="select sales_customerid" name="sales_customerid" id="sales_customerid" disabled>
                        <option value="" disabled selected hiddden>Select Customer</option>
                           @foreach ($customer as $customer_array)
                              <option value="{{ $customer_array->id }}"@if ($customer_array->id === $SalesData->customer_id) selected='selected' @endif>{{ $customer_array->name }}</option>
                           @endforeach
                     </select>
                  </div>
               </div>

               <div class="col-lg-3 col-sm-6 col-12">
                  <div class="form-group">
                     <label style="font-size:15px;padding-top: 5px;padding-bottom: 2px;">Date<span style="color: red;">*</span></label>
                     <input type="date" name="sales_date" placeholder="" value="{{ $SalesData->date }}" disabled>
                  </div>
               </div>
               

               <div class="col-lg-3 col-sm-6 col-12">
                  <div class="form-group">
                     <label style="font-size:15px;padding-top: 5px;padding-bottom: 2px;">Bank<span style="color: red;">*</span></label>
                     <select class="select form-control" name="sales_bank_id" id="sales_bank_id" required>
                        <option value="" disabled selected hiddden>Select Bank</option>
                        @foreach ($bank as $banks)
                           <option value="{{ $banks->id }}"@if ($banks->id === $SalesData->bank_id) selected='selected' @endif>{{ $banks->name }}</option>
                        @endforeach
                     </select>
                  </div>
               </div>


            </div>

            <br/>

            <div class="row">
               <div class="table-responsive col-12">
                  <table class="table">
                     <thead>
                        <tr>
                           <th style="font-size:15px; width:28%;">Product</th>
                           <th style="font-size:15px; width:12%;">Bag / Kg</th>
                           <th style="font-size:15px; width:12%;">Count </th>
                           <th style="font-size:15px; width:18%;">Price / Count</th>
                           <th style="font-size:15px; width:20%;">Amount</th>
                           
                        </tr>
                     </thead>
                     <tbody id="sales_productfields">
                     @foreach ($SalesProducts as $index => $Sales_Products)
                        <tr>
                           <td class="">
                              <input type="hidden"id="sales_detail_id"name="sales_detail_id[]" value="{{ $Sales_Products->id }}"/>
                              @foreach ($productlist as $products)
                                 @if ($products->id == $Sales_Products->productlist_id)
                                    <input type="text"class="form-control" name="product_name[]" value="{{ $products->name }}" readonly>
                                    <input type="hidden" id="sales_product_id" name="sales_product_id[]" value="{{ $Sales_Products->productlist_id }}" />
                                 @endif
                              @endforeach
                           </td>
                           <td><input type="text" class="form-control" id="sales_bagorkg" readonly name="sales_bagorkg[]" placeholder="Bag" value="{{ $Sales_Products->bagorkg }}" required /></td>
                           <td><input type="text" class="form-control sales_count" id="sales_count" readonly name="sales_count[]" placeholder="kgs" value="{{ $Sales_Products->count }}" required /></td>
                           <td><input type="text" class="form-control sales_priceperkg"  id="sales_priceperkg" name="sales_priceperkg[]" placeholder="Price Per Count"  required /></td>
                           <td class="text-end"><input type="text" class="form-control sales_total_price"  id="sales_total_price"  style="background-color: #e9ecef;" name="sales_total_price[]"  required /></td>
                           
                           </td>
                        </tr>
                        @endforeach
                     </tbody>
                     <tbody>
                        <tr>
                           <td></td>
                           <td></td>
                           <td></td>
                           <td style="font-size:15px;color: black;" class="text-end">Total</td>
                           <td><input type="text" class="form-control sales_total_amount" id="sales_total_amount" name="sales_total_amount" value="{{ $SalesData->total_amount }}" readonly style="background-color: #e9ecef;" /></td>
                           
                        </tr>
                        <tr>
                           <td colspan="3"><input type="text" class="form-control" id="sales_extracost_note" placeholder="Note" value="{{ $SalesData->note }}" name="sales_extracost_note" required/></td>
                           <td style="font-size:15px;color: black;" class="text-end">Extra Cost<span style="color: red;">*</span></td>
                           <td><input type="text" class="form-control sales_extracost" id="sales_extracost" placeholder="Extra Cost" name="sales_extracost" value="{{ $SalesData->extra_cost }}"/></td>
                        </tr>
                        <tr>
                           <td colspan="4" class="text-end" style="font-size:15px;color: black;">Gross Amount</td>
                           <td><input type="text" class="form-control sales_gross_amount" id="sales_gross_amount" placeholder="Gross Amount" value="{{ $SalesData->gross_amount }}" readonly style="background-color: #e9ecef;" name="sales_gross_amount"/></td>
                        </tr>
                        <tr>
                           <td colspan="4" class="text-end" style="font-size:15px;color: red;">Old Balance</td>
                           <td><input type="text" class="form-control sales_old_balance" id="sales_old_balance" placeholder="Old Balance" readonly value="0" style="background-color: #e9ecef;" name="sales_old_balance"/></td>
                        </tr>
                        <tr>
                           <td colspan="4" class="text-end" style="font-size:15px;color: green;">Grand Total</td>
                           <td><input type="text" class="form-control sales_grand_total" id="sales_grand_total" readonly placeholder="Grand Total" value="{{ $SalesData->grand_total }}" style="background-color: #e9ecef;" name="sales_grand_total"/></td>
                        </tr>
                        <tr>
                           <td colspan="4" class="text-end" style="font-size:15px;color: black;">Payable Amount<span style="color: red;">*</span></td>
                           <td><input type="text" class="form-control salespayable_amount" name="salespayable_amount" placeholder="Payable Amount" value="{{ $SalesData->paid_amount }}" id="salespayable_amount"></td>
                        </tr>
                        <tr>
                           <td colspan="4" class="text-end" style="font-size:15px;color: black;">Pending Amount</td>
                           <td><input type="text" class="form-control sales_pending_amount" name="sales_pending_amount" value="{{ $SalesData->balance_amount }}" readonly style="background-color: #e9ecef;" placeholder="Pending Amount" id="sales_pending_amount"></td>
                        </tr>
                     </tbody>
                  </table>
               </div>
            </div>

               <br/><br/>

            
            <div class="modal-footer">
               <input type="submit" class="btn btn-primary" name="submit" value="submit" />
               <a href="{{ route('sales.index') }}" class="btn btn-danger" value="">Cancel</a>
            </div>
         </form>


            




         </div>
      </div>
   </div>

@endsection
