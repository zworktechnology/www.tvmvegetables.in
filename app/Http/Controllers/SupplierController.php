<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use App\Models\Branch;
use App\Models\Purchase;
use App\Models\PurchaseProduct;
use App\Models\Productlist;
use App\Models\PurchasePayment;
use App\Models\BranchwiseBalance;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Carbon\Carbon;
use PDF;

class SupplierController extends Controller
{
    public function index()
    {

        $data = Supplier::where('soft_delete', '!=', 1)->get();
        $supplierarr_data = [];
        foreach ($data as $key => $datas) {

            $supplier_name = Supplier::findOrFail($datas->id);
            // Grand total
            $total_purchase_amt = Purchase::where('soft_delete', '!=', 1)->where('supplier_id', '=', $datas->id)->sum('gross_amount');
            if($total_purchase_amt != ""){
                $tot_purchaseAmount = $total_purchase_amt;
            }else {
                $tot_purchaseAmount = '0';
            }

            // Total Paid
            $total_paid = Purchase::where('soft_delete', '!=', 1)->where('supplier_id', '=', $datas->id)->sum('paid_amount');
            if($total_paid != ""){
                $total_paid_Amount = $total_paid;
            }else {
                $total_paid_Amount = '0';
            }
            $payment_total_paid = PurchasePayment::where('soft_delete', '!=', 1)->where('supplier_id', '=', $datas->id)->sum('amount');
            if($payment_total_paid != ""){
                $total_payment_paid = $payment_total_paid;
            }else {
                $total_payment_paid = '0';
            }

            $total_discount = PurchasePayment::where('soft_delete', '!=', 1)->where('supplier_id', '=', $datas->id)->sum('purchasepayment_discount');
            if($total_discount != ""){
                $total_discount_amont = $total_discount;
            }else {
                $total_discount_amont = '0';
            }

            $total_amount_paid = $total_paid_Amount + $total_payment_paid + $total_discount_amont;



            // Total Balance
            $total_balance = $tot_purchaseAmount - $total_amount_paid;

            $LastPattityal = Purchase::where('supplier_id', '=', $datas->id)->where('gross_amount', '=', NULL)->where('purchase_order', '=', NULL)->orderBy('date', 'asc')->where('soft_delete', '!=', 1)->first();
            if($LastPattityal){
                $last_date = $LastPattityal->date;
            }else {
                $last_date = '';
            }


            $totalpurchase = BranchwiseBalance::where('supplier_id', '=', $datas->id)->sum('purchase_amount');
            $totalpaidpurchase = BranchwiseBalance::where('supplier_id', '=', $datas->id)->sum('purchase_paid');
            $totalpurchasebla = BranchwiseBalance::where('supplier_id', '=', $datas->id)->sum('purchase_balance');


            $supplierarr_data[] = array(
                'unique_key' => $datas->unique_key,
                'name' => $supplier_name->name,
                'contact_number' => $datas->contact_number,
                'shop_name' => $datas->shop_name,
                'status' => $datas->status,
                'id' => $datas->id,
                'total_purchase_amt' => $totalpurchase + $total_discount_amont,
                'total_paid' => $totalpaidpurchase,
                'balance_amount' => $totalpurchasebla,
                'email_address' => $datas->email_address,
                'shop_address' => $datas->shop_address,
                'shop_contact_number' => $datas->shop_contact_number,
                'total_discount_amont' => $total_discount_amont,
                'LastPattityal' => $last_date,
            );


            $price = array();
            foreach ($supplierarr_data as $key => $row)
            {
                $price[$key] = $row['total_purchase_amt'];
            }
            array_multisort($price, SORT_DESC, $supplierarr_data);

        }


        $alldata_branch = Branch::where('soft_delete', '!=', 1)->where('status', '!=', 1)->get();
        $tot_balance_Arr = [];

        foreach ($alldata_branch as $key => $alldata_branchs) {
            $Supplier_array = Supplier::where('soft_delete', '!=', 1)->get();
            foreach ($Supplier_array as $key => $Supplier_arra) {

        $last_idrow = BranchwiseBalance::where('supplier_id', '=', $Supplier_arra->id)->where('branch_id', '=', $alldata_branchs->id)->first();




        if($last_idrow != ""){
            if($last_idrow->purchase_balance != NULL){
                $tot_balace = $last_idrow->purchase_balance;

            }else {

                $tot_balace = 0;

            }
        }else {
            $tot_balace = 0;
        }





                $tot_balance_Arr[] = array(
                    'Supplier_name' => $Supplier_arra->name,
                    'branch_name' => $alldata_branchs->shop_name,
                    'Supplier_id' => $Supplier_arra->id,
                    'balance_amount' => $tot_balace
                );

            }
        }


        $allbranch = Branch::where('soft_delete', '!=', 1)->where('status', '!=', 1)->get();

        $total_purchase_amount = Purchase::where('soft_delete', '!=', 1)->sum('gross_amount');
        if($total_purchase_amount != ""){
            $total_purchaseAmount = $total_purchase_amount;
        }else {
            $total_purchaseAmount = '0';
        }

        $supplierOldbalanceTot = Supplier::where('soft_delete', '!=', 1)->sum('old_balance');

            $TotalPurchase = $total_purchaseAmount + $supplierOldbalanceTot;


        $total_amuntpaid = Purchase::where('soft_delete', '!=', 1)->sum('paid_amount');
        if($total_amuntpaid != ""){
            $totalpaid_Amount = $total_amuntpaid;
        }else {
            $totalpaid_Amount = '0';
        }
        $paymenttotal_paid = PurchasePayment::where('soft_delete', '!=', 1)->sum('amount');
        if($paymenttotal_paid != ""){
            $totalpayment_paid = $paymenttotal_paid;
        }else {
            $totalpayment_paid = '0';
        }


        $discountpaid = PurchasePayment::where('soft_delete', '!=', 1)->sum('purchasepayment_discount');
        if($discountpaid != ""){
            $discount_paid = $discountpaid;
        }else {
            $discount_paid = '0';
        }

        $totalamount_paid = $totalpaid_Amount + $totalpayment_paid + $discount_paid;

        // Total Balance
        $totalbalance = $TotalPurchase - $totalamount_paid;

        return view('page.backend.supplier.index', compact('supplierarr_data', 'tot_balance_Arr', 'allbranch', 'TotalPurchase', 'totalamount_paid', 'totalbalance'));
    }


    public function branchdata($branch_id)
    {

        $data = Supplier::where('soft_delete', '!=', 1)->get();
        $supplierarr_data = [];
        foreach ($data as $key => $datas) {

            $supplier_name = Supplier::findOrFail($datas->id);
            // Grand total
            $total_purchase_amt = Purchase::where('soft_delete', '!=', 1)->where('supplier_id', '=', $datas->id)->where('branch_id', '=', $branch_id)->sum('gross_amount');
            if($total_purchase_amt != ""){
                $tot_purchaseAmount = $total_purchase_amt;
            }else {
                $tot_purchaseAmount = '0';
            }

            // Total Paid
            $total_paid = Purchase::where('soft_delete', '!=', 1)->where('supplier_id', '=', $datas->id)->where('branch_id', '=', $branch_id)->sum('paid_amount');
            if($total_paid != ""){
                $total_paid_Amount = $total_paid;
            }else {
                $total_paid_Amount = '0';
            }
            $payment_total_paid = PurchasePayment::where('soft_delete', '!=', 1)->where('supplier_id', '=', $datas->id)->where('branch_id', '=', $branch_id)->sum('amount');
            if($payment_total_paid != ""){
                $total_payment_paid = $payment_total_paid;
            }else {
                $total_payment_paid = '0';
            }

            $total_discount = PurchasePayment::where('soft_delete', '!=', 1)->where('supplier_id', '=', $datas->id)->where('branch_id', '=', $branch_id)->sum('purchasepayment_discount');
            if($total_discount != ""){
                $total_discount_amont = $total_discount;
            }else {
                $total_discount_amont = '0';
            }

            $total_amount_paid = $total_paid_Amount + $total_payment_paid + $total_discount_amont;



            // Total Balance
            $total_balance = $tot_purchaseAmount - $total_amount_paid;

            $totalpurchase = BranchwiseBalance::where('supplier_id', '=', $datas->id)->where('branch_id', '=', $branch_id)->sum('purchase_amount');
            $totalpaidpurchase = BranchwiseBalance::where('supplier_id', '=', $datas->id)->where('branch_id', '=', $branch_id)->sum('purchase_paid');
            $totalpurchasebla = BranchwiseBalance::where('supplier_id', '=', $datas->id)->where('branch_id', '=', $branch_id)->sum('purchase_balance');


            $LastPattityal = Purchase::where('supplier_id', '=', $datas->id)->where('gross_amount', '=', NULL)->where('purchase_order', '=', NULL)->orderBy('date', 'asc')->where('soft_delete', '!=', 1)->first();
            if($LastPattityal){
                $last_date = $LastPattityal->date;
            }else {
                $last_date = '';
            }


            $supplierarr_data[] = array(
                'unique_key' => $datas->unique_key,
                'name' => $supplier_name->name,
                'contact_number' => $datas->contact_number,
                'shop_name' => $datas->shop_name,
                'status' => $datas->status,
                'id' => $datas->id,
                'total_purchase_amt' => $totalpurchase + $total_discount_amont,
                'total_paid' => $totalpaidpurchase,
                'balance_amount' => $totalpurchasebla,
                'email_address' => $datas->email_address,
                'shop_address' => $datas->shop_address,
                'shop_contact_number' => $datas->shop_contact_number,
                'total_discount_amont' => $total_discount_amont,
                'LastPattityal' => $last_date,
            );


            $price = array();
            foreach ($supplierarr_data as $key => $row)
            {
                $price[$key] = $row['total_purchase_amt'];
            }
            array_multisort($price, SORT_DESC, $supplierarr_data);

        }



        $alldata_branch = Branch::where('soft_delete', '!=', 1)->where('status', '!=', 1)->get();
        $tot_balance_Arr = [];


        foreach ($alldata_branch as $key => $alldata_branchs) {
            $Supplier_array = Supplier::where('soft_delete', '!=', 1)->get();
            foreach ($Supplier_array as $key => $Supplier_arra) {

            $last_idrow = BranchwiseBalance::where('supplier_id', '=', $Supplier_arra->id)->where('branch_id', '=', $alldata_branchs->id)->first();

            if($last_idrow != ""){
                if($last_idrow->purchase_balance != NULL){
                    $tot_balace = $last_idrow->purchase_balance;

                }else {

                    $tot_balace = 0;

                }
            }else {
                $tot_balace = 0;
            }

                $tot_balance_Arr[] = array(
                    'Supplier_name' => $Supplier_arra->name,
                    'branch_name' => $alldata_branchs->shop_name,
                    'Supplier_id' => $Supplier_arra->id,
                    'balance_amount' => $tot_balace
                );

            }




        }
            $total_purchase_amount = Purchase::where('soft_delete', '!=', 1)->where('branch_id', '=', $branch_id)->sum('gross_amount');
            if($total_purchase_amount != ""){
                $total_purchaseAmount = $total_purchase_amount;
            }else {
                $total_purchaseAmount = '0';
            }

            $supplierOldbalanceTot = Supplier::where('soft_delete', '!=', 1)->sum('old_balance');

            $TotalPurchase = $total_purchaseAmount + $supplierOldbalanceTot;


            $total_amuntpaid = Purchase::where('soft_delete', '!=', 1)->where('branch_id', '=', $branch_id)->sum('paid_amount');
            if($total_amuntpaid != ""){
                $totalpaid_Amount = $total_amuntpaid;
            }else {
                $totalpaid_Amount = '0';
            }
            $paymenttotal_paid = PurchasePayment::where('soft_delete', '!=', 1)->where('branch_id', '=', $branch_id)->sum('amount');
            if($paymenttotal_paid != ""){
                $totalpayment_paid = $paymenttotal_paid;
            }else {
                $totalpayment_paid = '0';
            }


            $discountpaid = PurchasePayment::where('soft_delete', '!=', 1)->where('branch_id', '=', $branch_id)->sum('purchasepayment_discount');
            if($discountpaid != ""){
                $discount_paid = $discountpaid;
            }else {
                $discount_paid = '0';
            }

            $totalamount_paid = $totalpaid_Amount + $totalpayment_paid + $discount_paid;



            // Total Balance
            $totalbalance = $TotalPurchase - $totalamount_paid;


        $allbranch = Branch::where('soft_delete', '!=', 1)->where('status', '!=', 1)->get();

        return view('page.backend.supplier.index', compact('supplierarr_data', 'tot_balance_Arr', 'allbranch', 'TotalPurchase', 'totalamount_paid', 'totalbalance'));
    }


    public function allpdf_export() {
        $data = Supplier::where('soft_delete', '!=', 1)->get();
        $supplierarr_data = [];
        foreach ($data as $key => $datas) {

            $supplier_name = Supplier::findOrFail($datas->id);
            // Grand total
            $total_purchase_amt = Purchase::where('soft_delete', '!=', 1)->where('supplier_id', '=', $datas->id)->sum('gross_amount');
            if($total_purchase_amt != ""){
                $tot_purchaseAmount = $total_purchase_amt;
            }else {
                $tot_purchaseAmount = '0';
            }

            // Total Paid
            $total_paid = Purchase::where('soft_delete', '!=', 1)->where('supplier_id', '=', $datas->id)->sum('paid_amount');
            if($total_paid != ""){
                $total_paid_Amount = $total_paid;
            }else {
                $total_paid_Amount = '0';
            }
            $payment_total_paid = PurchasePayment::where('soft_delete', '!=', 1)->where('supplier_id', '=', $datas->id)->sum('amount');
            if($payment_total_paid != ""){
                $total_payment_paid = $payment_total_paid;
            }else {
                $total_payment_paid = '0';
            }

            $total_discount = PurchasePayment::where('soft_delete', '!=', 1)->where('supplier_id', '=', $datas->id)->sum('purchasepayment_discount');
            if($total_discount != ""){
                $total_discount_amont = $total_discount;
            }else {
                $total_discount_amont = '0';
            }

            $total_amount_paid = $total_paid_Amount + $total_payment_paid + $total_discount_amont;



            // Total Balance
            $total_balance = $tot_purchaseAmount - $total_amount_paid;


            $totalpurchase = BranchwiseBalance::where('supplier_id', '=', $datas->id)->sum('purchase_amount');
            $totalpaidpurchase = BranchwiseBalance::where('supplier_id', '=', $datas->id)->sum('purchase_paid');
            $totalpurchasebla = BranchwiseBalance::where('supplier_id', '=', $datas->id)->sum('purchase_balance');



            $supplierarr_data[] = array(
                'unique_key' => $datas->unique_key,
                'name' => $supplier_name->name,
                'contact_number' => $datas->contact_number,
                'shop_name' => $datas->shop_name,
                'total_purchase_amt' => $totalpurchase + $total_discount_amont,
                'total_paid' => $totalpaidpurchase,
                'balance_amount' => $totalpurchasebla,
                'total_discount_amont' => $total_discount_amont,
            );


            $price = array();
            foreach ($supplierarr_data as $key => $row)
            {
                $price[$key] = $row['balance_amount'];
            }
            array_multisort($price, SORT_DESC, $supplierarr_data);

        }

        $total_purchase_amount = Purchase::where('soft_delete', '!=', 1)->sum('gross_amount');
        if($total_purchase_amount != ""){
            $total_purchaseAmount = $total_purchase_amount;
        }else {
            $total_purchaseAmount = '0';
        }


        $supplierOldbalanceTot = Supplier::where('soft_delete', '!=', 1)->sum('old_balance');

        $TotalPurchase = $total_purchaseAmount + $supplierOldbalanceTot;


        $total_amuntpaid = Purchase::where('soft_delete', '!=', 1)->sum('paid_amount');
        if($total_amuntpaid != ""){
            $totalpaid_Amount = $total_amuntpaid;
        }else {
            $totalpaid_Amount = '0';
        }
        $paymenttotal_paid = PurchasePayment::where('soft_delete', '!=', 1)->sum('amount');
        if($paymenttotal_paid != ""){
            $totalpayment_paid = $paymenttotal_paid;
        }else {
            $totalpayment_paid = '0';
        }


        $discountpaid = PurchasePayment::where('soft_delete', '!=', 1)->sum('purchasepayment_discount');
        if($discountpaid != ""){
            $discount_paid = $discountpaid;
        }else {
            $discount_paid = '0';
        }

        $totalamount_paid = $totalpaid_Amount + $totalpayment_paid + $discount_paid;



        // Total Balance
        $totalbalance = $TotalPurchase - $totalamount_paid;
        $pdf = Pdf::loadView('page.backend.supplier.pdfexport_view', [
            'supplierarr_data' => $supplierarr_data,
            'total_purchaseAmount' => $TotalPurchase,
            'totalamount_paid' => $totalamount_paid,
            'totalbalance' => $totalbalance,
            'branch_name' => 'All Branches',

        ]);

        $name = 'Suppliers.' . 'pdf';

        return $pdf->stream($name);

    }



    public function pdf_export($last_word) {
        $data = Supplier::where('soft_delete', '!=', 1)->get();
        $supplierarr_data = [];
        foreach ($data as $key => $datas) {

            $supplier_name = Supplier::findOrFail($datas->id);
            // Grand total
            $total_purchase_amt = Purchase::where('soft_delete', '!=', 1)->where('supplier_id', '=', $datas->id)->where('branch_id', '=', $last_word)->sum('gross_amount');
            if($total_purchase_amt != ""){
                $tot_purchaseAmount = $total_purchase_amt;
            }else {
                $tot_purchaseAmount = '0';
            }

            // Total Paid
            $total_paid = Purchase::where('soft_delete', '!=', 1)->where('supplier_id', '=', $datas->id)->where('branch_id', '=', $last_word)->sum('paid_amount');
            if($total_paid != ""){
                $total_paid_Amount = $total_paid;
            }else {
                $total_paid_Amount = '0';
            }
            $payment_total_paid = PurchasePayment::where('soft_delete', '!=', 1)->where('supplier_id', '=', $datas->id)->where('branch_id', '=', $last_word)->sum('amount');
            if($payment_total_paid != ""){
                $total_payment_paid = $payment_total_paid;
            }else {
                $total_payment_paid = '0';
            }

            $total_discount = PurchasePayment::where('soft_delete', '!=', 1)->where('supplier_id', '=', $datas->id)->where('branch_id', '=', $last_word)->sum('purchasepayment_discount');
            if($total_discount != ""){
                $total_discount_amont = $total_discount;
            }else {
                $total_discount_amont = '0';
            }

            $total_amount_paid = $total_paid_Amount + $total_payment_paid + $total_discount_amont;



            // Total Balance
            $total_balance = $tot_purchaseAmount - $total_amount_paid;

            $totalpurchase = BranchwiseBalance::where('supplier_id', '=', $datas->id)->sum('purchase_amount');
            $totalpaidpurchase = BranchwiseBalance::where('supplier_id', '=', $datas->id)->sum('purchase_paid');
            $totalpurchasebla = BranchwiseBalance::where('supplier_id', '=', $datas->id)->sum('purchase_balance');

            $supplierarr_data[] = array(
                'unique_key' => $datas->unique_key,
                'name' => $supplier_name->name,
                'contact_number' => $datas->contact_number,
                'shop_name' => $datas->shop_name,
                'total_purchase_amt' => $totalpurchase + $total_discount_amont,
                'total_paid' => $totalpaidpurchase,
                'balance_amount' => $totalpurchasebla,
                'total_discount_amont' => $total_discount_amont,
            );

            $price = array();
            foreach ($supplierarr_data as $key => $row)
            {
                $price[$key] = $row['balance_amount'];
            }
            array_multisort($price, SORT_DESC, $supplierarr_data);

        }


        $total_purchase_amount = Purchase::where('soft_delete', '!=', 1)->where('branch_id', '=', $last_word)->sum('gross_amount');
        if($total_purchase_amount != ""){
            $total_purchaseAmount = $total_purchase_amount;
        }else {
            $total_purchaseAmount = '0';
        }

        $supplierOldbalanceTot = Supplier::where('soft_delete', '!=', 1)->sum('old_balance');

        $TotalPurchase = $total_purchaseAmount + $supplierOldbalanceTot;


        $total_amuntpaid = Purchase::where('soft_delete', '!=', 1)->where('branch_id', '=', $last_word)->sum('paid_amount');
        if($total_amuntpaid != ""){
            $totalpaid_Amount = $total_amuntpaid;
        }else {
            $totalpaid_Amount = '0';
        }
        $paymenttotal_paid = PurchasePayment::where('soft_delete', '!=', 1)->where('branch_id', '=', $last_word)->sum('amount');
        if($paymenttotal_paid != ""){
            $totalpayment_paid = $paymenttotal_paid;
        }else {
            $totalpayment_paid = '0';
        }


        $discountpaid = PurchasePayment::where('soft_delete', '!=', 1)->where('branch_id', '=', $last_word)->sum('purchasepayment_discount');
        if($discountpaid != ""){
            $discount_paid = $discountpaid;
        }else {
            $discount_paid = '0';
        }

        $totalamount_paid = $totalpaid_Amount + $totalpayment_paid + $discount_paid;



        // Total Balance
        $totalbalance = $TotalPurchase - $totalamount_paid;

        $branch_name = Branch::findOrFail($last_word);
        $pdf = Pdf::loadView('page.backend.supplier.pdfexport_view', [
            'supplierarr_data' => $supplierarr_data,
            'total_purchaseAmount' => $TotalPurchase,
            'totalamount_paid' => $totalamount_paid,
            'totalbalance' => $totalbalance,
            'branch_name' => $branch_name->shop_name,

        ]);

        $name = 'Suppliers.' . 'pdf';

        return $pdf->stream($name);

    }


    public function store(Request $request)
    {
        $randomkey = Str::random(5);

        $data = new Supplier();

        $data->unique_key = $randomkey;
        $data->name = $request->get('name');
        $data->contact_number = $request->get('contact_number');
        $data->email_address = $request->get('email');
        $data->shop_name = $request->get('shop_name');
        $data->shop_address = $request->get('shop_address');
        $data->shop_contact_number = $request->get('shop_contact_number');

        if($request->get('balance_amount') != ""){
            $balanceAmount = $request->get('balance_amount');
        }else {
            $balanceAmount = 0;
        }


        $data->old_balance = $balanceAmount;

        $data->save();

        $supplierid = $data->id;
        $PaymentBalanceDAta = BranchwiseBalance::where('supplier_id', '=', $supplierid)->first();
        if($PaymentBalanceDAta == "")
        {
            $balance_amount = $request->get('balance_amount');

            $paymentbalacedata = new BranchwiseBalance();

            $paymentbalacedata->supplier_id = $supplierid;
            $paymentbalacedata->branch_id = 1;
            $paymentbalacedata->purchase_balance = $balance_amount;
            $paymentbalacedata->purchase_amount = $balance_amount;
            $paymentbalacedata->purchase_paid = 0;

            $paymentbalacedata->save();
        }

        return redirect()->route('supplier.index')->with('add', 'Supplier Data added successfully!');
    }


    public function edit(Request $request, $unique_key)
    {
        $SupplierData = Supplier::where('unique_key', '=', $unique_key)->first();

        $SupplierData->name = $request->get('name');
        $SupplierData->contact_number = $request->get('contact_number');
        $SupplierData->email_address = $request->get('email');
        $SupplierData->shop_name = $request->get('shop_name');
        $SupplierData->shop_address = $request->get('shop_address');
        $SupplierData->shop_contact_number = $request->get('shop_contact_number');
        $SupplierData->status = $request->get('status');

        $SupplierData->update();

        return redirect()->route('supplier.index')->with('update', 'Supplier Data updated successfully!');
    }


    public function delete($unique_key)
    {
        $data = Supplier::where('unique_key', '=', $unique_key)->first();

        $data->soft_delete = 1;

        $data->update();

        return redirect()->route('supplier.index')->with('soft_destroy', 'Successfully deleted the Supplier !');
    }



    public function supplierview($unique_key, $last_word)
    {

        if($last_word != 'supplier'){

            $SupplierData = Supplier::where('unique_key', '=', $unique_key)->first();

            $today = Carbon::now()->format('Y-m-d');
            $data = Purchase::where('supplier_id', '=', $SupplierData->id)->where('branch_id', '=', $last_word)->where('soft_delete', '!=', 1)->get();


                $purchases = [];
                foreach ($data as $key => $datas_arr) {
                    $purchases[] = $datas_arr;
                }
                $purhcasepayment_s = [];
                $Purchasepaymentdata = PurchasePayment::where('supplier_id', '=', $SupplierData->id)->where('branch_id', '=', $last_word)->where('soft_delete', '!=', 1)->get();
                foreach ($Purchasepaymentdata as $key => $Purchasepaymentdatas) {
                    $purhcasepayment_s[] = $Purchasepaymentdatas;
                }


                $Purchase_data = [];
                $terms = [];

                $merge = array_merge($purchases, $purhcasepayment_s);


            foreach ($merge as $key => $datas) {

                $branch_name = Branch::findOrFail($datas->branch_id);
                $PurchaseProducts = PurchaseProduct::where('purchase_id', '=', $datas->id)->where('branch_id', '=', $last_word)->get();
                foreach ($PurchaseProducts as $key => $PurchaseProducts_arr) {

                    $productlist_ID = Productlist::findOrFail($PurchaseProducts_arr->productlist_id);
                    $terms[] = array(
                        'bag' => $PurchaseProducts_arr->bagorkg,
                        'kgs' => $PurchaseProducts_arr->count,
                        'price_per_kg' => $PurchaseProducts_arr->price_per_kg,
                        'total_price' => $PurchaseProducts_arr->total_price,
                        'product_name' => $productlist_ID->name,
                        'purchase_id' => $PurchaseProducts_arr->purchase_id,

                    );

                }


                if($datas->status != ""){
                    $paid = $datas->paid_amount;
                    $balance = $datas->balance_amount;
                    $type='PURHCASE';
                    $discount = '';
                }else {
                    $paid = $datas->amount;
                    $balance = $datas->payment_pending;
                    $type='PAYMENT';
                    $discount = $datas->purchasepayment_discount;
                }

                $Purchase_data[] = array(
                    'unique_key' => $datas->unique_key,
                    'branch_name' => $branch_name->shop_name,
                    'supplier_name' => $SupplierData->name,
                    'date' => $datas->date,
                    'time' => $datas->time,
                    'gross_amount' => $datas->gross_amount,
                    'paid_amount' => $paid,
                    'bill_no' => $datas->bill_no,
                    'purchase_order' => $datas->purchase_order,
                    'grand_total' => $datas->grand_total,
                    'balance_amount' => $balance,
                    'type' => $type,
                    'id' => $datas->id,
                    'sales_terms' => $terms,
                    'discount' => $discount,
                    'status' => $datas->status,
                    'branchheading' => '',
                    'customerheading' => '',
                    'fromdateheading' => '',
                    'todateheading' => '',
                    'datetime' => $datas->date . $datas->time,

                );
            }

            $branch = Branch::where('soft_delete', '!=', 1)->where('status', '!=', 1)->get();
            $Supplier = Supplier::where('soft_delete', '!=', 1)->get();


            $Suppliername = $SupplierData->name;
            $supplier_id = $SupplierData->id;
            $unique_key = $SupplierData->unique_key;





            $fromdate = '';
            $todate = '';
            $branchid = '';
            $supplierid = $SupplierData->id;



            // $total_sale_amt = Sales::where('soft_delete', '!=', 1)->where('customer_id', '=', $CustomerData->id)->where('branch_id', '=', $last_word)->sum('gross_amount');
            // if($total_sale_amt != ""){
            //     $tot_saleAmount = $total_sale_amt;
            // }else {
            //     $tot_saleAmount = '0';
            // }



            // $total_paid = Sales::where('soft_delete', '!=', 1)->where('customer_id', '=', $CustomerData->id)->where('branch_id', '=', $last_word)->sum('paid_amount');
            // if($total_paid != ""){
            //     $total_paid_Amount = $total_paid;
            // }else {
            //     $total_paid_Amount = '0';
            // }
            // $payment_total_paid = Salespayment::where('soft_delete', '!=', 1)->where('customer_id', '=', $CustomerData->id)->where('branch_id', '=', $last_word)->sum('amount');
            // if($payment_total_paid != ""){
            //     $total_payment_paid = $payment_total_paid;
            // }else {
            //     $total_payment_paid = '0';
            // }


            // $payment_discount = Salespayment::where('soft_delete', '!=', 1)->where('customer_id', '=', $CustomerData->id)->where('branch_id', '=', $last_word)->sum('salespayment_discount');
            // if($payment_discount != ""){
            //     $totpayment_discount = $payment_discount;
            // }else {
            //     $totpayment_discount = '0';
            // }
            // $total_amount_paid = $total_paid_Amount + $total_payment_paid + $totpayment_discount;



            // $total_balance = $tot_saleAmount - $total_amount_paid;



            $totalsaleAmt = BranchwiseBalance::where('supplier_id', '=', $SupplierData->id)->where('branch_id', '=', $last_word)->first();
            if($totalsaleAmt != ""){
                $tot_purchaseAmount = $totalsaleAmt->purchase_amount;
                $total_amount_paid = $totalsaleAmt->purchase_paid;
                $total_balance = $totalsaleAmt->purchase_balance;
            }else {
                $tot_purchaseAmount = '';
                $total_amount_paid = '';
                $total_balance = '';
            }

            $payment_purchase_discount = PurchasePayment::where('supplier_id', '=', $SupplierData->id)->where('branch_id', '=', $last_word)->where('soft_delete', '!=', 1)->sum('purchasepayment_discount');
            if($payment_purchase_discount != ""){
                $paymentpurchase_discount = $payment_purchase_discount;
            }else {
                $paymentpurchase_discount = '0';
            }



            $GETbranch = Branch::findOrFail($last_word);
            $GETBranchname = $GETbranch->shop_name;


            usort($Purchase_data, function($a1, $a2) {
                $value1 = strtotime($a1['datetime']);
                $value2 = strtotime($a2['datetime']);
                return ($value1 < $value2) ? 1 : -1;
             });

            return view('page.backend.supplier.view', compact('SupplierData', 'Purchase_data', 'branch', 'Supplier', 'Suppliername', 'supplier_id', 'unique_key', 'today',
                         'fromdate','todate', 'branchid', 'supplierid',  'tot_purchaseAmount', 'total_amount_paid', 'total_balance', 'GETBranchname', 'paymentpurchase_discount'));


        }else if($last_word == 'supplier'){

            $SupplierData = Supplier::where('unique_key', '=', $unique_key)->first();

            $today = Carbon::now()->format('Y-m-d');
            $data = Purchase::where('supplier_id', '=', $SupplierData->id)->where('soft_delete', '!=', 1)->get();


                $purchases = [];
                foreach ($data as $key => $datas_arr) {
                    $purchases[] = $datas_arr;
                }
                $purhcasepayment_s = [];
                $Purchasepaymentdata = PurchasePayment::where('supplier_id', '=', $SupplierData->id)->where('soft_delete', '!=', 1)->get();
                foreach ($Purchasepaymentdata as $key => $Purchasepaymentdatas) {
                    $purhcasepayment_s[] = $Purchasepaymentdatas;
                }


                $Purchase_data = [];
                $terms = [];

                $merge = array_merge($purchases, $purhcasepayment_s);


            foreach ($merge as $key => $datas) {

                $branch_name = Branch::findOrFail($datas->branch_id);
                $PurchaseProducts = PurchaseProduct::where('purchase_id', '=', $datas->id)->get();
                foreach ($PurchaseProducts as $key => $PurchaseProducts_arr) {

                    $productlist_ID = Productlist::findOrFail($PurchaseProducts_arr->productlist_id);
                    $terms[] = array(
                        'bag' => $PurchaseProducts_arr->bagorkg,
                        'kgs' => $PurchaseProducts_arr->count,
                        'price_per_kg' => $PurchaseProducts_arr->price_per_kg,
                        'total_price' => $PurchaseProducts_arr->total_price,
                        'product_name' => $productlist_ID->name,
                        'purchase_id' => $PurchaseProducts_arr->purchase_id,

                    );

                }


                if($datas->status != ""){
                    $paid = $datas->paid_amount;
                    $balance = $datas->balance_amount;
                    $type='PURHCASE';
                    $discount = '';
                }else {
                    $paid = $datas->amount;
                    $balance = $datas->payment_pending;
                    $type='PAYMENT';
                    $discount = $datas->purchasepayment_discount;
                }

                $Purchase_data[] = array(
                    'unique_key' => $datas->unique_key,
                    'branch_name' => $branch_name->shop_name,
                    'supplier_name' => $SupplierData->name,
                    'date' => $datas->date,
                    'time' => $datas->time,
                    'gross_amount' => $datas->gross_amount,
                    'paid_amount' => $paid,
                    'bill_no' => $datas->bill_no,
                    'purchase_order' => $datas->purchase_order,
                    'grand_total' => $datas->grand_total,
                    'balance_amount' => $balance,
                    'type' => $type,
                    'id' => $datas->id,
                    'sales_terms' => $terms,
                    'discount' => $discount,
                    'status' => $datas->status,
                    'branchheading' => '',
                    'customerheading' => '',
                    'fromdateheading' => '',
                    'todateheading' => '',
                    'datetime' => $datas->date . $datas->time,

                );
            }

            $branch = Branch::where('soft_delete', '!=', 1)->where('status', '!=', 1)->get();
            $Supplier = Supplier::where('soft_delete', '!=', 1)->get();


            $Suppliername = $SupplierData->name;
            $supplier_id = $SupplierData->id;
            $unique_key = $SupplierData->unique_key;





            $fromdate = '';
            $todate = '';
            $branchid = '';
            $supplierid = $SupplierData->id;



            // $total_sale_amt = Sales::where('soft_delete', '!=', 1)->where('customer_id', '=', $CustomerData->id)->where('branch_id', '=', $last_word)->sum('gross_amount');
            // if($total_sale_amt != ""){
            //     $tot_saleAmount = $total_sale_amt;
            // }else {
            //     $tot_saleAmount = '0';
            // }



            // $total_paid = Sales::where('soft_delete', '!=', 1)->where('customer_id', '=', $CustomerData->id)->where('branch_id', '=', $last_word)->sum('paid_amount');
            // if($total_paid != ""){
            //     $total_paid_Amount = $total_paid;
            // }else {
            //     $total_paid_Amount = '0';
            // }
            // $payment_total_paid = Salespayment::where('soft_delete', '!=', 1)->where('customer_id', '=', $CustomerData->id)->where('branch_id', '=', $last_word)->sum('amount');
            // if($payment_total_paid != ""){
            //     $total_payment_paid = $payment_total_paid;
            // }else {
            //     $total_payment_paid = '0';
            // }


            // $payment_discount = Salespayment::where('soft_delete', '!=', 1)->where('customer_id', '=', $CustomerData->id)->where('branch_id', '=', $last_word)->sum('salespayment_discount');
            // if($payment_discount != ""){
            //     $totpayment_discount = $payment_discount;
            // }else {
            //     $totpayment_discount = '0';
            // }
            // $total_amount_paid = $total_paid_Amount + $total_payment_paid + $totpayment_discount;



            // $total_balance = $tot_saleAmount - $total_amount_paid;



            $totalsaleAmt = BranchwiseBalance::where('supplier_id', '=', $SupplierData->id)->first();
            if($totalsaleAmt != ""){
                $tot_purchaseAmount = $totalsaleAmt->purchase_amount;
                $total_amount_paid = $totalsaleAmt->purchase_paid;
                $total_balance = $totalsaleAmt->purchase_balance;
            }else {
                $tot_purchaseAmount = '';
                $total_amount_paid = '';
                $total_balance = '';
            }

            $payment_purchase_discount = PurchasePayment::where('supplier_id', '=', $SupplierData->id)->where('soft_delete', '!=', 1)->sum('purchasepayment_discount');
            if($payment_purchase_discount != ""){
                $paymentpurchase_discount = $payment_purchase_discount;
            }else {
                $paymentpurchase_discount = '0';
            }



            $GETbranch = Branch::findOrFail($last_word);
            $GETBranchname = $GETbranch->shop_name;


            usort($Purchase_data, function($a1, $a2) {
                $value1 = strtotime($a1['datetime']);
                $value2 = strtotime($a2['datetime']);
                return ($value1 < $value2) ? 1 : -1;
             });

            return view('page.backend.supplier.view', compact('SupplierData', 'Purchase_data', 'branch', 'Supplier', 'Suppliername', 'supplier_id', 'unique_key', 'today',
                         'fromdate','todate', 'branchid', 'supplierid',  'tot_purchaseAmount', 'total_amount_paid', 'total_balance', 'GETBranchname', 'paymentpurchase_discount'));

        }


    }



    public function viewfilter(Request $request, $unique_key, $last_word)
    {
        $branchid = $request->get('branchid');
        $unique_key = $request->get('uniquekey');
        $SupplierData = Supplier::where('unique_key', '=', $unique_key)->first();

        $Supplier = Supplier::where('soft_delete', '!=', 1)->where('status', '!=', 1)->get();



        $fromdate = $request->get('fromdate');
        $todate = $request->get('todate');

        if($branchid != 'supplier'){

            if($fromdate){
                $GETbranch = Branch::findOrFail($branchid);
                $GETBranchname = $GETbranch->shop_name;

                $data = Purchase::where('date', '=', $fromdate)->where('supplier_id', '=', $SupplierData->id)->where('branch_id', '=', $branchid)->where('soft_delete', '!=', 1)->get();

                $sales = [];
                foreach ($data as $key => $datas_arr) {
                    $sales[] = $datas_arr;
                }
                $salepayment_s = [];
                $Salespaymentdata = PurchasePayment::where('date', '=', $fromdate)->where('supplier_id', '=', $SupplierData->id)->where('branch_id', '=', $branchid)->where('soft_delete', '!=', 1)->get();
                foreach ($Salespaymentdata as $key => $Salespaymentdatas) {
                    $salepayment_s[] = $Salespaymentdatas;
                }


                $Purchase_data = [];
                $sales_terms = [];

                $merge = array_merge($sales, $salepayment_s);





                foreach ($merge as $key => $datas) {

                    $branch_name = Branch::findOrFail($datas->branch_id);
                    $SalesProducts = PurchaseProduct::where('purchase_id', '=', $datas->id)->where('branch_id', '=', $branchid)->get();
                    foreach ($SalesProducts as $key => $SalesProducts_arr) {

                        $productlist_ID = Productlist::findOrFail($SalesProducts_arr->productlist_id);
                        $terms[] = array(
                            'bag' => $SalesProducts_arr->bagorkg,
                            'kgs' => $SalesProducts_arr->count,
                            'price_per_kg' => $SalesProducts_arr->price_per_kg,
                            'total_price' => $SalesProducts_arr->total_price,
                            'product_name' => $productlist_ID->name,
                            'purchase_id' => $SalesProducts_arr->purchase_id,

                        );

                    }


                    if($datas->status != ""){
                        $paid = $datas->paid_amount;
                        $balance = $datas->balance_amount;
                        $type='PURHCASE';
                        $discount = '';
                    }else {
                        $paid = $datas->amount;
                        $balance = $datas->payment_pending;
                        $type='PAYMENT';
                        $discount = $datas->purchasepayment_discount;
                    }

                    $Purchase_data[] = array(
                        'unique_key' => $datas->unique_key,
                        'branch_name' => $branch_name->shop_name,
                        'customer_name' => $SupplierData->name,
                        'date' => $datas->date,
                        'time' => $datas->time,
                        'gross_amount' => $datas->gross_amount,
                        'paid_amount' => $paid,
                        'bill_no' => $datas->bill_no,
                        'purchase_order' => $datas->purchase_order,
                        'grand_total' => $datas->grand_total,
                        'balance_amount' => $balance,
                        'type' => $type,
                        'id' => $datas->id,
                        'sales_terms' => $terms,
                        'discount' => $discount,
                        'status' => $datas->status,
                        'branchheading' => '',
                        'customerheading' => '',
                        'fromdateheading' => '',
                        'todateheading' => '',
                        'datetime' => $datas->date . $datas->time,

                    );
                }

                $Suppliername = $SupplierData->name;
                $supplier_id = $SupplierData->id;
                $unique_key = $SupplierData->unique_key;
                $supplierid = $SupplierData->id;


                $total_sale_amt = Purchase::where('date', '=', $fromdate)->where('soft_delete', '!=', 1)->where('supplier_id', '=', $SupplierData->id)->where('branch_id', '=', $branchid)->sum('gross_amount');
                if($total_sale_amt != ""){
                    $tot_purchaseAmount = $total_sale_amt;
                }else {
                    $tot_purchaseAmount = '0';
                }


                // Total Paid
                $total_paid = Purchase::where('date', '=', $fromdate)->where('soft_delete', '!=', 1)->where('supplier_id', '=', $SupplierData->id)->where('branch_id', '=', $branchid)->sum('payment_paid_amount');
                if($total_paid != ""){
                    $total_paid_Amount = $total_paid;
                }else {
                    $total_paid_Amount = '0';
                }
                $payment_total_paid = PurchasePayment::where('date', '=', $fromdate)->where('soft_delete', '!=', 1)->where('supplier_id', '=', $SupplierData->id)->where('branch_id', '=', $branchid)->sum('amount');
                if($payment_total_paid != ""){
                    $total_payment_paid = $payment_total_paid;
                }else {
                    $total_payment_paid = '0';
                }


                $payment_discount = PurchasePayment::where('date', '=', $fromdate)->where('soft_delete', '!=', 1)->where('supplier_id', '=', $SupplierData->id)->where('branch_id', '=', $branchid)->sum('purchasepayment_discount');
                if($payment_discount != ""){
                    $totpayment_discount = $payment_discount;
                }else {
                    $totpayment_discount = '0';
                }
                $total_amount_paid = $total_paid_Amount + $total_payment_paid + $totpayment_discount;
                $total_balance = $tot_purchaseAmount - $total_amount_paid;


                // $tot_saleAmount = BranchwiseBalance::where('customer_id', '=', $SupplierData->id)->where('branch_id', '=', $branchid)->sum('sales_amount');
                // $total_amount_paid = BranchwiseBalance::where('customer_id', '=', $SupplierData->id)->where('branch_id', '=', $branchid)->sum('sales_paid');
                // $total_balance = BranchwiseBalance::where('customer_id', '=', $SupplierData->id)->where('branch_id', '=', $branchid)->sum('sales_balance');
    
                $payment_sale_discount = PurchasePayment::where('date', '=', $fromdate)->
                                                        where('supplier_id', '=', $SupplierData->id)
                                                        ->where('branch_id', '=', $branchid)
                                                        ->where('soft_delete', '!=', 1)
                                                        ->sum('purchasepayment_discount');
                if($payment_sale_discount != ""){
                    $paymentpurchase_discount = $payment_sale_discount;
                }else {
                    $paymentpurchase_discount = '0';
                }


            }



            if($todate){
                $GETbranch = Branch::findOrFail($branchid);
                $GETBranchname = $GETbranch->shop_name;

                $data = Purchase::where('date', '=', $todate)->where('supplier_id', '=', $SupplierData->id)->where('branch_id', '=', $branchid)->where('soft_delete', '!=', 1)->get();

                $sales = [];
                foreach ($data as $key => $datas_arr) {
                    $sales[] = $datas_arr;
                }
                $salepayment_s = [];
                $Salespaymentdata = PurchasePayment::where('date', '=', $todate)->where('supplier_id', '=', $SupplierData->id)->where('branch_id', '=', $branchid)->where('soft_delete', '!=', 1)->get();
                foreach ($Salespaymentdata as $key => $Salespaymentdatas) {
                    $salepayment_s[] = $Salespaymentdatas;
                }


                $Purchase_data = [];
                $sales_terms = [];

                $merge = array_merge($sales, $salepayment_s);





                foreach ($merge as $key => $datas) {

                    $branch_name = Branch::findOrFail($datas->branch_id);
                    $SalesProducts = PurchaseProduct::where('purchase_id', '=', $datas->id)->where('branch_id', '=', $branchid)->get();
                    foreach ($SalesProducts as $key => $SalesProducts_arr) {

                        $productlist_ID = Productlist::findOrFail($SalesProducts_arr->productlist_id);
                        $terms[] = array(
                            'bag' => $SalesProducts_arr->bagorkg,
                            'kgs' => $SalesProducts_arr->count,
                            'price_per_kg' => $SalesProducts_arr->price_per_kg,
                            'total_price' => $SalesProducts_arr->total_price,
                            'product_name' => $productlist_ID->name,
                            'purchase_id' => $SalesProducts_arr->purchase_id,

                        );

                    }


                    if($datas->status != ""){
                        $paid = $datas->paid_amount;
                        $balance = $datas->balance_amount;
                        $type='PURHCASE';
                        $discount = '';
                    }else {
                        $paid = $datas->amount;
                        $balance = $datas->payment_pending;
                        $type='PAYMENT';
                        $discount = $datas->purchasepayment_discount;
                    }

                    $Purchase_data[] = array(
                        'unique_key' => $datas->unique_key,
                        'branch_name' => $branch_name->shop_name,
                        'customer_name' => $SupplierData->name,
                        'date' => $datas->date,
                        'time' => $datas->time,
                        'gross_amount' => $datas->gross_amount,
                        'paid_amount' => $paid,
                        'bill_no' => $datas->bill_no,
                        'purchase_order' => $datas->purchase_order,
                        'grand_total' => $datas->grand_total,
                        'balance_amount' => $balance,
                        'type' => $type,
                        'id' => $datas->id,
                        'sales_terms' => $terms,
                        'discount' => $discount,
                        'status' => $datas->status,
                        'branchheading' => '',
                        'customerheading' => '',
                        'fromdateheading' => '',
                        'todateheading' => '',
                        'datetime' => $datas->date . $datas->time,

                    );
                }

                $Suppliername = $SupplierData->name;
                $supplier_id = $SupplierData->id;
                $unique_key = $SupplierData->unique_key;
                $supplierid = $SupplierData->id;


                $total_sale_amt = Purchase::where('date', '=', $todate)->where('soft_delete', '!=', 1)->where('supplier_id', '=', $SupplierData->id)->where('branch_id', '=', $branchid)->sum('gross_amount');
                if($total_sale_amt != ""){
                    $tot_purchaseAmount = $total_sale_amt;
                }else {
                    $tot_purchaseAmount = '0';
                }


                // Total Paid
                $total_paid = Purchase::where('date', '=', $todate)->where('soft_delete', '!=', 1)->where('supplier_id', '=', $SupplierData->id)->where('branch_id', '=', $branchid)->sum('payment_paid_amount');
                if($total_paid != ""){
                    $total_paid_Amount = $total_paid;
                }else {
                    $total_paid_Amount = '0';
                }
                $payment_total_paid = PurchasePayment::where('date', '=', $todate)->where('soft_delete', '!=', 1)->where('supplier_id', '=', $SupplierData->id)->where('branch_id', '=', $branchid)->sum('amount');
                if($payment_total_paid != ""){
                    $total_payment_paid = $payment_total_paid;
                }else {
                    $total_payment_paid = '0';
                }


                $payment_discount = PurchasePayment::where('date', '=', $todate)->where('soft_delete', '!=', 1)->where('supplier_id', '=', $SupplierData->id)->where('branch_id', '=', $branchid)->sum('purchasepayment_discount');
                if($payment_discount != ""){
                    $totpayment_discount = $payment_discount;
                }else {
                    $totpayment_discount = '0';
                }
                $total_amount_paid = $total_paid_Amount + $total_payment_paid + $totpayment_discount;
                $total_balance = $tot_purchaseAmount - $total_amount_paid;


                // $tot_saleAmount = BranchwiseBalance::where('customer_id', '=', $SupplierData->id)->where('branch_id', '=', $branchid)->sum('sales_amount');
                // $total_amount_paid = BranchwiseBalance::where('customer_id', '=', $SupplierData->id)->where('branch_id', '=', $branchid)->sum('sales_paid');
                // $total_balance = BranchwiseBalance::where('customer_id', '=', $SupplierData->id)->where('branch_id', '=', $branchid)->sum('sales_balance');
    
                $payment_sale_discount = PurchasePayment::where('date', '=', $todate)->where('supplier_id', '=', $SupplierData->id)
                                                        ->where('branch_id', '=', $branchid)
                                                        ->where('soft_delete', '!=', 1)
                                                        ->sum('purchasepayment_discount');
                if($payment_sale_discount != ""){
                    $paymentpurchase_discount = $payment_sale_discount;
                }else {
                    $paymentpurchase_discount = '0';
                }


            }

            if($fromdate && $todate){
                $GETbranch = Branch::findOrFail($branchid);
                $GETBranchname = $GETbranch->shop_name;

                $data = Purchase::whereBetween('date', [$fromdate, $todate])->where('supplier_id', '=', $SupplierData->id)->where('branch_id', '=', $branchid)->where('soft_delete', '!=', 1)->get();

                $sales = [];
                foreach ($data as $key => $datas_arr) {
                    $sales[] = $datas_arr;
                }
                $salepayment_s = [];
                $Salespaymentdata = PurchasePayment::whereBetween('date', [$fromdate, $todate])->where('supplier_id', '=', $SupplierData->id)->where('branch_id', '=', $branchid)->where('soft_delete', '!=', 1)->get();
                foreach ($Salespaymentdata as $key => $Salespaymentdatas) {
                    $salepayment_s[] = $Salespaymentdatas;
                }


                $Purchase_data = [];
                $sales_terms = [];

                $merge = array_merge($sales, $salepayment_s);





                foreach ($merge as $key => $datas) {

                    $branch_name = Branch::findOrFail($datas->branch_id);
                    $SalesProducts = PurchaseProduct::where('purchase_id', '=', $datas->id)->where('branch_id', '=', $branchid)->get();
                    foreach ($SalesProducts as $key => $SalesProducts_arr) {

                        $productlist_ID = Productlist::findOrFail($SalesProducts_arr->productlist_id);
                        $terms[] = array(
                            'bag' => $SalesProducts_arr->bagorkg,
                            'kgs' => $SalesProducts_arr->count,
                            'price_per_kg' => $SalesProducts_arr->price_per_kg,
                            'total_price' => $SalesProducts_arr->total_price,
                            'product_name' => $productlist_ID->name,
                            'purchase_id' => $SalesProducts_arr->purchase_id,

                        );

                    }


                    if($datas->status != ""){
                        $paid = $datas->paid_amount;
                        $balance = $datas->balance_amount;
                        $type='PURHCASE';
                        $discount = '';
                    }else {
                        $paid = $datas->amount;
                        $balance = $datas->payment_pending;
                        $type='PAYMENT';
                        $discount = $datas->purchasepayment_discount;
                    }

                    $Purchase_data[] = array(
                        'unique_key' => $datas->unique_key,
                        'branch_name' => $branch_name->shop_name,
                        'customer_name' => $SupplierData->name,
                        'date' => $datas->date,
                        'time' => $datas->time,
                        'gross_amount' => $datas->gross_amount,
                        'paid_amount' => $paid,
                        'bill_no' => $datas->bill_no,
                        'purchase_order' => $datas->purchase_order,
                        'grand_total' => $datas->grand_total,
                        'balance_amount' => $balance,
                        'type' => $type,
                        'id' => $datas->id,
                        'sales_terms' => $terms,
                        'discount' => $discount,
                        'status' => $datas->status,
                        'branchheading' => '',
                        'customerheading' => '',
                        'fromdateheading' => '',
                        'todateheading' => '',
                        'datetime' => $datas->date . $datas->time,

                    );
                }

                $Suppliername = $SupplierData->name;
                $supplier_id = $SupplierData->id;
                $unique_key = $SupplierData->unique_key;
                $supplierid = $SupplierData->id;


                $total_sale_amt = Purchase::whereBetween('date', [$fromdate, $todate])->where('soft_delete', '!=', 1)->where('supplier_id', '=', $SupplierData->id)->where('branch_id', '=', $branchid)->sum('gross_amount');
                if($total_sale_amt != ""){
                    $tot_purchaseAmount = $total_sale_amt;
                }else {
                    $tot_purchaseAmount = '0';
                }


                // Total Paid
                $total_paid = Purchase::whereBetween('date', [$fromdate, $todate])->where('soft_delete', '!=', 1)->where('supplier_id', '=', $SupplierData->id)->where('branch_id', '=', $branchid)->sum('payment_paid_amount');
                if($total_paid != ""){
                    $total_paid_Amount = $total_paid;
                }else {
                    $total_paid_Amount = '0';
                }
                $payment_total_paid = PurchasePayment::whereBetween('date', [$fromdate, $todate])->where('soft_delete', '!=', 1)->where('supplier_id', '=', $SupplierData->id)->where('branch_id', '=', $branchid)->sum('amount');
                if($payment_total_paid != ""){
                    $total_payment_paid = $payment_total_paid;
                }else {
                    $total_payment_paid = '0';
                }


                $payment_discount = PurchasePayment::whereBetween('date', [$fromdate, $todate])->where('soft_delete', '!=', 1)->where('supplier_id', '=', $SupplierData->id)->where('branch_id', '=', $branchid)->sum('purchasepayment_discount');
                if($payment_discount != ""){
                    $totpayment_discount = $payment_discount;
                }else {
                    $totpayment_discount = '0';
                }
                $total_amount_paid = $total_paid_Amount + $total_payment_paid + $totpayment_discount;
                $total_balance = $tot_purchaseAmount - $total_amount_paid;


                // $tot_saleAmount = BranchwiseBalance::where('customer_id', '=', $SupplierData->id)->where('branch_id', '=', $branchid)->sum('sales_amount');
                // $total_amount_paid = BranchwiseBalance::where('customer_id', '=', $SupplierData->id)->where('branch_id', '=', $branchid)->sum('sales_paid');
                // $total_balance = BranchwiseBalance::where('customer_id', '=', $SupplierData->id)->where('branch_id', '=', $branchid)->sum('sales_balance');
    
                $payment_sale_discount = PurchasePayment::whereBetween('date', [$fromdate, $todate])->where('supplier_id', '=', $SupplierData->id)
                                                        ->where('branch_id', '=', $branchid)
                                                        ->where('soft_delete', '!=', 1)
                                                        ->sum('purchasepayment_discount');
                if($payment_sale_discount != ""){
                    $paymentpurchase_discount = $payment_sale_discount;
                }else {
                    $paymentpurchase_discount = '0';
                }

            }


        }else if($branchid == 'customer'){


            if($fromdate){
                $GETBranchname = '';

                $data = Purchase::where('date', '=', $fromdate)->where('supplier_id', '=', $SupplierData->id)->where('soft_delete', '!=', 1)->get();

                $sales = [];
                foreach ($data as $key => $datas_arr) {
                    $sales[] = $datas_arr;
                }
                $salepayment_s = [];
                $Salespaymentdata = PurchasePayment::where('date', '=', $fromdate)->where('supplier_id', '=', $SupplierData->id)->where('soft_delete', '!=', 1)->get();
                foreach ($Salespaymentdata as $key => $Salespaymentdatas) {
                    $salepayment_s[] = $Salespaymentdatas;
                }


                $Purchase_data = [];
                $sales_terms = [];

                $merge = array_merge($sales, $salepayment_s);





                foreach ($merge as $key => $datas) {

                    $SalesProducts = PurchaseProduct::where('purchase_id', '=', $datas->id)->get();
                    foreach ($SalesProducts as $key => $SalesProducts_arr) {

                        $productlist_ID = Productlist::findOrFail($SalesProducts_arr->productlist_id);
                        $terms[] = array(
                            'bag' => $SalesProducts_arr->bagorkg,
                            'kgs' => $SalesProducts_arr->count,
                            'price_per_kg' => $SalesProducts_arr->price_per_kg,
                            'total_price' => $SalesProducts_arr->total_price,
                            'product_name' => $productlist_ID->name,
                            'purchase_id' => $SalesProducts_arr->purchase_id,

                        );

                    }


                    if($datas->status != ""){
                        $paid = $datas->paid_amount;
                        $balance = $datas->balance_amount;
                        $type='PURHCASE';
                        $discount = '';
                    }else {
                        $paid = $datas->amount;
                        $balance = $datas->payment_pending;
                        $type='PAYMENT';
                        $discount = $datas->purchasepayment_discount;
                    }

                    $Purchase_data[] = array(
                        'unique_key' => $datas->unique_key,
                        'branch_name' => $branch_name->shop_name,
                        'customer_name' => $SupplierData->name,
                        'date' => $datas->date,
                        'time' => $datas->time,
                        'gross_amount' => $datas->gross_amount,
                        'paid_amount' => $paid,
                        'bill_no' => $datas->bill_no,
                        'purchase_order' => $datas->purchase_order,
                        'grand_total' => $datas->grand_total,
                        'balance_amount' => $balance,
                        'type' => $type,
                        'id' => $datas->id,
                        'sales_terms' => $terms,
                        'discount' => $discount,
                        'status' => $datas->status,
                        'branchheading' => '',
                        'customerheading' => '',
                        'fromdateheading' => '',
                        'todateheading' => '',
                        'datetime' => $datas->date . $datas->time,

                    );
                }

                $Suppliername = $SupplierData->name;
                $supplier_id = $SupplierData->id;
                $unique_key = $SupplierData->unique_key;
                $supplierid = $SupplierData->id;


                $total_sale_amt = Purchase::where('date', '=', $fromdate)->where('soft_delete', '!=', 1)->where('supplier_id', '=', $SupplierData->id)->sum('gross_amount');
                if($total_sale_amt != ""){
                    $tot_purchaseAmount = $total_sale_amt;
                }else {
                    $tot_purchaseAmount = '0';
                }


                // Total Paid
                $total_paid = Purchase::where('date', '=', $fromdate)->where('soft_delete', '!=', 1)->where('supplier_id', '=', $SupplierData->id)->sum('payment_paid_amount');
                if($total_paid != ""){
                    $total_paid_Amount = $total_paid;
                }else {
                    $total_paid_Amount = '0';
                }
                $payment_total_paid = PurchasePayment::where('date', '=', $fromdate)->where('soft_delete', '!=', 1)->where('supplier_id', '=', $SupplierData->id)->sum('amount');
                if($payment_total_paid != ""){
                    $total_payment_paid = $payment_total_paid;
                }else {
                    $total_payment_paid = '0';
                }


                $payment_discount = PurchasePayment::where('date', '=', $fromdate)->where('soft_delete', '!=', 1)->where('supplier_id', '=', $SupplierData->id)->sum('purchasepayment_discount');
                if($payment_discount != ""){
                    $totpayment_discount = $payment_discount;
                }else {
                    $totpayment_discount = '0';
                }
                $total_amount_paid = $total_paid_Amount + $total_payment_paid + $totpayment_discount;
                $total_balance = $tot_purchaseAmount - $total_amount_paid;


                // $tot_saleAmount = BranchwiseBalance::where('customer_id', '=', $SupplierData->id)->where('branch_id', '=', $branchid)->sum('sales_amount');
                // $total_amount_paid = BranchwiseBalance::where('customer_id', '=', $SupplierData->id)->where('branch_id', '=', $branchid)->sum('sales_paid');
                // $total_balance = BranchwiseBalance::where('customer_id', '=', $SupplierData->id)->where('branch_id', '=', $branchid)->sum('sales_balance');
    
                $payment_sale_discount = PurchasePayment::where('date', '=', $fromdate)->where('supplier_id', '=', $SupplierData->id)
                                                        ->where('soft_delete', '!=', 1)
                                                        ->sum('purchasepayment_discount');
                if($payment_sale_discount != ""){
                    $paymentpurchase_discount = $payment_sale_discount;
                }else {
                    $paymentpurchase_discount = '0';
                }


            }



            if($todate){
                $GETBranchname = '';

                $data = Purchase::where('date', '=', $todate)->where('supplier_id', '=', $SupplierData->id)->where('soft_delete', '!=', 1)->get();

                $sales = [];
                foreach ($data as $key => $datas_arr) {
                    $sales[] = $datas_arr;
                }
                $salepayment_s = [];
                $Salespaymentdata = PurchasePayment::where('date', '=', $todate)->where('supplier_id', '=', $SupplierData->id)->where('soft_delete', '!=', 1)->get();
                foreach ($Salespaymentdata as $key => $Salespaymentdatas) {
                    $salepayment_s[] = $Salespaymentdatas;
                }


                $Purchase_data = [];
                $sales_terms = [];

                $merge = array_merge($sales, $salepayment_s);





                foreach ($merge as $key => $datas) {

                    $SalesProducts = PurchaseProduct::where('purchase_id', '=', $datas->id)->get();
                    foreach ($SalesProducts as $key => $SalesProducts_arr) {

                        $productlist_ID = Productlist::findOrFail($SalesProducts_arr->productlist_id);
                        $terms[] = array(
                            'bag' => $SalesProducts_arr->bagorkg,
                            'kgs' => $SalesProducts_arr->count,
                            'price_per_kg' => $SalesProducts_arr->price_per_kg,
                            'total_price' => $SalesProducts_arr->total_price,
                            'product_name' => $productlist_ID->name,
                            'purchase_id' => $SalesProducts_arr->purchase_id,

                        );

                    }


                    if($datas->status != ""){
                        $paid = $datas->paid_amount;
                        $balance = $datas->balance_amount;
                        $type='PURHCASE';
                        $discount = '';
                    }else {
                        $paid = $datas->amount;
                        $balance = $datas->payment_pending;
                        $type='PAYMENT';
                        $discount = $datas->purchasepayment_discount;
                    }

                    $Purchase_data[] = array(
                        'unique_key' => $datas->unique_key,
                        'branch_name' => '',
                        'customer_name' => $SupplierData->name,
                        'date' => $datas->date,
                        'time' => $datas->time,
                        'gross_amount' => $datas->gross_amount,
                        'paid_amount' => $paid,
                        'bill_no' => $datas->bill_no,
                        'purchase_order' => $datas->purchase_order,
                        'grand_total' => $datas->grand_total,
                        'balance_amount' => $balance,
                        'type' => $type,
                        'id' => $datas->id,
                        'sales_terms' => $terms,
                        'discount' => $discount,
                        'status' => $datas->status,
                        'branchheading' => '',
                        'customerheading' => '',
                        'fromdateheading' => '',
                        'todateheading' => '',
                        'datetime' => $datas->date . $datas->time,

                    );
                }

                $Suppliername = $SupplierData->name;
                $supplier_id = $SupplierData->id;
                $unique_key = $SupplierData->unique_key;
                $supplierid = $SupplierData->id;


                $total_sale_amt = Purchase::where('date', '=', $todate)->where('soft_delete', '!=', 1)->where('supplier_id', '=', $SupplierData->id)->sum('gross_amount');
                if($total_sale_amt != ""){
                    $tot_purchaseAmount = $total_sale_amt;
                }else {
                    $tot_purchaseAmount = '0';
                }


                // Total Paid
                $total_paid = Purchase::where('date', '=', $todate)->where('soft_delete', '!=', 1)->where('supplier_id', '=', $SupplierData->id)->sum('payment_paid_amount');
                if($total_paid != ""){
                    $total_paid_Amount = $total_paid;
                }else {
                    $total_paid_Amount = '0';
                }
                $payment_total_paid = PurchasePayment::where('date', '=', $todate)->where('soft_delete', '!=', 1)->where('supplier_id', '=', $SupplierData->id)->sum('amount');
                if($payment_total_paid != ""){
                    $total_payment_paid = $payment_total_paid;
                }else {
                    $total_payment_paid = '0';
                }


                $payment_discount = PurchasePayment::where('date', '=', $todate)->where('soft_delete', '!=', 1)->where('supplier_id', '=', $SupplierData->id)->sum('purchasepayment_discount');
                if($payment_discount != ""){
                    $totpayment_discount = $payment_discount;
                }else {
                    $totpayment_discount = '0';
                }
                $total_amount_paid = $total_paid_Amount + $total_payment_paid + $totpayment_discount;
                $total_balance = $tot_purchaseAmount - $total_amount_paid;


                // $tot_saleAmount = BranchwiseBalance::where('customer_id', '=', $SupplierData->id)->where('branch_id', '=', $branchid)->sum('sales_amount');
                // $total_amount_paid = BranchwiseBalance::where('customer_id', '=', $SupplierData->id)->where('branch_id', '=', $branchid)->sum('sales_paid');
                // $total_balance = BranchwiseBalance::where('customer_id', '=', $SupplierData->id)->where('branch_id', '=', $branchid)->sum('sales_balance');
    
                $payment_sale_discount = PurchasePayment::where('date', '=', $todate)->where('supplier_id', '=', $SupplierData->id)
                                                        ->where('soft_delete', '!=', 1)
                                                        ->sum('purchasepayment_discount');
                if($payment_sale_discount != ""){
                    $paymentpurchase_discount = $payment_sale_discount;
                }else {
                    $paymentpurchase_discount = '0';
                }


            }

            if($fromdate && $todate){
                $GETBranchname = '';

                $data = Purchase::whereBetween('date', [$fromdate, $todate])->where('supplier_id', '=', $SupplierData->id)->where('soft_delete', '!=', 1)->get();

                $sales = [];
                foreach ($data as $key => $datas_arr) {
                    $sales[] = $datas_arr;
                }
                $salepayment_s = [];
                $Salespaymentdata = PurchasePayment::whereBetween('date', [$fromdate, $todate])->where('supplier_id', '=', $SupplierData->id)->where('soft_delete', '!=', 1)->get();
                foreach ($Salespaymentdata as $key => $Salespaymentdatas) {
                    $salepayment_s[] = $Salespaymentdatas;
                }


                $Purchase_data = [];
                $sales_terms = [];

                $merge = array_merge($sales, $salepayment_s);





                foreach ($merge as $key => $datas) {

                    $SalesProducts = PurchaseProduct::where('purchase_id', '=', $datas->id)->get();
                    foreach ($SalesProducts as $key => $SalesProducts_arr) {

                        $productlist_ID = Productlist::findOrFail($SalesProducts_arr->productlist_id);
                        $terms[] = array(
                            'bag' => $SalesProducts_arr->bagorkg,
                            'kgs' => $SalesProducts_arr->count,
                            'price_per_kg' => $SalesProducts_arr->price_per_kg,
                            'total_price' => $SalesProducts_arr->total_price,
                            'product_name' => $productlist_ID->name,
                            'purchase_id' => $SalesProducts_arr->purchase_id,

                        );

                    }


                    if($datas->status != ""){
                        $paid = $datas->paid_amount;
                        $balance = $datas->balance_amount;
                        $type='PURHCASE';
                        $discount = '';
                    }else {
                        $paid = $datas->amount;
                        $balance = $datas->payment_pending;
                        $type='PAYMENT';
                        $discount = $datas->purchasepayment_discount;
                    }

                    $Purchase_data[] = array(
                        'unique_key' => $datas->unique_key,
                        'branch_name' => '',
                        'customer_name' => $SupplierData->name,
                        'date' => $datas->date,
                        'time' => $datas->time,
                        'gross_amount' => $datas->gross_amount,
                        'paid_amount' => $paid,
                        'bill_no' => $datas->bill_no,
                        'purchase_order' => $datas->purchase_order,
                        'grand_total' => $datas->grand_total,
                        'balance_amount' => $balance,
                        'type' => $type,
                        'id' => $datas->id,
                        'sales_terms' => $terms,
                        'discount' => $discount,
                        'status' => $datas->status,
                        'branchheading' => '',
                        'customerheading' => '',
                        'fromdateheading' => '',
                        'todateheading' => '',
                        'datetime' => $datas->date . $datas->time,

                    );
                }

                $Suppliername = $SupplierData->name;
                $supplier_id = $SupplierData->id;
                $unique_key = $SupplierData->unique_key;
                $supplierid = $SupplierData->id;


                $total_sale_amt = Purchase::whereBetween('date', [$fromdate, $todate])->where('soft_delete', '!=', 1)->where('supplier_id', '=', $SupplierData->id)->sum('gross_amount');
                if($total_sale_amt != ""){
                    $tot_purchaseAmount = $total_sale_amt;
                }else {
                    $tot_purchaseAmount = '0';
                }


                // Total Paid
                $total_paid = Purchase::whereBetween('date', [$fromdate, $todate])->where('soft_delete', '!=', 1)->where('supplier_id', '=', $SupplierData->id)->sum('payment_paid_amount');
                if($total_paid != ""){
                    $total_paid_Amount = $total_paid;
                }else {
                    $total_paid_Amount = '0';
                }
                $payment_total_paid = PurchasePayment::whereBetween('date', [$fromdate, $todate])->where('soft_delete', '!=', 1)->where('supplier_id', '=', $SupplierData->id)->sum('amount');
                if($payment_total_paid != ""){
                    $total_payment_paid = $payment_total_paid;
                }else {
                    $total_payment_paid = '0';
                }


                $payment_discount = PurchasePayment::whereBetween('date', [$fromdate, $todate])->where('soft_delete', '!=', 1)->where('supplier_id', '=', $SupplierData->id)->sum('purchasepayment_discount');
                if($payment_discount != ""){
                    $totpayment_discount = $payment_discount;
                }else {
                    $totpayment_discount = '0';
                }
                $total_amount_paid = $total_paid_Amount + $total_payment_paid + $totpayment_discount;
                $total_balance = $tot_purchaseAmount - $total_amount_paid;


                // $tot_saleAmount = BranchwiseBalance::where('customer_id', '=', $SupplierData->id)->where('branch_id', '=', $branchid)->sum('sales_amount');
                // $total_amount_paid = BranchwiseBalance::where('customer_id', '=', $SupplierData->id)->where('branch_id', '=', $branchid)->sum('sales_paid');
                // $total_balance = BranchwiseBalance::where('customer_id', '=', $SupplierData->id)->where('branch_id', '=', $branchid)->sum('sales_balance');
    
                $payment_sale_discount = PurchasePayment::whereBetween('date', [$fromdate, $todate])->where('supplier_id', '=', $SupplierData->id)
                                                        ->where('soft_delete', '!=', 1)
                                                        ->sum('purchasepayment_discount');
                if($payment_sale_discount != ""){
                    $paymentpurchase_discount = $payment_sale_discount;
                }else {
                    $paymentpurchase_discount = '0';
                }

            }

        }


        usort($Purchase_data, function($a1, $a2) {
            $value1 = strtotime($a1['datetime']);
            $value2 = strtotime($a2['datetime']);
            return ($value1 < $value2) ? 1 : -1;
         });

         $branch = '';
         $today = Carbon::now()->format('Y-m-d');
            return view('page.backend.supplier.view', compact('SupplierData', 'Purchase_data', 'branch', 'Supplier', 'Suppliername', 'supplier_id', 'unique_key', 'today',
                         'fromdate','todate', 'branchid', 'supplierid',  'tot_purchaseAmount', 'total_amount_paid', 'total_balance', 'GETBranchname', 'paymentpurchase_discount'));
                         
    }


    public function getsupplierbalance()
    {

        $supplierid = request()->get('supplierid');

        $branch = Branch::where('soft_delete', '!=', 1)->where('status', '!=', 1)->get();
        $supplier_output = [];
        $total_bal_amount = 0;
        foreach ($branch as $key => $get_all_branch) {

            $get_all_balance = Purchase::where('soft_delete', '!=', 1)
                                        ->where('status', '!=', 1)
                                        ->where('supplier_id', '=', $supplierid)
                                        ->where('branch_id', '=', $get_all_branch->id)
                                        ->latest('id')
                                        ->first();

           if($get_all_balance != ""){



                $supplier_output[] = array(
                    'balance_amount' => $get_all_balance->balance_amount,
                    'branch' => $get_all_branch->shop_name,
                );
           }

        }

        if (isset($supplier_output) & !empty($supplier_output)) {
            echo json_encode($supplier_output);
        }else{
            echo json_encode(
                array('status' => 'false')
            );
        }


    }



    public function checkduplicate(Request $request)
    {
        if(request()->get('query'))
        {
            $query = request()->get('query');
            $supplierdata = Supplier::where('contact_number', '=', $query)->first();

            $userData['data'] = $supplierdata;
            echo json_encode($userData);
        }
    }



    public function supplierpdf_export($last_word) {
        $data = Supplier::where('soft_delete', '!=', 1)->get();
        $supplierarr_data = [];
        foreach ($data as $key => $datas) {

            $supplier_name = Supplier::findOrFail($datas->id);
            // Grand total
            $total_purchase_amt = Purchase::where('soft_delete', '!=', 1)->where('supplier_id', '=', $datas->id)->where('branch_id', '=', $last_word)->sum('gross_amount');
            if($total_purchase_amt != ""){
                $tot_purchaseAmount = $total_purchase_amt;
            }else {
                $tot_purchaseAmount = '0';
            }

            // Total Paid
            $total_paid = Purchase::where('soft_delete', '!=', 1)->where('supplier_id', '=', $datas->id)->where('branch_id', '=', $last_word)->sum('paid_amount');
            if($total_paid != ""){
                $total_paid_Amount = $total_paid;
            }else {
                $total_paid_Amount = '0';
            }
            $payment_total_paid = PurchasePayment::where('soft_delete', '!=', 1)->where('supplier_id', '=', $datas->id)->where('branch_id', '=', $last_word)->sum('amount');
            if($payment_total_paid != ""){
                $total_payment_paid = $payment_total_paid;
            }else {
                $total_payment_paid = '0';
            }

            $total_discount = PurchasePayment::where('soft_delete', '!=', 1)->where('supplier_id', '=', $datas->id)->where('branch_id', '=', $last_word)->sum('purchasepayment_discount');
            if($total_discount != ""){
                $total_discount_amont = $total_discount;
            }else {
                $total_discount_amont = '0';
            }

            $total_amount_paid = $total_paid_Amount + $total_payment_paid + $total_discount_amont;



            // Total Balance
            $total_balance = $tot_purchaseAmount - $total_amount_paid;

            $totalpurchase = BranchwiseBalance::where('supplier_id', '=', $datas->id)->sum('purchase_amount');
            $totalpaidpurchase = BranchwiseBalance::where('supplier_id', '=', $datas->id)->sum('purchase_paid');
            $totalpurchasebla = BranchwiseBalance::where('supplier_id', '=', $datas->id)->sum('purchase_balance');

            $supplierarr_data[] = array(
                'unique_key' => $datas->unique_key,
                'name' => $supplier_name->name,
                'contact_number' => $datas->contact_number,
                'shop_name' => $datas->shop_name,
                'total_purchase_amt' => $totalpurchase + $total_discount_amont,
                'total_paid' => $totalpaidpurchase,
                'balance_amount' => $totalpurchasebla,
                'total_discount_amont' => $total_discount_amont,
            );

            $price = array();
            foreach ($supplierarr_data as $key => $row)
            {
                $price[$key] = $row['balance_amount'];
            }
            array_multisort($price, SORT_DESC, $supplierarr_data);

        }


        $total_purchase_amount = Purchase::where('soft_delete', '!=', 1)->where('branch_id', '=', $last_word)->sum('gross_amount');
        if($total_purchase_amount != ""){
            $total_purchaseAmount = $total_purchase_amount;
        }else {
            $total_purchaseAmount = '0';
        }

        $supplierOldbalanceTot = Supplier::where('soft_delete', '!=', 1)->sum('old_balance');

        $TotalPurchase = $total_purchaseAmount + $supplierOldbalanceTot;


        $total_amuntpaid = Purchase::where('soft_delete', '!=', 1)->where('branch_id', '=', $last_word)->sum('paid_amount');
        if($total_amuntpaid != ""){
            $totalpaid_Amount = $total_amuntpaid;
        }else {
            $totalpaid_Amount = '0';
        }
        $paymenttotal_paid = PurchasePayment::where('soft_delete', '!=', 1)->where('branch_id', '=', $last_word)->sum('amount');
        if($paymenttotal_paid != ""){
            $totalpayment_paid = $paymenttotal_paid;
        }else {
            $totalpayment_paid = '0';
        }


        $discountpaid = PurchasePayment::where('soft_delete', '!=', 1)->where('branch_id', '=', $last_word)->sum('purchasepayment_discount');
        if($discountpaid != ""){
            $discount_paid = $discountpaid;
        }else {
            $discount_paid = '0';
        }

        $totalamount_paid = $totalpaid_Amount + $totalpayment_paid + $discount_paid;



        // Total Balance
        $totalbalance = $TotalPurchase - $totalamount_paid;

       

        return view('page.backend.supplier.pdfview', compact('supplierarr_data', 'TotalPurchase', 'totalamount_paid', 'totalbalance', 'total_purchaseAmount'));
    }



    public function supplierallpdf_export() {

        $data = Supplier::where('soft_delete', '!=', 1)->get();
        $supplierarr_data = [];
        foreach ($data as $key => $datas) {

            $supplier_name = Supplier::findOrFail($datas->id);
            // Grand total
            $total_purchase_amt = Purchase::where('soft_delete', '!=', 1)->where('supplier_id', '=', $datas->id)->sum('gross_amount');
            if($total_purchase_amt != ""){
                $tot_purchaseAmount = $total_purchase_amt;
            }else {
                $tot_purchaseAmount = '0';
            }

            // Total Paid
            $total_paid = Purchase::where('soft_delete', '!=', 1)->where('supplier_id', '=', $datas->id)->sum('paid_amount');
            if($total_paid != ""){
                $total_paid_Amount = $total_paid;
            }else {
                $total_paid_Amount = '0';
            }
            $payment_total_paid = PurchasePayment::where('soft_delete', '!=', 1)->where('supplier_id', '=', $datas->id)->sum('amount');
            if($payment_total_paid != ""){
                $total_payment_paid = $payment_total_paid;
            }else {
                $total_payment_paid = '0';
            }

            $total_discount = PurchasePayment::where('soft_delete', '!=', 1)->where('supplier_id', '=', $datas->id)->sum('purchasepayment_discount');
            if($total_discount != ""){
                $total_discount_amont = $total_discount;
            }else {
                $total_discount_amont = '0';
            }

            $total_amount_paid = $total_paid_Amount + $total_payment_paid + $total_discount_amont;



            // Total Balance
            $total_balance = $tot_purchaseAmount - $total_amount_paid;


            $totalpurchase = BranchwiseBalance::where('supplier_id', '=', $datas->id)->sum('purchase_amount');
            $totalpaidpurchase = BranchwiseBalance::where('supplier_id', '=', $datas->id)->sum('purchase_paid');
            $totalpurchasebla = BranchwiseBalance::where('supplier_id', '=', $datas->id)->sum('purchase_balance');



            $supplierarr_data[] = array(
                'unique_key' => $datas->unique_key,
                'name' => $supplier_name->name,
                'contact_number' => $datas->contact_number,
                'shop_name' => $datas->shop_name,
                'total_purchase_amt' => $totalpurchase + $total_discount_amont,
                'total_paid' => $totalpaidpurchase,
                'balance_amount' => $totalpurchasebla,
                'total_discount_amont' => $total_discount_amont,
            );


            $price = array();
            foreach ($supplierarr_data as $key => $row)
            {
                $price[$key] = $row['balance_amount'];
            }
            array_multisort($price, SORT_DESC, $supplierarr_data);

        }

        $total_purchase_amount = Purchase::where('soft_delete', '!=', 1)->sum('gross_amount');
        if($total_purchase_amount != ""){
            $total_purchaseAmount = $total_purchase_amount;
        }else {
            $total_purchaseAmount = '0';
        }


        $supplierOldbalanceTot = Supplier::where('soft_delete', '!=', 1)->sum('old_balance');

        $TotalPurchase = $total_purchaseAmount + $supplierOldbalanceTot;


        $total_amuntpaid = Purchase::where('soft_delete', '!=', 1)->sum('paid_amount');
        if($total_amuntpaid != ""){
            $totalpaid_Amount = $total_amuntpaid;
        }else {
            $totalpaid_Amount = '0';
        }
        $paymenttotal_paid = PurchasePayment::where('soft_delete', '!=', 1)->sum('amount');
        if($paymenttotal_paid != ""){
            $totalpayment_paid = $paymenttotal_paid;
        }else {
            $totalpayment_paid = '0';
        }


        $discountpaid = PurchasePayment::where('soft_delete', '!=', 1)->sum('purchasepayment_discount');
        if($discountpaid != ""){
            $discount_paid = $discountpaid;
        }else {
            $discount_paid = '0';
        }

        $totalamount_paid = $totalpaid_Amount + $totalpayment_paid + $discount_paid;



        // Total Balance
        $totalbalance = $TotalPurchase - $totalamount_paid;
        return view('page.backend.supplier.pdfview', compact('supplierarr_data', 'TotalPurchase', 'totalamount_paid', 'totalbalance', 'total_purchaseAmount'));
    }

}
