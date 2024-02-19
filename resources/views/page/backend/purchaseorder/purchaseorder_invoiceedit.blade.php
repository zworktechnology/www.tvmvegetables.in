@extends('layout.backend.auth')

@section('content')

   <div class="content">
      <div class="page-header">
         <div class="page-title">
            <h4>Update Purchase</h4>
         </div>
      </div>

      <div class="card">
         <div class="card-body">
         <form autocomplete="off" method="POST" action="{{ route('purchaseorder.purchaseorder_invoiceeditupdate', ['unique_key' => $PurchaseData->unique_key]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="row">
                <div class="col-lg-4 col-sm-4 col-12">
                    <div class="form-group">
                       <label style="font-size:15px;padding-top: 5px;padding-bottom: 2px;">Date<span style="color: red;">*</span></label>
                       <input type="date" name="date" placeholder="" readonly value="{{ $PurchaseData->date }}">
                    </div>
                 </div>
                 <div class="col-lg-4 col-sm-4 col-12">
                    <div class="form-group">
                       <label style="font-size:15px;padding-top: 5px;padding-bottom: 2px;">Time<span style="color: red;">*</span></label>
                       <input type="time" name="time" placeholder="" readonly value="{{ $PurchaseData->time }}">
                    </div>
                 </div>
               <div class="col-lg-4 col-sm-4 col-12">
                  <div class="form-group">
                     <label style="font-size:15px;padding-top: 5px;padding-bottom: 2px;">Supplier<span style="color: red;">*</span> </label>
                     <select class="form-control js-example-basic-single select" name="supplier_id" id="supplier_id" disabled>
                        <option value="" disabled selected hiddden>Select Supplier</option>
                           @foreach ($supplier as $suppliers)
                              <option value="{{ $suppliers->id }}"@if ($suppliers->id === $PurchaseData->supplier_id) selected='selected' @endif>{{ $suppliers->name }}</option>
                           @endforeach
                     </select>
                  </div>
               </div>

               <div class="col-lg-3 col-sm-3 col-12" hidden>
                  <div class="form-group">
                     <label style="font-size:15px;padding-top: 5px;padding-bottom: 2px;">Branch<span style="color: red;">*</span></label>
                     <select class="form-control js-example-basic-single select" name="branch_id" id="branch_id" disabled>
                        <option value="" disabled selected hiddden>Select Branch</option>
                           @foreach ($branch as $branches)
                              <option value="{{ $branches->id }}"@if ($branches->id === $PurchaseData->branch_id) selected='selected' @endif>{{ $branches->shop_name }}</option>
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
                           <th style="font-size:15px; width:20%;">Product</th>
                           <th style="font-size:15px; width:10%;">Bag / Kg</th>
                           <th style="font-size:15px; width:10%;">Count </th>
                           <th style="font-size:15px; width:10%;">Note </th>
                           <th style="font-size:15px; width:23%;">Price / Count</th>
                           <th style="font-size:15px; width:15%;"></th>

                        </tr>
                     </thead>
                     <tbody id="product_fields">
                     @foreach ($PurchaseProducts as $index => $Purchase_Products)
                        <tr>
                           <td class="">
                              <input type="hidden"id="purchase_detail_id"name="purchase_detail_id[]" value="{{ $Purchase_Products->id }}"/>
                              @foreach ($productlist as $products)
                                 @if ($products->id == $Purchase_Products->productlist_id)
                                    <input type="text"class="form-control" name="product_name[]" value="{{ $products->name }}" readonly>
                                    <input type="hidden" id="product_id" name="product_id[]" value="{{ $Purchase_Products->productlist_id }}" />
                                 @endif
                              @endforeach
                           </td>
                           <td><input type="text" class="form-control" id="bagorkg" readonly name="bagorkg[]" placeholder="bagorkg" value="{{ $Purchase_Products->bagorkg }}"  /></td>
                           <td><input type="text" class="form-control count" id="count"  name="count[]" placeholder="count" value="{{ $Purchase_Products->count }}"  /></td>
                           <td><input type="text" class="form-control count" id="count"  name="count[]" placeholder="count" value="{{ $Purchase_Products->note }}"  /></td>
                           <td><input type="text" class="form-control price_per_kg" id="price_per_kg"
                                                    name="price_per_kg[]" placeholder="Price Per count"  value="{{ $Purchase_Products->price_per_kg }}" /></td>
                                            <td></td>
                                            <td class="text-end"><input type="text" class="form-control total_price" readonly
                                                    id="total_price" style="background-color: #e9ecef;"
                                                    name="total_price[]" placeholder="" value="{{ $Purchase_Products->total_price }}"/></td>

                                            </td>
                        </tr>
                        @endforeach
                     </tbody>
                     <tbody><tr><td></td></tr></tbody>

                                <tbody>
                                    <tr>
                                        <td colspan="4">


                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input commission_ornet" type="radio" {{ $PurchaseData->commission_ornet == 'commission' ? 'checked' :''}}
                                                name="commission_ornet" id="commission" value="commission">
                                                <label class="form-check-label" for="commission">
                                                Commission
                                                </label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input commission_ornet" type="radio" {{ $PurchaseData->commission_ornet == 'netprice' ? 'checked' :''}}
                                                name="commission_ornet" id="netprice" value="netprice">
                                                <label class="form-check-label" for="netprice">
                                                Net Price
                                                </label>
                                            </div>
                                        </td>
                                        <td colspan="1"><input type="text"  value="{{ $PurchaseData->commission_percent }}" class="form-control commission_percent" name="commission_percent" id="commission_percent"/></td>
                                        <td colspan="1"><input type="text" class="form-control commission_amount" readonly   name="commission_amount" id="commission_amount" value="{{ $PurchaseData->commission_amount }}"/></td>

                                    </tr>
                                </tbody>
                                <tbody id="extracost_tr">
                                @foreach ($PurchaseExtracosts as $index => $Purchase_Extracosts)
                                    <tr>
                                        <td style="font-size:15px; color: black;" class="text-end">Extra Cost<span
                                            style="color: red;">*</span></td>
                                        <td colspan="4">
                                            <input type="hidden" name="purchase_extracost_id"/>
                                            <select class=" form-control bagorkg" name="extracost_note[]" id="extracost_note" required>
                                                <option value="" selected hidden class="text-muted">Select</option>
                                                <option value="Hire"{{ $Purchase_Extracosts->extracost_note == 'Hire' ? 'selected' : '' }}>Hire</option>
                                                <option value="Wage"{{ $Purchase_Extracosts->extracost_note == 'Wage' ? 'selected' : '' }}>Wage</option>
                                                <option value="Gate"{{ $Purchase_Extracosts->extracost_note == 'Gate' ? 'selected' : '' }}>Gate</option>
                                                <option value="Advance"{{ $Purchase_Extracosts->extracost_note == 'Advance' ? 'selected' : '' }}>Advance</option>
                                            </select>
                                        </td>

                                        <td colspan="1"><input type="text" class="form-control extracost" id="extracost"
                                                placeholder="Extra Cost"  name="extracost[]" readonly
                                                value="{{ $Purchase_Extracosts->extracost }}" /></td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tbody>
                                    <tr>
                                        <td colspan="5" class="text-end" style="font-size:15px;color: black;">Total</td>
                                        <td colspan="1" ><input type="hidden" class="form-control total_extracost" value="" name="total_extracost" id="total_extracost" readonly />
                                            <input type="text" class="form-control tot_comm_extracost"  readonly name="tot_comm_extracost"
                                            style="background-color: #e9ecef;" value="{{ $PurchaseData->tot_comm_extracost }}"/></td>
                                        <td colspan="1"><input type="text" class="form-control total_amount" id="total_amount"
                                                name="total_amount" value="{{ $PurchaseData->total_amount }}" readonly
                                                style="background-color: #e9ecef;" /></td>
                                    </tr>
                                    <tr>
                                        <td colspan="5" class="text-end" style="font-size:15px;color: black;">Gross
                                            Amount</td>
                                        <td colspan="2"><input type="text" class="form-control gross_amount" id="gross_amount"
                                                placeholder="Gross Amount" value="{{ $PurchaseData->gross_amount }}"
                                                readonly style="background-color: #e9ecef;" name="gross_amount" /></td>
                                    </tr>
                                    <tr>
                                        <td colspan="5" class="text-end" style="font-size:15px;color: red;">Old
                                            Balance</td>
                                        <td colspan="2"><input type="text" class="form-control old_balance" id="old_balance"
                                                placeholder="Old Balance" readonly value="{{ $PurchaseData->old_balance }}"
                                                style="background-color: #e9ecef;" name="old_balance" /></td>
                                    </tr>
                                    <tr>
                                        <td colspan="5" class="text-end" style="font-size:15px;color: green;">Grand
                                            Total</td>
                                        <td colspan="2"><input type="text" class="form-control grand_total" id="grand_total"
                                                readonly placeholder="Grand Total"
                                                value="{{ $PurchaseData->grand_total }}"
                                                style="background-color: #e9ecef;" name="grand_total" /></td>
                                    </tr>
                                    <tr>
                                        <td colspan="5" class="text-end" style="font-size:15px;color: black;">Payable
                                            Amount<span style="color: red;">*</span></td>
                                        <td colspan="2"><input type="text" class="form-control payable_amount"
                                                name="payable_amount" placeholder="Payable Amount" required
                                                value="{{ $PurchaseData->paid_amount }}" id="payable_amount"></td>
                                    </tr>
                                    <tr>
                                        <td colspan="5" class="text-end" style="font-size:15px;color: black;">Pending
                                            Amount</td>
                                        <td colspan="2"><input type="text" class="form-control pending_amount"
                                                name="pending_amount" value="{{ $PurchaseData->balance_amount }}"
                                                readonly style="background-color: #e9ecef;" placeholder="Pending Amount"
                                                id="pending_amount"></td>
                                    </tr>
                                </tbody>
                  </table>
               </div>


               <div class="col-lg-12 col-sm-12 col-12">
                    <div class="form-group">
                       <label style="font-size:15px;padding-top: 5px;padding-bottom: 2px;">Remarks<span style="color: red;">*</span></label>
                       <textarea name="purchase_remark" id="purchase_remark" class="form-control">{{ $PurchaseData->purchase_remark }}</textarea>
                    </div>
                 </div>
            </div>

               <br/><br/>


            <div class="modal-footer">
               <input type="submit" class="btn btn-primary" name="submit" value="submit" />
               <a href="{{ route('purchaseorder.purchaseorder_index') }}" class="btn btn-danger" value="">Cancel</a>
            </div>
         </form>







         </div>
      </div>
   </div>

@endsection
