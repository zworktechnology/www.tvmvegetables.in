@extends('layout.backend.auth')

@section('content')

   <div class="content">
      <div class="page-header">
         <div class="page-title">
            <h4>Create New Purchase</h4>
         </div>
      </div>



        <div class="card">
            <div class="card-body">
                <form autocomplete="off" method="POST" action="{{ route('purchaseorder.purchaseorder_store') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-lg-4 col-sm-4 col-12">
                            <div class="form-group">
                                <label style="font-size:15px;padding-top: 5px;padding-bottom: 2px;">Date<span
                                        style="color: red;">*</span></label>
                                <input type="date" name="date" placeholder="" value="{{ $today }}" required>
                            </div>
                        </div>

                        <div class="col-lg-4 col-sm-4 col-12">
                            <div class="form-group">
                                <label style="font-size:15px;padding-top: 5px;padding-bottom: 2px;">Time<span
                                        style="color: red;"> *</span></label>
                                <input type="time" name="time" placeholder="" value="{{ $timenow }}" required>
                            </div>
                        </div>

                        <div class="col-lg-4 col-sm-4 col-12">
                            <div class="form-group">
                                <label style="font-size:15px;padding-top: 5px;padding-bottom: 2px;">Supplier<span
                                        style="color: red;"> *</span> </label>
                                <select class="form-control js-example-basic-single select invoice_supplier" name="supplier_id" id="supplier_id" required>
                                    <option value="" disabled selected hiddden>Select Supplier</option>
                                    @foreach ($supplier as $suppliers)
                                        <option value="{{ $suppliers->id }}">{{ $suppliers->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-lg-4 col-sm-4 col-12" hidden>
                            <div class="form-group">
                                <label style="font-size:15px;padding-top: 5px;padding-bottom: 2px;">Branch<span
                                        style="color: red;"> *</span></label>
                                <select class="form-control js-example-basic-single select invoice_branchid" name="branch_id" id="branch_id" required>
                                    <option value="" disabled selected hiddden>Select Branch</option>
                                    @foreach ($branch as $branches)
                                        <option value="{{ $branches->id }}" selected>{{ $branches->shop_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <br />

                    <div class="row">
                        <div class="table-responsive col-lg-12 col-sm-12 col-12">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th style="font-size:15px; width:20%;">Product<span style="color: red;"> *</span></th>
                                        <th style="font-size:15px; width:10%;">Bag / Kg<span style="color: red;"> *</span></th>
                                        <th style="font-size:15px; width:10%;">Count<span style="color: red;"> *</span></th>
                                        <th style="font-size:15px; width:10%;">Note </th>
                                        <th style="font-size:15px; width:10%;">Price Per Count<span style="color: red;"> *</span></th>
                                        <th style="font-size:15px; width:15%;"></th>
                                        <th style="font-size:15px; width:15%;"></th>
                                        <th style="font-size:15px; width:10%;">Action </th>
                                    </tr>
                                </thead>
                                <tbody class="purchaseorder_fields" >
                                    <tr>
                                        <td>
                                            <input type="hidden"id="purchase_detail_id"name="purchase_detail_id[]" />
                                            <select class="form-control product_id" name="product_id[]"
                                                id="product_id1"required>
                                                <option value="" selected hidden class="text-muted">Select Product
                                                </option>
                                                @foreach ($productlist as $productlists)
                                                    <option value="{{ $productlists->id }}">{{ $productlists->id }} - {{ $productlists->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            <select class=" form-control bagorkg" name="bagorkg[]" id="bagorkg1"required>
                                                <option value="" selected hidden class="text-muted">Select</option>
                                                <option value="bag">Bag</option>
                                                <option value="kg">Kg</option>
                                            </select>
                                        </td>
                                        <td>
                                            <input type="text" class="form-control count" id="count" name="count[]"
                                                placeholder="count" value="" required />
                                        </td>
                                        <td>
                                            <input type="text" class="form-control note" id="note" name="note[]"
                                                placeholder="note" value=""/>
                                        </td>
                                        <td><input type="text" class="form-control price_per_kg" id="price_per_kg"
                                                    name="price_per_kg[]" placeholder="Price Per count" required /></td>
                                        <td></td>
                                        <td class="text-end"><input type="text" class="form-control total_price"
                                                    id="total_price" style="background-color: #e9ecef;" readonly
                                                    name="total_price[]" placeholder=""required /></td>
                                        <td>
                                            <button style="width: 35px;margin-right:5px;"class="py-1 text-white font-medium rounded-lg text-sm  text-center btn btn-primary addpurchaseorderfields" type="button" id="" value="Add">+</button>
                                            <button style="width: 35px;" class="py-1 text-white font-medium rounded-lg text-sm  text-center btn btn-danger remove-tr" type="button" >-</button>

                                        </td>
                                    </tr>
                                </tbody>
                                <tbody>
                                    <tr>
                                        <td colspan="4">


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
                                        <td></td>
                                    </tr>
                                </tbody>
                                <tbody class="extracost_tr">
                                    <tr>
                                        <td style="font-size:15px; color: black;" class="text-end">Extra Cost<span
                                            style="color: red;">*</span></td>
                                        <td colspan="4">
                                            <input type="hidden" name="purchase_extracost_id"/>
                                            <select class=" form-control bagorkg" name="extracost_note[]" id="extracost_note" required>
                                                <option value="" selected hidden class="text-muted">Select</option>
                                                <option value="Hire">Hire</option>
                                                <option value="Wage">Wage</option>
                                                <option value="Gate">Gate</option>
                                                <option value="Advance">Advance</option>
                                            </select>
                                        </td>

                                        <td><input type="text" class="form-control extracost" id="extracost"
                                                placeholder="Extra Cost" required name="extracost[]"
                                                value="" /></td>
                                        <td><button style="width: 35px;"class="py-1 addextranotefields text-white font-medium rounded-lg text-sm  text-center btn btn-primary"
                                                type="button" id="" value="Add">+</button>
                                                <button style="width: 35px;"class="py-1 text-white remove-extratr font-medium rounded-lg text-sm  text-center btn btn-danger" type="button" id="" value="Add">-</button></td>
                                    </tr>
                                </tbody>
                                <tbody>
                                    <tr>
                                        <td colspan="5" class="text-end" style="font-size:15px;color: black;">Total</td>
                                        <td><input type="hidden" class="form-control total_extracost" value="" name="total_extracost" id="total_extracost" readonly />
                                            <input type="text" class="form-control tot_comm_extracost" value="" readonly name="tot_comm_extracost"
                                            style="background-color: #e9ecef;"/></td>
                                        <td><input type="text" class="form-control total_amount" id="total_amount"
                                                name="total_amount" value="" readonly
                                                style="background-color: #e9ecef;" /></td>
                                        <td></td>

                                    </tr>
                                    <tr>
                                        <td colspan="5" class="text-end" style="font-size:15px;color: black;">Gross
                                            Amount</td>
                                        <td colspan="2"><input type="text" class="form-control gross_amount" id="gross_amount"
                                                placeholder="Gross Amount" value=""
                                                readonly style="background-color: #e9ecef;" name="gross_amount" /></td>
                                        <td></td>

                                    </tr>
                                    <tr>
                                        <td colspan="5" class="text-end" style="font-size:15px;color: red;">Old
                                            Balance</td>
                                        <td colspan="2"><input type="text" class="form-control old_balance" id="old_balance"
                                                placeholder="Old Balance" readonly value="0"
                                                style="background-color: #e9ecef;" name="old_balance" /></td>
                                        <td></td>

                                    </tr>
                                    <tr>
                                        <td colspan="5" class="text-end" style="font-size:15px;color: green;">Grand
                                            Total</td>
                                        <td colspan="2"><input type="text" class="form-control grand_total" id="grand_total"
                                                readonly placeholder="Grand Total"
                                                value=""
                                                style="background-color: #e9ecef;" name="grand_total" /></td>
                                        <td></td>

                                    </tr>
                                    <tr>
                                        <td colspan="1" class="text-end" style="font-size:15px;color: black;">Bank<span style="color: red;"> *</span></td>
                                        <td colspan="3">
                                            <select class="form-control js-example-basic-single select" name="bank_id" id="bank_id" required>
                                                <option value="" disabled selected hiddden>Select Bank</option>
                                                @foreach ($bank as $banks)
                                                    <option
                                                        value="{{ $banks->id }}">
                                                        {{ $banks->name }}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td colspan="1" class="text-end" style="font-size:15px;color: black;">Payable Amount<span style="color: red;"> *</span></td>
                                        <td colspan="2">
                                            <input type="text" class="form-control payable_amount" name="payable_amount" placeholder="Payable Amount" required value="" id="payable_amount"></td>
                                        <td></td>

                                    </tr>
                                    <tr>
                                        <td colspan="5" class="text-end" style="font-size:15px;color: black;">Pending
                                            Amount</td>
                                        <td colspan="2"><input type="text" class="form-control pending_amount"
                                                name="pending_amount" value=""
                                                readonly style="background-color: #e9ecef;" placeholder="Pending Amount"
                                                id="pending_amount"></td>
                                        <td></td>

                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <input type="submit" class="btn btn-primary" onclick="purchase_ordersubmitForm(this);" />
                        <a href="{{ route('purchaseorder.purchaseorder_index') }}" class="btn btn-danger" value="">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
