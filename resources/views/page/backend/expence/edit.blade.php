@extends('layout.backend.auth')

@section('content')

   <div class="content">
      <div class="page-header">
         <div class="page-title">
            <h4>Update Expense</h4>
         </div>
      </div>



        <div class="card">
            <div class="card-body">
                <form autocomplete="off" method="POST" action="{{ route('expence.update', ['unique_key' => $data->unique_key]) }}" enctype="multipart/form-data">
                @method('PUT')
                @csrf
                    <div class="row">
                        <div class="col-lg-6 col-sm-6 col-12">
                            <div class="form-group">
                                <label style="font-size:15px;padding-top: 5px;padding-bottom: 2px;">Date<span
                                        style="color: red;">*</span></label>
                                <input type="date" name="date" placeholder="" value="{{ $data->date }}" required>
                            </div>
                        </div>

                        <div class="col-lg-6 col-sm-6 col-12">
                            <div class="form-group">
                                <label style="font-size:15px;padding-top: 5px;padding-bottom: 2px;">Time<span
                                        style="color: red;">*</span></label>
                                <input type="time" name="time" placeholder="" value="{{  $data->time }}" required>
                            </div>
                        </div>



                        <div class="col-lg-5 col-sm-5 col-12" hidden>
                            <div class="form-group">
                                <label style="font-size:15px;padding-top: 5px;padding-bottom: 2px;">Branch<span
                                        style="color: red;">*</span></label>
                                <select class="form-control js-example-basic-single select" name="branch_id" id="branch_id" required>
                                    <option value="" disabled selected hiddden>Select Branch</option>
                                    @foreach ($branch as $branches)
                                        <option value="{{ $branches->id }}"@if ($branches->id === $data->branch_id) selected='selected' @endif>{{ $branches->shop_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-1 col-sm-1 col-12">
                            <label style="font-size:15px;padding-top: 5px;padding-bottom: 2px;">Action <span
                                style="color: red;">*</span></label>

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
                                @foreach ($Expense_details as $index => $Expense_detail)
                                    <tr>
                                        <td>
                                            <input type="hidden"id="expense_detialid"name="expense_detialid[]" value="{{ $Expense_detail->id }}"/>
                                            <input type="text" class="form-control expense_note" id="expense_note" name="expense_note[]" placeholder="Note" value="{{ $Expense_detail->expense_note }}" required />
                                        </td>
                                        <td>
                                            <input type="text" class="form-control expense_amount" id="expense_amount" name="expense_amount[]" placeholder="Amount" value="{{ $Expense_detail->expense_amount }}" required />
                                        </td>
                                        <td>
                                        <button style="width: 35px;"class="addexpensefilds py-1 text-white font-medium rounded-lg text-sm  text-center btn btn-primary"
                                            type="button" id="" value="Add">+</button>
                                         <button style="width: 35px;" class="py-1 text-white font-medium rounded-lg text-sm  text-center btn btn-danger remove-expensetr" type="button" >-</button>
                                        </td>
                                    </tr>
                                    @endforeach
                                    <input type="hidden" name="tot_expense_amount" id="tot_expense_amount" value="{{$data->amount}}"/>
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
