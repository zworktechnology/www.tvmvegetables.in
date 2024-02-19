<div class="modal-dialog modal-xl">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="purchaseviewLargeModalLabel{{ $purchasedata['unique_key'] }}">Purchase Bill
                Details</h5>
            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>

        <div class="modal-body">
            <div class="card">
                <div class="card-body">
                    <div style="padding-bottom: 25px;">
                        <h6>Bill No : #<span style="font-weight:700;" class="purchase_bill_no"></span></h6>
                    </div>
                    <div class="invoice-box table-height"
                        style="max-width: 1600px;width:100%;overflow: auto;padding: 0;font-size: 14px;line-height: 24px;color: #555;">
                        <div class="row">
                            <div class="col-lg-4 col-sm-3 col-12">
                                <div class="card">
                                    <div class="card-body">
                                        <span class=""><span
                                                style="vertical-align: inherit;margin-bottom:25px;vertical-align: inherit;font-size:16px;color:red;font-weight:700;line-height: 35px; ">BASIC
                                                INFO</span></span><br>
                                        <span style="font-size:14px; color:black;">Bill No: </span>&nbsp;&nbsp;&nbsp;
                                        #<span class="purchase_bill_no"
                                            style="vertical-align: inherit;vertical-align: inherit;font-size: 14px;color:#000;font-weight: 400;"></span><br>
                                        <span style="font-size:14px; color:black;">Date of Purchase:
                                        </span>&nbsp;&nbsp;&nbsp;<span class="date"
                                            style="vertical-align: inherit;vertical-align: inherit;font-size: 14px;color:#000;font-weight: 400;"></span><br>
                                        <span style="font-size:14px; color:black;">Time of Purchase:
                                        </span>&nbsp;&nbsp;&nbsp;<span class="time"
                                            style="vertical-align: inherit;vertical-align: inherit;font-size: 14px;color:#000;font-weight: 400;"></span><br>
                                        <span style="font-size:14px; color:black;">Payment Method:
                                        </span>&nbsp;&nbsp;&nbsp;<span class="bank_namedata"
                                            style="vertical-align: inherit;vertical-align: inherit;font-size: 14px;color:#000;font-weight: 400;"></span><br>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-sm-3 col-12">
                                <div class="card">
                                    <div class="card-body">
                                        <span class="">
                                            <font
                                                style="vertical-align: inherit;margin-bottom:25px;vertical-align: inherit;font-size:16px;color:red;font-weight:700;line-height: 35px; ">
                                                SUPPLIER INFO</font>
                                        </span><br>
                                        <span style="font-size:14px; color:black;">Name: </span>&nbsp;&nbsp;&nbsp;<span
                                            class="suppliername"
                                            style="vertical-align: inherit;vertical-align: inherit;font-size: 14px;color:#000;font-weight: 400;"></span><br>
                                        <span style="font-size:14px; color:black;">Contact No:
                                        </span>&nbsp;&nbsp;&nbsp;<span class="supplier_contact_number"
                                            style="vertical-align: inherit;vertical-align: inherit;font-size: 14px;color:#000;font-weight: 400;"></span><br>
                                        <span style="font-size:14px; color:black;">Shop Name:
                                        </span>&nbsp;&nbsp;&nbsp;<span class="supplier_shop_name"
                                            style="vertical-align: inherit;vertical-align: inherit;font-size: 14px;color:#000;font-weight: 400;"></span><br>
                                        <span style="font-size:14px; color:black;">Address:
                                        </span>&nbsp;&nbsp;&nbsp;<span class="supplier_shop_address"
                                            style="vertical-align: inherit;vertical-align: inherit;font-size: 14px;color:#000;font-weight: 400;"></span><br>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-sm-3 col-12">
                                <div class="card">
                                    <div class="card-body">
                                        <span class="">
                                            <font
                                                style="vertical-align: inherit;margin-bottom:25px;vertical-align: inherit;font-size:16px;color:red;font-weight:700;line-height: 35px; ">
                                                BRANCH INFO</font>
                                        </span><br>
                                        <span style="font-size:14px; color:black;">Name: </span>&nbsp;&nbsp;&nbsp;<span
                                            class="branchname"
                                            style="vertical-align: inherit;vertical-align: inherit;font-size: 14px;color:#000;font-weight: 400;"></span><br>
                                        <span style="font-size:14px; color:black;">Contact No:
                                        </span>&nbsp;&nbsp;&nbsp;<span class="branch_contact_number"
                                            style="vertical-align: inherit;vertical-align: inherit;font-size: 14px;color:#000;font-weight: 400;"></span><br>
                                        <span style="font-size:14px; color:black;">Shop Name:
                                        </span>&nbsp;&nbsp;&nbsp;<span class="branch_shop_name"
                                            style="vertical-align: inherit;vertical-align: inherit;font-size: 14px;color:#000;font-weight: 400;"></span><br>
                                        <span style="font-size:14px; color:black;">Address:
                                        </span>&nbsp;&nbsp;&nbsp;<span class="branch_address"
                                            style="vertical-align: inherit;vertical-align: inherit;font-size: 14px;color:#000;font-weight: 400;"></span><br>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12 col-12  col-sm-12">
                                <p style="color:black;font-weight:700;">PRODUCTS DETAILS</p>
                                <div class="row">
                                    <div class="col-lg-2 col-sm-2 col-12 border">
                                        <span class=""
                                            style="vertical-align: inherit;vertical-align: inherit;font-size: 14px;color:#000;font-weight: 700;line-height: 35px; ">Product</span>
                                    </div>
                                    <div class="col-lg-2 col-sm-2 col-12 border">
                                        <span class=""
                                            style="vertical-align: inherit;vertical-align: inherit;font-size: 14px;color:#000;font-weight: 700;line-height: 35px; ">Bag
                                            / Kg</span>
                                    </div>
                                    <div class="col-lg-2 col-sm-2 col-12 border">
                                        <span class=""
                                            style="vertical-align: inherit;vertical-align: inherit;font-size: 14px;color:#000;font-weight: 700;line-height: 35px; ">Count</span>
                                    </div>
                                    <div class="col-lg-2 col-sm-2 col-12 border">
                                        <span class=""
                                            style="vertical-align: inherit;vertical-align: inherit;font-size: 14px;color:#000;font-weight: 700;line-height: 35px; ">Price
                                            Per Count</span>
                                    </div>
                                    <div class="col-lg-2 col-sm-2 col-12 border">
                                        <span class=""
                                            style="vertical-align: inherit;vertical-align: inherit;font-size: 14px;color:#000;font-weight: 700;line-height: 35px; "></span>
                                    </div>
                                    <div class="col-lg-2 col-sm-2 col-12 border">
                                        <span class=""
                                            style="vertical-align: inherit;vertical-align: inherit;font-size: 14px;color:#000;font-weight: 700;line-height: 35px; ">Total</span>
                                    </div>
                                </div>
                                <div class="row ">
                                    @foreach ($purchasedata['terms'] as $index => $term_arr)
                                        @if ($term_arr['purchase_id'] == $purchasedata['id'])
                                            <div class="col-lg-2 col-sm-2 col-12 border">
                                                <span
                                                    class=""style="vertical-align: inherit;vertical-align: inherit;font-size: 14px;color:#000;font-weight: 400;line-height: 35px; ">{{ $term_arr['product_name'] }}</span>
                                            </div>
                                            <div class="col-lg-2 col-sm-2 col-12 border">
                                                <span
                                                    class=""style="vertical-align: inherit;vertical-align: inherit;font-size: 14px;color:#000;font-weight: 400;line-height: 35px; ">{{ $term_arr['bag'] }}</span>
                                            </div>
                                            <div class="col-lg-2 col-sm-2 col-12 border">
                                                <span
                                                    class=""style="vertical-align: inherit;vertical-align: inherit;font-size: 14px;color:#000;font-weight: 400;line-height: 35px; ">{{ $term_arr['kgs'] }}</span>
                                            </div>
                                            <div class="col-lg-2 col-sm-2 col-12 border">
                                                <span
                                                    class=""style="vertical-align: inherit;vertical-align: inherit;font-size: 14px;color:#000;font-weight: 400;line-height: 35px; ">{{ $term_arr['price_per_kg'] }}</span>
                                            </div>
                                            <div class="col-lg-2 col-sm-2 col-12 border">
                                                <span
                                                    class=""style="vertical-align: inherit;vertical-align: inherit;font-size: 14px;color:#000;font-weight: 400;line-height: 35px; "></span>
                                            </div>
                                            <div class="col-lg-2 col-sm-2 col-12 border">
                                                <span
                                                    class=""style="vertical-align: inherit;vertical-align: inherit;font-size: 14px;color:#000;font-weight: 400;line-height: 35px; ">{{ $term_arr['total_price'] }}</span>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12 col-12 col-sm-12">
                                <div class="row">
                                    <div class="col-lg-2 col-sm-2 col-12 ">
                                        <span class=""
                                            style="vertical-align: inherit;vertical-align: inherit;font-size: 14px;color:#000;font-weight: 700;line-height: 35px; "></span>
                                    </div>
                                    <div class="col-lg-2 col-sm-2 col-12 ">
                                        <span class=""
                                            style="vertical-align: inherit;vertical-align: inherit;font-size: 14px;color:#000;font-weight: 700;line-height: 35px; "></span>
                                    </div>
                                    <div class="col-lg-2 col-sm-2 col-12 border">
                                        <span class=""
                                            style="vertical-align: inherit;vertical-align: inherit;font-size: 14px;color:#000;font-weight: 700;line-height: 35px; ">Commission</span>
                                    </div>
                                    <div class="col-lg-2 col-sm-2 col-12 border">
                                        <span class="purchase_commisionpercentage"
                                            style="vertical-align: inherit;vertical-align: inherit;font-size: 14px;color:#000;line-height: 35px; "></span>
                                    </div>
                                    <div class="col-lg-2 col-sm-2 col-12 border">
                                        <span class=""
                                            style="vertical-align: inherit;vertical-align: inherit;font-size: 14px;color:#000;line-height: 35px; "><span
                                                class="purchase_commision"></span></span>
                                    </div>
                                    <div class="col-lg-2 col-sm-2 col-12">
                                        <span class=""
                                            style="vertical-align: inherit;vertical-align: inherit;font-size: 14px;color:#000;font-weight: 700;line-height: 35px; "></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12 col-12 col-sm-12">
                                <div class="row">
                                    @foreach ($purchasedata['Extracost_Arr'] as $index => $Extracost_Arr)
                                        @if ($Extracost_Arr['purchase_id'] == $purchasedata['id'])
                                            <div class="col-lg-2 col-sm-2 col-12 ">
                                                <span class=""
                                                    style="vertical-align: inherit;vertical-align: inherit;font-size: 14px;color:#000;font-weight: 700;line-height: 35px; "></span>
                                            </div>
                                            <div class="col-lg-2 col-sm-2 col-12 ">
                                                <span class=""
                                                    style="vertical-align: inherit;vertical-align: inherit;font-size: 14px;color:#000;font-weight: 700;line-height: 35px; "></span>
                                            </div>
                                            <div class="col-lg-2 col-sm-2 col-12 border">
                                                <span class=""
                                                    style="vertical-align: inherit;vertical-align: inherit;font-size: 14px;color:#000;font-weight: 700;line-height: 35px; ">Extra
                                                    Cost</span>
                                            </div>
                                            <div class="col-lg-2 col-sm-2 col-12 border">
                                                <span class=""
                                                    style="vertical-align: inherit;vertical-align: inherit;font-size: 14px;color:#000;line-height: 35px; ">{{ $Extracost_Arr['extracost_note'] }}</span>
                                            </div>
                                            <div class="col-lg-2 col-sm-2 col-12 border">
                                                <span class=""
                                                    style="vertical-align: inherit;vertical-align: inherit;font-size: 14px;color:#000;line-height: 35px; ">{{ $Extracost_Arr['extracost'] }}</span>
                                            </div>
                                            <div class="col-lg-2 col-sm-2 col-12">
                                                <span class=""
                                                    style="vertical-align: inherit;vertical-align: inherit;font-size: 14px;color:#000;font-weight: 700;line-height: 35px; "></span>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12 col-12 col-sm-12">
                                <div class="row">
                                    <div class="col-lg-2 col-sm-2 col-12 ">
                                        <span class=""
                                            style="vertical-align: inherit;vertical-align: inherit;font-size: 14px;color:#000;font-weight: 700;line-height: 35px; "></span>
                                    </div>
                                    <div class="col-lg-2 col-sm-2 col-12 ">
                                        <span class=""
                                            style="vertical-align: inherit;vertical-align: inherit;font-size: 14px;color:#000;font-weight: 700;line-height: 35px; "></span>
                                    </div>
                                    <div class="col-lg-2 col-sm-2 col-12">
                                        <span class=""
                                            style="vertical-align: inherit;vertical-align: inherit;font-size: 14px;color:#000;font-weight: 700;line-height: 35px; "></span>
                                    </div>
                                    <div class="col-lg-2 col-sm-2 col-12 border">
                                        <span class=""
                                            style="vertical-align: inherit;vertical-align: inherit;font-size: 14px;color:#000;font-weight: 700;line-height: 35px; ">Total</span>
                                    </div>
                                    <div class="col-lg-2 col-sm-2 col-12 border">
                                        <span class=""
                                            style="vertical-align: inherit;vertical-align: inherit;font-size: 14px;color:#000;line-height: 35px; "><span class="tot_comm_extracost"></span></span>
                                    </div>
                                    <div class="col-lg-2 col-sm-2 col-12 border">
                                        <span class=""
                                            style="vertical-align: inherit;vertical-align: inherit;font-size: 14px;color:#000;line-height: 35px; "><span class="purchase_total_amount"></span></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12 col-12 col-sm-12">
                                <div class="row">
                                    <div class="col-lg-2 col-sm-2 col-12 ">
                                        <span class=""
                                            style="vertical-align: inherit;vertical-align: inherit;font-size: 14px;color:#000;font-weight: 700;line-height: 35px; "></span>
                                    </div>
                                    <div class="col-lg-2 col-sm-2 col-12 ">
                                        <span class=""
                                            style="vertical-align: inherit;vertical-align: inherit;font-size: 14px;color:#000;font-weight: 700;line-height: 35px; "></span>
                                    </div>
                                    <div class="col-lg-2 col-sm-2 col-12 ">
                                        <span class=""
                                            style="vertical-align: inherit;vertical-align: inherit;font-size: 14px;color:#000;font-weight: 700;line-height: 35px; "></span>
                                    </div>
                                    <div class="col-lg-4 col-sm-4 col-12 border">
                                        <span class=""
                                            style="vertical-align: inherit;vertical-align: inherit;font-size: 14px;color:#000;font-weight: 700;line-height: 35px; ">Grand Total</span>
                                    </div>
                                    <div class="col-lg-2 col-sm-2 col-12 border">
                                        <span class=""
                                            style="vertical-align: inherit;vertical-align: inherit;font-size: 14px;color:#000;line-height: 35px; "><span class="purchase_grossamont"></span></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12 col-12 col-sm-12">
                                <div class="row">
                                    <div class="col-lg-2 col-sm-2 col-12 ">
                                        <span class=""
                                            style="vertical-align: inherit;vertical-align: inherit;font-size: 14px;color:#000;font-weight: 700;line-height: 35px; "></span>
                                    </div>
                                    <div class="col-lg-2 col-sm-2 col-12 ">
                                        <span class=""
                                            style="vertical-align: inherit;vertical-align: inherit;font-size: 14px;color:#000;font-weight: 700;line-height: 35px; "></span>
                                    </div>
                                    <div class="col-lg-2 col-sm-2 col-12 ">
                                        <span class=""
                                            style="vertical-align: inherit;vertical-align: inherit;font-size: 14px;color:#000;font-weight: 700;line-height: 35px; "></span>
                                    </div>
                                    <div class="col-lg-4 col-sm-4 col-12 border">
                                        <span class=""
                                            style="vertical-align: inherit;vertical-align: inherit;font-size: 14px;color:#000;font-weight: 700;line-height: 35px; ">Old Balance</span>
                                    </div>
                                    <div class="col-lg-2 col-sm-2 col-12 border">
                                        <span class=""
                                            style="vertical-align: inherit;vertical-align: inherit;font-size: 14px;color:#000;line-height: 35px; "><span class="purchase_old_balance"></span></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12 col-12 col-sm-12">
                                <div class="row">
                                    <div class="col-lg-2 col-sm-2 col-12 ">
                                        <span class=""
                                            style="vertical-align: inherit;vertical-align: inherit;font-size: 14px;color:#000;font-weight: 700;line-height: 35px; "></span>
                                    </div>
                                    <div class="col-lg-2 col-sm-2 col-12 ">
                                        <span class=""
                                            style="vertical-align: inherit;vertical-align: inherit;font-size: 14px;color:#000;font-weight: 700;line-height: 35px; "></span>
                                    </div>
                                    <div class="col-lg-2 col-sm-2 col-12 ">
                                        <span class=""
                                            style="vertical-align: inherit;vertical-align: inherit;font-size: 14px;color:#000;font-weight: 700;line-height: 35px; "></span>
                                    </div>
                                    <div class="col-lg-4 col-sm-4 col-12 border">
                                        <span class=""
                                            style="vertical-align: inherit;vertical-align: inherit;font-size: 14px;color:#000;font-weight: 700;line-height: 35px; ">Grand Total</span>
                                    </div>
                                    <div class="col-lg-2 col-sm-2 col-12 border">
                                        <span class=""
                                            style="vertical-align: inherit;vertical-align: inherit;font-size: 14px;color:#000;line-height: 35px; "><span class="purchase_grand_total"></span></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12 col-12 col-sm-12">
                                <div class="row">
                                    <div class="col-lg-2 col-sm-2 col-12 ">
                                        <span class=""
                                            style="vertical-align: inherit;vertical-align: inherit;font-size: 14px;color:#000;font-weight: 700;line-height: 35px; "></span>
                                    </div>
                                    <div class="col-lg-2 col-sm-2 col-12 ">
                                        <span class=""
                                            style="vertical-align: inherit;vertical-align: inherit;font-size: 14px;color:#000;font-weight: 700;line-height: 35px; "></span>
                                    </div>
                                    <div class="col-lg-2 col-sm-2 col-12 ">
                                        <span class=""
                                            style="vertical-align: inherit;vertical-align: inherit;font-size: 14px;color:#000;font-weight: 700;line-height: 35px; "></span>
                                    </div>
                                    <div class="col-lg-4 col-sm-4 col-12 border">
                                        <span class=""
                                            style="vertical-align: inherit;vertical-align: inherit;font-size: 14px;color:#000;font-weight: 700;line-height: 35px; ">Payed Amount Total</span>
                                    </div>
                                    <div class="col-lg-2 col-sm-2 col-12 border">
                                        <span class=""
                                            style="vertical-align: inherit;vertical-align: inherit;font-size: 14px;color:#000;line-height: 35px; "><span class="purchase_paid_amount"></span></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12 col-12 col-sm-12">
                                <div class="row">
                                    <div class="col-lg-2 col-sm-2 col-12 ">
                                        <span class=""
                                            style="vertical-align: inherit;vertical-align: inherit;font-size: 14px;color:#000;font-weight: 700;line-height: 35px; "></span>
                                    </div>
                                    <div class="col-lg-2 col-sm-2 col-12 ">
                                        <span class=""
                                            style="vertical-align: inherit;vertical-align: inherit;font-size: 14px;color:#000;font-weight: 700;line-height: 35px; "></span>
                                    </div>
                                    <div class="col-lg-2 col-sm-2 col-12 ">
                                        <span class=""
                                            style="vertical-align: inherit;vertical-align: inherit;font-size: 14px;color:#000;font-weight: 700;line-height: 35px; "></span>
                                    </div>
                                    <div class="col-lg-4 col-sm-4 col-12 border">
                                        <span class=""
                                            style="vertical-align: inherit;vertical-align: inherit;font-size: 14px;color:#000;font-weight: 700;line-height: 35px; ">Pending Balance Amount</span>
                                    </div>
                                    <div class="col-lg-2 col-sm-2 col-12 border">
                                        <span class=""
                                            style="vertical-align: inherit;vertical-align: inherit;font-size: 14px;color:#000;line-height: 35px; "><span class="purchase_balance_amount"></span></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->
