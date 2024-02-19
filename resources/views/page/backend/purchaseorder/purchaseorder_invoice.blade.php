@extends('layout.backend.auth')

@section('content')
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h4>Purchase Order - Pattial</h4>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <form autocomplete="off" method="POST"
                    action="{{ route('purchaseorder.purchaseorder_invoiceupdate', ['unique_key' => $PurchaseData->unique_key]) }}"
                    enctype="multipart/form-data">
                    @method('PUT')
                    @csrf
                    <div class="row">
                        <div class="col-lg-4 col-sm-4 col-12">
                            <div class="form-group">
                                <label style="font-size:15px;padding-top: 5px;padding-bottom: 2px;">Date<span
                                        style="color: red;">*</span></label>
                                <input type="date" name="date" placeholder="" value="{{ $PurchaseData->date }}"
                                    disabled style="background-color: #e9ecef;">
                            </div>
                        </div>

                        <div class="col-lg-4 col-sm-4 col-12">
                            <div class="form-group">
                                <label style="font-size:15px;padding-top: 5px;padding-bottom: 2px;">Time<span
                                        style="color: red;">*</span></label>
                                <input type="time" name="time" placeholder="" value="{{ $PurchaseData->time }}"
                                    disabled style="background-color: #e9ecef;">
                            </div>
                        </div>

                        <div class="col-lg-4 col-sm-4 col-12">
                            <div class="form-group">
                                <label style="font-size:15px;padding-top: 5px;padding-bottom: 2px;">Bill No<span
                                        style="color: red;">*</span></label>
                                <input type="text" name="billno" placeholder="Bill No" id="billno"
                                    value="{{ $PurchaseData->bill_no }}" style="background-color: #e9ecef;" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-4 col-sm-4 col-12">
                            <div class="form-group">
                                <label style="font-size:15px;padding-top: 5px;padding-bottom: 2px;">Supplier<span
                                        style="color: red;">*</span> </label>
                                <select class="select form-control invoice_supplier" name="supplier_id" id="supplier_id"
                                    disabled>
                                    <option value="" disabled selected hiddden>Select Supplier</option>
                                    @foreach ($supplier as $suppliers)
                                        <option
                                            value="{{ $suppliers->id }}"@if ($suppliers->id === $PurchaseData->supplier_id) selected='selected' @endif>
                                            {{ $suppliers->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-lg-4 col-sm-4 col-12">
                            <div class="form-group">
                                <label style="font-size:15px;padding-top: 5px;padding-bottom: 2px;">Branch<span
                                        style="color: red;">*</span></label>
                                <select class="select form-control invoice_branchid" name="branch_id" id="branch_id"
                                    disabled>
                                    <option value="" disabled selected hiddden>Select Branch</option>
                                    @foreach ($branch as $branches)
                                        <option
                                            value="{{ $branches->id }}"@if ($branches->id === $PurchaseData->branch_id) selected='selected' @endif>
                                            {{ $branches->shop_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        
                        <div class="col-lg-4 col-sm-4 col-12">
                            <div class="form-group">
                                <label style="font-size:15px;padding-top: 5px;padding-bottom: 2px;">Bank<span
                                        style="color: red;">*</span></label>
                                <select class="form-control js-example-basic-single select" name="bank_id" id="bank_id" required>
                                    <option value="" disabled selected hiddden>Select Bank</option>
                                    @foreach ($bank as $banks)
                                        <option
                                            value="{{ $banks->id }}"@if ($banks->id === $PurchaseData->bank_id) selected='selected' @endif>
                                            {{ $banks->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <br />
                    <div class="row">
                        <div class="table-responsive col-12">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th style="font-size:15px; width:20%;">Product</th>
                                        <th style="font-size:15px; width:10%;">Bag / Kg</th>
                                        <th style="font-size:15px; width:10%;">Count </th>
                                        <th style="font-size:15px; width:20%;">Price / Count</th>
                                        <th style="font-size:15px; width:20%;">Amount</th>
                                        <th style="font-size:15px; width:20%;">Amount</th>

                                    </tr>
                                </thead>
                                <tbody id="product_fields">
                                    @foreach ($PurchaseProducts as $index => $Purchase_Products)
                                        <tr>
                                            <td class="">
                                                <input type="hidden"id="purchase_detail_id"name="purchase_detail_id[]"
                                                    value="{{ $Purchase_Products->id }}" />
                                                @foreach ($productlist as $products)
                                                    @if ($products->id == $Purchase_Products->productlist_id)
                                                        <input type="text"class="form-control" name="product_name[]"
                                                            value="{{ $products->name }}" readonly>
                                                        <input type="hidden" id="product_id" name="product_id[]"
                                                            value="{{ $Purchase_Products->productlist_id }}" />
                                                    @endif
                                                @endforeach
                                            </td>
                                            <td><input type="text" class="form-control" id="bagorkg" readonly
                                                    name="bagorkg[]" placeholder="bagorkg"
                                                    value="{{ $Purchase_Products->bagorkg }}" required /></td>
                                            <td><input type="text" class="form-control count" id="count" readonly
                                                    name="count[]" placeholder="count"
                                                    value="{{ $Purchase_Products->count }}" required /></td>
                                            <td><input type="text" class="form-control price_per_kg" id="price_per_kg"
                                                    name="price_per_kg[]" placeholder="Price Per count" required /></td>
                                            <td></td>
                                            <td class="text-end"><input type="text" class="form-control total_price"
                                                    id="total_price" style="background-color: #e9ecef;"
                                                    name="total_price[]" placeholder=""required /></td>

                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>

                                <tbody><tr><td></td></tr></tbody>

                                <tbody>
                                    <tr>
                                        <td colspan="3">
                                            

                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input commission_ornet" type="radio" name="commission_ornet" id="commission" value="commission">
                                                <label class="form-check-label" for="commission">
                                                Commission
                                                </label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input commission_ornet" type="radio" name="commission_ornet" id="netprice" value="netprice">
                                                <label class="form-check-label" for="netprice">
                                                Net Price
                                                </label>
                                            </div>
                                        </td>
                                        <td><input type="text" style="display:none" value="" class="form-control commission_percent" name="commission_percent" id="commission_percent"/></td>
                                        <td><input type="text" class="form-control commission_amount" readonly  name="commission_amount" id="commission_amount" value=""/></td>
                                        <td></td>
                                    </tr>
                                </tbody>
                                <tbody id="extracost_tr">
                                    <tr>
                                        <td style="font-size:15px; color: black;" class="text-end">Extra Cost<span
                                            style="color: red;">*</span></td>
                                        <td colspan="3"><input type="hidden" name="purchase_extracost_id"/>
                                            <input type="text" class="form-control"
                                                id="extracost_note" placeholder="Note" value=""
                                                name="extracost_note[]" required /></td>
                                        
                                        <td><input type="text" class="form-control extracost" id="extracost"
                                                placeholder="Extra Cost" required name="extracost[]"
                                                value="" /></td>
                                        <td><button style="width: 35px;"class="py-1 text-white font-medium rounded-lg text-sm  text-center btn btn-primary"
                                                type="button" id="addextranotefields" value="Add">+</button></td>
                                    </tr>
                                </tbody>
                                <tbody>
                                    <tr>
                                        <td colspan="4" class="text-end" style="font-size:15px;color: black;">Total</td>
                                        <td><input type="hidden" class="form-control total_extracost" value="" name="total_extracost" id="total_extracost" readonly />
                                            <input type="text" class="form-control tot_comm_extracost" value="" readonly name="tot_comm_extracost"
                                            style="background-color: #e9ecef;"/></td>
                                        <td><input type="text" class="form-control total_amount" id="total_amount"
                                                name="total_amount" value="{{ $PurchaseData->total_amount }}" readonly
                                                style="background-color: #e9ecef;" /></td>
                                    </tr>
                                    <tr>
                                        <td colspan="4" class="text-end" style="font-size:15px;color: black;">Gross
                                            Amount</td>
                                        <td colspan="2"><input type="text" class="form-control gross_amount" id="gross_amount"
                                                placeholder="Gross Amount" value="{{ $PurchaseData->gross_amount }}"
                                                readonly style="background-color: #e9ecef;" name="gross_amount" /></td>
                                    </tr>
                                    <tr>
                                        <td colspan="4" class="text-end" style="font-size:15px;color: red;">Old
                                            Balance</td>
                                        <td colspan="2"><input type="text" class="form-control old_balance" id="old_balance"
                                                placeholder="Old Balance" readonly value="0"
                                                style="background-color: #e9ecef;" name="old_balance" /></td>
                                    </tr>
                                    <tr>
                                        <td colspan="4" class="text-end" style="font-size:15px;color: green;">Grand
                                            Total</td>
                                        <td colspan="2"><input type="text" class="form-control grand_total" id="grand_total"
                                                readonly placeholder="Grand Total"
                                                value="{{ $PurchaseData->grand_total }}"
                                                style="background-color: #e9ecef;" name="grand_total" /></td>
                                    </tr>
                                    <tr>
                                        <td colspan="4" class="text-end" style="font-size:15px;color: black;">Payable
                                            Amount<span style="color: red;">*</span></td>
                                        <td colspan="2"><input type="text" class="form-control payable_amount"
                                                name="payable_amount" placeholder="Payable Amount" required
                                                value="{{ $PurchaseData->paid_amount }}" id="payable_amount"></td>
                                    </tr>
                                    <tr>
                                        <td colspan="4" class="text-end" style="font-size:15px;color: black;">Pending
                                            Amount</td>
                                        <td colspan="2"><input type="text" class="form-control pending_amount"
                                                name="pending_amount" value="{{ $PurchaseData->balance_amount }}"
                                                readonly style="background-color: #e9ecef;" placeholder="Pending Amount"
                                                id="pending_amount"></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <br /><br />
                    <div class="modal-footer">
                        <input type="submit" class="btn btn-primary" onclick="purchaseorder_invoiceubmitForm(this);" />
                        <a href="{{ route('purchaseorder.purchaseorder_index') }}" class="btn btn-danger" value="">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
