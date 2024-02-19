@extends('layout.backend.auth')

@section('content')
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h4>Purchase</h4>
            </div>
            <div class="row">
                <div style="display: flex;">
                    <form autocomplete="off" method="POST" action="{{ route('purchase.datefilter') }}">
                        @method('PUT')
                        @csrf
                        <div style="display: flex">
                            <div style="margin-right: 10px;"><input type="date" name="from_date" required class="form-control from_date"
                                    value="{{ $from_date }}"></div>
                            <div style="margin-right: 10px;"><input type="submit" class="btn btn-success" value="Search" /></div>
                        </div>
                    </form>
                    <a href="{{ route('purchase.create') }}" class="btn btn-added">Add Purchase</a>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table  customerdatanew">
                        <thead>
                            <tr>
                                <th>Bill No</th>
                                <th>Date & Time</th>
                                <th>Supplier</th>
                                <th>Branch</th>
                                <th>Total</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($purchase_data as $keydata => $purchasedata)
                                <tr>
                                    <td>#{{ $purchasedata['bill_no'] }}</td>
                                    <td>{{ date('d M Y', strtotime($purchasedata['date'])) }} -
                                        {{ date('h:i A', strtotime($purchasedata['date'])) }}</td>
                                    <td>{{ $purchasedata['supplier_name'] }}</td>
                                    <td>{{ $purchasedata['branch_name'] }}</td>
                                    <td>{{ $purchasedata['gross_amount'] }}</td>
                                    <td>
                                        <ul class="list-unstyled hstack gap-1 mb-0">
                                            <li>

                                                <a href="{{ route('purchase.edit', ['unique_key' => $purchasedata['unique_key']]) }}"
                                                    class="badges bg-lightyellow" style="color: white">Edit</a>
                                            </li>
                                            <li>
                                                <a href="#purchaseview{{ $purchasedata['unique_key'] }}" data-bs-toggle="modal"
                                                    data-id="{{ $purchasedata['id'] }}"
                                                    data-bs-target=".purchaseview-modal-xl{{ $purchasedata['unique_key'] }}"
                                                    class="badges bg-lightred purchaseview" style="color: white">View</a>

                                            </li>
                                            <li>
                                                <a href="{{ route('purchase.invoice', ['unique_key' => $purchasedata['unique_key']]) }}"
                                                    class="badges bg-lightgreen" style="color: white">Invoice</a>
                                            </li>
                                            <li hidden>
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
