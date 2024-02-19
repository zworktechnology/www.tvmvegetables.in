@extends('layout.backend.auth')

@section('content')
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h4>Print Area View</h4>
            </div>
            <div style="display: flex;">
                <button onclick="printDiv('printableArea')" class="btn btn-success me-2"><i class="fa fa-print"></i>
                    Print</button>
            </div>
        </div>


        <div class="content">
            <div id="printableArea" style="width:148mm; height:210mm">
                <div>
                    <div>
                        <img src="{{ asset('assets/backend/img/spmheader1.png') }}" style="margin-top: 5px;">
                        <hr style="margin-top: -1px; background-color : blue">
                    </div>
                    <div style="margin-right: 10px; margin-left: 10px; margin-top: -10px;">
                        <div>
                            <p style="font-weight: 900; text-align: left; margin-bottom: 3px; color: darkblue;">Bill No : <span
                                style="color: darkblue;">{{ $PurchaseData->bill_no }}</span></p>
                            <div class="col-12" style="display: flex; font-weight: 900">
                                <div class="col-6">
                                    <p style="text-align: left; margin-bottom: 3px; color: darkblue;">திரு : <span
                                            style="color: darkblue;">{{ $supplier_upper }}</span></p>
                                </div>
                                <div class="col-6">
                                    <p style="text-align: right; padding-right: 15px;  margin-bottom: 3px; color: darkblue;">தேதி : <span
                                            style="color: darkblue;">{{ date('d-m-Y', strtotime($PurchaseData->date)) }}</span>
                                    </p>
                                </div>
                            </div>
                            <div style="margin-top: 5px;">
                                <table style="line-height: inherit;text-align: left;overflow: auto; width:100%;">
                                    <tr class="heading "
                                        style="background:#eee; border-bottom: 1px solid lightgray ; border-top: 1px solid lightgray ;">
                                        <td style="padding: 2px;vertical-align: middle;color: green; Padding-left : 20px;">
                                            <b>Rate</b>
                                        </td>
                                        <td style="padding: 2px;vertical-align: middle;color: green;">
                                            <b>Particulars</b>
                                        </td>
                                        <td style="padding: 2px;vertical-align: middle;color: green;">
                                            <b>Count</b>
                                        </td>
                                        <td style="padding: 2px;vertical-align: middle;color: green;">
                                            <b>Note</b>
                                        </td>
                                        <td
                                            style="padding: 2px;vertical-align: middle;color: green; text-align: left; padding-right: 15px;">
                                            <b>Amount</b>
                                        </td>
                                    </tr>
                                    @foreach ($PurchaseProducts as $index => $PurchaseProducts_array)
                                        @if ($PurchaseProducts_array->purchase_id == $PurchaseData->id)
                                            <tr class="details" style="border-bottom:1px solid lightgray;">
                                                <td
                                                    style="padding: 2px;vertical-align: top; vertical-align: inherit;vertical-align: inherit;color:#000; Padding-left : 20px;">
                                                    {{ $PurchaseProducts_array->price_per_kg }}
                                                </td>
                                                <td
                                                    style="padding: 2px;vertical-align: top; vertical-align: inherit;vertical-align: inherit;color:#000;">
                                                    @foreach ($productlist as $products)
                                                        @if ($products->id == $PurchaseProducts_array->productlist_id)
                                                            {{ $products->name }}
                                                        @endif
                                                    @endforeach
                                                </td>
                                                <td
                                                    style="padding: 2px;vertical-align: top; vertical-align: inherit;vertical-align: inherit;color:#000;">
                                                    {{ $PurchaseProducts_array->count }} -
                                                    {{ $PurchaseProducts_array->bagorkg }}
                                                </td>
                                                <td
                                                    style="padding: 2px;vertical-align: top;vertical-align: inherit;vertical-align: inherit;color:#000;">
                                                    {{ $PurchaseProducts_array->note }}
                                                </td>
                                                <td
                                                    style="padding: 2px;vertical-align: top; vertical-align: inherit;vertical-align: inherit;color:#000; text-align: left; padding-right: 15px;">
                                                    {{ $PurchaseProducts_array->total_price }}.00
                                                </td>
                                            </tr>
                                        @endif
                                    @endforeach
                                </table>
                            </div>
                            <div class="row" style="margin-top: 3px; margin-top: 70mm;">
                                <p style="text-align: left; margin-bottom: 3px; color: darkblue; Padding-left : 20px;">Extra
                                    Cost</p>
                                <div class="col-12" style="display: flex;">
                                    <div class="col-6">
                                        <div style="display: flex">
                                            <div class="col-6">
                                                <p
                                                    style="text-align: left; margin-bottom: 3px; color: black; Padding-left : 10px;">
                                                    COMMISSION</p>
                                            </div>
                                            <div class="col-2">
                                                <p style="text-align: left; margin-bottom: 3px; color: black;">:</p>
                                            </div>
                                            <div class="col-4">
                                                <p
                                                    style="text-align: left; margin-bottom: 3px; color: black; padding-right : 2px; padding-right: 20px;">
                                                    ₹ {{ $PurchaseData->commission_amount }}</p>
                                            </div>
                                        </div>
                                        @foreach ($PurchaseExtracosts as $index => $PurchaseExtracosts_arr)
                                            <div style="display: flex">
                                                <div class="col-6">
                                                    <p
                                                        style="text-align: left; margin-bottom: 3px; color: black; Padding-left : 10px;">
                                                        {{ $PurchaseExtracosts_arr->extracost_note }}</p>
                                                </div>
                                                <div class="col-2">
                                                    <p style="text-align: left; margin-bottom: 3px; color: black;">:</p>
                                                </div>
                                                <div class="col-4">
                                                    <p
                                                        style="text-align: left; margin-bottom: 3px; color: black; padding-right : 2px; padding-right: 20px;">
                                                        ₹ {{ $PurchaseExtracosts_arr->extracost }}</p>
                                                </div>
                                            </div>
                                        @endforeach
                                        <div style="display: flex">
                                            <div class="col-6">
                                                <p
                                                    style="text-align: left; margin-bottom: 3px; color: red; Padding-left : 10px;">
                                                    Total</p>
                                            </div>
                                            <div class="col-2">
                                                <p style="text-align: left; margin-bottom: 3px; color: red;">:</p>
                                            </div>
                                            <div class="col-4">
                                                <p
                                                    style="text-align: left; margin-bottom: 3px; color: red; padding-right : 2px; padding-right: 20px;">
                                                    ₹ {{ $PurchaseData->tot_comm_extracost }}</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-6" style="display: flex;">
                                        <div class="col-6">
                                            <p
                                                style="text-align: left; margin-bottom: 3px; color: green; border-left:1px solid black; padding-left : 2px;">
                                                GROSS AMOUNT</p>
                                            <p
                                                style="text-align: left; margin-bottom: 3px; color: black; border-left:1px solid black; padding-left : 2px;">
                                                EXPENSES</p>
                                            <p
                                                style="text-align: left; margin-bottom: 3px; color: transparent; border-left:1px solid black; padding-left : 2px;">
                                                Total</p>
                                            <p
                                                style="text-align: left; margin-bottom: 3px; color: red; border-left:1px solid black; padding-left : 2px;">
                                                OLD BALANCE</p>
                                            <p
                                                style="text-align: left; margin-bottom: 3px; color: transparent ; border-left:1px solid black; padding-left : 2px;">
                                                Total</p>
                                        </div>
                                        <div class="col-1">
                                            <p style="text-align: left; margin-bottom: 3px; color: green;">:</p>
                                            <p style="text-align: left; margin-bottom: 3px; color: black;">:</p>
                                            <p style="text-align: left; margin-bottom: 3px; color: blue;">:</p>
                                            <p style="text-align: left; margin-bottom: 3px; color: red;">:</p>
                                            <p style="text-align: left; margin-bottom: 3px; color: blue;">:</p>
                                        </div>
                                        <div class="col-5">
                                            <p
                                                style="text-align: left; margin-bottom: 3px; color: green; padding-right: 15px;">
                                                ₹ {{ $PurchaseData->total_amount }}</p>
                                            <p
                                                style="text-align: left; margin-bottom: 3px; color: black;  border-bottom:1px solid lightgray; padding-right: 15px;">
                                                ₹ {{ $PurchaseData->tot_comm_extracost }}</p>
                                            <p
                                                style="text-align: left; margin-bottom: 3px; color: blue; padding-right: 15px;">
                                                ₹ {{ $PurchaseData->gross_amount }}</p>
                                            <p
                                                style="text-align: left; margin-bottom: 3px; color: red; border-bottom:1px solid lightgray; padding-right: 15px;">
                                                ₹ {{ $PurchaseData->old_balance }}.00</p>
                                            <p
                                                style="text-align: left; margin-bottom: 3px; color: blue;padding-right: 15px;">
                                                ₹ {{ $PurchaseData->grand_total }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
