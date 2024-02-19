<div class="modal-dialog modal-xl">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="purchaseviewLargeModalLabel{{ $purchasedata['unique_key'] }}">Purchase
                Details</h5>
            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>

        <div class="modal-body">
            <div class="card">
                <div class="card-body">
                    <h4 style="text-align: center; font-weight:700; color:blue; margin-bottom: 10px;">Thirupathi Trading
                        Co.,</h4>
                    <div class="invoice-box table-height"
                        style="max-width: 1600px;width:100%;overflow: auto;padding: 0;font-size: 14px;line-height: 24px;color: #555;">
                        <div class="row">
                            <div class="col-lg-6 col-sm-6 col-12">
                                <div class="card">
                                    <div class="card-body">
                                        <div style="display: flex">
                                            <div class="col-lg-3 col-sm-5 col-12">
                                                <p style="font-weight:500; margin-bottom: 1px; color:blue;">Bill No</p>
                                                <p style="font-weight:500; margin-bottom: 1px; color:blue;">Date & Time
                                                </p>
                                                <p style="font-weight:500; margin-bottom: 1px; color:blue;">From</p>
                                            </div>
                                            <div class="col-lg-1 col-sm-1 col-12">
                                                <p style="font-weight:500; margin-bottom: 1px;">:</p>
                                                <p style="font-weight:500; margin-bottom: 1px;">:</p>
                                                <p style="font-weight:500; margin-bottom: 1px;">:</p>
                                            </div>
                                            <div class="col-lg-8 col-sm-8 col-12">
                                                <p class="purchaseorder_bill_no" style="margin-bottom: 1px;"></p>
                                                <p style="margin-bottom: 1px;"><span
                                                        class="purchaseorder_date"></span><span> - </span><span
                                                        class="purchaseorder_time"></span></p>
                                                <p style="margin-bottom: 1px;">Thirupathi Trading Co., N.S.88, Gandhi
                                                    Market, Trichy -8. Cell : 98424 44022</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 col-sm-6 col-12">
                                <div class="card">
                                    <div class="card-body">
                                        <div style="display: flex">
                                            <div class="col-lg-3 col-sm-5 col-12">
                                                <p style="font-weight:500; margin-bottom: 1px; color:blue;">Supplier</p>
                                                <p style="font-weight:500; margin-bottom: 1px; color:blue;">Shop</p>
                                                <p style="font-weight:500; margin-bottom: 1px; color:blue;">To</p>
                                            </div>
                                            <div class="col-lg-1 col-sm-1 col-12">
                                                <p style="font-weight:500; margin-bottom: 1px;">:</p>
                                                <p style="font-weight:500; margin-bottom: 1px;">:</p>
                                                <p style="font-weight:500; margin-bottom: 1px;">:</p>
                                            </div>
                                            <div class="col-lg-8 col-sm-8 col-12">
                                                <p class="purchaseorder_suppliername" style="margin-bottom: 1px;"></p>
                                                <p class="purchaseordersupplier_shop_name" style="margin-bottom: 1px;">
                                                </p>
                                                <p style="margin-bottom: 1px;"><span
                                                        class="purchaseordersupplier_shop_address"></span><span> Cell :
                                                    </span><span class="purchaseordersupplier_contact_number"></span>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-body">
                                <div class="col-lg-12 col-12 col-sm-12">
                                    <p style="font-weight:700; color:blue; margin: 5px;">PRODUCTS DETAILS</p>
                                    <div class="row">
                                        <div class="col-lg-2 col-sm-2 col-12 border">
                                            <span class=""
                                                style="vertical-align: inherit; vertical-align: inherit; font-size: 14px; color:red; font-weight: 700; line-height: 35px; margin-left: 5px; ">Price
                                                Per Count</span>
                                        </div>
                                        <div class="col-lg-2 col-sm-2 col-12 border">
                                            <span class=""
                                                style="vertical-align: inherit; vertical-align: inherit; font-size: 14px; color:red; font-weight: 700; line-height: 35px; ">Particulars</span>
                                        </div>
                                        <div class="col-lg-2 col-sm-2 col-12 border">
                                            <span class=""
                                                style="vertical-align: inherit; vertical-align: inherit; font-size: 14px; color:red; font-weight: 700; line-height: 35px; ">Count</span>
                                        </div>
                                        <div class="col-lg-2 col-sm-2 col-12 border">
                                            <span class=""
                                                style="vertical-align: inherit; vertical-align: inherit; font-size: 14px; color:red; font-weight: 700; line-height: 35px; ">Note</span>
                                        </div>
                                        <div class="col-lg-2 col-sm-2 col-12 border">
                                            <span class=""
                                                style="vertical-align: inherit; vertical-align: inherit; font-size: 14px; color:red; font-weight: 700; line-height: 35px; "></span>
                                        </div>
                                        <div class="col-lg-2 col-sm-2 col-12 border">
                                            <span class=""
                                                style="vertical-align: inherit; vertical-align: inherit; font-size: 14px; color:red; font-weight: 700; line-height: 35px; ">Total</span>
                                        </div>
                                    </div>
                                    <div class="row ">
                                        @foreach ($purchasedata['terms'] as $index => $term_arr)
                                            @if ($term_arr['purchase_id'] == $purchasedata['id'])
                                                <div class="col-lg-2 col-sm-2 col-12 border">
                                                    <span
                                                        class=""style="vertical-align: inherit;vertical-align: inherit;font-size: 14px;color:#000;font-weight: 400;line-height: 35px; margin-left: 5px;">{{ $term_arr['price_per_kg'] }}</span>
                                                </div>
                                                <div class="col-lg-2 col-sm-2 col-12 border">
                                                    <span
                                                        class=""style="vertical-align: inherit;vertical-align: inherit;font-size: 14px;color:#000;font-weight: 400;line-height: 35px; ">{{ $term_arr['product_name'] }}</span>
                                                </div>
                                                <div class="col-lg-2 col-sm-2 col-12 border">
                                                    <span
                                                        class=""style="vertical-align: inherit;vertical-align: inherit;font-size: 14px;color:#000;font-weight: 400;line-height: 35px; ">{{ $term_arr['kgs'] }}
                                                        - {{ $term_arr['bag'] }}</span>
                                                </div>
                                                <div class="col-lg-2 col-sm-2 col-12 border">
                                                    <span
                                                        class=""style="vertical-align: inherit;vertical-align: inherit;font-size: 14px;color:#000;font-weight: 400;line-height: 35px; "></span>
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
                                <div class="col-lg-12 col-12 col-sm-12">
                                    <div class="row">
                                        <div class="col-lg-2 col-sm-2 col-12 ">
                                            <span class=""
                                                style="vertical-align: inherit;vertical-align: inherit;font-size: 14px;color:#000;font-weight: 700; line-height: 35px; "></span>
                                        </div>
                                        <div class="col-lg-4 col-sm-4 col-12 border">
                                            <span class=""
                                                style="vertical-align: inherit;vertical-align: inherit;font-size: 14px;color:red;font-weight: 700;line-height: 35px; ">
                                                type</span><span> - </span><span
                                                class="purchaseorder_commission_ornet"></span>
                                        </div>
                                        <div class="col-lg-2 col-sm-2 col-12 border">
                                            <span class="purchaseorder_commisionpercentage"
                                                style="vertical-align: inherit;vertical-align: inherit;font-size: 14px;color:#000;line-height: 35px; "></span>
                                        </div>
                                        <div class="col-lg-2 col-sm-2 col-12 border">
                                            <span class=""
                                                style="vertical-align: inherit;vertical-align: inherit;font-size: 14px;color:#000;line-height: 35px; "><span
                                                    class="purchaseorder_commision"></span></span>
                                        </div>
                                        <div class="col-lg-2 col-sm-2 col-12">
                                            <span class=""
                                                style="vertical-align: inherit;vertical-align: inherit;font-size: 14px;color:#000;font-weight: 700;line-height: 35px; "></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-12 col-12 col-sm-12">
                                    <div class="row">
                                        @foreach ($purchasedata['Extracost_Arr'] as $index => $Extracost_Arr)
                                            @if ($Extracost_Arr['purchase_id'] == $purchasedata['id'])
                                                <div class="col-lg-2 col-sm-2 col-12 ">
                                                    <span class=""
                                                        style="vertical-align: inherit;vertical-align: inherit;font-size: 14px;color:#000;font-weight: 700;line-height: 35px; "></span>
                                                </div>
                                                <div class="col-lg-2 col-sm-2 col-12 border">
                                                    <span class=""
                                                        style="vertical-align: inherit;vertical-align: inherit;font-size: 14px;color:red;font-weight: 700;line-height: 35px; ">Extra
                                                        Cost</span>
                                                </div>
                                                <div class="col-lg-4 col-sm-4 col-12 border">
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
                                                style="vertical-align: inherit;vertical-align: inherit;font-size: 14px;color:red;font-weight: 700;line-height: 35px; ">Total</span>
                                        </div>
                                        <div class="col-lg-2 col-sm-2 col-12 border">
                                            <span class=""
                                                style="vertical-align: inherit;vertical-align: inherit;font-size: 14px;color:#000;line-height: 35px; "><span
                                                    class="purchaseorder_tot_comm_extracost"></span></span>
                                        </div>
                                        <div class="col-lg-2 col-sm-2 col-12 border">
                                            <span class=""
                                                style="vertical-align: inherit;vertical-align: inherit;font-size: 14px;color:#000;line-height: 35px; "><span
                                                    class="purchaseorder_total_amount"></span></span>
                                        </div>
                                    </div>
                                </div>
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
                                                style="vertical-align: inherit;vertical-align: inherit;font-size: 14px;color:red;font-weight: 700;line-height: 35px; ">Grand
                                                Total</span>
                                        </div>
                                        <div class="col-lg-2 col-sm-2 col-12 border">
                                            <span class=""
                                                style="vertical-align: inherit;vertical-align: inherit;font-size: 14px;color:#000;line-height: 35px; "><span
                                                    class="purchaseorder_grossamont"></span></span>
                                        </div>
                                    </div>
                                </div>
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
                                                style="vertical-align: inherit;vertical-align: inherit;font-size: 14px;color:red;font-weight: 700;line-height: 35px; ">Old
                                                Balance</span>
                                        </div>
                                        <div class="col-lg-2 col-sm-2 col-12 border">
                                            <span class=""
                                                style="vertical-align: inherit;vertical-align: inherit;font-size: 14px;color:#000;line-height: 35px; "><span
                                                    class="purchaseorder_old_balance"></span></span>
                                        </div>
                                    </div>
                                </div>
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
                                                style="vertical-align: inherit;vertical-align: inherit;font-size: 14px;color:red;font-weight: 700;line-height: 35px; ">Grand
                                                Total</span>
                                        </div>
                                        <div class="col-lg-2 col-sm-2 col-12 border">
                                            <span class=""
                                                style="vertical-align: inherit;vertical-align: inherit;font-size: 14px;color:#000;line-height: 35px; "><span
                                                    class="purchaseorder_grand_total"></span></span>
                                        </div>
                                    </div>
                                </div>
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
                                                style="vertical-align: inherit;vertical-align: inherit;font-size: 14px;color:red;font-weight: 700;line-height: 35px; ">Payed
                                                Amount Total</span>
                                        </div>
                                        <div class="col-lg-2 col-sm-2 col-12 border">
                                            <span class=""
                                                style="vertical-align: inherit;vertical-align: inherit;font-size: 14px;color:#000;line-height: 35px; "><span
                                                    class="purchaseorder_paid_amount"></span></span>
                                        </div>
                                    </div>
                                </div>
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
                                                style="vertical-align: inherit;vertical-align: inherit;font-size: 14px;color:red;font-weight: 700;line-height: 35px; ">Pending
                                                Balance Amount</span>
                                        </div>
                                        <div class="col-lg-2 col-sm-2 col-12 border">
                                            <span class=""
                                                style="vertical-align: inherit;vertical-align: inherit;font-size: 14px;color:#000;line-height: 35px; "><span
                                                    class="purchaseorder_balance_amount"></span></span>
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
</div>
