<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use App\Models\Branch;
use App\Models\Expence;
use App\Models\Expensedetail;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Carbon\Carbon;

class ExpenceController extends Controller
{
    public function index()
    {

        $today = Carbon::now()->format('Y-m-d');
        $branch = Branch::where('soft_delete', '!=', 1)->get();
        $timenow = Carbon::now()->format('H:i');


        $data = Expence::where('date', '=', $today)->where('soft_delete', '!=', 1)->get();
        $expense_data = [];
        $terms = [];
        foreach ($data as $key => $datas) {
            $branch_name = Branch::findOrFail($datas->branch_id);

            $ExpenseDetails = Expensedetail::where('expense_id', '=', $datas->id)->get();
            foreach ($ExpenseDetails as $key => $ExpenseDetails_arr) {

                $terms[] = array(
                    'expense_note' => $ExpenseDetails_arr->expense_note,
                    'expense_amount' => $ExpenseDetails_arr->expense_amount,
                    'expense_id' => $ExpenseDetails_arr->expense_id,

                );

            }

            $expense_data[] = array(
                'branch_name' => $branch_name->shop_name,
                'date' => $datas->date,
                'time' => $datas->time,
                'amount' => $datas->amount,
                'note' => $datas->note,
                'unique_key' => $datas->unique_key,
                'id' => $datas->id,
                'branch_id' => $datas->branch_id,
                'terms' => $terms,
            );
        }
        return view('page.backend.expence.index', compact('expense_data', 'branch', 'today', 'timenow'));
    }

    public function expensebranch($branch_id)
    {
        $today = Carbon::now()->format('Y-m-d');
        $branch = Branch::where('soft_delete', '!=', 1)->get();
        $timenow = Carbon::now()->format('H:i');


        $data = Expence::where('branch_id', '=', $branch_id)->where('date', '=', $today)->where('soft_delete', '!=', 1)->get();
        $expense_data = [];
        $terms = [];

        foreach ($data as $key => $datas) {
            $branch_name = Branch::findOrFail($datas->branch_id);

            $ExpenseDetails = Expensedetail::where('expense_id', '=', $datas->id)->get();
            foreach ($ExpenseDetails as $key => $ExpenseDetails_arr) {

                $terms[] = array(
                    'expense_note' => $ExpenseDetails_arr->expense_note,
                    'expense_amount' => $ExpenseDetails_arr->expense_amount,
                    'expense_id' => $ExpenseDetails_arr->expense_id,

                );

            }

            $expense_data[] = array(
                'branch_name' => $branch_name->shop_name,
                'date' => $datas->date,
                'time' => $datas->time,
                'amount' => $datas->amount,
                'note' => $datas->note,
                'unique_key' => $datas->unique_key,
                'id' => $datas->id,
                'branch_id' => $datas->branch_id,
                'terms' => $terms,
            );
        }



        return view('page.backend.expence.index', compact('expense_data', 'branch', 'today', 'timenow'));
    }


    public function expensedata_branch($today, $branch_id)
    {

        $branch = Branch::where('soft_delete', '!=', 1)->get();
        $timenow = Carbon::now()->format('H:i');


        $data = Expence::where('branch_id', '=', $branch_id)->where('date', '=', $today)->where('soft_delete', '!=', 1)->get();
        $expense_data = [];
        $terms = [];

        foreach ($data as $key => $datas) {
            $branch_name = Branch::findOrFail($datas->branch_id);

            $ExpenseDetails = Expensedetail::where('expense_id', '=', $datas->id)->get();
            foreach ($ExpenseDetails as $key => $ExpenseDetails_arr) {

                $terms[] = array(
                    'expense_note' => $ExpenseDetails_arr->expense_note,
                    'expense_amount' => $ExpenseDetails_arr->expense_amount,
                    'expense_id' => $ExpenseDetails_arr->expense_id,

                );

            }

            $expense_data[] = array(
                'branch_name' => $branch_name->shop_name,
                'date' => $datas->date,
                'time' => $datas->time,
                'amount' => $datas->amount,
                'note' => $datas->note,
                'unique_key' => $datas->unique_key,
                'id' => $datas->id,
                'branch_id' => $datas->branch_id,
                'terms' => $terms,
            );
        }



        return view('page.backend.expence.index', compact('expense_data', 'branch', 'today', 'timenow'));
    }


    public function datefilter(Request $request)
    {

        $today = $request->get('from_date');


        $branch = Branch::where('soft_delete', '!=', 1)->get();
        $timenow = Carbon::now()->format('H:i');


        $data = Expence::where('date', '=', $today)->where('soft_delete', '!=', 1)->get();
        $expense_data = [];
        $terms = [];

        foreach ($data as $key => $datas) {
            $branch_name = Branch::findOrFail($datas->branch_id);

            $ExpenseDetails = Expensedetail::where('expense_id', '=', $datas->id)->get();
            foreach ($ExpenseDetails as $key => $ExpenseDetails_arr) {

                $terms[] = array(
                    'expense_note' => $ExpenseDetails_arr->expense_note,
                    'expense_amount' => $ExpenseDetails_arr->expense_amount,
                    'expense_id' => $ExpenseDetails_arr->expense_id,

                );

            }

            $expense_data[] = array(
                'branch_name' => $branch_name->shop_name,
                'date' => $datas->date,
                'time' => $datas->time,
                'amount' => $datas->amount,
                'note' => $datas->note,
                'unique_key' => $datas->unique_key,
                'id' => $datas->id,
                'branch_id' => $datas->branch_id,
                'terms' => $terms,
            );
        }
        return view('page.backend.expence.index', compact('expense_data', 'branch', 'today', 'timenow'));

    }


    public function create()
    {
        $today = Carbon::now()->format('Y-m-d');
        $branch = Branch::where('soft_delete', '!=', 1)->get();
        $timenow = Carbon::now()->format('H:i');



        return view('page.backend.expence.create', compact('today', 'branch', 'timenow'));
    }




    public function store(Request $request)
    {
        $randomkey = Str::random(5);

        $data = new Expence();

        $data->unique_key = $randomkey;
        $data->date = $request->get('date');
        $data->time = $request->get('time');
        $data->branch_id = $request->get('branch_id');
        $data->save();


        $insertedId = $data->id;
        $total = 0;
        foreach ($request->get('expense_note') as $key => $expense_note) {
            $total +=  $request->expense_amount[$key];

            $Expensedetail = new Expensedetail;
            $Expensedetail->expense_id = $insertedId;
            $Expensedetail->expense_note = $expense_note;
            $Expensedetail->expense_amount = $request->expense_amount[$key];
            $Expensedetail->save();

            DB::table('expences')->where('id', $insertedId)->update([
                'amount' => $total
            ]);
        }

        return redirect()->route('expence.index')->with('add', 'Expence Data added successfully!');
    }



    public function edit($unique_key)
    {
        $today = Carbon::now()->format('Y-m-d');
        $branch = Branch::where('soft_delete', '!=', 1)->get();
        $timenow = Carbon::now()->format('H:i');
        $data = Expence::where('unique_key', '=', $unique_key)->where('soft_delete', '!=', 1)->first();
        $Expense_details = Expensedetail::where('expense_id', '=', $data->id)->get();


        return view('page.backend.expence.edit', compact('today', 'branch', 'timenow', 'data', 'Expense_details'));
    }


    public function update(Request $request, $unique_key)
    {
        $ExpenceData = Expence::where('unique_key', '=', $unique_key)->first();

        $ExpenceData->date = $request->get('date');
        $ExpenceData->time = $request->get('time');
        $ExpenceData->branch_id = $request->get('branch_id');
        $ExpenceData->update();

        $ExpenceDataID = $ExpenceData->id;



        $getinsertedExpense = Expensedetail::where('expense_id', '=', $ExpenceDataID)->get();
        $Expensedetil = array();
        foreach ($getinsertedExpense as $key => $getinserted_Expense) {
            $Expensedetil[] = $getinserted_Expense->id;
        }

        $expense_detialid = $request->expense_detialid;
        $updated_ExpenseDetlid = array_filter($expense_detialid);
        $different_ids = array_merge(array_diff($Expensedetil, $updated_ExpenseDetlid), array_diff($updated_ExpenseDetlid, $Expensedetil));

        if (!empty($different_ids)) {
            foreach ($different_ids as $key => $different_id) {
                Expensedetail::where('id', $different_id)->delete();
            }
        }


        $total = 0;
        
        foreach ($request->get('expense_detialid') as $key => $expense_detialid) {

            if ($expense_detialid > 0) {
                $total =  $request->get('tot_expense_amount');

                $expense_note = $request->expense_note[$key];
                $expense_amount = $request->expense_amount[$key];

                DB::table('expensedetails')->where('id', $expense_detialid)->update([
                    'expense_note' => $expense_note,  'expense_amount' => $expense_amount
                ]);

                DB::table('expences')->where('id', $ExpenceDataID)->update([
                    'amount' => $total
                ]);
            } else if ($expense_detialid == '') {

                $total =  $request->get('tot_expense_amount');

                $Expensedetail = new Expensedetail;
                $Expensedetail->expense_id = $ExpenceDataID;
                $Expensedetail->expense_note = $request->expense_note[$key];
                $Expensedetail->expense_amount = $request->expense_amount[$key];
                $Expensedetail->save();

                DB::table('expences')->where('id', $ExpenceDataID)->update([
                    'amount' => $total
                ]);
            }


        }

        return redirect()->route('expence.index')->with('update', 'Expence Data updated successfully!');
    }


    public function delete($unique_key)
    {
        $data = Expence::where('unique_key', '=', $unique_key)->first();

        $data->soft_delete = 1;

        $data->update();

        return redirect()->route('expence.index')->with('soft_destroy', 'Successfully deleted the Expence !');
    }



    public function report() {
        $today = Carbon::now()->format('Y-m-d');
        $timenow = Carbon::now()->format('H:i');
        $branch = Branch::where('soft_delete', '!=', 1)->where('status', '!=', 1)->get();

        $expense_data = [];
            $branchwise_report = Expence::where('soft_delete', '!=', 1)->get();
            $expense_data = [];
            $terms = [];
            // if($branchwise_report != ''){
            //     foreach ($branchwise_report as $key => $branchwise_datas) {

            //         $branch_name = Branch::findOrFail($branchwise_datas->branch_id);

            //         $ExpenseDetails = Expensedetail::where('expense_id', '=', $branchwise_datas->id)->get();
            //         foreach ($ExpenseDetails as $key => $ExpenseDetails_arr) {

            //             $terms[] = array(
            //                 'expense_note' => $ExpenseDetails_arr->expense_note,
            //                 'expense_amount' => $ExpenseDetails_arr->expense_amount,
            //                 'expense_id' => $ExpenseDetails_arr->expense_id,

            //             );

            //         }

            //         $expense_data[] = array(
            //             'branch_name' => $branch_name->shop_name,
            //             'date' => $branchwise_datas->date,
            //             'time' => $branchwise_datas->time,
            //             'amount' => $branchwise_datas->amount,
            //             'note' => $branchwise_datas->note,
            //             'unique_key' => $branchwise_datas->unique_key,
            //             'id' => $branchwise_datas->id,
            //             'terms' => $terms,
            //             'branch_id' => $branchwise_datas->branch_id,
            //             'branchheading' => $branch_name->shop_name,
            //             'fromdateheading' => '',
            //             'todateheading' => '',
            //         );
            //     }
            // }
        return view('page.backend.expence.report', compact('branch', 'expense_data', 'today', 'timenow'));
    }



    public function report_view(Request $request)
    {
        $expencereport_fromdate = $request->get('expencereport_fromdate');
        $expencereport_todate = $request->get('expencereport_todate');
        $expencereport_branch = $request->get('expencereport_branch');


        $branch = Branch::where('soft_delete', '!=', 1)->where('status', '!=', 1)->get();
        $today = Carbon::now()->format('Y-m-d');
        $timenow = Carbon::now()->format('H:i');




        if($expencereport_branch != ""){
            $getBranch = Branch::findOrFail($expencereport_branch);

            $branchwise_report = Expence::where('branch_id', '=', $expencereport_branch)->where('soft_delete', '!=', 1)->get();
            $expense_data = [];
            if($branchwise_report != ''){
                foreach ($branchwise_report as $key => $branchwise_datas) {
                    $branch_name = Branch::findOrFail($branchwise_datas->branch_id);


                    $expense_data[] = array(
                        'branch_name' => $branch_name->shop_name,
                        'date' => $branchwise_datas->date,
                        'time' => $branchwise_datas->time,
                        'amount' => $branchwise_datas->amount,
                        'note' => $branchwise_datas->note,
                        'unique_key' => $branchwise_datas->unique_key,
                        'id' => $branchwise_datas->id,
                        'branch_id' => $branchwise_datas->branch_id,
                        'branchheading' => $getBranch->shop_name,
                        'fromdateheading' => '',
                        'todateheading' => '',
                    );
                }
            }else{

                $expense_data[] = array(
                    'branch_name' => '',
                    'date' => '',
                    'time' => '',
                    'amount' => '',
                    'note' => '',
                    'unique_key' => '',
                    'id' => '',
                    'branch_id' => '',
                    'branchheading' => $getBranch->shop_name,
                    'fromdateheading' => '',
                    'todateheading' => '',
                );
            }
        }



        if($expencereport_fromdate || $expencereport_todate){


            if($expencereport_fromdate){
                $branchwise_report = Expence::where('date', '=', $expencereport_fromdate)->where('soft_delete', '!=', 1)->get();
                $expense_data = [];
                if($branchwise_report != ''){
                    foreach ($branchwise_report as $key => $branchwise_datas) {
                        $branch_name = Branch::findOrFail($branchwise_datas->branch_id);


                        $expense_data[] = array(
                            'branch_name' => $branch_name->shop_name,
                            'date' => $branchwise_datas->date,
                            'time' => $branchwise_datas->time,
                            'amount' => $branchwise_datas->amount,
                            'note' => $branchwise_datas->note,
                            'unique_key' => $branchwise_datas->unique_key,
                            'id' => $branchwise_datas->id,
                            'branch_id' => $branchwise_datas->branch_id,
                            'branchheading' => '',
                            'fromdateheading' => date('d-M-Y', strtotime($expencereport_fromdate)),
                            'todateheading' => '',
                        );
                    }
                }else{

                    $expense_data[] = array(
                        'branch_name' => '',
                        'date' => '',
                        'time' => '',
                        'amount' => '',
                        'note' => '',
                        'unique_key' => '',
                        'id' => '',
                        'branch_id' => '',
                        'branchheading' => '',
                        'fromdateheading' => date('d-M-Y', strtotime($expencereport_fromdate)),
                        'todateheading' => '',
                    );
                }

            }

            if($expencereport_todate){
                $branchwise_report = Expence::where('date', '=', $expencereport_todate)->where('soft_delete', '!=', 1)->get();
                $expense_data = [];
                if($branchwise_report != ''){
                    foreach ($branchwise_report as $key => $branchwise_datas) {
                        $branch_name = Branch::findOrFail($branchwise_datas->branch_id);


                        $expense_data[] = array(
                            'branch_name' => $branch_name->shop_name,
                            'date' => $branchwise_datas->date,
                            'time' => $branchwise_datas->time,
                            'amount' => $branchwise_datas->amount,
                            'note' => $branchwise_datas->note,
                            'unique_key' => $branchwise_datas->unique_key,
                            'id' => $branchwise_datas->id,
                            'branch_id' => $branchwise_datas->branch_id,
                            'branchheading' => '',
                            'fromdateheading' => '',
                            'todateheading' => date('d-M-Y', strtotime($expencereport_todate)),
                        );
                    }
                }else{

                    $expense_data[] = array(
                        'branch_name' => '',
                        'date' => '',
                        'time' => '',
                        'amount' => '',
                        'note' => '',
                        'unique_key' => '',
                        'id' => '',
                        'branch_id' => '',
                        'branchheading' => '',
                        'fromdateheading' => '',
                        'todateheading' => date('d-M-Y', strtotime($expencereport_todate)),
                    );
                }
            }


        }




        if($expencereport_fromdate && $expencereport_branch){
            $GetBranch = Branch::findOrFail($expencereport_branch);

            $branchwise_report = Expence::where('date', '=', $expencereport_fromdate)->where('branch_id', '=', $expencereport_branch)->where('soft_delete', '!=', 1)->get();
            $expense_data = [];
            if($branchwise_report != ''){
                foreach ($branchwise_report as $key => $branchwise_datas) {
                    $branch_name = Branch::findOrFail($branchwise_datas->branch_id);


                    $expense_data[] = array(
                        'branch_name' => $branch_name->shop_name,
                        'date' => $branchwise_datas->date,
                        'time' => $branchwise_datas->time,
                        'amount' => $branchwise_datas->amount,
                        'note' => $branchwise_datas->note,
                        'unique_key' => $branchwise_datas->unique_key,
                        'id' => $branchwise_datas->id,
                        'branch_id' => $branchwise_datas->branch_id,
                        'branchheading' => $GetBranch->shop_name,
                        'fromdateheading' => date('d-M-Y', strtotime($expencereport_fromdate)),
                        'todateheading' => '',
                    );
                }
            }else{

                $expense_data[] = array(
                    'branch_name' => '',
                    'date' => '',
                    'time' => '',
                    'amount' => '',
                    'note' => '',
                    'unique_key' => '',
                    'id' => '',
                    'branch_id' => '',
                    'branchheading' => $GetBranch->shop_name,
                    'fromdateheading' => date('d-M-Y', strtotime($expencereport_fromdate)),
                    'todateheading' => '',
                );
            }
        }


        if($expencereport_fromdate && $expencereport_todate){

            $branchwise_report = Expence::whereBetween('date', [$expencereport_fromdate, $expencereport_todate])->where('soft_delete', '!=', 1)->get();
            $expense_data = [];
            if($branchwise_report != ''){
                foreach ($branchwise_report as $key => $branchwise_datas) {
                    $branch_name = Branch::findOrFail($branchwise_datas->branch_id);


                    $expense_data[] = array(
                        'branch_name' => $branch_name->shop_name,
                        'date' => $branchwise_datas->date,
                        'time' => $branchwise_datas->time,
                        'amount' => $branchwise_datas->amount,
                        'note' => $branchwise_datas->note,
                        'unique_key' => $branchwise_datas->unique_key,
                        'id' => $branchwise_datas->id,
                        'branch_id' => $branchwise_datas->branch_id,
                        'branchheading' => '',
                        'fromdateheading' => date('d-M-Y', strtotime($expencereport_fromdate)),
                        'todateheading' => date('d-M-Y', strtotime($expencereport_todate)),
                    );
                }
            }else{

                $expense_data[] = array(
                    'branch_name' => '',
                    'date' => '',
                    'time' => '',
                    'amount' => '',
                    'note' => '',
                    'unique_key' => '',
                    'id' => '',
                    'branch_id' => '',
                    'branchheading' => '',
                    'fromdateheading' => date('d-M-Y', strtotime($expencereport_fromdate)),
                    'todateheading' => date('d-M-Y', strtotime($expencereport_todate)),
                );
            }
        }




        if($expencereport_todate && $expencereport_branch){
            $GetBranch = Branch::findOrFail($expencereport_branch);

            $branchwise_report = Expence::where('date', '=', $expencereport_todate)->where('branch_id', '=', $expencereport_branch)->where('soft_delete', '!=', 1)->get();
            $expense_data = [];
            if($branchwise_report != ''){
                foreach ($branchwise_report as $key => $branchwise_datas) {
                    $branch_name = Branch::findOrFail($branchwise_datas->branch_id);


                    $expense_data[] = array(
                        'branch_name' => $branch_name->shop_name,
                        'date' => $branchwise_datas->date,
                        'time' => $branchwise_datas->time,
                        'amount' => $branchwise_datas->amount,
                        'note' => $branchwise_datas->note,
                        'unique_key' => $branchwise_datas->unique_key,
                        'id' => $branchwise_datas->id,
                        'branch_id' => $branchwise_datas->branch_id,
                        'branchheading' => $GetBranch->shop_name,
                        'fromdateheading' => '',
                        'todateheading' => date('d-M-Y', strtotime($expencereport_todate)),
                    );
                }
            }else{

                $expense_data[] = array(
                    'branch_name' => '',
                    'date' => '',
                    'time' => '',
                    'amount' => '',
                    'note' => '',
                    'unique_key' => '',
                    'id' => '',
                    'branch_id' => '',
                    'branchheading' => $GetBranch->shop_name,
                    'fromdateheading' => '',
                    'todateheading' => date('d-M-Y', strtotime($expencereport_todate)),
                );
            }
        }



        if($expencereport_fromdate && $expencereport_todate && $expencereport_branch){
            $GetBranch = Branch::findOrFail($expencereport_branch);

            $branchwise_report = Expence::whereBetween('date', [$expencereport_fromdate, $expencereport_todate])->where('branch_id', '=', $expencereport_branch)->where('soft_delete', '!=', 1)->get();
            $expense_data = [];
            if($branchwise_report != ''){
                foreach ($branchwise_report as $key => $branchwise_datas) {
                    $branch_name = Branch::findOrFail($branchwise_datas->branch_id);


                    $expense_data[] = array(
                        'branch_name' => $branch_name->shop_name,
                        'date' => $branchwise_datas->date,
                        'time' => $branchwise_datas->time,
                        'amount' => $branchwise_datas->amount,
                        'note' => $branchwise_datas->note,
                        'unique_key' => $branchwise_datas->unique_key,
                        'id' => $branchwise_datas->id,
                        'branch_id' => $branchwise_datas->branch_id,
                        'branchheading' => $GetBranch->shop_name,
                        'fromdateheading' => date('d-M-Y', strtotime($expencereport_fromdate)),
                        'todateheading' => date('d-M-Y', strtotime($expencereport_todate)),
                    );
                }
            }else{

                $expense_data[] = array(
                    'branch_name' => '',
                    'date' => '',
                    'time' => '',
                    'amount' => '',
                    'note' => '',
                    'unique_key' => '',
                    'id' => '',
                    'branch_id' => '',
                    'branchheading' => $GetBranch->shop_name,
                    'fromdateheading' => date('d-M-Y', strtotime($expencereport_fromdate)),
                    'todateheading' => date('d-M-Y', strtotime($expencereport_todate)),
                );
            }
        }






        return view('page.backend.expence.report', compact('branch', 'expense_data', 'today', 'timenow'));
    }


}
