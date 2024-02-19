@extends('layout.backend.auth')

@section('content')

   <div class="content">
      <div class="page-header">
         <div class="page-title">
            <h4>Add Expense</h4>
         </div>
      </div>



        <div class="card">
            <div class="card-body">
                <form autocomplete="off" method="POST" action="{{ route('expence.store') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-lg-6 col-sm-6 col-12">
                            <div class="form-group">
                                <label style="font-size:15px;padding-top: 5px;padding-bottom: 2px;">Date<span
                                        style="color: red;">*</span></label>
                                <input type="date" name="date" placeholder="" value="{{ $today }}" required>
                            </div>
                        </div>

                        <div class="col-lg-6 col-sm-6 col-12">
                            <div class="form-group">
                                <label style="font-size:15px;padding-top: 5px;padding-bottom: 2px;">Time<span
                                        style="color: red;">*</span></label>
                                <input type="time" name="time" placeholder="" value="{{ $timenow }}" required>
                            </div>
                        </div>



                        <div class="col-lg-6 col-sm-6 col-12" hidden>
                            <div class="form-group">
                                <label style="font-size:15px;padding-top: 5px;padding-bottom: 2px;">Branch<span
                                        style="color: red;">*</span></label>
                                <select class="form-control js-example-basic-single select" name="branch_id" id="branch_id" required>
                                    <option value="" disabled selected hiddden>Select Branch</option>
                                    @foreach ($branch as $branches)
                                        <option value="{{ $branches->id }}" selected>{{ $branches->shop_name }}</option>
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
                                        <th>Note</th>
                                        <th>Amount</th>
                                        <th>Action </th>
                                    </tr>
                                </thead>
                                <tbody class="expensefilds">
                                    <tr>
                                        <td>
                                            <input type="hidden"id="expense_detialid"name="expense_detialid[]" />
                                            <input type="text" class="form-control expense_note" id="expense_note" name="expense_note[]" placeholder="Note" value="" required />
                                        </td>
                                        <td>
                                            <input type="text" class="form-control expense_amount" id="expense_amount" name="expense_amount[]" placeholder="Amount" value="" required />
                                        </td>
                                        <td>
                                            <button style="width: 35px;"class="addexpensefilds py-1 text-white font-medium rounded-lg text-sm  text-center btn btn-primary"
                                                type="button" id="" value="Add">+</button>
                                                <button style="width: 35px;"class="py-1 text-white remove-expensetr font-medium rounded-lg text-sm  text-center btn btn-danger" type="button" id="" value="">-</button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <br /><br />

                    <div class="modal-footer">
                        <input type="submit" class="btn btn-primary" />
                        <a href="{{ route('expence.index') }}" class="btn btn-danger" value="">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
