@extends('layout.backend.auth')

@section('content')

<div class="content">
        <div class="page-header">
            <div class="page-title">
                <h4>Customer - Print Area View</h4>
            </div>
            <div style="display: flex;">
                <button onclick="printDiv('customerprintableArea')" class="btn btn-success me-2"><i class="fa fa-print"></i>
                    Print</button>
            </div>
        </div>
        

        <div class="content">
            <div id="customerprintableArea" >
                <div >


                     <div class="row" >
                        <div class="logoname">
                           <div>
                                 <h4  style="color:green;text-align:center;">Customer Balance Report</h4>
                           </div>
                        </div>
                        <div class="row" style="margin-top: 1%;">
                           <div class="col-lg-4 col-sm-4 col-12 d-flex">
                                 <div class="dash-count" style="background-color: #0f3800;">
                                    <div class="dash-counts">
                                       <h4>Rs. {{ $TotalSale }}</h4>
                                       <h5>Total Sale </h5>
                                    </div>
                                 </div>
                           </div>
                           <div class="col-lg-4 col-sm-4 col-12 d-flex">
                                 <div class="dash-count das1" style="background-color: #92d8a3;">
                                    <div class="dash-counts">
                                       <h4>Rs. {{ $total_saleamount_paid }}</h4>
                                       <h5>Total Paid</h5>
                                    </div>
                                 </div>
                           </div>
                           <div class="col-lg-4 col-sm-4 col-12 d-flex">
                                 <div class="dash-count das2" style="background-color: #d48282;">
                                    <div class="dash-counts">
                                       <h4>Rs. {{ $saletotal_balance }}</h4>
                                       <h5>Total Pending</h5>
                                    </div>
                                 </div>
                           </div>
                        </div>

                    </div>
                        <table  class="table border"  id="customers" style="margin-top:10px;">
                           <thead class="border" style="background: #eee;  ">
                                 <tr>
                                    <th class="border" style="color:black;">Sl. No</th>
                                    <th class="border" style="color:black;">Customer</th>
                                    <th class="border" style="color:black;">Balance</th>
                                 </tr>
                           </thead>
                           <tbody id="customer_index">
                                 @foreach ($customerarr_data as $keydata => $outputs)
                                 <tr>
                                    <td class="border" style="color:#424951;">{{ ++$keydata }}</td>
                                    <td class="border" style="font-size: 14px;color:#424951;">{{ $outputs['name'] }}</td>
                                    <td class="border" style="font-size: 14px;color:#424951;">{{ $outputs['balance_amount'] }}</td>
                                 </tr>
                                 @endforeach
                           </tbody>
                        </table>
                     </div>
                </div>
            </div>
        </div>
    </div>
@endsection
