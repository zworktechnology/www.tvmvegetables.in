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
                        <form autocomplete="off" method="POST" action="{{ route('purchase.datefilter') }}">
                            @method('PUT')
                            @csrf
                            <div style="display: flex">
                                <div style="margin-right: 10px;"><input type="date" name="from_date"  required
                                        class="form-control from_date" value="{{ $today }}"></div>
                                <div style="margin-right: 10px;"><input type="submit" class="btn btn-success"
                                        value="Search" /></div>
                            </div>
                        </form>
                        <a href="{{ route('purchase.create') }}" class="btn btn-added" style="margin-right: 10px;background-color: #9571e7;">Add
                            Purchase</a>
                    </div>
                </div>
            </div>
        </div>
        @php

            preg_match("/[^\/]+$/", Request::url(), $matches);
        $pos = $matches[0];
        @endphp
        <div class="row py-2" style="margin-bottom:10px;">
            <div class="col-lg-2 col-sm-4 col-6">
                <a href="{{ route('purchase.index') }}" style="color: black">
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
                    <a href="/purchase_branchdata/{{$today}}/{{ $allbranches->id }}">
                        <div class="dash-widget " @if ($last_word == $allbranches->id)
                    style="border-color:red; background-color: red;"
                    @endif >
                            <div class="dash-widgetcontent">
                                <h6 @if ($last_word == $allbranches->id) style="font-weight: bold; color:white" @endif>{{ $allbranches->shop_name }}</h6>
                            </div>
                        </div>
                    </a>
                    <a href="#todaystock{{ $allbranches->id }}" data-bs-toggle="modal"data-id="{{ $allbranches->id }}"
                            data-bs-target=".todaystock-modal-xl{{ $allbranches->id }}" class="btn btn-added " style="color:white;background-color: #9571e7;font-size: 13px;font-weight: 600;">Current Details</a>

                            <div class="modal fade todaystock-modal-xl{{ $allbranches->id }}" tabindex="-1"role="dialog" data-bs-backdrop="static"
                                aria-labelledby="todaystockLargeModalLabel{{ $allbranches->id }}"aria-hidden="true">
                                @include('page.backend.purchase.todaystock')
                            </div>
                </div>
            @endforeach
        </div>




        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table  customerdatanew">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Bill No</th>
                                <th>Supplier</th>
                                <th>Branch</th>
                                <th>Product Details</th>
                                <th>Total</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($purchase_data as $keydata => $purchasedata)
                                <tr>
                                    <td>{{ date('d-m-Y', strtotime($purchasedata['date'])) }}</td>
                                    <td>#{{ $purchasedata['bill_no'] }}</td>
                                    <td>{{ $purchasedata['supplier_name'] }}</td>
                                    <td>{{ $purchasedata['branch_name'] }}</td>
                                    <td style="text-transform: uppercase;">
                                    @foreach ($purchasedata['terms'] as $index => $terms_array)
                                                    @if ($terms_array['purchase_id'] == $purchasedata['id'])
                                                    {{ $terms_array['product_name'] }} - {{ $terms_array['kgs'] }}{{ $terms_array['bag'] }},<br/>
                                                    @endif
                                                    @endforeach
                                    </td>
                                    <td>{{ $purchasedata['gross_amount'] }}</td>
                                    <td>
                                        <ul class="list-unstyled hstack gap-1 mb-0">
                                            @if ($purchasedata['status'] == 0)
                                                <li>
                                                    <a href="{{ route('purchase.edit', ['unique_key' => $purchasedata['unique_key']]) }}"
                                                        class="badges bg-lightyellow" style="color: white">Edit</a>
                                                </li>
                                            @endif
                                            @if ($purchasedata['status'] == 1)
                                            @if ($purchasedata['date'] == $today_date)
                                                <li>
                                                    <a href="{{ route('purchase.invoiceedit', ['unique_key' => $purchasedata['unique_key']]) }}"
                                                        class="badges bg-lightyellow" style="color: white">Edit</a>
                                                </li>
                                                @endif
                                            @endif
                                            <li>
                                                <a href="#purchaseview{{ $purchasedata['unique_key'] }}"
                                                    data-bs-toggle="modal" data-id="{{ $purchasedata['id'] }}"
                                                    data-bs-target=".purchaseview-modal-xl{{ $purchasedata['unique_key'] }}"
                                                    class="badges bg-lightred purchaseview" style="color: white">View</a>

                                            </li>


                                                @if ($purchasedata['status'] == 0)
                                                        <li>

                                                                <a href="{{ route('purchase.invoice', ['unique_key' => $purchasedata['unique_key']]) }}"
                                                                    class="badges bg-lightgreen purchase_pattiyal" style="color: white" data-id="{{ $purchasedata['unique_key'] }}">
                                                                    Pattial</a>
                                                        </li>



                                                @elseif ($purchasedata['status'] == 1)
                                                    <li>   <a href="{{ route('purchase.print_view', ['unique_key' => $purchasedata['unique_key']]) }}"
                                                        class="badges bg-green" style="color: white">Invoice</a></li>
                                                @endif

                                                <li>
                                                    <a href="#delete{{ $purchasedata['unique_key'] }}" data-bs-toggle="modal"
                                                        data-id="{{ $purchasedata['unique_key'] }}"
                                                        data-bs-target=".purchasedelete-modal-xl{{ $purchasedata['unique_key'] }}"
                                                        class="badges bg-lightgrey" style="color: white">Delete</a>
                                                </li>

                                        </ul>
                                    </td>
                                </tr>

                                <div class="modal fade purchaseview-modal-xl{{ $purchasedata['unique_key'] }}"
                                    tabindex="-1" role="dialog" data-bs-backdrop="static"
                                    aria-labelledby="purchaseviewLargeModalLabel{{ $purchasedata['unique_key'] }}"
                                    aria-hidden="true">
                                    @include('page.backend.purchase.view')
                                </div>

                                <div class="modal fade purchasedelete-modal-xl{{ $purchasedata['unique_key'] }}"
                                    tabindex="-1" role="dialog"
                                    aria-labelledby="purchasedeleteLargeModalLabel{{ $purchasedata['unique_key'] }}"
                                    aria-hidden="true">
                                    @include('page.backend.purchase.delete')
                                </div>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>




    </div>
@endsection
