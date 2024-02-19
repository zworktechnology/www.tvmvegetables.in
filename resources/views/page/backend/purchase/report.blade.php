@extends('layout.backend.auth')

@section('content')
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h4>Purchase Report</h4>
            </div>
        </div>

        <form autocomplete="off" method="POST" action="{{ route('purchase.report_view') }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf

            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-3 col-sm-6 col-12">
                            <div class="form-group">
                                <label>From Date</label>
                                <input type="date" name="purchasereport_fromdate" id="purchasereport_fromdate">
                            </div>
                        </div>
                        <div class="col-lg-3 col-sm-6 col-12">
                            <div class="form-group">
                                <label>To Date</label>
                                <input type="date" name="purchasereport_todate" id="purchasereport_todate">
                            </div>
                        </div>
                        <div class="col-lg-3 col-sm-6 col-12" hidden>
                            <div class="form-group">
                                <label>Branch</label>
                                <select class="purchasereport_branch form-control js-example-basic-single select" name="purchasereport_branch"
                                    id="purchasereport_branch">
                                    <option value="" selected>Select Branch</option>
                                    @foreach ($branch as $branches)
                                        <option value="{{ $branches->id }}" selected>{{ $branches->shop_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-4 col-sm-6 col-12">
                            <div class="form-group">
                                <label>Supplier</label>
                                <select class="form-control js-example-basic-single select" name="purchasereport_supplier" id="purchasereport_supplier">
                                    <option value="" selected>Select Supplier</option>
                                    @foreach ($supplierarr as $suppliers)
                                        <option value="{{ $suppliers->id }}">{{ $suppliers->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-1 col-sm-6 col-12">
                            <div class="form-group">
                                <label style="color: white">Action</label>
                                <input type="submit" class="btn btn-primary" name="submit" value="Filter" />
                            </div>
                        </div>
                        <div class="col-lg-1 col-sm-6 col-12">
                            <a href="{{ route('purchase.report') }}">
                            <div class="form-group">
                                <label style="color: white">Action</label>
                                <input type="button" class="btn btn-warning" name="submit" value="Clear" />
                            </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>



            <div class="card">
                <div class="card-body">
                    <div class="row">
                    @foreach ($Purchase_data as $keydata => $purchase)
                        @if ($purchase['unique_key'] != '')

                        @if($keydata == 0)
                        <div class="col-lg-3 col-sm-6 col-12" style="margin-bottom: 0px;">
                            <div class="form-group">
                                <label>From Date :<span style="color: red">{{ $purchase['fromdateheading'] }}</span></label>
                            </div>
                        </div>
                        <div class="col-lg-3 col-sm-6 col-12" style="margin-bottom: 0px;">
                            <div class="form-group">
                                <label>To Date :<span style="color: red">{{ $purchase['todateheading'] }}</span></label>
                            </div>
                        </div>
                        <div class="col-lg-3 col-sm-6 col-12" hidden style="margin-bottom: 0px;">
                            <div class="form-group">
                                <label>Branch :<span style="color: red"> {{ $purchase['branchheading'] }}</span></label>
                            </div>
                        </div>
                        <div class="col-lg-3 col-sm-6 col-12" style="margin-bottom: 0px;">
                            <div class="form-group">
                                <label>Supplier : <span style="color: red">{{ $purchase['supplierheading'] }}</span></label>
                            </div>
                        </div>
                        @endif
                        @endif
                    @endforeach
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <div class="row">
                        @if ($Purchase_data != '')
                            <div class="table-responsive">
                                <table class="table  customerdatanew">
                                    <thead style="background: #5e54c966;">
                                        <tr>
                                            <th>S.No</th>
                                            <th>Bill No</th>
                                            <th>Date & Time</th>
                                            <th>Supplier</th>
                                            <th>Type</th>
                                            <th>Particulars</th>
                                            <th>Debit</th>
                                            <th>Credit</th>
                                            <th>Discount</th>
                                            <th>Total</th>
                                        </tr>
                                    </thead>
                                    <tbody style="background: #f8f9fa;">

                                        @foreach ($Purchase_data as $keydata => $purchasedata)
                                            @if ($purchasedata['unique_key'] != '')
                                                <tr>

                                                    <td>{{ ++$keydata }}</td>
                                                    <td>#{{ $purchasedata['bill_no'] }}</td>
                                                    <td>{{ date('Y-m-d', strtotime($purchasedata['date'])) }} - {{ date('h:i A', strtotime($purchasedata['time'])) }}</td>
                                                    <td>{{ $purchasedata['supplier_name'] }}</td>
                                                    <td>{{ $purchasedata['type'] }}</td>
                                                    <td style="text-transform: uppercase;">
                                                    @foreach ($purchasedata['terms'] as $index => $terms_array)
                                                    @if ($terms_array['purchase_id'] == $purchasedata['id'])
                                                    @if ($purchasedata['purchase_order'] == 1)
                                                    {{ $terms_array['product_name'] }} - {{ $terms_array['kgs'] }} {{ $terms_array['bag'] }} - ₹ {{ $terms_array['price_per_kg'] }}<br/>
                                                    @endif
                                                    @endif
                                                    @endforeach
                                                    </td>
                                                    <td>{{ $purchasedata['gross_amount'] }}</td>
                                                    <td>{{ $purchasedata['paid_amount'] }}</td> 
                                                    <td>{{ $purchasedata['discount'] }}</td>
                                                    <td>{{ $purchasedata['balance_amount'] }}</td> 
                                                </tr>

                                                
                                            @endif
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif

                    </div>
                </div>

        </form>
    </div>
@endsection
