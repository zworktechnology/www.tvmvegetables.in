<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Models\Branch;
use App\Models\Customer;
use App\Models\Sales;
use App\Models\SalesProduct;
use App\Models\Salespayment;
use App\Models\Productlist;
use App\Models\BranchwiseBalance;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;


class SalespaymentController extends Controller
{
    public function index()
    {
        $today = Carbon::now()->format('Y-m-d');
        $data = Salespayment::where('date', '=', $today)->where('soft_delete', '!=', 1)->get();
        $timenow = Carbon::now()->format('H:i');

        $allbranch = Branch::where('soft_delete', '!=', 1)->where('status', '!=', 1)->get();
        $customer = Customer::where('soft_delete', '!=', 1)->where('status', '!=', 1)->get();
        
        return view('page.backend.salespayment.index', compact('data', 'today', 'allbranch', 'customer', 'timenow'));
    }


    public function salespaymentbranch($branch_id)
    {
        $today = Carbon::now()->format('Y-m-d');
        $data = Salespayment::where('branch_id', '=', $branch_id)->where('date', '=', $today)->where('soft_delete', '!=', 1)->get();
       
       
        $timenow = Carbon::now()->format('H:i');

        $allbranch = Branch::where('soft_delete', '!=', 1)->where('status', '!=', 1)->get();
        $customer = Customer::where('soft_delete', '!=', 1)->where('status', '!=', 1)->get();
        return view('page.backend.salespayment.index', compact('data', 'today', 'allbranch', 'customer', 'timenow'));
    }


    public function salespayment_branchdata($today, $branch_id)
    {
        $today = Carbon::now()->format('Y-m-d');
        $data = Salespayment::where('branch_id', '=', $branch_id)->where('date', '=', $today)->where('soft_delete', '!=', 1)->get();
       

        $timenow = Carbon::now()->format('H:i');

        $allbranch = Branch::where('soft_delete', '!=', 1)->where('status', '!=', 1)->get();
        $customer = Customer::where('soft_delete', '!=', 1)->where('status', '!=', 1)->get();
        return view('page.backend.salespayment.index', compact('data', 'today', 'allbranch', 'customer', 'timenow'));
    }


    public function datefilter(Request $request) {


        $today = $request->get('from_date');
        $data = Salespayment::where('date', '=', $today)->where('soft_delete', '!=', 1)->get();
        
        $timenow = Carbon::now()->format('H:i');

        $allbranch = Branch::where('soft_delete', '!=', 1)->where('status', '!=', 1)->get();
        $customer = Customer::where('soft_delete', '!=', 1)->where('status', '!=', 1)->get();

        return view('page.backend.salespayment.index', compact('data', 'today', 'allbranch', 'customer', 'timenow'));

    }

    public function create()
    {
        $today = Carbon::now()->format('Y-m-d');
        $timenow = Carbon::now()->format('H:i');

        $allbranch = Branch::where('soft_delete', '!=', 1)->where('status', '!=', 1)->get();
        $customer = Customer::where('soft_delete', '!=', 1)->where('status', '!=', 1)->get();



        return view('page.backend.salespayment.create', compact('today', 'allbranch', 'timenow', 'customer'));
    }

    public function store(Request $request)
    {

        $customer_id = $request->get('customer_id');
        $branch_id = $request->get('branch_id');


        $SalesData = BranchwiseBalance::where('customer_id', '=', $customer_id)->where('branch_id', '=', $branch_id)->first();
        if($SalesData != ""){


            $randomkey = Str::random(5);

            $data = new Salespayment();
    
            $data->unique_key = $randomkey;
            $data->customer_id = $request->get('customer_id');
            $data->branch_id = $request->get('branch_id');
            $data->sales_id = $request->get('payment_sales_id');
            $data->date = $request->get('date');
            $data->time = $request->get('time');
            $data->oldblance = $request->get('sales_oldblance');
            $data->salespayment_discount = $request->get('salespayment_discount');
            $data->salespayment_totalamount = $request->get('salespayment_totalamount');
            $data->amount = $request->get('spayment_payableamount');
            $data->payment_pending = $request->get('spayment_pending');
            $data->save();


            $old_grossamount = $SalesData->sales_amount;
            $old_paid = $SalesData->sales_paid;

            $payment_paid_amount = $request->get('spayment_payableamount');
            $salespayment_discount = $request->get('salespayment_discount');


            $new_salesamount = $old_grossamount - $salespayment_discount;
            $new_paid = $old_paid + $payment_paid_amount;
            $new_balance = $new_salesamount - $new_paid;

            DB::table('branchwise_balances')->where('customer_id', $customer_id)->where('branch_id', $branch_id)->update([
                'sales_amount' => $new_salesamount, 'sales_paid' => $new_paid, 'sales_balance' => $new_balance
            ]);

    
        }


        return redirect()->route('salespayment.index')->with('add', 'Payment Data added successfully!');
    }



    public function edit(Request $request, $unique_key)
    {
        $SalespaymentData = Salespayment::where('unique_key', '=', $unique_key)->first();

        $today = Carbon::now()->format('Y-m-d');
        $timenow = Carbon::now()->format('H:i');

        $allbranch = Branch::where('soft_delete', '!=', 1)->where('status', '!=', 1)->get();
        $customer = Customer::where('soft_delete', '!=', 1)->where('status', '!=', 1)->get();

        return view('page.backend.salespayment.edit', compact('today', 'allbranch', 'timenow', 'customer', 'SalespaymentData'));
    }



    public function update(Request $request, $unique_key)
    {
        $SalespaymentData = Salespayment::where('unique_key', '=', $unique_key)->first();
        
        $discount = $SalespaymentData->salespayment_discount;
        $paidamount = $SalespaymentData->amount;
        $customer_id = $SalespaymentData->customer_id;
        $branch_id = $SalespaymentData->branch_id;
        $payment_paid_amount = $request->get('spayment_payableamount');
        $p_discount = $request->get('salespayment_discount');
        

        
        $salesData = BranchwiseBalance::where('customer_id', '=', $customer_id)->where('branch_id', '=', $branch_id)->first();
        $old_paid = $salesData->sales_paid;

        

        if($p_discount > $discount){

            $diff_discount = $p_discount - $discount;
            $total_salesamount = $salesData->sales_amount - $diff_discount;


        }else if($p_discount < $discount){

            $diff_discount = $discount - $p_discount;
            $total_salesamount = $salesData->sales_amount + $diff_discount;

        }else if($p_discount == $discount){

            $total_salesamount = $salesData->sales_amount;
        }


        if($payment_paid_amount > $paidamount){
            $diff_paid = $payment_paid_amount - $paidamount;
            $total_paid = $old_paid + $diff_paid;

        }else if($payment_paid_amount < $paidamount){

            $diff_paid = $paidamount - $payment_paid_amount;
            $total_paid = $old_paid - $diff_paid;

        }else if($payment_paid_amount == $paidamount){

            $total_paid = $old_paid;
        }
        


        $new_balance = $total_salesamount - $total_paid;

        DB::table('branchwise_balances')->where('customer_id', $customer_id)->where('branch_id', $branch_id)->update([
            'sales_amount' => $total_salesamount, 'sales_paid' => $total_paid, 'sales_balance' => $new_balance
        ]);





        $SalespaymentData->date = $request->get('date');
        $SalespaymentData->time = $request->get('time');
        $SalespaymentData->oldblance = $request->get('sales_oldblance');
        $SalespaymentData->salespayment_discount = $request->get('salespayment_discount');
        $SalespaymentData->salespayment_totalamount = $request->get('salespayment_totalamount');
        $SalespaymentData->amount = $request->get('spayment_payableamount');
        $SalespaymentData->payment_pending = $request->get('spayment_pending');
        $SalespaymentData->update();


        
        
            
        return redirect()->route('salespayment.index')->with('add', 'Payment Data added successfully!');
       


    }
   


    public function delete($unique_key)
    {
        $data = Salespayment::where('unique_key', '=', $unique_key)->first();

        $data->soft_delete = 1;

        $data->update();

        return redirect()->route('salespayment.index')->with('soft_destroy', 'Successfully deleted the Payments !');
    }

}
