@extends('layout.backend.auth')

@section('content')

   <div class="content">
      <div class="page-header">
         <div class="page-title">
            <h4>Add Sales</h4>
         </div>
      </div>

      <div class="card">
         <div class="card-body">
         <form autocomplete="off" method="POST" action="{{ route('sales.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="row">


                <div class="col-lg-6 col-sm-6 col-12">
                    <div class="form-group">
                       <label style="font-size:15px;padding-top: 5px;padding-bottom: 2px;">Date<span style="color: red;">*</span></label>
                       <input type="date" name="sales_date" placeholder="" value="{{ $today }}">
                    </div>
                 </div>
  
                 <div class="col-lg-6 col-sm-6 col-12">
                    <div class="form-group">
                       <label style="font-size:15px;padding-top: 5px;padding-bottom: 2px;">Time<span style="color: red;">*</span></label>
                       <input type="time" name="sales_time" placeholder="" value="{{ $timenow }}">
                    </div>
                 </div>

               <div class="col-lg-4 col-sm-6 col-12">
                  <div class="form-group">
                     <label style="font-size:15px;padding-top: 5px;padding-bottom: 2px;">Branch<span style="color: red;">*</span></label>
                     <select class="form-control js-example-basic-single select sales_branch_id" name="sales_branch_id" id="sales_branch_id">
                        <option value="" disabled selected hiddden>Select Branch</option>
                           @foreach ($branch as $branches)
                              <option value="{{ $branches->id }}">{{ $branches->shop_name }}</option>
                           @endforeach
                     </select>
                  </div>
               </div>
            
               <div class="col-lg-4 col-sm-6 col-12">
                  <div class="form-group">
                     <label style="font-size:15px;padding-top: 5px;padding-bottom: 2px;">Customer<span style="color: red;">*</span> </label>
                     <select class="form-control js-example-basic-single select sales_customerid" name="sales_customerid" id="sales_customerid">
                        <option value="" disabled selected hiddden>Select Customer</option>
                           @foreach ($customer as $customer_array)
                              <option value="{{ $customer_array->id }}">{{ $customer_array->name }}</option>
                           @endforeach
                     </select>
                  </div>
               </div>

               <div class="col-lg-3 col-sm-6 col-12">
                  <div class="form-group">
                     <label style="font-size:15px;padding-top: 5px;padding-bottom: 2px;">Bank<span style="color: red;">*</span></label>
                     <select class="select" name="sales_bank_id" id="sales_bank_id">
                        <option value="" disabled selected hiddden>Select Bank</option>
                        @foreach ($bank as $banks)
                           <option value="{{ $banks->id }}">{{ $banks->name }}</option>
                        @endforeach
                     </select>
                  </div>
               </div>

               <div class="col-lg-1 col-sm-1 col-12">
                <label style="font-size:15px;padding-top: 5px;padding-bottom: 2px;">Action</label>
                <button style="margin-top:10px; width: 35px;"class="addsalesproductfields py-1 text-white font-medium rounded-lg text-sm  text-center btn btn-primary"
                              type="button" id="" value="Add">+</button>
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
                           <th style="font-size:15px; width:20%;">Action</th>
                           
                        </tr>
                     </thead>
                     <tbody class="sales_productfields">
                        <tr>
                           <td class="">
                              <input type="hidden"id="sales_detail_id"name="sales_detail_id[]" />
                              <select class="form-control js-example-basic-single select sales_product_id" name="sales_product_id[]" id="sales_product_id1"required>
                                 <option value="" selected hidden class="text-muted">Select Product</option>
                                    
                              </select>
                           </td>
                           <td><select class=" form-control sales_bagorkg" name="sales_bagorkg[]" id="sales_bagorkg1"required>
                                 <option value="" selected hidden class="text-muted">Select</option>
                                     <option value="bag">Bag</option>
                                    <option value="kg">Kg</option>
                              </select>
                           </td>
                           <td><input type="text" class="form-control sales_count" id="sales_count" name="sales_count[]" placeholder="count" value="" required /></td>
                           <td><input type="text" class="form-control sales_priceperkg" id="sales_priceperkg" name="sales_priceperkg[]" placeholder="Price Per Count" value="" required /></td>
                           <td class="text-end"><input type="text" class="form-control sales_total_price" readonly id="sales_total_price"  style="background-color: #e9ecef;" name="sales_total_price[]" placeholder="" value="" required /></td>
                           <td>
                              <button style=" width: 35px;"class="addsalesproductfields py-1 text-white font-medium rounded-lg text-sm  text-center btn btn-primary"
                              type="button" id="" value="Add">+</button>
                            <button style="width: 35px;" class="py-1 text-white font-medium rounded-lg text-sm  text-center btn btn-danger remove-salestr" type="button" >-</button>
                           </td>
                        </tr>
                     </tbody>
                     <tbody>
                        <tr>
                           <td></td>
                           <td></td>
                           <td></td>
                           <td style="font-size:15px;color: black;" class="text-end">Total</td>
                           <td colspan="2"><input type="text" class="form-control sales_total_amount" id="sales_total_amount" name="sales_total_amount" readonly style="background-color: #e9ecef;" /></td>
                           
                        </tr>
                        <tr>
                           <td style="font-size:15px;color: black;" class="text-end">Extra Cost<span style="color: red;">*</span></td>
                           <td colspan="3"><input type="text" class="form-control" id="sales_extracost_note" placeholder="Note" name="sales_extracost_note" value="No"/></td>
                           <td colspan="2"><input type="text" class="form-control sales_extracost" id="sales_extracost" placeholder="Extra Cost" name="sales_extracost" value="0"/></td>
                          
                        </tr>
                        <tr>
                           <td colspan="4" class="text-end" style="font-size:15px;color: black;">Gross Amount</td>
                           <td colspan="2"><input type="text" class="form-control sales_gross_amount" id="sales_gross_amount" placeholder="Gross Amount" readonly style="background-color: #e9ecef;" name="sales_gross_amount"/></td>
                           
                        </tr>
                        <tr>
                           <td colspan="4" class="text-end" style="font-size:15px;color: red;">Old Balance</td>
                           <td colspan="2"><input type="text" class="form-control sales_old_balance" id="sales_old_balance" placeholder="Old Balance" readonly value="0" style="background-color: #e9ecef;" name="sales_old_balance"/></td>
                           
                        </tr>
                        <tr>
                           <td colspan="4" class="text-end" style="font-size:15px;color: green;">Grand Total</td>
                           <td colspan="2"><input type="text" class="form-control sales_grand_total" id="sales_grand_total" readonly placeholder="Grand Total" style="background-color: #e9ecef;" name="sales_grand_total"/></td>
                           
                        </tr>
                        <tr>
                           <td colspan="4" class="text-end" style="font-size:15px;color: black;">Payable Amount<span style="color: red;">*</span></td>
                           <td colspan="2"><input type="text" class="form-control salespayable_amount" name="salespayable_amount" placeholder="Payable Amount" id="salespayable_amount" required></td>
                           
                        </tr>
                        <tr>
                           <td colspan="4" class="text-end" style="font-size:15px;color: black;">Pending Amount</td>
                           <td colspan="2"><input type="text" class="form-control sales_pending_amount" name="sales_pending_amount" readonly style="background-color: #e9ecef;" placeholder="Pending Amount" id="sales_pending_amount"></td>
                        </tr>
                     </tbody>
                  </table>
               </div>
            </div>

               <br/><br/>

            
            <div class="modal-footer">
            <input type="submit" class="btn btn-primary" onclick="salessubmitForm(this);"/>
               <a href="{{ route('sales.index') }}" class="btn btn-danger" value="">Cancel</a>
            </div>
         </form>


            




         </div>
      </div>
   </div>

@endsection
