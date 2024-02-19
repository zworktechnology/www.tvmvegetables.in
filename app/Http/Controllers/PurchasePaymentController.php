<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Models\Branch;
use App\Models\Supplier;
use App\Models\Purchase;
use App\Models\PurchaseProduct;
use App\Models\PurchasePayment;
use App\Models\Productlist;
use App\Models\BranchwiseBalance;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;


class PurchasePaymentController extends Controller
{
    public function index()
    {
        $today = Carbon::now()->format('Y-m-d');
        $data = PurchasePayment::where('date', '=', $today)->where('soft_delete', '!=', 1)->get();
        
        $timenow = Carbon::now()->format('H:i');

        $allbranch = Branch::where('soft_delete', '!=', 1)->where('status', '!=', 1)->get();
        $supplier = Supplier::orderBy('name', 'ASC')->where('soft_delete', '!=', 1)->where('status', '!=', 1)->get();
        
        return view('page.backend.purchasepayment.index', compact('data', 'today', 'allbranch', 'supplier', 'timenow'));
    }


    public function purchasepaymentbranch($branch_id)
    {
        $today = Carbon::now()->format('Y-m-d');
        $data = PurchasePayment::where('branch_id', '=', $branch_id)->where('date', '=', $today)->where('soft_delete', '!=', 1)->get();
       
        
        $timenow = Carbon::now()->format('H:i');

        $allbranch = Branch::where('soft_delete', '!=', 1)->where('status', '!=', 1)->get();
        $supplier = Supplier::orderBy('name', 'ASC')->where('soft_delete', '!=', 1)->where('status', '!=', 1)->get();
        return view('page.backend.purchasepayment.index', compact('data', 'today', 'allbranch', 'supplier', 'timenow'));
    }


    public function purchasepayment_branchdata($today, $branch_id)
    {
        $today = Carbon::now()->format('Y-m-d');
        $data = PurchasePayment::where('branch_id', '=', $branch_id)->where('date', '=', $today)->where('soft_delete', '!=', 1)->get();
       
       
        $timenow = Carbon::now()->format('H:i');

        $allbranch = Branch::where('soft_delete', '!=', 1)->where('status', '!=', 1)->get();
        $supplier = Supplier::orderBy('name', 'ASC')->where('soft_delete', '!=', 1)->where('status', '!=', 1)->get();
        return view('page.backend.purchasepayment.index', compact('data', 'today', 'allbranch', 'supplier', 'timenow'));
    }


    public function datefilter(Request $request) {


        $today = $request->get('from_date');
        $data = PurchasePayment::where('date', '=', $today)->where('soft_delete', '!=', 1)->get();
        
        $timenow = Carbon::now()->format('H:i');

        $allbranch = Branch::where('soft_delete', '!=', 1)->where('status', '!=', 1)->get();
        $supplier = Supplier::orderBy('name', 'ASC')->where('soft_delete', '!=', 1)->where('status', '!=', 1)->get();

        return view('page.backend.purchasepayment.index', compact('data', 'today', 'allbranch', 'supplier', 'timenow'));

    }


    public function create()
    {
        $today = Carbon::now()->format('Y-m-d');
        $timenow = Carbon::now()->format('H:i');

        $allbranch = Branch::orderBy('name', 'ASC')->where('soft_delete', '!=', 1)->where('status', '!=', 1)->get();
        $supplier = Supplier::orderBy('name', 'ASC')->where('soft_delete', '!=', 1)->where('status', '!=', 1)->get();



        return view('page.backend.purchasepayment.create', compact('today', 'allbranch', 'timenow', 'supplier'));
    }


    public function store(Request $request)
    {
        

        $supplier_id = $request->get('supplier_id');
        $branch_id = $request->get('branch_id');

        
        $PurchseData = BranchwiseBalance::where('supplier_id', '=', $supplier_id)->where('branch_id', '=', $branch_id)->first();
        if($PurchseData != ""){


            $randomkey = Str::random(5);

            $data = new PurchasePayment();

            $data->unique_key = $randomkey;
            $data->supplier_id = $request->get('supplier_id');
            $data->branch_id = $request->get('branch_id');
            $data->purchase_id = $request->get('payment_purchase_id');
            $data->date = $request->get('date');
            $data->time = $request->get('time');
            $data->oldblance = $request->get('oldblance');
            $data->purchasepayment_discount = $request->get('purchasepayment_discount');
            $data->purchasepayment_totalamount = $request->get('purchasepayment_totalamount');
            $data->amount = $request->get('payment_payableamount');
            $data->payment_pending = $request->get('payment_pending');
            $data->save();



            $old_grossamount = $PurchseData->purchase_amount;
            $old_paid = $PurchseData->purchase_paid;

            $payment_paid_amount = $request->get('payment_payableamount');
            $purchasepayment_discount = $request->get('purchasepayment_discount');


            $new_purchaseamount = $old_grossamount - $purchasepayment_discount;
            $new_paid = $old_paid + $payment_paid_amount;
            $new_balance = $new_purchaseamount - $new_paid;
            

            DB::table('branchwise_balances')->where('supplier_id', $supplier_id)->where('branch_id', $branch_id)->update([
                'purchase_amount' => $new_purchaseamount, 'purchase_paid' => $new_paid, 'purchase_balance' => $new_balance
            ]);

        }

        

        return redirect()->route('purchasepayment.index')->with('add', 'Payment Data added successfully!');
    }



    public function edit(Request $request, $unique_key)
    {
        $PurchasePaymentData = PurchasePayment::where('unique_key', '=', $unique_key)->first();

        $today = Carbon::now()->format('Y-m-d');
        $timenow = Carbon::now()->format('H:i');

        $allbranch = Branch::orderBy('name', 'ASC')->where('soft_delete', '!=', 1)->where('status', '!=', 1)->get();
        $supplier = Supplier::orderBy('name', 'ASC')->where('soft_delete', '!=', 1)->where('status', '!=', 1)->get();

        return view('page.backend.purchasepayment.edit', compact('today', 'allbranch', 'timenow', 'supplier', 'PurchasePaymentData'));
    }


    public function update(Request $request, $unique_key)
    {
        $PurchasePaymentData = PurchasePayment::where('unique_key', '=', $unique_key)->first();
        
        $discount = $PurchasePaymentData->purchasepayment_discount;
        $paidamount = $PurchasePaymentData->amount;
        $supplier_id = $PurchasePaymentData->supplier_id;
        $branch_id = $PurchasePaymentData->branch_id;
        $payment_paid_amount = $request->get('payment_payableamount');
        $p_discount = $request->get('purchasepayment_discount');
        

        
        $PurchseData = BranchwiseBalance::where('supplier_id', '=', $supplier_id)->where('branch_id', '=', $branch_id)->first();
        $old_paid = $PurchseData->purchase_paid;

        

        if($p_discount > $discount){

            $diff_discount = $p_discount - $discount;
            $total_purchaseamount = $PurchseData->purchase_amount - $diff_discount;


        }else if($p_discount < $discount){

            $diff_discount = $discount - $p_discount;
            $total_purchaseamount = $PurchseData->purchase_amount + $diff_discount;

        }else if($p_discount == $discount){

            $total_purchaseamount = $PurchseData->purchase_amount;
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
        


        $new_balance = $total_purchaseamount - $total_paid;

        DB::table('branchwise_balances')->where('supplier_id', $supplier_id)->where('branch_id', $branch_id)->update([
            'purchase_amount' => $total_purchaseamount, 'purchase_paid' => $total_paid, 'purchase_balance' => $new_balance
        ]);





        $PurchasePaymentData->date = $request->get('date');
        $PurchasePaymentData->time = $request->get('time');
        $PurchasePaymentData->oldblance = $request->get('oldblance');
        $PurchasePaymentData->purchasepayment_discount = $request->get('purchasepayment_discount');
        $PurchasePaymentData->purchasepayment_totalamount = $request->get('purchasepayment_totalamount');
        $PurchasePaymentData->amount = $request->get('payment_payableamount');
        $PurchasePaymentData->payment_pending = $request->get('payment_pending');
        $PurchasePaymentData->update();


        
        
            
        return redirect()->route('purchasepayment.index')->with('add', 'Payment Data added successfully!');
       


    }


    public function delete($unique_key)
    {
        $data = PurchasePayment::where('unique_key', '=', $unique_key)->first();

        $data->soft_delete = 1;

        $data->update();

        return redirect()->route('purchasepayment.index')->with('soft_destroy', 'Successfully deleted the Payments !');
    }

}
