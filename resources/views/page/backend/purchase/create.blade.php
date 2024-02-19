@extends('layout.backend.auth')

@section('content')

   <div class="content">
      <div class="page-header">
         <div class="page-title">
            <h4>Add Purchase</h4>
         </div>
      </div>



        <div class="card">
            <div class="card-body">
                <form autocomplete="off" method="POST" action="{{ route('purchase.store') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-lg-3 col-sm-3 col-12">
                            <div class="form-group">
                                <label style="font-size:15px;padding-top: 5px;padding-bottom: 2px;">Date<span
                                        style="color: red;">*</span></label>
                                <input type="date" name="date" placeholder="" value="{{ $today }}" required>
                            </div>
                        </div>

                        <div class="col-lg-3 col-sm-3 col-12">
                            <div class="form-group">
                                <label style="font-size:15px;padding-top: 5px;padding-bottom: 2px;">Time<span
                                        style="color: red;">*</span></label>
                                <input type="time" name="time" placeholder="" value="{{ $timenow }}" required>
                            </div>
                        </div>

                        <div class="col-lg-3 col-sm-3 col-12">
                            <div class="form-group">
                                <label style="font-size:15px;padding-top: 5px;padding-bottom: 2px;">Supplier<span
                                        style="color: red;">*</span> </label>
                                <select class="form-control js-example-basic-single select" name="supplier_id" id="supplier_id" required>
                                    <option value="" disabled selected hiddden>Select Supplier</option>
                                    @foreach ($supplier as $suppliers)
                                        <option value="{{ $suppliers->id }}">{{ $suppliers->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-lg-3 col-sm-3 col-12">
                            <div class="form-group">
                                <label style="font-size:15px;padding-top: 5px;padding-bottom: 2px;">Branch<span
                                        style="color: red;">*</span></label>
                                <select class="form-control js-example-basic-single select branch_id" name="branch_id" id="branch_id" required>
                                    <option value="" disabled selected hiddden>Select Branch</option>
                                    @foreach ($branch as $branches)
                                        <option value="{{ $branches->id }}">{{ $branches->shop_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <br />

                    <div class="row">
                        <div class="table-responsive col-lg-12 col-sm-12 col-12">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th style="font-size:15px; width:40%;">Product</th>
                                        <th style="font-size:15px; width:25%;">Bag / Kg</th>
                                        <th style="font-size:15px; width:25%;">Count </th>
                                        <th style="font-size:15px; width:10%;">Action </th>
                                    </tr>
                                </thead>
                                <tbody class="product_fields">
                                    <tr>
                                        <td>
                                            <input type="hidden"id="purchase_detail_id"name="purchase_detail_id[]" />
                                            <select class="form-control js-example-basic-single product_id select" name="product_id[]"
                                                id="product_id1"required>
                                                <option value="" selected hidden class="text-muted">Select Product
                                                </option>
                                                @foreach ($productlist as $productlists)
                                                    <option value="{{ $productlists->id }}">{{ $productlists->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            <select class=" form-control bagorkg" name="bagorkg[]" id="bagorkg1"required>
                                                <option value="" selected hidden class="text-muted">Select</option>
                                                <option value="bag">Bag</option>
                                                <option value="kg">Kg</option>
                                            </select>
                                        </td>
                                        <td>
                                            <input type="text" class="form-control count" id="count" name="count[]"
                                                placeholder="count" value="" required />
                                        </td>
                                        <td>
                                            <button style="width: 35px;"class="py-1 text-white font-medium rounded-lg text-sm  text-center btn btn-primary addproductfields"
                                                type="button" id="" value="Add">+</button>
                                             <button style="width: 35px;" class="py-1 text-white font-medium rounded-lg text-sm  text-center btn btn-danger remove-tr" type="button" >-</button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <br /><br />

                    <div class="modal-footer">
                        <input type="submit" class="btn btn-primary" onclick="purchasesubmitForm(this);" />
                        <a href="{{ route('purchase.index') }}" class="btn btn-danger" value="">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
