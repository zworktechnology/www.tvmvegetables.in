@extends('layout.backend.auth')

@section('content')
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h4>Product</h4>
            </div>
            <div class="page-btn">
                <button type="button" class="btn btn-primary waves-effect waves-light btn-added" data-bs-toggle="modal"
                    data-bs-target=".product-modal-xl">Add Product</button>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-2 col-sm-4 col-6">
                <a href="{{ route('product.index') }}" style="color: black">
                    <div class="dash-widget">
                        <div class="dash-widgetcontent">
                            <h6 style="font-weight: bold;">All</h6>
                        </div>
                    </div>
                </a>
            </div>
            @foreach ($branch_data as $keydata => $allbranches)
                <div class="col-lg-2 col-sm-4 col-6">
                    <div class="dash-widget">
                        <div class="dash-widgetcontent">
                            <h6 style="font-weight: bold;">
                                <a href="#branch_view{{ $allbranches->id }}"
                                    data-bs-toggle="modal"data-id="{{ $allbranches->id }}"
                                    data-bs-target=".branch_view-modal-xl{{ $allbranches->id }}"
                                    style="color:black">{{ $allbranches->name }}</a>
                            </h6>
                        </div>
                    </div>
                </div>

                <div class="modal fade branch_view-modal-xl{{ $allbranches->id }}" tabindex="-1"role="dialog"
                    data-bs-backdrop="static"
                    aria-labelledby="branch_viewLargeModalLabel{{ $allbranches->id }}"aria-hidden="true">
                    @include('page.backend.product.branch_view')
                </div>
            @endforeach
        </div>


        <div class="row">
            <div class="col-lg-12 col-sm-12 col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table  customerdatanew">
                                <thead>
                                    <tr>
                                        <th>Sl. No</th>
                                        <th>Branch</th>
                                        <th>Product</th>
                                        <th>Bags</th>
                                        <th>Kilograms</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($product_data as $keydata => $producttdata)
                                        <tr>
                                            <td>{{ ++$keydata }}</td>
                                            <td>{{ $producttdata['branch'] }}</td>
                                            <td>{{ $producttdata['productlist'] }}</td>
                                            <td>{{ $producttdata['available_stockin_bag'] }}</td>
                                            <td>{{ $producttdata['available_stockin_kilograms'] }}</td>
                                            @if ($producttdata['status'] == 0)
                                                <td><span class="badges bg-lightgreen">Active</span></td>
                                            @else
                                                <td><span class="badges bg-lightred">De-Active</span></td>
                                            @endif
                                            <td>
                                                <ul class="list-unstyled hstack gap-1 mb-0">
                                                    <li>
                                                        <a href="#edit{{ $producttdata['unique_key'] }}"
                                                            data-bs-toggle="modal"
                                                            data-id="{{ $producttdata['unique_key'] }}"
                                                            data-bs-target=".productedit-modal-xl{{ $producttdata['unique_key'] }}"
                                                            class="badges bg-lightyellow" style="color: white">Edit</a>
                                                    </li>
                                                    <li>
                                                        <a href="#delete{{ $producttdata['unique_key'] }}"
                                                            data-bs-toggle="modal"
                                                            data-id="{{ $producttdata['unique_key'] }}"
                                                            data-bs-target=".productdelete-modal-xl{{ $producttdata['unique_key'] }}"
                                                            class="badges bg-lightgrey" style="color: white">Delete</a>
                                                    </li>
                                                </ul>
                                            </td>
                                        </tr>

                                        <div class="modal fade productedit-modal-xl{{ $producttdata['unique_key'] }}"
                                            tabindex="-1" role="dialog" data-bs-backdrop="static"
                                            aria-labelledby="producteditLargeModalLabel{{ $producttdata['unique_key'] }}"
                                            aria-hidden="true">
                                            @include('page.backend.product.edit')
                                        </div>

                                        <div class="modal fade productdelete-modal-xl{{ $producttdata['unique_key'] }}"
                                            tabindex="-1" data-bs-backdrop="static" role="dialog"
                                            aria-labelledby="productdeleteLargeModalLabel{{ $producttdata['unique_key'] }}"
                                            aria-hidden="true">
                                            @include('page.backend.product.delete')
                                        </div>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-sm-6 col-12" hidden>
                <div class="card">
                    <div class="card-body">
                        <div class="col-lg-12 col-sm-6 col-12">
                            <form autocomplete="off" method="POST" action="{{ route('productlist.store') }}">
                                @csrf
                                <div class="form-group">
                                    <label>Name</label>
                                    <input type="text" name="name" placeholder="Enter Product name">
                                </div>
                                <div class="col-lg-12 button-align">
                                    <button type="submit"
                                        class="badges bg-lightgreen waves-effect waves-light btn-added">Submit</button>
                            </form>
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
                                    <th>Sl.No</th>
                                    <th>name</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($productlistdata as $keydata => $productlist_array)
                                    <tr>
                                        <td>{{ ++$keydata }}</td>
                                        <td>{{ $productlist_array->name }}</td>
                                        <td><a href="#edit{{ $productlist_array->unique_key }}" data-bs-toggle="modal"
                                                data-id="{{ $productlist_array->unique_key }}"
                                                data-bs-target=".productlistedit-modal-xl{{ $productlist_array->unique_key }}"
                                                class="badges bg-lightyellow" style="color: white">Edit</a></td>
                                    </tr>


                                    <div class="modal fade productlistedit-modal-xl{{ $productlist_array->unique_key }}"
                                        tabindex="-1" role="dialog"
                                        aria-labelledby="productlistediteditLargeModalLabel{{ $productlist_array->unique_key }}"
                                        aria-hidden="true">
                                        @include('page.backend.product.productlistedit')
                                    </div>
                                @endforeach
                            </tbody>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>

    <div class="modal fade product-modal-xl" tabindex="-1" role="dialog" aria-labelledby="productLargeModalLabel"
        aria-hidden="true">
        @include('page.backend.product.create')
    </div>


    </div>
@endsection
