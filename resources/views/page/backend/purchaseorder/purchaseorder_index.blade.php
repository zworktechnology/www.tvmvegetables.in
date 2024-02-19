@extends('layout.backend.auth')

@section('content')
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h4>Purchase</h4>
            </div>
            <div class="page-btn">
                <div class="row">
                    <div style="display: flex;">
                        <form autocomplete="off" method="POST" action="{{ route('purchaseorder.purchaseorder_datefilter') }}">
                            @method('PUT')
                            @csrf
                            <div style="display: flex">
                                <div style="margin-right: 10px;"><input type="date" name="from_date"  required
                                        class="form-control from_date" value="{{ $today }}"></div>
                                <div style="margin-right: 10px;"><input type="submit" class="btn btn-success"
                                        value="Search" /></div>
                            </div>
                        </form>
                        <a href="{{ route('purchaseorder.purchaseorder_create') }}" class="btn btn-added" style="margin-right: 10px;">Add
                            Purchase</a>
                    </div>
                </div>
            </div>
        </div>
        @php

            preg_match("/[^\/]+$/", Request::url(), $matches);
        $pos = $matches[0];
        @endphp
        {{-- <div class="row py-2" style="margin-bottom:10px;">
            <div class="col-lg-2 col-sm-4 col-6">
                <a href="{{ route('purchaseorder.purchaseorder_index') }}" style="color: black">
                    <div class="dash-widget" @if ($pos == "purchase")
                    style="border-color:red; background-color: red; margin-bottom:18px;"
                    @endif>
                        <div class="dash-widgetcontent">
                            <h6 @if ($pos == "purchase") style="font-weight: bold; color:white" @endif>All</h6>
                        </div>
                    </div>
                </a>
            </div>
                            @php
                            $lastword = Request::url();
                            preg_match("/[^\/]+$/", $lastword, $matches);
                            $last_word = $matches[0];
                            @endphp
            @foreach ($allbranch as $keydata => $allbranches)

                <div class="col-lg-2 col-sm-4 col-6">
                    <a href="/purchaseorder_branchdata/{{$today}}/{{ $allbranches->id }}">
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




        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table  customerdatanew">
                        <thead>
                            <tr>
                                {{-- <th>Date</th> --}}
                                <th>Bill No</th>
                                <th>Supplier</th>
                                {{-- <th>Branch</th> --}}
                                <th>Product Details</th>
                                <th>Total</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($purchase_data as $keydata => $purchasedata)
                                <tr>
                                    {{-- <td>{{ date('d-m-Y', strtotime($purchasedata['date'])) }}</td> --}}
                                    <td>#{{ $purchasedata['bill_no'] }}</td>
                                    <td>{{ $purchasedata['supplier_name'] }}</td>
                                    {{-- <td>{{ $purchasedata['branch_name'] }}</td> --}}
                                    <td style="text-transform: uppercase;">
                                    @foreach ($purchasedata['terms'] as $index => $terms_array)
                                                    @if ($terms_array['purchase_id'] == $purchasedata['id'])
                                                    {{ $terms_array['product_name'] }} - {{ $terms_array['kgs'] }} {{ $terms_array['bag'] }} x ₹ {{ $terms_array['price_per_kg'] }}<br/>
                                                    @endif
                                                    @endforeach
                                    </td>
                                    <td>₹ {{ $purchasedata['gross_amount'] }}</td>
                                    <td>
                                        <ul class="list-unstyled hstack gap-1 mb-0">


                                                <li>
                                                    <a href="{{ route('purchaseorder.purchaseorder_invoiceedit', ['unique_key' => $purchasedata['unique_key']]) }}"
                                                        class="badges bg-lightyellow" style="color: white">Edit</a>
                                                </li>
                                            <li>
                                                <a href="#purchaseorderview{{ $purchasedata['unique_key'] }}"
                                                    data-bs-toggle="modal" data-id="{{ $purchasedata['id'] }}"
                                                    data-bs-target=".purchaseorderview-modal-xl{{ $purchasedata['unique_key'] }}"
                                                    class="badges bg-lightred purchaseorderview" style="color: white">View</a>

                                            </li>

                                            <li>


                                                 <a href="{{ route('purchaseorder.purchaseorder_printview', ['unique_key' => $purchasedata['unique_key']]) }}"
                                                        class="badges bg-green" style="color: white">Invoice</a>
                                            </li>

                                        </ul>
                                    </td>
                                </tr>

                                <div class="modal fade purchaseorderview-modal-xl{{ $purchasedata['unique_key'] }}"
                                    tabindex="-1" role="dialog" data-bs-backdrop="static"
                                    aria-labelledby="purchaseorderviewLargeModalLabel{{ $purchasedata['unique_key'] }}"
                                    aria-hidden="true">
                                    @include('page.backend.purchaseorder.purchaseorder_view')
                                </div>


                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>




    </div>
@endsection
