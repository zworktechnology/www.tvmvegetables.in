<div class="modal-dialog modal-l">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="checkbalanceLargeModalLabel">Balance Report - {{ $suppliertdata['name'] }}</h5>
        </div>
        <div class="modal-body">
            <div class="row">

                <div class="col-lg-6 col-sm-6 col-6 border"
                    style="vertical-align: inherit;vertical-align: inherit;font-size: 16px;color:red;font-weight: 600;line-height: 35px; ">
                    Branch</div>
                <div class="col-lg-6 col-sm-6 col-6 border"
                    style="vertical-align: inherit;vertical-align: inherit;font-size: 16px;color:red;font-weight: 600;line-height: 35px; ">
                    Balance Amount</div>
            </div>
            <div class="row">
                @php
                    $total = 0;
                @endphp
                @foreach ($tot_balance_Arr as $keydata => $tot_balance_Array)
                    @if ($tot_balance_Array['Supplier_id'] == $suppliertdata['id'])
                        @php
                            $total += $tot_balance_Array['balance_amount'];
                        @endphp

                        <div class="col-lg-6 col-sm-6 col-6 border"
                            style="vertical-align: inherit;vertical-align: inherit;font-size: 14px;color:#000;font-weight: 400;line-height: 35px; ">
                            <span class="">{{ $tot_balance_Array['branch_name'] }}</span>
                        </div>
                        <div class="col-lg-6 col-sm-6 col-6 border"
                            style="vertical-align: inherit;vertical-align: inherit;font-size: 14px;color:#000;font-weight: 400;line-height: 35px; ">
                            ₹ <span class="">{{ $tot_balance_Array['balance_amount'] }}</span></div>
                    @endif
                @endforeach
            </div>
            <div class="row">

                <div class="col-lg-6 col-sm-6 col-6 border"
                    style="vertical-align: inherit;vertical-align: inherit;font-size: 16px;color:red;font-weight: 600;line-height: 35px; ">
                    Total</div>
                <div class="col-lg-6 col-sm-6 col-6 border"
                    style="vertical-align: inherit;vertical-align: inherit;font-size: 16px;color:red;font-weight: 600;line-height: 35px; ">
                    ₹ <span class="supplier_totblance">{{ $total }}</span></div>
            </div>
            <hr>
            <div class="col-lg-12 button-align">
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal" aria-label="Close">Cancel</button>
            </div>
        </div>
    </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->
