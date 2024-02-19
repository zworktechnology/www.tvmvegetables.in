
@extends('layout.backend.auth')

@section('content')
    <div class="content">


                @php

            preg_match("/[^\/]+$/", Request::url(), $matches);
            $pos = $matches[0];
            @endphp


                @php
                $lastword = Request::url();
                preg_match("/[^\/]+$/", $lastword, $matches);
                $last_word = $matches[0];
                @endphp

        <div class="page-header">
            <div class="page-title">
                <h4>Customer</h4>
            </div>
            <div class="page-btn">
                <div style="display:flex;">

                    <input type="button" style="margin-right:10px" class="btn btn-lightgreen waves-effect waves-light btn-added badges bg-green" id="viewtotal" value="View Total">

                    <button type="button" style="margin-right:10px" class="btn btn-primary waves-effect waves-light btn-added" data-bs-toggle="modal"
                        data-bs-target=".cusomer-modal-xl">Add Customer</button>

                        @if ($last_word != "customer")
                        <a href="/customer_pdf_export/{{$last_word}}" class="badges bg-lightgrey btn btn-added">Pdf Export</a>
                        @else
                        <a href="/allcustomer_pdf_export" class="badges bg-lightgrey btn btn-added">Pdf Export</a>
                        @endif


                </div>
            </div>
        </div>


        {{-- <div class="row py-2" style="margin-bottom:10px;">
            <div class="col-lg-2 col-sm-4 col-6">
                <a href="{{ route('customer.index') }}" style="color: black">
                    <div class="dash-widget" @if ($pos == "customer")
                    style="border-color:red; background-color: red; margin-bottom:18px;"
                    @endif>
                        <div class="dash-widgetcontent">
                            <h6 @if ($pos == "customer") style="font-weight: bold; color:white" @endif>All</h6>
                        </div>
                    </div>
                </a>
            </div>

            @foreach ($allbranch as $keydata => $allbranches)

                <div class="col-lg-2 col-sm-4 col-6">
                    <a href="{{ route('customer.branchdata', ['branch_id' => $allbranches->id]) }}">
                        <div class="dash-widget " @if ($last_word == $allbranches->id)
                    style="border-color:red; background-color: red;"
                    @endif >
                            <div class="dash-widgetcontent">
                                <h6 @if ($last_word == $allbranches->id) style="font-weight: bold; color:white" @endif>{{ $allbranches->shop_name }}</h6>
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach
        </div> --}}

        <div class="row" style="display:none" id="totaldiv">
            <div class="col-lg-3 col-sm-6 col-12">
                <div class="dash-widget">
                    <div class="dash-widgetcontent">
                        <h5>Summary</h5>
                        <h6 style="opacity: 0%;">Summary</h6>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-sm-6 col-12">
                <div class="dash-widget dash1">
                    <div class="dash-widgetcontent">
                        <h5>₹ <span class="counters" data-count="{{ $TotalSale }}"></span></h5>
                        <h6>Total Sales Value</h6>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-sm-6 col-12">
                <div class="dash-widget dash2">
                    <div class="dash-widgetcontent">
                        <h5>₹ <span class="counters" data-count="{{$total_saleamount_paid}}"></span></h5>
                        <h6>Total Paid Value</h6>
                    </div>
                </div>
            </div>
            @php
            $total = $TotalSale - $total_saleamount_paid;
            @endphp
            <div class="col-lg-3 col-sm-6 col-12">
                <div class="dash-widget dash3">
                    <div class="dash-widgetcontent">
                        <h5>₹ <span class="counters" data-count="{{$total }}"></span></h5>
                        <h6>Total Balance Value</h6>
                    </div>
                </div>
            </div>
        </div>



        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table  customerdatanew">
                        <thead>
                            <tr>
                                <th>Sl. No</th>
                                <th>Name</th>
                                <th>Total Sale</th>
                                <th>Total Discount</th>
                                <th>Total Paid</th>
                                <th>Total Balance</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($customerarr_data as $keydata => $customertdata)
                                <tr>
                                    <td>{{ ++$keydata }}</td>
                                    <td>{{ $customertdata['name'] }}</td>
                                    <td>₹ {{ $customertdata['total_sale_amt'] }}</td>
                                    <td>₹ {{ $customertdata['totpayment_discount'] }}</td>
                                    <td>₹ {{ $customertdata['total_paid'] }}</td>
                                    <td>₹ {{ $customertdata['balance_amount'] }}</td>
                                    <td>
                                        <ul class="list-unstyled hstack gap-1 mb-0">
                                            <li>
                                                <a href="#edit{{ $customertdata['unique_key'] }}" data-bs-toggle="modal"
                                                    data-id="{{ $customertdata['unique_key'] }}"
                                                    data-bs-target=".cusomeredit-modal-xl{{ $customertdata['unique_key'] }}" class="badges bg-lightyellow" style="color: white">Edit</a>
                                            </li>
                                            <li>
                                                <a href="#delete{{ $customertdata['unique_key'] }}" data-bs-toggle="modal"
                                                    data-id="{{ $customertdata['unique_key'] }}"
                                                    data-bs-target=".cusomerdelete-modal-xl{{ $customertdata['unique_key'] }}" class="badges bg-lightgrey" style="color: white">Delete</a>
                                            </li>

                                            {{-- <li>
                                                <a href="#customercheckbalance{{ $customertdata['unique_key'] }}" data-bs-toggle="modal"
                                                    data-id="{{ $customertdata['id'] }}"
                                                    data-bs-target=".customercheckbalance-modal-xl{{ $customertdata['unique_key'] }}" class="badges bg-lightred customercheckbalance" style="color: white">Check Balance</a>
                                            </li> --}}
                                            <li>
                                                <a href="/customerview/{{ $customertdata['unique_key'] }}/{{$last_word}}"
                                                class="badges bg-lightgreen" style="color: white">View</a>
                                            </li>
                                        </ul>
                                    </td>
                                </tr>

                                <div class="modal fade cusomeredit-modal-xl{{ $customertdata['unique_key'] }}" tabindex="-1"
                                    role="dialog" data-bs-backdrop="static" aria-labelledby="customereditLargeModalLabel{{ $customertdata['unique_key'] }}"
                                    aria-hidden="true">
                                    @include('page.backend.customer.edit')
                                </div>


                                <div class="modal fade customercheckbalance-modal-xl{{ $customertdata['unique_key'] }}" tabindex="-1"
                                    role="dialog" data-bs-backdrop="static" aria-labelledby="customercheckbalanceLargeModalLabel{{ $customertdata['unique_key'] }}"
                                    aria-hidden="true">
                                    @include('page.backend.customer.checkbalance')
                                </div>

                                <div class="modal fade cusomerdelete-modal-xl{{ $customertdata['unique_key'] }}"
                                    tabindex="-1" role="dialog"data-bs-backdrop="static"
                                    aria-labelledby="customerdeleteLargeModalLabel{{ $customertdata['unique_key'] }}"
                                    aria-hidden="true">
                                    @include('page.backend.customer.delete')
                                </div>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="modal fade cusomer-modal-xl" tabindex="-1" role="dialog" aria-labelledby="customerLargeModalLabel"
            aria-hidden="true" data-bs-backdrop="static">
            @include('page.backend.customer.create')
        </div>


    </div>
@endsection
