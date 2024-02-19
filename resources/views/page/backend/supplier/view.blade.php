@extends('layout.backend.auth')

@section('content')
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h4>Supplier View - <span style="color:red;">{{ $Suppliername }}</span></h4>
            </div>

            @php
                $lastword = Request::url();
                preg_match("/[^\/]+$/", $lastword, $matches);
                $last_word = $matches[0];
                @endphp


            <form autocomplete="off" method="POST" action="{{ route('supplier.viewfilter', ['unique_key' => $unique_key, 'last_word' => $last_word]) }}" enctype="multipart/form-data">
                @method('PUT')
                @csrf
                <div class="page-btn" style="display: flex">

                    <div class="col-lg-4 col-sm-6 col-12" style="margin: 0px 3px;">
                        <div class="form-group">
                            <input type="date" name="fromdate" id="fromdate" value="{{$fromdate}}"
                                style="color:black">
                        </div>
                    </div>
                    <div class="col-lg-4 col-sm-6 col-12" style="margin: 0px 3px;">
                        <div class="form-group">
                            <input type="date" name="todate" id="todate" value="{{$todate}}"
                                style="color:black">
                        </div>
                    </div>
                    <input type="hidden" name="customerid" id="customerid" value="{{ $supplier_id }}" />
                    <input type="hidden" name="uniquekey" id="uniquekey" value="{{ $unique_key }}" />
                    <input type="hidden" name="branchid" id="branchid" value="{{ $last_word }}" />
                    <div class="col-lg-2 col-sm-6 col-12" style="margin: 0px 3px;">
                        <div class="form-group">
                            <input type="submit" class="btn btn-primary" name="submit" value="Search" />
                        </div>
                    </div>

                </div>
            </form>
        </div>
        <div class="row" >
            <div class="col-lg-3 col-sm-6 col-12">
                <div class="dash-widget" style="background: #0f3800;">
                    <div class="dash-widgetcontent">
                        @php $totalamounts = $tot_purchaseAmount + $paymentpurchase_discount; @endphp
                        <h5>₹ <span class="counters" data-count="{{ $totalamounts }}"></span></h5>
                        <h6>Total PurchaseAmount</h6>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-sm-6 col-12">
                <div class="dash-widget dash1" style="background: #92d8a3;">
                    <div class="dash-widgetcontent">
                        <h5>₹ <span class="counters" data-count="{{ $paymentpurchase_discount }}"></span></h5>
                        <h6>Total Discount</h6>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-sm-6 col-12">
                <div class="dash-widget dash2" style="background: #dac88e;">
                    <div class="dash-widgetcontent">
                        <h5>₹ <span class="counters" data-count="{{ $total_amount_paid }}"></span></h5>
                        <h6>Total Payment Received</h6>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-sm-6 col-12">
                <div class="dash-widget dash3" style="background: #d48282;">
                    <div class="dash-widgetcontent">
                        <h5>₹ <span class="counters" data-count="{{ $total_balance }}"></span></h5>
                        <h6>Total Pending Balance</h6>
                    </div>
                </div>
            </div>
        </div>


        <div class="col-lg-12 col-sm-12 col-12" >
                <div class="page-header">
                    <div class="page-title">
                        <h4>Accounting view</h4>
                    </div>
                </div>


                <div class="card">
                    <div class="card-body">
                    @if ($Purchase_data != '')
                            <div class="table-responsive">
                                <table class="table  customerdatanew">
                                    <thead style="background: #5e54c966;">
                                        <tr>
                                        <th>S.No</th>
                                            <th>Date & Time</th>
                                            {{-- <th>Customer</th> --}}
                                            {{-- <th>Branch</th> --}}
                                            <th>Type</th>
                                            <th>Bill No</th>
                                            <th style="">Particulars</th>
                                            <th style="">Debit</th>
                                            <th style="">Credit</th>
                                            <th style="">Discount</th>
                                            <th style="">Total</th>
                                        </tr>
                                    </thead>
                                    <tbody style="background: #f8f9fa;">

                                        @foreach ($Purchase_data as $keydata => $Purchase_datas)
                                            @if ($Purchase_datas['unique_key'] != '')
                                                <tr>
                                                <td>{{ ++$keydata }}</td>
                                                    <td>{{ date('Y-m-d', strtotime($Purchase_datas['date'])) }} - {{ date('h:i A', strtotime($Purchase_datas['time'])) }}</td>
                                                    {{-- <td>{{ $Purchase_datas['supplier_name'] }}</td> --}}
                                                    {{-- <td>{{ $Purchase_datas['branch_name'] }}</td> --}}
                                                    <td>{{ $Purchase_datas['type'] }}</td>
                                                    <td>{{ $Purchase_datas['bill_no'] }}</td>
                                                    <td style="text-transform: uppercase;">
                                                    @if ($Purchase_datas['type'] == 'PURHCASE')
                                                    @foreach ($Purchase_datas['sales_terms'] as $index => $terms_array)
                                                    @if ($terms_array['purchase_id'] == $Purchase_datas['id'])
                                                    {{ $terms_array['product_name'] }} - {{ $terms_array['kgs'] }}{{ $terms_array['bag'] }}-{{ $terms_array['price_per_kg'] }},<br/>
                                                    @endif
                                                    @endforeach
                                                    @endif
                                                    </td>

                                                    <td>{{ $Purchase_datas['gross_amount'] }}</td>

                                                    <td>{{ $Purchase_datas['paid_amount'] }}</td>
                                                    <td>{{ $Purchase_datas['discount'] }}</td>
                                                    <td>{{ $Purchase_datas['balance_amount'] }}</td>
                                                </tr>


                                            @endif
                                        @endforeach


                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>






        </div>
    </div>
@endsection
