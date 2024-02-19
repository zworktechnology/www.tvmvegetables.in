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
                        <img src="{{ asset('assets/backend/img/spmheader.png') }}" style="margin-top: 5px;">
                        <hr style="margin-top: -8px; background-color : blue">
                    </div>
                    <div style="margin-right: 10px; margin-left: 10px; margin-top: -10px;">
                        <div>
                            <div class="col-12" style="display: flex; font-weight: 900">
                                <div class="col-6">
                                    <p style="text-align: left; margin-bottom: 3px; color: darkblue;">திரு : <span
                                        style="color: darkblue;">{{ $customer_upper }}</span></p>
                                </div>
                                <div class="col-6">
                                    <p style="text-align: right; padding-right: 15px; margin-bottom: 3px; color: darkblue;">தேதி : <span
                                        style="color: darkblue;">{{ date('d-m-Y', strtotime($SalesData->date)) }}</span></p>
                                </div>
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
                                    <td style="padding: 2px;vertical-align: middle;color: green; text-align: left;">
                                        <b>Amount</b>
                                    </td>
                                </tr>
                                @foreach ($SalesProduct_darta as $index => $SalesProduct_darta_arr)
                                    @if ($SalesProduct_darta_arr->sales_id == $SalesData->id)
                                        <tr class="details" style="border-bottom:1px solid lightgray ;">
                                            <td
                                                style="padding: 2px;vertical-align: top; vertical-align: inherit;vertical-align: inherit;color:#000; Padding-left : 20px;">
                                                {{ $SalesProduct_darta_arr->price_per_kg }}
                                            </td>
                                            <td
                                                style="padding: 2px;vertical-align: top; vertical-align: inherit;vertical-align: inherit;color:#000;">
                                                @foreach ($productlist as $products)
                                                    @if ($products->id == $SalesProduct_darta_arr->productlist_id)
                                                        {{ $products->name }}
                                                    @endif
                                                @endforeach
                                            </td>
                                            <td
                                                style="padding: 2px;vertical-align: top; vertical-align: inherit;vertical-align: inherit;color:#000;">
                                                {{ $SalesProduct_darta_arr->count }} -
                                                {{ $SalesProduct_darta_arr->bagorkg }}
                                            </td>
                                            <td
                                                style="padding: 2px;vertical-align: top;vertical-align: inherit;vertical-align: inherit;color:#000;">
                                                {{ $SalesProduct_darta_arr->note }}
                                            </td>
                                            <td
                                                style="padding: 2px;vertical-align: top; vertical-align: inherit;vertical-align: inherit;color:#000; text-align: left;">
                                                {{ $SalesProduct_darta_arr->total_price }}.00
                                            </td>
                                        </tr>
                                    @endif
                                @endforeach
                            </table>
                        </div>
                        <div class="row" style="margin-top: 3px;">
                            <div class="col-5"></div>
                            <div class="col-3">
                                <p style="text-align: left; margin-bottom: 3px; color: black;">கூலி</p>
                                <p style="text-align: left; margin-bottom: 3px; color: green;">பில் தொகை</p>
                                <p style="text-align: left; margin-bottom: 3px; color: red;">முன் பாக்கி</p>
                                <p style="text-align: left; margin-bottom: 3px; color: blue;">மொத்த பாக்கி</p>
                                <p style="text-align: left; margin-bottom: 3px; color: green;">வரவு</p>
                                <p style="text-align: left; margin-bottom: 3px; color: red;">மொத்த பாக்கி</p>
                            </div>
                            <div class="col-1">
                                <p style="text-align: left; margin-bottom: 3px; color: black;">:</p>
                                <p style="text-align: left; margin-bottom: 3px; color: green;">:</p>
                                <p style="text-align: left; margin-bottom: 3px; color: red;">:</p>
                                <p style="text-align: left; margin-bottom: 3px; color: blue;">:</p>
                                <p style="text-align: left; margin-bottom: 3px; color: green;">:</p>
                                <p style="text-align: left; margin-bottom: 3px; color: red;">:</p>
                            </div>
                            <div class="col-3">
                                <p style="text-align: left; margin-bottom: 3px; color: black; border-bottom:1px solid lightgray; text-align: left;">₹ {{ $SalesData->extra_cost }}</p>
                                <p style="text-align: left; margin-bottom: 3px; color: green; text-align: left;">₹ {{ $SalesData->gross_amount }}</p>
                                <p style="text-align: left; margin-bottom: 3px; color: red; border-bottom:1px solid lightgray; text-align: left;">₹ {{ $SalesData->old_balance }}</p>
                                <p style="text-align: left; margin-bottom: 3px; color: blue; text-align: left;">₹ {{ $SalesData->grand_total }}</p>
                                <p style="text-align: left; margin-bottom: 3px; color: green; border-bottom:1px solid lightgray; text-align: left;">₹ {{ $SalesData->paid_amount }}</p>
                                <p style="text-align: left; margin-bottom: 3px; color: red; text-align: left;">₹ {{ $SalesData->balance_amount }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
