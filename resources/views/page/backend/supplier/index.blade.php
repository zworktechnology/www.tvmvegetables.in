@extends('layout.backend.auth')

@section('content')
<style>
</style>
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
                <h4>Supplier</h4>
            </div>
            <div class="page-btn">
                <div style="display:flex;">

                    <input type="button" style="margin-right:10px" class="btn btn-lightgreen waves-effect waves-light btn-added badges bg-green" id="viewsuppliertotal" value="View Total">

                    <button type="button" style="margin-right:10px" class="btn btn-primary waves-effect waves-light btn-added" data-bs-toggle="modal"
                        data-bs-target=".supplier-modal-xl">Add Supplier</button>

                    @if ($last_word != "supplier")
                    <a href="/supplierpdf_export/{{$last_word}}" class="badges bg-lightgrey btn btn-added">Pdf Export</a>
                    @else
                    <a href="/supplierallpdf_export" class="badges bg-lightgrey btn btn-added">Pdf Export</a>
                    @endif
                </div>
            </div>
        </div>



        {{-- <div class="row py-2" style="margin-bottom:10px;">
            <div class="col-lg-2 col-sm-4 col-6">
                <a href="{{ route('supplier.index') }}" style="color: black">
                    <div class="dash-widget" @if ($pos == "supplier")
                    style="border-color:red; background-color: red; margin-bottom:18px;"
                    @endif>
                        <div class="dash-widgetcontent">
                            <h6 @if ($pos == "supplier") style="font-weight: bold; color:white" @endif>All</h6>
                        </div>
                    </div>
                </a>
            </div>

            @foreach ($allbranch as $keydata => $allbranches)
            <input type="hidden" name="branchwise_id" id="branchwise_id" value="{{$last_word}}" />

                <div class="col-lg-2 col-sm-4 col-6">
                    <a href="{{ route('supplier.branchdata', ['branch_id' => $allbranches->id]) }}">
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

        <div class="row" style="display:none" id="suppliertotaldiv">
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
                        <h5>₹ <span class="counters" data-count="{{ $TotalPurchase }}"></span></h5>
                        <h6>Total Purchase Value</h6>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-sm-6 col-12">
                <div class="dash-widget dash2">
                    <div class="dash-widgetcontent">
                        <h5>₹ <span class="counters" data-count="{{$totalamount_paid}}"></span></h5>
                        <h6>Total Paid Value</h6>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-sm-6 col-12">
                <div class="dash-widget dash3">
                    <div class="dash-widgetcontent">
                        <h5>₹ <span class="counters" data-count="{{$totalbalance }}"></span></h5>
                        <h6>Total Balance Value</h6>
                    </div>
                </div>
            </div>
        </div>


        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table  supplierdatanew">
                        <thead>
                            <tr>
                                <th>Sl. No</th>
                                <th>Name</th>
                                <th>Total Purchase</th>
                                <th>Total Discount</th>
                                <th>Total Paid</th>
                                <th>Total Balance</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($supplierarr_data as $keydata => $suppliertdata)
                                <tr>
                                    <td>{{ ++$keydata }}</td>
                                    <td>{{ $suppliertdata['name'] }}</td>
                                    <td>₹ {{ $suppliertdata['total_purchase_amt'] }}</td>
                                    <td>₹ {{ $suppliertdata['total_discount_amont'] }}</td>
                                    <td>₹ {{ $suppliertdata['total_paid'] }}</td>
                                    <td>₹ {{ $suppliertdata['balance_amount'] }}</td>
                                    <td>
                                        <ul class="list-unstyled hstack gap-1 mb-0">
                                            <li>
                                                <a href="#edit{{ $suppliertdata['unique_key'] }}" data-bs-toggle="modal"
                                                    data-id="{{ $suppliertdata['unique_key'] }}"
                                                    data-bs-target=".supplieredit-modal-xl{{ $suppliertdata['unique_key'] }}" class="badges bg-lightyellow" style="color: white">Edit</a>
                                            </li><br/>
                                            <li>
                                                <a href="#delete{{ $suppliertdata['unique_key'] }}" data-bs-toggle="modal"
                                                    data-id="{{ $suppliertdata['unique_key'] }}"
                                                    data-bs-target=".supplierdelete-modal-xl{{ $suppliertdata['unique_key'] }}" class="badges bg-lightgrey" style="color: white">Delete</a>
                                            </li>
                                            {{-- <li>
                                                <a href="#checkbalance{{ $suppliertdata['unique_key'] }}" data-bs-toggle="modal"
                                                    data-id="{{ $suppliertdata['id'] }}"
                                                    data-bs-target=".checkbalance-modal-xl{{ $suppliertdata['unique_key'] }}" class="badges bg-lightred checkbalance" style="color: white">Check Balance</a>
                                            </li> --}}
                                            <li>
                                                <a href="/supplierview/{{ $suppliertdata['unique_key'] }}/{{$last_word}}"
                                                class="badges bg-lightgreen" style="color: white">View</a>
                                            </li>
                                            @if ($suppliertdata['LastPattityal'] != "")
                                            <li>
                                                <a class="badges bg-lightred" style="color: white">{{ date('d-m-Y', strtotime($suppliertdata['LastPattityal'])) }}</a>
                                            </li>
                                            @endif
                                        </ul>
                                    </td>

                                </tr>

                                <div class="modal fade supplieredit-modal-xl{{ $suppliertdata['unique_key'] }}"
                                    tabindex="-1" role="dialog" data-bs-backdrop="static"
                                    aria-labelledby="suppliereditLargeModalLabel{{ $suppliertdata['unique_key'] }}"
                                    aria-hidden="true">
                                    @include('page.backend.supplier.edit')
                                </div>

                                <div class="modal fade checkbalance-modal-xl{{ $suppliertdata['unique_key'] }}"
                                    tabindex="-1" role="dialog" data-bs-backdrop="static"
                                    aria-labelledby="checkbalanceLargeModalLabel{{ $suppliertdata['unique_key'] }}"
                                    aria-hidden="true">
                                    @include('page.backend.supplier.checkbalance')
                                </div>


                                <div class="modal fade supplierdelete-modal-xl{{ $suppliertdata['unique_key'] }}"
                                    tabindex="-1" role="dialog" data-bs-backdrop="static"
                                    aria-labelledby="supplierdeleteLargeModalLabel{{ $suppliertdata['unique_key'] }}"
                                    aria-hidden="true">
                                    @include('page.backend.supplier.delete')
                                </div>


                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="modal fade supplier-modal-xl" tabindex="-1" role="dialog" aria-labelledby="supplierLargeModalLabel"
            aria-hidden="true">
            @include('page.backend.supplier.create')
        </div>

    </div>
@endsection



