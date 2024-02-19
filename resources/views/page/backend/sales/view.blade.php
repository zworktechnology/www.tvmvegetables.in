<div class="modal-dialog modal-xl">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="salesviewLargeModalLabel{{ $Sales_datas['unique_key'] }}">Sales Details</h5>
            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        </div>
        <div class="modal-body">
            
                

        <div class="card">
         <div class="card-body">
            <div style="padding-bottom: 25px;">
            <h4>Bill No : #<span  class="sales_bill_no"></span></h4>
               
         </div>
         <div class="invoice-box table-height" style="max-width: 1600px;width:100%;overflow: auto;padding: 0;font-size: 14px;line-height: 24px;color: #555;">
            <div class="row">

               <div class="col-lg-4 col-sm-3 col-12">
                  <div class="card">
                     <div class="card-body">
                     <span  class=""><font style="vertical-align: inherit;margin-bottom:25px;vertical-align: inherit;font-size:16px;color:#3a3435;font-weight:700;line-height: 35px; ">BASIC INFO</font></span><br>
                     <span style="font-size:14px; color:black;">Bill No: </span>&nbsp;&nbsp;&nbsp; #<span  class="sales_bill_no" style="vertical-align: inherit;vertical-align: inherit;font-size: 14px;color:#000;font-weight: 400;"></span><br>
                     <span style="font-size:14px; color:black;">Date: </span>&nbsp;&nbsp;&nbsp;<span  class="sales_date" style="vertical-align: inherit;vertical-align: inherit;font-size: 14px;color:#000;font-weight: 400;"></span><br>
                     <span style="font-size:14px; color:black;">Time: </span>&nbsp;&nbsp;&nbsp;<span  class="sales_time" style="vertical-align: inherit;vertical-align: inherit;font-size: 14px;color:#000;font-weight: 400;"></span><br>
                     <span style="font-size:14px; color:black;">Bank Name: </span>&nbsp;&nbsp;&nbsp;<span  class="sales_bank_namedata" style="vertical-align: inherit;vertical-align: inherit;font-size: 14px;color:#000;font-weight: 400;"></span><br>
                     </div>
                  </div>
               </div>
               <div class="col-lg-4 col-sm-3 col-12">
                  <div class="card">
                     <div class="card-body">
                     <span  class=""><font style="vertical-align: inherit;margin-bottom:25px;vertical-align: inherit;font-size:16px;color:#3a3435;font-weight:700;line-height: 35px; ">CUSTOMER INFO</font></span><br>
                     <span style="font-size:14px; color:black;">Name: </span>&nbsp;&nbsp;&nbsp;<span  class="sales_customername" style="vertical-align: inherit;vertical-align: inherit;font-size: 14px;color:#000;font-weight: 400;"></span><br>
                     <span style="font-size:14px; color:black;">Contact No: </span>&nbsp;&nbsp;&nbsp;<span  class="sales_customercontact_number" style="vertical-align: inherit;vertical-align: inherit;font-size: 14px;color:#000;font-weight: 400;"></span><br>
                     <span style="font-size:14px; color:black;">Shop Name: </span>&nbsp;&nbsp;&nbsp;<span  class="sales_customershop_name" style="vertical-align: inherit;vertical-align: inherit;font-size: 14px;color:#000;font-weight: 400;"></span><br>
                     <span style="font-size:14px; color:black;">Address: </span>&nbsp;&nbsp;&nbsp;<span  class="sales_customershop_address" style="vertical-align: inherit;vertical-align: inherit;font-size: 14px;color:#000;font-weight: 400;"></span><br>
                     </div>
                  </div>
               </div>
               <div class="col-lg-4 col-sm-3 col-12">
                  <div class="card">
                     <div class="card-body">
                     <span  class=""><font style="vertical-align: inherit;margin-bottom:25px;vertical-align: inherit;font-size:16px;color:#3a3435;font-weight:700;line-height: 35px; ">BRANCH INFO</font></span><br>
                     <span style="font-size:14px; color:black;">Name: </span>&nbsp;&nbsp;&nbsp;<span  class="sales_branchname" style="vertical-align: inherit;vertical-align: inherit;font-size: 14px;color:#000;font-weight: 400;"></span><br>
                     <span style="font-size:14px; color:black;">Contact No: </span>&nbsp;&nbsp;&nbsp;<span  class="salesbranch_contact_number" style="vertical-align: inherit;vertical-align: inherit;font-size: 14px;color:#000;font-weight: 400;"></span><br>
                     <span style="font-size:14px; color:black;">Shop Name: </span>&nbsp;&nbsp;&nbsp;<span  class="salesbranch_shop_name" style="vertical-align: inherit;vertical-align: inherit;font-size: 14px;color:#000;font-weight: 400;"></span><br>
                     <span style="font-size:14px; color:black;">Address: </span>&nbsp;&nbsp;&nbsp;<span  class="salesbranch_address" style="vertical-align: inherit;vertical-align: inherit;font-size: 14px;color:#000;font-weight: 400;"></span><br>
                     </div>
                  </div>
               </div>




               
               
                  
            
            </div> 


            <div class="row">

               
                        <div class="col-lg-3 col-sm-3 col-12 border">
                           <span class="" style="vertical-align: inherit;vertical-align: inherit;font-size: 14px;color:#000;font-weight: 400;line-height: 35px; ">Product</span>
                        </div>
                        <div class="col-lg-2 col-sm-3 col-12 border">
                           <span class="" style="vertical-align: inherit;vertical-align: inherit;font-size: 14px;color:#000;font-weight: 400;line-height: 35px; ">Bag / Kg</span>
                        </div>
                        <div class="col-lg-2 col-sm-3 col-12 border">
                           <span class="" style="vertical-align: inherit;vertical-align: inherit;font-size: 14px;color:#000;font-weight: 400;line-height: 35px; ">Count</span>
                        </div>
                        <div class="col-lg-2 col-sm-3 col-12 border">
                           <span class="" style="vertical-align: inherit;vertical-align: inherit;font-size: 14px;color:#000;font-weight: 400;line-height: 35px; ">Price / Count</span>
                        </div>
                        <div class="col-lg-3 col-sm-3 col-12 border">
                           <span class="" style="vertical-align: inherit;vertical-align: inherit;font-size: 14px;color:#000;font-weight: 400;line-height: 35px; ">Amount</span>
                        </div>
            </div>
            <div class="row ">
                  @foreach ($Sales_datas['sales_terms'] as $index => $sales_terms)
                     @if ($sales_terms['sales_id'] == $Sales_datas['id'])
                        <div class="col-lg-3 col-sm-3 col-12 border">
                           <span class=""style="vertical-align: inherit;vertical-align: inherit;font-size: 14px;color:#000;font-weight: 400;line-height: 35px; ">{{ $sales_terms['product_name'] }}</span>
                        </div>
                        <div class="col-lg-2 col-sm-3 col-12 border">
                           <span class=""style="vertical-align: inherit;vertical-align: inherit;font-size: 14px;color:#000;font-weight: 400;line-height: 35px; ">{{ $sales_terms['bag'] }}</span>
                        </div>
                        <div class="col-lg-2 col-sm-3 col-12 border">
                           <span class=""style="vertical-align: inherit;vertical-align: inherit;font-size: 14px;color:#000;font-weight: 400;line-height: 35px; ">{{ $sales_terms['kgs'] }}</span>
                        </div>
                        <div class="col-lg-2 col-sm-3 col-12 border">
                           <span class=""style="vertical-align: inherit;vertical-align: inherit;font-size: 14px;color:#000;font-weight: 400;line-height: 35px; ">{{ $sales_terms['price_per_kg'] }}</span>
                        </div>
                        <div class="col-lg-3 col-sm-3 col-12 border">
                           <span class=""style="vertical-align: inherit;vertical-align: inherit;font-size: 14px;color:#000;font-weight: 400;line-height: 35px; ">{{ $sales_terms['total_price'] }}</span>
                        </div>
                     @endif
                  @endforeach
            </div>
<br/>

         </div>

         <div class="row">
            <div class="col-lg-6 ">
               <div class="total-order w-100 max-widthauto mb-4">
                  <ul>
                     <li>
                        <h4>Total</h4>
                        <h5 class="">₹ <span  class="sales_total_amount"></span></h5>
                     </li>
                     <li>
                        <h4>Extra Cost </h4>
                        <h5>₹ <span  class="sales_extra_cost"></span></h5>
                     </li>
                     <li>
                        <h4>Old Balance</h4>
                        <h5>₹ <span  class="sales_old_balance"></span></h5>
                     </li>
                  </ul>
               </div>
            </div>
            <div class="col-lg-6 ">
               <div class="total-order w-100 max-widthauto mb-4">
                  <ul>
                     <li>
                        <h4>Grand Total</h4>
                        <h5>₹ <span  class="sales_grand_total"></span></h5>
                     </li>
                     <li class="total">
                        <h4>Paid Amount</h4>
                        <h5 style="color:green">₹ <span  class="sales_paid_amount"></span></h5>
                     </li>
                     <li class="total">
                        <h4>Balance Amount</h4>
                        <h5 style="color:red">₹ <span  class="sales_balance_amount"></span></h5>
                     </li>
                  </ul>
               </div>
               </div>
            </div>
         </div>
   </div>


        </div>
    </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->
