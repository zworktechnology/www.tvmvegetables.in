<?php

namespace App\Http\Controllers;
use App\Models\Customer;
use App\Models\Purchase;
use App\Models\Sales;
use App\Models\Productlist;
use App\Models\SalesProduct;
use App\Models\Salespayment;
use App\Models\BranchwiseBalance;
use App\Models\Branch;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Carbon\Carbon;
use PDF;

class CustomerController extends Controller
{
    public function index()
    {
        $data = Customer::where('soft_delete', '!=', 1)->get();
        $totalSAleAmount = 0;
        $TotaSalePaid = 0;
        $customerarr_data = [];
        foreach ($data as $key => $datas) {
            $Customer_name = Customer::findOrFail($datas->id);

            // Total Sale
            $total_sale_amt = Sales::where('soft_delete', '!=', 1)->where('customer_id', '=', $datas->id)->sum('gross_amount');
            if($total_sale_amt != ""){
                $tot_saleAmount = $total_sale_amt;
            }else {
                $tot_saleAmount = '0';
            }

            $totalSAleAmount += $tot_saleAmount;


            // Total Paid
            $total_paid = Sales::where('soft_delete', '!=', 1)->where('customer_id', '=', $datas->id)->sum('paid_amount');
            if($total_paid != ""){
                $total_paid_Amount = $total_paid;
            }else {
                $total_paid_Amount = '0';
            }

            $payment_total_paid = Salespayment::where('soft_delete', '!=', 1)->where('customer_id', '=', $datas->id)->sum('amount');
            if($payment_total_paid != ""){
                $total_payment_paid = $payment_total_paid;
            }else {
                $total_payment_paid = '0';
            }

            $payment_discount = Salespayment::where('soft_delete', '!=', 1)->where('customer_id', '=', $datas->id)->sum('salespayment_discount');
            if($payment_discount != ""){
                $totpayment_discount = $payment_discount;
            }else {
                $totpayment_discount = '0';
            }

            $total_amount_paid = $total_paid_Amount + $total_payment_paid + $totpayment_discount;
            $TotaSalePaid += $total_amount_paid;

            // Total Balance
            $total_balance = $tot_saleAmount - $total_amount_paid;
            $totalsale = BranchwiseBalance::where('customer_id', '=', $datas->id)->sum('sales_amount');
            $totalpaidsale = BranchwiseBalance::where('customer_id', '=', $datas->id)->sum('sales_paid');
            $totalsalebla = BranchwiseBalance::where('customer_id', '=', $datas->id)->sum('sales_balance');

            $customerarr_data[] = array(
                'unique_key' => $datas->unique_key,
                'name' => $Customer_name->name,
                'contact_number' => $datas->contact_number,
                'shop_name' => $datas->shop_name,
                'status' => $datas->status,
                'id' => $datas->id,
                'email_address' => $datas->email_address,
                'shop_address' => $datas->shop_address,
                'shop_contact_number' => $datas->shop_contact_number,
                'total_sale_amt' => $totalsale + $totpayment_discount,
                'total_paid' => $totalpaidsale,
                'balance_amount' => $totalsalebla,
                'totpayment_discount' => $totpayment_discount,
            );


            $price = array();
            foreach ($customerarr_data as $key => $row)
            {
                $price[$key] = $row['total_sale_amt'];
            }
            array_multisort($price, SORT_DESC, $customerarr_data);
        }


        $alldata_branch = Branch::where('soft_delete', '!=', 1)->where('status', '!=', 1)->get();
        $tot_balance_Arr = [];

        foreach ($alldata_branch as $key => $alldata_branchs) {
            $Customer_array = Customer::where('soft_delete', '!=', 1)->get();
            foreach ($Customer_array as $key => $Customer_arra) {


                $last_idrow = BranchwiseBalance::where('customer_id', '=', $Customer_arra->id)->where('branch_id', '=', $alldata_branchs->id)->first();

                if($last_idrow != ""){
                    if($last_idrow->sales_balance != NULL){
                        $tot_balace = $last_idrow->sales_balance;

                    }else {

                        $tot_balace = 0;

                    }

                }else {
                    $tot_balace = 0;
                }

                $tot_balance_Arr[] = array(
                    'customer_name' => $Customer_arra->name,
                    'branch_name' => $alldata_branchs->shop_name,
                    'customer_id' => $Customer_arra->id,
                    'balance_amount' => $tot_balace
                );

            }
        }

        $allbranch = Branch::where('soft_delete', '!=', 1)->where('status', '!=', 1)->get();

        $total_sale_amount = Sales::where('soft_delete', '!=', 1)->sum('gross_amount');
            if($total_sale_amount != ""){
                $totsaleAmount = $total_sale_amount;
            }else {
                $totsaleAmount = '0';
            }

            $CustomerOldbalanceTot = Customer::where('soft_delete', '!=', 1)->sum('old_balance');

            $TotalSale = $totsaleAmount + $CustomerOldbalanceTot;


            // Total Paid
            $total_salepaid = Sales::where('soft_delete', '!=', 1)->sum('paid_amount');
            if($total_salepaid != ""){
                $total_salepaid_Amount = $total_salepaid;
            }else {
                $total_salepaid_Amount = '0';
            }
            $payment_saletotal_paid = Salespayment::where('soft_delete', '!=', 1)->sum('amount');
            if($payment_saletotal_paid != ""){
                $total_sakepayment_paid = $payment_saletotal_paid;
            }else {
                $total_sakepayment_paid = '0';
            }

            $payment_sale_discount = Salespayment::where('soft_delete', '!=', 1)->sum('salespayment_discount');
            if($payment_sale_discount != ""){
                $paymentsale_discount = $payment_sale_discount;
            }else {
                $paymentsale_discount = '0';
            }
            $total_saleamount_paid = $total_salepaid_Amount + $total_sakepayment_paid + $paymentsale_discount;


            // Total Balance
            $saletotal_balance = $totsaleAmount - $total_saleamount_paid;

        return view('page.backend.customer.index', compact('customerarr_data', 'tot_balance_Arr', 'allbranch', 'totsaleAmount', 'total_saleamount_paid', 'saletotal_balance', 'totalSAleAmount', 'TotaSalePaid', 'TotalSale'));
    }


    public function branchdata($branch_id)
    {
        $data = Customer::where('soft_delete', '!=', 1)->get();
        $totalSAleAmount = 0;
        $TotaSalePaid = 0;
        $TOTALDiscount = 0;
        $customerarr_data = [];
        foreach ($data as $key => $datas) {
            $Customer_name = Customer::findOrFail($datas->id);

            // Total Sale
            $total_sale_amt = Sales::where('soft_delete', '!=', 1)->where('customer_id', '=', $datas->id)->where('branch_id', '=', $branch_id)->sum('gross_amount');
            if($total_sale_amt != ""){
                $tot_saleAmount = $total_sale_amt;
            }else {
                $tot_saleAmount = '0';
            }
            $totalSAleAmount += $tot_saleAmount;

            // Total Paid
            $total_paid = Sales::where('soft_delete', '!=', 1)->where('customer_id', '=', $datas->id)->where('branch_id', '=', $branch_id)->sum('paid_amount');
            if($total_paid != ""){
                $total_paid_Amount = $total_paid;
            }else {
                $total_paid_Amount = '0';
            }
            $payment_total_paid = Salespayment::where('soft_delete', '!=', 1)->where('customer_id', '=', $datas->id)->where('branch_id', '=', $branch_id)->sum('amount');
            if($payment_total_paid != ""){
                $total_payment_paid = $payment_total_paid;
            }else {
                $total_payment_paid = '0';
            }

            $payment_discount = Salespayment::where('soft_delete', '!=', 1)->where('customer_id', '=', $datas->id)->where('branch_id', '=', $branch_id)->sum('salespayment_discount');
            if($payment_discount != ''){
                $totpayment_discount = $payment_discount;
            }else {
                $totpayment_discount = 0;
            }

            $TOTALDiscount += $totpayment_discount;
            $total_amount_paid = $total_paid_Amount + $total_payment_paid + $totpayment_discount;
            $TotaSalePaid += $total_amount_paid;

            // Total Balance
            $total_balance = $tot_saleAmount - $total_amount_paid;

            $totalsaleAmt = BranchwiseBalance::where('customer_id', '=', $datas->id)->where('branch_id', '=', $branch_id)->first();
            if($totalsaleAmt != ""){
                $totalsale = $totalsaleAmt->sales_amount;
                $totalpaidsale = $totalsaleAmt->sales_paid;
                $totalsalebla = $totalsaleAmt->sales_balance;
            }else {
                $totalsale = 0;
                $totalpaidsale = 0;
                $totalsalebla = 0;
            }
            $total_sake_amount = $totalsale + $totpayment_discount;

            $customerarr_data[] = array(
                'unique_key' => $datas->unique_key,
                'name' => $Customer_name->name,
                'contact_number' => $datas->contact_number,
                'shop_name' => $datas->shop_name,
                'status' => $datas->status,
                'id' => $datas->id,
                'email_address' => $datas->email_address,
                'shop_address' => $datas->shop_address,
                'shop_contact_number' => $datas->shop_contact_number,
                'total_sale_amt' => $total_sake_amount,
                'total_paid' => $totalpaidsale,
                'balance_amount' => $totalsalebla,
                'totpayment_discount' => $totpayment_discount,
            );


            $price = array();
            foreach ($customerarr_data as $key => $row)
            {
                $price[$key] = $row['total_sale_amt'];
            }
            array_multisort($price, SORT_DESC, $customerarr_data);
        }


        $alldata_branch = Branch::where('soft_delete', '!=', 1)->where('status', '!=', 1)->get();
        $tot_balance_Arr = [];

        foreach ($alldata_branch as $key => $alldata_branchs) {
            $Customer_array = Customer::where('soft_delete', '!=', 1)->get();
            foreach ($Customer_array as $key => $Customer_arra) {


                $last_idrow = BranchwiseBalance::where('customer_id', '=', $Customer_arra->id)->where('branch_id', '=', $alldata_branchs->id)->first();

                if($last_idrow != ""){
                    if($last_idrow->sales_balance != NULL){
                        $tot_balace = $last_idrow->sales_balance;

                    }else {

                        $tot_balace = 0;

                    }

                }else {
                    $tot_balace = 0;
                }

                $tot_balance_Arr[] = array(
                    'customer_name' => $Customer_arra->name,
                    'branch_name' => $alldata_branchs->shop_name,
                    'customer_id' => $Customer_arra->id,
                    'balance_amount' => $tot_balace
                );

            }
        }
        $allbranch = Branch::where('soft_delete', '!=', 1)->where('status', '!=', 1)->get();

            $total_sale_amount = Sales::where('soft_delete', '!=', 1)->where('branch_id', '=', $branch_id)->sum('gross_amount');
            if($total_sale_amount != ""){
                $totsaleAmount = $total_sale_amount;
            }else {
                $totsaleAmount = '0';
            }

            $CustomerOldbalanceTot = Customer::where('soft_delete', '!=', 1)->sum('old_balance');

            $TotalSale = $totsaleAmount + $CustomerOldbalanceTot;


            // Total Paid
            $total_salepaid = Sales::where('soft_delete', '!=', 1)->where('branch_id', '=', $branch_id)->sum('paid_amount');
            if($total_salepaid != ""){
                $total_salepaid_Amount = $total_salepaid;
            }else {
                $total_salepaid_Amount = '0';
            }
            $payment_saletotal_paid = Salespayment::where('soft_delete', '!=', 1)->where('branch_id', '=', $branch_id)->sum('amount');
            if($payment_saletotal_paid != ""){
                $total_sakepayment_paid = $payment_saletotal_paid;
            }else {
                $total_sakepayment_paid = '0';
            }

            $payment_sale_discount = Salespayment::where('soft_delete', '!=', 1)->sum('salespayment_discount');
            if($payment_sale_discount != ""){
                $paymentsale_discount = $payment_sale_discount;
            }else {
                $paymentsale_discount = '0';
            }

            $total_saleamount_paid = $total_salepaid_Amount + $total_sakepayment_paid + $paymentsale_discount;


            // Total Balance
            $saletotal_balance = $TotalSale - $total_saleamount_paid;

        return view('page.backend.customer.index', compact('customerarr_data', 'tot_balance_Arr', 'allbranch', 'totsaleAmount', 'total_saleamount_paid', 'saletotal_balance', 'TotalSale', 'TotaSalePaid'));
    }


    public function allbranchpdf_export()
    {

        $data = Customer::where('soft_delete', '!=', 1)->get();

        $customerarr_data = [];
        foreach ($data as $key => $datas) {
            $Customer_name = Customer::findOrFail($datas->id);

            // Total Sale
            $total_sale_amt = Sales::where('soft_delete', '!=', 1)->where('customer_id', '=', $datas->id)->sum('gross_amount');
            if($total_sale_amt != ""){
                $tot_saleAmount = $total_sale_amt;
            }else {
                $tot_saleAmount = '0';
            }


            // Total Paid
            $total_paid = Sales::where('soft_delete', '!=', 1)->where('customer_id', '=', $datas->id)->sum('paid_amount');
            if($total_paid != ""){
                $total_paid_Amount = $total_paid;
            }else {
                $total_paid_Amount = '0';
            }
            $payment_total_paid = Salespayment::where('soft_delete', '!=', 1)->where('customer_id', '=', $datas->id)->sum('amount');
            if($payment_total_paid != ""){
                $total_payment_paid = $payment_total_paid;
            }else {
                $total_payment_paid = '0';
            }


            $payment_discount = Salespayment::where('soft_delete', '!=', 1)->where('customer_id', '=', $datas->id)->sum('salespayment_discount');
            if($payment_discount != ""){
                $totpayment_discount = $payment_discount;
            }else {
                $totpayment_discount = '0';
            }
            $total_amount_paid = $total_paid_Amount + $total_payment_paid + $totpayment_discount;


            // Total Balance
            $total_balance = $tot_saleAmount - $total_amount_paid;


            $totalsale = BranchwiseBalance::where('customer_id', '=', $datas->id)->sum('sales_amount');
            $totalpaidsale = BranchwiseBalance::where('customer_id', '=', $datas->id)->sum('sales_paid');
            $totalsalebla = BranchwiseBalance::where('customer_id', '=', $datas->id)->sum('sales_balance');

            $customerarr_data[] = array(
                'unique_key' => $datas->unique_key,
                'name' => $Customer_name->name,
                'contact_number' => $datas->contact_number,
                'shop_name' => $datas->shop_name,
                'status' => $datas->status,
                'id' => $datas->id,
                'email_address' => $datas->email_address,
                'shop_address' => $datas->shop_address,
                'shop_contact_number' => $datas->shop_contact_number,
                'total_sale_amt' => $totalsale,
                'total_paid' => $totalpaidsale,
                'balance_amount' => $totalsalebla,
                'totpayment_discount' => $totpayment_discount,
            );


            $price = array();
            foreach ($customerarr_data as $key => $row)
            {
                $price[$key] = $row['balance_amount'];
            }
            array_multisort($price, SORT_DESC, $customerarr_data);
        }


        $total_sale_amount = Sales::where('soft_delete', '!=', 1)->sum('gross_amount');
            if($total_sale_amount != ""){
                $totsaleAmount = $total_sale_amount;
            }else {
                $totsaleAmount = '0';
            }

            $CustomerOldbalanceTot = Customer::where('soft_delete', '!=', 1)->sum('old_balance');

            $TotalSale = $totsaleAmount + $CustomerOldbalanceTot;

            // Total Paid
            $total_salepaid = Sales::where('soft_delete', '!=', 1)->sum('paid_amount');
            if($total_salepaid != ""){
                $total_salepaid_Amount = $total_salepaid;
            }else {
                $total_salepaid_Amount = '0';
            }
            $payment_saletotal_paid = Salespayment::where('soft_delete', '!=', 1)->sum('amount');
            if($payment_saletotal_paid != ""){
                $total_sakepayment_paid = $payment_saletotal_paid;
            }else {
                $total_sakepayment_paid = '0';
            }

            $payment_sale_discount = Salespayment::where('soft_delete', '!=', 1)->sum('salespayment_discount');
            if($payment_sale_discount != ""){
                $paymentsale_discount = $payment_sale_discount;
            }else {
                $paymentsale_discount = '0';
            }
            $total_saleamount_paid = $total_salepaid_Amount + $total_sakepayment_paid + $paymentsale_discount;


            // Total Balance
            $saletotal_balance = $TotalSale - $total_saleamount_paid;

            $today = Carbon::now()->format('Y-m-d');


            $pdf = Pdf::loadView('page.backend.customer.pdfexport_view', [
                'customerarr_data' => $customerarr_data,
                'totsaleAmount' => $TotalSale,
                'total_saleamount_paid' => $total_saleamount_paid,
                'saletotal_balance' => $saletotal_balance,
                'today' => date('d-m-Y', strtotime($today)),
                'branch_name' => 'All Branches',

            ]);

            $name = 'Customers.' . 'pdf';

            return $pdf->stream($name);
    }



    public function customerpdf_export($last_word)
    {

        $data = Customer::where('soft_delete', '!=', 1)->get();

        $customerarr_data = [];
        foreach ($data as $key => $datas) {
            $Customer_name = Customer::findOrFail($datas->id);

            // Total Sale
            $total_sale_amt = Sales::where('soft_delete', '!=', 1)->where('customer_id', '=', $datas->id)->where('branch_id', '=', $last_word)->sum('gross_amount');
            if($total_sale_amt != ""){
                $tot_saleAmount = $total_sale_amt;
            }else {
                $tot_saleAmount = '0';
            }


            // Total Paid
            $total_paid = Sales::where('soft_delete', '!=', 1)->where('customer_id', '=', $datas->id)->where('branch_id', '=', $last_word)->sum('paid_amount');
            if($total_paid != ""){
                $total_paid_Amount = $total_paid;
            }else {
                $total_paid_Amount = '0';
            }
            $payment_total_paid = Salespayment::where('soft_delete', '!=', 1)->where('customer_id', '=', $datas->id)->where('branch_id', '=', $last_word)->sum('amount');
            if($payment_total_paid != ""){
                $total_payment_paid = $payment_total_paid;
            }else {
                $total_payment_paid = '0';
            }


            $payment_discount = Salespayment::where('soft_delete', '!=', 1)->where('customer_id', '=', $datas->id)->where('branch_id', '=', $last_word)->sum('salespayment_discount');
            if($payment_discount != ""){
                $totpayment_discount = $payment_discount;
            }else {
                $totpayment_discount = '0';
            }
            $total_amount_paid = $total_paid_Amount + $total_payment_paid + $totpayment_discount;


            // Total Balance
            $total_balance = $tot_saleAmount - $total_amount_paid;


            $totalsaleAmt = BranchwiseBalance::where('customer_id', '=', $datas->id)->where('branch_id', '=', $last_word)->first();
            if($totalsaleAmt != ""){
                $totalsale = $totalsaleAmt->sales_amount;
                $totalpaidsale = $totalsaleAmt->sales_paid;
                $totalsalebla = $totalsaleAmt->sales_balance;
            }else {
                $totalsale = '';
                $totalpaidsale = '';
                $totalsalebla = '';
            }

            $customerarr_data[] = array(
                'unique_key' => $datas->unique_key,
                'name' => $Customer_name->name,
                'contact_number' => $datas->contact_number,
                'shop_name' => $datas->shop_name,
                'status' => $datas->status,
                'id' => $datas->id,
                'email_address' => $datas->email_address,
                'shop_address' => $datas->shop_address,
                'shop_contact_number' => $datas->shop_contact_number,
                'total_sale_amt' => $totalsale,
                'total_paid' => $totalpaidsale,
                'balance_amount' => $totalsalebla,
                'totpayment_discount' => $totpayment_discount,
            );


            $price = array();
            foreach ($customerarr_data as $key => $row)
            {
                $price[$key] = $row['balance_amount'];
            }
            array_multisort($price, SORT_DESC, $customerarr_data);
        }


        $total_sale_amount = Sales::where('soft_delete', '!=', 1)->where('branch_id', '=', $last_word)->sum('gross_amount');
            if($total_sale_amount != ""){
                $totsaleAmount = $total_sale_amount;
            }else {
                $totsaleAmount = '0';
            }

            $CustomerOldbalanceTot = Customer::where('soft_delete', '!=', 1)->sum('old_balance');

            $TotalSale = $totsaleAmount + $CustomerOldbalanceTot;


            // Total Paid
            $total_salepaid = Sales::where('soft_delete', '!=', 1)->where('branch_id', '=', $last_word)->sum('paid_amount');
            if($total_salepaid != ""){
                $total_salepaid_Amount = $total_salepaid;
            }else {
                $total_salepaid_Amount = '0';
            }
            $payment_saletotal_paid = Salespayment::where('soft_delete', '!=', 1)->where('branch_id', '=', $last_word)->sum('amount');
            if($payment_saletotal_paid != ""){
                $total_sakepayment_paid = $payment_saletotal_paid;
            }else {
                $total_sakepayment_paid = '0';
            }

            $payment_sale_discount = Salespayment::where('soft_delete', '!=', 1)->where('branch_id', '=', $last_word)->sum('salespayment_discount');
            if($payment_sale_discount != ""){
                $paymentsale_discount = $payment_sale_discount;
            }else {
                $paymentsale_discount = '0';
            }
            $total_saleamount_paid = $total_salepaid_Amount + $total_sakepayment_paid + $paymentsale_discount;


            // Total Balance
            $saletotal_balance = $TotalSale - $total_saleamount_paid;
            $branch_name = Branch::findOrFail($last_word);

            $today = Carbon::now()->format('Y-m-d');


            $pdf = Pdf::loadView('page.backend.customer.pdfexport_view', [
                'customerarr_data' => $customerarr_data,
                'totsaleAmount' => $TotalSale,
                'total_saleamount_paid' => $total_saleamount_paid,
                'saletotal_balance' => $saletotal_balance,
                'branch_name' => $branch_name->shop_name,
                'today' => date('d-m-Y', strtotime($today)),

            ]);

            $name = 'Customers.' . 'pdf';

            return $pdf->stream($name);
    }

    public function store(Request $request)
    {
        $randomkey = Str::random(5);

        $data = new Customer();

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

        $customerid = $data->id;
        $PaymentBalanceDAta = BranchwiseBalance::where('customer_id', '=', $customerid)->first();
        if($PaymentBalanceDAta == "")
        {
            $balance_amount = $request->get('balance_amount');

            $paymentbalacedata = new BranchwiseBalance();

            $paymentbalacedata->customer_id = $customerid;
            $paymentbalacedata->branch_id = 1;
            $paymentbalacedata->sales_balance = $balance_amount;
            $paymentbalacedata->sales_amount = $balance_amount;
            $paymentbalacedata->sales_paid = 0;

            $paymentbalacedata->save();
        }

        return redirect()->route('customer.index')->with('add', 'Customer Data added successfully!');
    }


    public function edit(Request $request, $unique_key)
    {
        $CustomerData = Customer::where('unique_key', '=', $unique_key)->first();

        $CustomerData->name = $request->get('name');
        $CustomerData->contact_number = $request->get('contact_number');
        $CustomerData->email_address = $request->get('email');
        $CustomerData->shop_name = $request->get('shop_name');
        $CustomerData->shop_address = $request->get('shop_address');
        $CustomerData->shop_contact_number = $request->get('shop_contact_number');
        $CustomerData->status = $request->get('status');

        $CustomerData->update();

        return redirect()->route('customer.index')->with('update', 'Customer Data updated successfully!');
    }


    public function customerview($unique_key, $last_word)
    {

        if($last_word != 'customer'){


            $CustomerData = Customer::where('unique_key', '=', $unique_key)->first();

            $today = Carbon::now()->format('Y-m-d');
            $data = Sales::where('customer_id', '=', $CustomerData->id)->where('branch_id', '=', $last_word)->where('soft_delete', '!=', 1)->get();


                $sales = [];
                foreach ($data as $key => $datas_arr) {
                    $sales[] = $datas_arr;
                }
                $salepayment_s = [];
                $Salespaymentdata = Salespayment::where('customer_id', '=', $CustomerData->id)->where('branch_id', '=', $last_word)->where('soft_delete', '!=', 1)->get();
                foreach ($Salespaymentdata as $key => $Salespaymentdatas) {
                    $salepayment_s[] = $Salespaymentdatas;
                }


                $Sales_data = [];
                $terms = [];

                $merge = array_merge($sales, $salepayment_s);


            foreach ($merge as $key => $datas) {

                $branch_name = Branch::findOrFail($datas->branch_id);
                $SalesProducts = SalesProduct::where('sales_id', '=', $datas->id)->where('branch_id', '=', $last_word)->get();
                foreach ($SalesProducts as $key => $SalesProducts_arr) {

                    $productlist_ID = Productlist::findOrFail($SalesProducts_arr->productlist_id);
                    $terms[] = array(
                        'bag' => $SalesProducts_arr->bagorkg,
                        'kgs' => $SalesProducts_arr->count,
                        'price_per_kg' => $SalesProducts_arr->price_per_kg,
                        'total_price' => $SalesProducts_arr->total_price,
                        'product_name' => $productlist_ID->name,
                        'sales_id' => $SalesProducts_arr->sales_id,

                    );

                }


                if($datas->status != ""){
                    $paid = $datas->paid_amount;
                    $balance = $datas->balance_amount;
                    $type='SALES';
                    $discount = '';
                }else {
                    $paid = $datas->amount;
                    $balance = $datas->payment_pending;
                    $type='PAYMENT';
                    $discount = $datas->salespayment_discount;
                }

                $Sales_data[] = array(
                    'unique_key' => $datas->unique_key,
                    'branch_name' => $branch_name->shop_name,
                    'customer_name' => $CustomerData->name,
                    'date' => $datas->date,
                    'time' => $datas->time,
                    'gross_amount' => $datas->gross_amount,
                    'paid_amount' => $paid,
                    'bill_no' => $datas->bill_no,
                    'sales_order' => $datas->sales_order,
                    'grand_total' => $datas->grand_total,
                    'balance_amount' => $balance,
                    'type' => $type,
                    'id' => $datas->id,
                    'sales_terms' => $terms,
                    'discount' => $discount,
                    'status' => $datas->status,
                    'branchheading' => $branch_name->shop_name,
                    'customerheading' => '',
                    'fromdateheading' => '',
                    'todateheading' => '',
                    'datetime' => $datas->date . $datas->time,

                );
            }

            $branch = Branch::where('soft_delete', '!=', 1)->where('status', '!=', 1)->get();
            $Customer = Customer::where('soft_delete', '!=', 1)->get();


            $Customername = $CustomerData->name;
            $customer_id = $CustomerData->id;
            $unique_key = $CustomerData->unique_key;





            $fromdate = '';
            $todate = '';
            $branchid = '';
            $customerid = $CustomerData->id;



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



            $totalsaleAmt = BranchwiseBalance::where('customer_id', '=', $CustomerData->id)->where('branch_id', '=', $last_word)->first();
            if($totalsaleAmt != ""){
                $tot_saleAmount = $totalsaleAmt->sales_amount;
                $total_amount_paid = $totalsaleAmt->sales_paid;
                $total_balance = $totalsaleAmt->sales_balance;
            }else {
                $tot_saleAmount = '';
                $total_amount_paid = '';
                $total_balance = '';
            }

            $payment_sale_discount = Salespayment::where('customer_id', '=', $CustomerData->id)->where('branch_id', '=', $last_word)->where('soft_delete', '!=', 1)->sum('salespayment_discount');
            if($payment_sale_discount != ""){
                $paymentsale_discount = $payment_sale_discount;
            }else {
                $paymentsale_discount = '0';
            }



            $GETbranch = Branch::findOrFail($last_word);
            $GETBranchname = $GETbranch->shop_name;


            usort($Sales_data, function($a1, $a2) {
                $value1 = strtotime($a1['datetime']);
                $value2 = strtotime($a2['datetime']);
                return ($value1 < $value2) ? 1 : -1;
             });

            return view('page.backend.customer.view', compact('CustomerData', 'Sales_data', 'branch', 'Customer', 'Customername', 'customer_id', 'unique_key', 'today',
                         'fromdate','todate', 'branchid', 'customerid',  'tot_saleAmount', 'total_amount_paid', 'total_balance', 'GETBranchname', 'paymentsale_discount'));


        }else if($last_word == 'customer'){

            $CustomerData = Customer::where('unique_key', '=', $unique_key)->first();

            $today = Carbon::now()->format('Y-m-d');

            $data = Sales::where('customer_id', '=', $CustomerData->id)->where('soft_delete', '!=', 1)->get();
            $sales = [];
            foreach ($data as $key => $datas_arr) {
                $sales[] = $datas_arr;
            }
            $salepayment_s = [];
            $Salespaymentdata = Salespayment::where('customer_id', '=', $CustomerData->id)->where('soft_delete', '!=', 1)->get();
            foreach ($Salespaymentdata as $key => $Salespaymentdatas) {
                $salepayment_s[] = $Salespaymentdatas;
            }


            $Sales_data = [];
            $terms = [];

            $merge = array_merge($sales, $salepayment_s);


            foreach ($merge as $key => $datas) {

                $branch_name = Branch::findOrFail($datas->branch_id);
                $SalesProducts = SalesProduct::where('sales_id', '=', $datas->id)->get();
                foreach ($SalesProducts as $key => $SalesProducts_arr) {

                    $productlist_ID = Productlist::findOrFail($SalesProducts_arr->productlist_id);
                    $terms[] = array(
                        'bag' => $SalesProducts_arr->bagorkg,
                        'kgs' => $SalesProducts_arr->count,
                        'price_per_kg' => $SalesProducts_arr->price_per_kg,
                        'total_price' => $SalesProducts_arr->total_price,
                        'product_name' => $productlist_ID->name,
                        'sales_id' => $SalesProducts_arr->sales_id,

                    );

                }


                if($datas->status != ""){
                    $paid = $datas->paid_amount;
                    $balance = $datas->balance_amount;
                    $type='SALES';
                    $discount = '';
                }else {
                    $paid = $datas->amount;
                    $balance = $datas->payment_pending;
                    $type='PAYMENT';
                    $discount = $datas->salespayment_discount;
                }

                $Sales_data[] = array(
                    'unique_key' => $datas->unique_key,
                    'branch_name' => $branch_name->shop_name,
                    'customer_name' => $CustomerData->name,
                    'date' => $datas->date,
                    'gross_amount' => $datas->gross_amount,
                    'paid_amount' => $paid,
                    'bill_no' => $datas->bill_no,
                    'sales_order' => $datas->sales_order,
                    'grand_total' => $datas->grand_total,
                    'balance_amount' => $balance,
                    'type' => $type,
                    'discount' => $discount,
                    'id' => $datas->id,
                    'sales_terms' => $terms,
                    'status' => $datas->status,
                    'branchheading' => $branch_name->shop_name,
                    'customerheading' => '',
                    'fromdateheading' => '',
                    'todateheading' => '',
                    'datetime' => $datas->date . $datas->time,

                );
            }

            $branch = Branch::where('soft_delete', '!=', 1)->where('status', '!=', 1)->get();
            $Customer = Customer::where('soft_delete', '!=', 1)->get();


            $Customername = $CustomerData->name;
            $customer_id = $CustomerData->id;
            $unique_key = $CustomerData->unique_key;




            $fromdate = '';
            $todate = '';
            $branchid = '';
            $customerid = $CustomerData->id;



            // $total_sale_amt = Sales::where('soft_delete', '!=', 1)->where('customer_id', '=', $CustomerData->id)->sum('gross_amount');
            // if($total_sale_amt != ""){
            //     $tot_saleAmount = $total_sale_amt;
            // }else {
            //     $tot_saleAmount = '0';
            // }



            // $total_paid = Sales::where('soft_delete', '!=', 1)->where('customer_id', '=', $CustomerData->id)->sum('paid_amount');
            // if($total_paid != ""){
            //     $total_paid_Amount = $total_paid;
            // }else {
            //     $total_paid_Amount = '0';
            // }
            // $payment_total_paid = Salespayment::where('soft_delete', '!=', 1)->where('customer_id', '=', $CustomerData->id)->sum('amount');
            // if($payment_total_paid != ""){
            //     $total_payment_paid = $payment_total_paid;
            // }else {
            //     $total_payment_paid = '0';
            // }


            // $payment_discount = Salespayment::where('soft_delete', '!=', 1)->where('customer_id', '=', $CustomerData->id)->sum('salespayment_discount');
            // if($payment_discount != ""){
            //     $totpayment_discount = $payment_discount;
            // }else {
            //     $totpayment_discount = '0';
            // }
            // $total_amount_paid = $total_paid_Amount + $total_payment_paid + $totpayment_discount;



            // $total_balance = $tot_saleAmount - $total_amount_paid;



            $tot_saleAmount = BranchwiseBalance::where('customer_id', '=', $CustomerData->id)->sum('sales_amount');
            $total_amount_paid = BranchwiseBalance::where('customer_id', '=', $CustomerData->id)->sum('sales_paid');
            $total_balance = BranchwiseBalance::where('customer_id', '=', $CustomerData->id)->sum('sales_balance');

            $payment_sale_discount = Salespayment::where('customer_id', '=', $CustomerData->id)->where('soft_delete', '!=', 1)->sum('salespayment_discount');
            if($payment_sale_discount != ""){
                $paymentsale_discount = $payment_sale_discount;
            }else {
                $paymentsale_discount = '0';
            }


            $GETBranchname = 'All Branch';


            usort($Sales_data, function($a1, $a2) {
                $value1 = strtotime($a1['datetime']);
                $value2 = strtotime($a2['datetime']);
                return ($value1 < $value2) ? 1 : -1;
             });

            return view('page.backend.customer.view', compact('CustomerData', 'Sales_data', 'branch', 'Customer', 'Customername', 'customer_id', 'unique_key', 'today',
                         'fromdate', 'todate', 'branchid', 'customerid', 'tot_saleAmount', 'total_amount_paid', 'total_balance', 'GETBranchname', 'paymentsale_discount'));

        }

    }



    public function viewfilter(Request $request, $unique_key, $last_word)
    {
        $branchid = $request->get('branchid');
        $unique_key = $request->get('uniquekey');
        $CustomerData = Customer::where('unique_key', '=', $unique_key)->first();

        $Customer = Customer::where('soft_delete', '!=', 1)->where('status', '!=', 1)->get();



        $fromdate = $request->get('fromdate');
        $todate = $request->get('todate');

        if($branchid != 'customer'){

            if($fromdate){
                $GETbranch = Branch::findOrFail($branchid);
                $GETBranchname = $GETbranch->shop_name;

                $data = Sales::where('date', '=', $fromdate)->where('customer_id', '=', $CustomerData->id)->where('branch_id', '=', $branchid)->where('soft_delete', '!=', 1)->get();

                $sales = [];
                foreach ($data as $key => $datas_arr) {
                    $sales[] = $datas_arr;
                }
                $salepayment_s = [];
                $Salespaymentdata = Salespayment::where('date', '=', $fromdate)->where('customer_id', '=', $CustomerData->id)->where('branch_id', '=', $branchid)->where('soft_delete', '!=', 1)->get();
                foreach ($Salespaymentdata as $key => $Salespaymentdatas) {
                    $salepayment_s[] = $Salespaymentdatas;
                }


                $Sales_data = [];
                $sales_terms = [];

                $merge = array_merge($sales, $salepayment_s);





                foreach ($merge as $key => $datas) {

                    $branch_name = Branch::findOrFail($datas->branch_id);
                    $SalesProducts = SalesProduct::where('sales_id', '=', $datas->id)->where('branch_id', '=', $branchid)->get();
                    foreach ($SalesProducts as $key => $SalesProducts_arr) {

                        $productlist_ID = Productlist::findOrFail($SalesProducts_arr->productlist_id);
                        $terms[] = array(
                            'bag' => $SalesProducts_arr->bagorkg,
                            'kgs' => $SalesProducts_arr->count,
                            'price_per_kg' => $SalesProducts_arr->price_per_kg,
                            'total_price' => $SalesProducts_arr->total_price,
                            'product_name' => $productlist_ID->name,
                            'sales_id' => $SalesProducts_arr->sales_id,

                        );

                    }


                    if($datas->status != ""){
                        $paid = $datas->paid_amount;
                        $balance = $datas->balance_amount;
                        $type='SALES';
                        $discount = '';
                    }else {
                        $paid = $datas->amount;
                        $balance = $datas->payment_pending;
                        $type='PAYMENT';
                        $discount = $datas->salespayment_discount;
                    }

                    $Sales_data[] = array(
                        'unique_key' => $datas->unique_key,
                        'branch_name' => $branch_name->shop_name,
                        'customer_name' => $CustomerData->name,
                        'date' => $datas->date,
                        'time' => $datas->time,
                        'gross_amount' => $datas->gross_amount,
                        'paid_amount' => $paid,
                        'bill_no' => $datas->bill_no,
                        'sales_order' => $datas->sales_order,
                        'grand_total' => $datas->grand_total,
                        'balance_amount' => $balance,
                        'type' => $type,
                        'discount' => $discount,
                        'id' => $datas->id,
                        'sales_terms' => $terms,
                        'status' => $datas->status,
                        'branchheading' => $branch_name->shop_name,
                        'customerheading' => '',
                        'fromdateheading' => '',
                        'todateheading' => '',
                        'datetime' => $datas->date . $datas->time,

                    );
                }

                $Customername = $CustomerData->name;
                $customer_id = $CustomerData->id;
                $unique_key = $CustomerData->unique_key;
                $customerid = $CustomerData->id;


                $total_sale_amt = Sales::where('date', '=', $fromdate)->where('soft_delete', '!=', 1)->where('customer_id', '=', $CustomerData->id)->where('branch_id', '=', $branchid)->sum('gross_amount');
                if($total_sale_amt != ""){
                    $tot_saleAmount = $total_sale_amt;
                }else {
                    $tot_saleAmount = '0';
                }


                // Total Paid
                $total_paid = Sales::where('date', '=', $fromdate)->where('soft_delete', '!=', 1)->where('customer_id', '=', $CustomerData->id)->where('branch_id', '=', $branchid)->sum('paid_amount');
                if($total_paid != ""){
                    $total_paid_Amount = $total_paid;
                }else {
                    $total_paid_Amount = '0';
                }
                $payment_total_paid = Salespayment::where('date', '=', $fromdate)->where('soft_delete', '!=', 1)->where('customer_id', '=', $CustomerData->id)->where('branch_id', '=', $branchid)->sum('amount');
                if($payment_total_paid != ""){
                    $total_payment_paid = $payment_total_paid;
                }else {
                    $total_payment_paid = '0';
                }


                $payment_discount = Salespayment::where('date', '=', $fromdate)->where('soft_delete', '!=', 1)->where('customer_id', '=', $CustomerData->id)->where('branch_id', '=', $branchid)->sum('salespayment_discount');
                if($payment_discount != ""){
                    $totpayment_discount = $payment_discount;
                }else {
                    $totpayment_discount = '0';
                }
                $total_amount_paid = $total_paid_Amount + $total_payment_paid + $totpayment_discount;
                $total_balance = $tot_saleAmount - $total_amount_paid;


                // $tot_saleAmount = BranchwiseBalance::where('customer_id', '=', $CustomerData->id)->where('branch_id', '=', $branchid)->sum('sales_amount');
                // $total_amount_paid = BranchwiseBalance::where('customer_id', '=', $CustomerData->id)->where('branch_id', '=', $branchid)->sum('sales_paid');
                // $total_balance = BranchwiseBalance::where('customer_id', '=', $CustomerData->id)->where('branch_id', '=', $branchid)->sum('sales_balance');
    
                $payment_sale_discount = Salespayment::where('date', '=', $fromdate)->
                                                        where('customer_id', '=', $CustomerData->id)
                                                        ->where('branch_id', '=', $branchid)
                                                        ->where('soft_delete', '!=', 1)
                                                        ->sum('salespayment_discount');
                if($payment_sale_discount != ""){
                    $paymentsale_discount = $payment_sale_discount;
                }else {
                    $paymentsale_discount = '0';
                }


            }



            if($todate){
                $GETbranch = Branch::findOrFail($branchid);
                $GETBranchname = $GETbranch->shop_name;


                $data = Sales::where('date', '=', $todate)->where('customer_id', '=', $CustomerData->id)->where('branch_id', '=', $branchid)->where('soft_delete', '!=', 1)->get();
                $Sales_data = [];


                $sales = [];
                foreach ($data as $key => $datas_arr) {
                    $sales[] = $datas_arr;
                }
                $salepayment_s = [];
                $Salespaymentdata = Salespayment::where('date', '=', $todate)->where('customer_id', '=', $CustomerData->id)->where('branch_id', '=', $branchid)->where('soft_delete', '!=', 1)->get();
                foreach ($Salespaymentdata as $key => $Salespaymentdatas) {
                    $salepayment_s[] = $Salespaymentdatas;
                }


                $Sales_data = [];
                $terms = [];

                $merge = array_merge($sales, $salepayment_s);
                foreach ($merge as $key => $datas) {

                    $branch_name = Branch::findOrFail($datas->branch_id);
                    $SalesProducts = SalesProduct::where('sales_id', '=', $datas->id)->where('branch_id', '=', $branchid)->get();
                    foreach ($SalesProducts as $key => $SalesProducts_arr) {

                        $productlist_ID = Productlist::findOrFail($SalesProducts_arr->productlist_id);
                        $terms[] = array(
                            'bag' => $SalesProducts_arr->bagorkg,
                            'kgs' => $SalesProducts_arr->count,
                            'price_per_kg' => $SalesProducts_arr->price_per_kg,
                            'total_price' => $SalesProducts_arr->total_price,
                            'product_name' => $productlist_ID->name,
                            'sales_id' => $SalesProducts_arr->sales_id,

                        );

                    }


                    if($datas->status != ""){
                        $paid = $datas->paid_amount;
                        $balance = $datas->balance_amount;
                        $type='SALES';
                        $discount = '';
                    }else {
                        $paid = $datas->amount;
                        $balance = $datas->payment_pending;
                        $type='PAYMENT';
                        $discount = $datas->salespayment_discount;
                    }

                    $Sales_data[] = array(
                        'unique_key' => $datas->unique_key,
                        'branch_name' => $branch_name->shop_name,
                        'customer_name' => $CustomerData->name,
                        'date' => $datas->date,
                        'time' => $datas->time,
                        'gross_amount' => $datas->gross_amount,
                        'paid_amount' => $paid,
                        'bill_no' => $datas->bill_no,
                        'sales_order' => $datas->sales_order,
                        'grand_total' => $datas->grand_total,
                        'balance_amount' => $balance,
                        'type' => $type,
                        'discount' => $discount,
                        'id' => $datas->id,
                        'sales_terms' => $terms,
                        'status' => $datas->status,
                        'branchheading' => $branch_name->shop_name,
                        'customerheading' => '',
                        'fromdateheading' => '',
                        'todateheading' => '',
                        'datetime' => $datas->date . $datas->time,

                    );
                }

                $Customername = $CustomerData->name;
                $customer_id = $CustomerData->id;
                $unique_key = $CustomerData->unique_key;
                $customerid = $CustomerData->id;


                $total_sale_amt = Sales::where('date', '=', $todate)->where('soft_delete', '!=', 1)->where('customer_id', '=', $CustomerData->id)->where('branch_id', '=', $branchid)->sum('gross_amount');
                if($total_sale_amt != ""){
                    $tot_saleAmount = $total_sale_amt;
                }else {
                    $tot_saleAmount = '0';
                }


                // Total Paid
                $total_paid = Sales::where('date', '=', $todate)->where('soft_delete', '!=', 1)->where('customer_id', '=', $CustomerData->id)->where('branch_id', '=', $branchid)->sum('paid_amount');
                if($total_paid != ""){
                    $total_paid_Amount = $total_paid;
                }else {
                    $total_paid_Amount = '0';
                }
                $payment_total_paid = Salespayment::where('date', '=', $todate)->where('soft_delete', '!=', 1)->where('customer_id', '=', $CustomerData->id)->where('branch_id', '=', $branchid)->sum('amount');
                if($payment_total_paid != ""){
                    $total_payment_paid = $payment_total_paid;
                }else {
                    $total_payment_paid = '0';
                }


                $payment_discount = Salespayment::where('date', '=', $todate)->where('soft_delete', '!=', 1)->where('customer_id', '=', $CustomerData->id)->where('branch_id', '=', $branchid)->sum('salespayment_discount');
                if($payment_discount != ""){
                    $totpayment_discount = $payment_discount;
                }else {
                    $totpayment_discount = '0';
                }
                $total_amount_paid = $total_paid_Amount + $total_payment_paid + $totpayment_discount;
                $total_balance = $tot_saleAmount - $total_amount_paid;


                // $tot_saleAmount = BranchwiseBalance::where('customer_id', '=', $CustomerData->id)->where('branch_id', '=', $branchid)->sum('sales_amount');
                // $total_amount_paid = BranchwiseBalance::where('customer_id', '=', $CustomerData->id)->where('branch_id', '=', $branchid)->sum('sales_paid');
                // $total_balance = BranchwiseBalance::where('customer_id', '=', $CustomerData->id)->where('branch_id', '=', $branchid)->sum('sales_balance');
    
                $payment_sale_discount = Salespayment::where('date', '=', $todate)->
                                                    where('customer_id', '=', $CustomerData->id)
                                                    ->where('branch_id', '=', $branchid)
                                                    ->where('soft_delete', '!=', 1)
                                                    ->sum('salespayment_discount');
                if($payment_sale_discount != ""){
                    $paymentsale_discount = $payment_sale_discount;
                }else {
                    $paymentsale_discount = '0';
                }

            }

            if($fromdate && $todate){
                $GETbranch = Branch::findOrFail($branchid);
                $GETBranchname = $GETbranch->shop_name;

                $data = Sales::whereBetween('date', [$fromdate, $todate])->where('customer_id', '=', $CustomerData->id)->where('branch_id', '=', $branchid)->where('soft_delete', '!=', 1)->get();
                $sales = [];
                foreach ($data as $key => $datas_arr) {
                    $sales[] = $datas_arr;
                }
                $salepayment_s = [];
                $Salespaymentdata = Salespayment::whereBetween('date', [$fromdate, $todate])->where('customer_id', '=', $CustomerData->id)->where('branch_id', '=', $branchid)->where('soft_delete', '!=', 1)->get();
                foreach ($Salespaymentdata as $key => $Salespaymentdatas) {
                    $salepayment_s[] = $Salespaymentdatas;
                }


                $Sales_data = [];
                $terms = [];

                $merge = array_merge($sales, $salepayment_s);
                foreach ($merge as $key => $datas) {

                    $branch_name = Branch::findOrFail($datas->branch_id);
                    $SalesProducts = SalesProduct::where('sales_id', '=', $datas->id)->where('branch_id', '=', $branchid)->get();
                    foreach ($SalesProducts as $key => $SalesProducts_arr) {

                        $productlist_ID = Productlist::findOrFail($SalesProducts_arr->productlist_id);
                        $terms[] = array(
                            'bag' => $SalesProducts_arr->bagorkg,
                            'kgs' => $SalesProducts_arr->count,
                            'price_per_kg' => $SalesProducts_arr->price_per_kg,
                            'total_price' => $SalesProducts_arr->total_price,
                            'product_name' => $productlist_ID->name,
                            'sales_id' => $SalesProducts_arr->sales_id,

                        );

                    }


                    if($datas->status != ""){
                        $paid = $datas->paid_amount;
                        $balance = $datas->balance_amount;
                        $type='SALES';
                        $discount = '';
                    }else {
                        $paid = $datas->amount;
                        $balance = $datas->payment_pending;
                        $type='PAYMENT';
                        $discount = $datas->salespayment_discount;
                    }

                    $Sales_data[] = array(
                        'unique_key' => $datas->unique_key,
                        'branch_name' => $branch_name->shop_name,
                        'customer_name' => $CustomerData->name,
                        'date' => $datas->date,
                        'time' => $datas->time,
                        'gross_amount' => $datas->gross_amount,
                        'paid_amount' => $paid,
                        'bill_no' => $datas->bill_no,
                        'sales_order' => $datas->sales_order,
                        'grand_total' => $datas->grand_total,
                        'balance_amount' => $balance,
                        'type' => $type,
                        'discount' => $discount,
                        'id' => $datas->id,
                        'sales_terms' => $terms,
                        'status' => $datas->status,
                        'branchheading' => $branch_name->shop_name,
                        'customerheading' => '',
                        'fromdateheading' => '',
                        'todateheading' => '',
                        'datetime' => $datas->date . $datas->time,

                    );
                }

                $Customername = $CustomerData->name;
                $customer_id = $CustomerData->id;
                $unique_key = $CustomerData->unique_key;
                $customerid = $CustomerData->id;


                $total_sale_amt = Sales::whereBetween('date', [$fromdate, $todate])->where('soft_delete', '!=', 1)->where('customer_id', '=', $CustomerData->id)->where('branch_id', '=', $branchid)->sum('gross_amount');
                if($total_sale_amt != ""){
                    $tot_saleAmount = $total_sale_amt;
                }else {
                    $tot_saleAmount = '0';
                }


                // Total Paid
                $total_paid = Sales::whereBetween('date', [$fromdate, $todate])->where('soft_delete', '!=', 1)->where('customer_id', '=', $CustomerData->id)->where('branch_id', '=', $branchid)->sum('paid_amount');
                if($total_paid != ""){
                    $total_paid_Amount = $total_paid;
                }else {
                    $total_paid_Amount = '0';
                }
                $payment_total_paid = Salespayment::whereBetween('date', [$fromdate, $todate])->where('soft_delete', '!=', 1)->where('customer_id', '=', $CustomerData->id)->where('branch_id', '=', $branchid)->sum('amount');
                if($payment_total_paid != ""){
                    $total_payment_paid = $payment_total_paid;
                }else {
                    $total_payment_paid = '0';
                }


                $payment_discount = Salespayment::whereBetween('date', [$fromdate, $todate])->where('soft_delete', '!=', 1)->where('customer_id', '=', $CustomerData->id)->where('branch_id', '=', $branchid)->sum('salespayment_discount');
                if($payment_discount != ""){
                    $totpayment_discount = $payment_discount;
                }else {
                    $totpayment_discount = '0';
                }
                $total_amount_paid = $total_paid_Amount + $total_payment_paid + $totpayment_discount;
                $total_balance = $tot_saleAmount - $total_amount_paid;


                // $tot_saleAmount = BranchwiseBalance::where('customer_id', '=', $CustomerData->id)->where('branch_id', '=', $branchid)->sum('sales_amount');
                // $total_amount_paid = BranchwiseBalance::where('customer_id', '=', $CustomerData->id)->where('branch_id', '=', $branchid)->sum('sales_paid');
                // $total_balance = BranchwiseBalance::where('customer_id', '=', $CustomerData->id)->where('branch_id', '=', $branchid)->sum('sales_balance');
    
                $payment_sale_discount = Salespayment::whereBetween('date', [$fromdate, $todate])
                                                        ->where('customer_id', '=', $CustomerData->id)
                                                        ->where('branch_id', '=', $branchid)
                                                        ->where('soft_delete', '!=', 1)
                                                        ->sum('salespayment_discount');
                if($payment_sale_discount != ""){
                    $paymentsale_discount = $payment_sale_discount;
                }else {
                    $paymentsale_discount = '0';
                }

            }


        }else if($branchid == 'customer'){


            if($fromdate){
                $GETBranchname = 'All Branch';


                $data = Sales::where('date', '=', $fromdate)->where('customer_id', '=', $CustomerData->id)->where('soft_delete', '!=', 1)->get();
                $sales = [];
                foreach ($data as $key => $datas_arr) {
                    $sales[] = $datas_arr;
                }
                $salepayment_s = [];
                $Salespaymentdata = Salespayment::where('date', '=', $fromdate)->where('customer_id', '=', $CustomerData->id)->where('soft_delete', '!=', 1)->get();
                foreach ($Salespaymentdata as $key => $Salespaymentdatas) {
                    $salepayment_s[] = $Salespaymentdatas;
                }


                $Sales_data = [];
                $terms = [];

                $merge = array_merge($sales, $salepayment_s);





                foreach ($merge as $key => $datas) {
                    $branch_name = Branch::findOrFail($datas->branch_id);

                    $SalesProducts = SalesProduct::where('sales_id', '=', $datas->id)->get();
                    foreach ($SalesProducts as $key => $SalesProducts_arr) {

                        $productlist_ID = Productlist::findOrFail($SalesProducts_arr->productlist_id);
                        $terms[] = array(
                            'bag' => $SalesProducts_arr->bagorkg,
                            'kgs' => $SalesProducts_arr->count,
                            'price_per_kg' => $SalesProducts_arr->price_per_kg,
                            'total_price' => $SalesProducts_arr->total_price,
                            'product_name' => $productlist_ID->name,
                            'sales_id' => $SalesProducts_arr->sales_id,

                        );

                    }


                    if($datas->status != ""){
                        $paid = $datas->paid_amount;
                        $balance = $datas->balance_amount;
                        $type='SALES';
                        $discount = '';
                    }else {
                        $paid = $datas->amount;
                        $balance = $datas->payment_pending;
                        $type='PAYMENT';
                        $discount = $datas->salespayment_discount;
                    }

                    $Sales_data[] = array(
                        'unique_key' => $datas->unique_key,
                        'branch_name' => $branch_name->shop_name,
                        'customer_name' => $CustomerData->name,
                        'date' => $datas->date,
                        'time' => $datas->time,
                        'gross_amount' => $datas->gross_amount,
                        'paid_amount' => $paid,
                        'bill_no' => $datas->bill_no,
                        'sales_order' => $datas->sales_order,
                        'grand_total' => $datas->grand_total,
                        'balance_amount' => $balance,
                        'type' => $type,
                        'id' => $datas->id,
                        'sales_terms' => $terms,
                        'discount' => $discount,
                        'status' => $datas->status,
                        'branchheading' => $branch_name->shop_name,
                        'customerheading' => '',
                        'fromdateheading' => '',
                        'todateheading' => '',
                        'datetime' => $datas->date . $datas->time,

                    );
                }

                $Customername = $CustomerData->name;
                $customer_id = $CustomerData->id;
                $unique_key = $CustomerData->unique_key;

                $customerid = $CustomerData->id;


                $total_sale_amt = Sales::where('date', '=', $fromdate)->where('soft_delete', '!=', 1)->where('customer_id', '=', $CustomerData->id)->sum('gross_amount');
                if($total_sale_amt != ""){
                    $tot_saleAmount = $total_sale_amt;
                }else {
                    $tot_saleAmount = '0';
                }


                // Total Paid
                $total_paid = Sales::where('date', '=', $fromdate)->where('soft_delete', '!=', 1)->where('customer_id', '=', $CustomerData->id)->sum('paid_amount');
                if($total_paid != ""){
                    $total_paid_Amount = $total_paid;
                }else {
                    $total_paid_Amount = '0';
                }
                $payment_total_paid = Salespayment::where('date', '=', $fromdate)->where('soft_delete', '!=', 1)->where('customer_id', '=', $CustomerData->id)->sum('amount');
                if($payment_total_paid != ""){
                    $total_payment_paid = $payment_total_paid;
                }else {
                    $total_payment_paid = '0';
                }


                $payment_discount = Salespayment::where('date', '=', $fromdate)->where('soft_delete', '!=', 1)->where('customer_id', '=', $CustomerData->id)->sum('salespayment_discount');
                if($payment_discount != ""){
                    $totpayment_discount = $payment_discount;
                }else {
                    $totpayment_discount = '0';
                }
                $total_amount_paid = $total_paid_Amount + $total_payment_paid + $totpayment_discount;
                $total_balance = $tot_saleAmount - $total_amount_paid;


                // $tot_saleAmount = BranchwiseBalance::where('customer_id', '=', $CustomerData->id)->sum('sales_amount');
                // $total_amount_paid = BranchwiseBalance::where('customer_id', '=', $CustomerData->id)->sum('sales_paid');
                // $total_balance = BranchwiseBalance::where('customer_id', '=', $CustomerData->id)->sum('sales_balance');
    
                $payment_sale_discount = Salespayment::where('date', '=', $fromdate)->where('customer_id', '=', $CustomerData->id)->where('soft_delete', '!=', 1)->sum('salespayment_discount');
                if($payment_sale_discount != ""){
                    $paymentsale_discount = $payment_sale_discount;
                }else {
                    $paymentsale_discount = '0';
                }

            }



            if($todate){

                $GETBranchname = 'All Branch';
                $data = Sales::where('date', '=', $todate)->where('customer_id', '=', $CustomerData->id)->where('soft_delete', '!=', 1)->get();
                $sales = [];
                foreach ($data as $key => $datas_arr) {
                    $sales[] = $datas_arr;
                }
                $salepayment_s = [];
                $Salespaymentdata = Salespayment::where('date', '=', $todate)->where('customer_id', '=', $CustomerData->id)->where('soft_delete', '!=', 1)->get();
                foreach ($Salespaymentdata as $key => $Salespaymentdatas) {
                    $salepayment_s[] = $Salespaymentdatas;
                }


                $Sales_data = [];
                $terms = [];

                $merge = array_merge($sales, $salepayment_s);
                foreach ($merge as $key => $datas) {

                    $branch_name = Branch::findOrFail($datas->branch_id);


                    $SalesProducts = SalesProduct::where('sales_id', '=', $datas->id)->get();
                    foreach ($SalesProducts as $key => $SalesProducts_arr) {

                        $productlist_ID = Productlist::findOrFail($SalesProducts_arr->productlist_id);
                        $terms[] = array(
                            'bag' => $SalesProducts_arr->bagorkg,
                            'kgs' => $SalesProducts_arr->count,
                            'price_per_kg' => $SalesProducts_arr->price_per_kg,
                            'total_price' => $SalesProducts_arr->total_price,
                            'product_name' => $productlist_ID->name,
                            'sales_id' => $SalesProducts_arr->sales_id,

                        );

                    }


                    if($datas->status != ""){
                        $paid = $datas->paid_amount;
                        $balance = $datas->balance_amount;
                        $type='SALES';
                        $discount = '';
                    }else {
                        $paid = $datas->amount;
                        $balance = $datas->payment_pending;
                        $type='PAYMENT';
                        $discount = $datas->salespayment_discount;
                    }

                    $Sales_data[] = array(
                        'unique_key' => $datas->unique_key,
                        'branch_name' => $branch_name->shop_name,
                        'customer_name' => $CustomerData->name,
                        'date' => $datas->date,
                        'time' => $datas->time,
                        'gross_amount' => $datas->gross_amount,
                        'paid_amount' => $paid,
                        'bill_no' => $datas->bill_no,
                        'sales_order' => $datas->sales_order,
                        'grand_total' => $datas->grand_total,
                        'balance_amount' => $balance,
                        'type' => $type,
                        'discount' => $discount,
                        'id' => $datas->id,
                        'sales_terms' => $terms,
                        'status' => $datas->status,
                        'branchheading' => $branch_name->shop_name,
                        'customerheading' => '',
                        'fromdateheading' => '',
                        'todateheading' => '',
                        'datetime' => $datas->date . $datas->time,

                    );
                }

                $Customername = $CustomerData->name;
                $customer_id = $CustomerData->id;
                $unique_key = $CustomerData->unique_key;
                $customerid = $CustomerData->id;


                $total_sale_amt = Sales::where('date', '=', $todate)->where('soft_delete', '!=', 1)->where('customer_id', '=', $CustomerData->id)->sum('gross_amount');
                if($total_sale_amt != ""){
                    $tot_saleAmount = $total_sale_amt;
                }else {
                    $tot_saleAmount = '0';
                }


                // Total Paid
                $total_paid = Sales::where('date', '=', $todate)->where('soft_delete', '!=', 1)->where('customer_id', '=', $CustomerData->id)->sum('paid_amount');
                if($total_paid != ""){
                    $total_paid_Amount = $total_paid;
                }else {
                    $total_paid_Amount = '0';
                }
                $payment_total_paid = Salespayment::where('date', '=', $todate)->where('soft_delete', '!=', 1)->where('customer_id', '=', $CustomerData->id)->sum('amount');
                if($payment_total_paid != ""){
                    $total_payment_paid = $payment_total_paid;
                }else {
                    $total_payment_paid = '0';
                }


                $payment_discount = Salespayment::where('date', '=', $todate)->where('soft_delete', '!=', 1)->where('customer_id', '=', $CustomerData->id)->sum('salespayment_discount');
                if($payment_discount != ""){
                    $totpayment_discount = $payment_discount;
                }else {
                    $totpayment_discount = '0';
                }
                $total_amount_paid = $total_paid_Amount + $total_payment_paid + $totpayment_discount;
                $total_balance = $tot_saleAmount - $total_amount_paid;


                // $tot_saleAmount = BranchwiseBalance::where('customer_id', '=', $CustomerData->id)->sum('sales_amount');
                // $total_amount_paid = BranchwiseBalance::where('customer_id', '=', $CustomerData->id)->sum('sales_paid');
                // $total_balance = BranchwiseBalance::where('customer_id', '=', $CustomerData->id)->sum('sales_balance');
    
                $payment_sale_discount = Salespayment::where('date', '=', $todate)->where('customer_id', '=', $CustomerData->id)->where('soft_delete', '!=', 1)->sum('salespayment_discount');
                if($payment_sale_discount != ""){
                    $paymentsale_discount = $payment_sale_discount;
                }else {
                    $paymentsale_discount = '0';
                }

            }

            if($fromdate && $todate){

                $GETBranchname = 'All Branch';
                $data = Sales::whereBetween('date', [$fromdate, $todate])->where('customer_id', '=', $CustomerData->id)->where('soft_delete', '!=', 1)->get();
                $sales = [];
                foreach ($data as $key => $datas_arr) {
                    $sales[] = $datas_arr;
                }
                $salepayment_s = [];
                $Salespaymentdata = Salespayment::whereBetween('date', [$fromdate, $todate])->where('customer_id', '=', $CustomerData->id)->where('soft_delete', '!=', 1)->get();
                foreach ($Salespaymentdata as $key => $Salespaymentdatas) {
                    $salepayment_s[] = $Salespaymentdatas;
                }


                $Sales_data = [];
                $terms = [];

                $merge = array_merge($sales, $salepayment_s);
                foreach ($merge as $key => $datas) {

                    $branch_name = Branch::findOrFail($datas->branch_id);

                    $SalesProducts = SalesProduct::where('sales_id', '=', $datas->id)->get();
                    foreach ($SalesProducts as $key => $SalesProducts_arr) {

                        $productlist_ID = Productlist::findOrFail($SalesProducts_arr->productlist_id);
                        $terms[] = array(
                            'bag' => $SalesProducts_arr->bagorkg,
                            'kgs' => $SalesProducts_arr->count,
                            'price_per_kg' => $SalesProducts_arr->price_per_kg,
                            'total_price' => $SalesProducts_arr->total_price,
                            'product_name' => $productlist_ID->name,
                            'sales_id' => $SalesProducts_arr->sales_id,

                        );

                    }


                    if($datas->status != ""){
                        $paid = $datas->paid_amount;
                        $balance = $datas->balance_amount;
                        $type='SALES';
                        $discount = '';
                    }else {
                        $paid = $datas->amount;
                        $balance = $datas->payment_pending;
                        $type='PAYMENT';
                        $discount = $datas->salespayment_discount;
                    }

                    $Sales_data[] = array(
                        'unique_key' => $datas->unique_key,
                        'branch_name' => $branch_name->shop_name,
                        'customer_name' => $CustomerData->name,
                        'date' => $datas->date,
                        'time' => $datas->time,
                        'gross_amount' => $datas->gross_amount,
                        'paid_amount' => $paid,
                        'bill_no' => $datas->bill_no,
                        'sales_order' => $datas->sales_order,
                        'grand_total' => $datas->grand_total,
                        'balance_amount' => $balance,
                        'discount' => $discount,
                        'type' => $type,
                        'id' => $datas->id,
                        'sales_terms' => $terms,
                        'status' => $datas->status,
                        'branchheading' => $branch_name->shop_name,
                        'customerheading' => '',
                        'fromdateheading' => '',
                        'todateheading' => '',
                        'datetime' => $datas->date . $datas->time,

                    );
                }

                $Customername = $CustomerData->name;
                $customer_id = $CustomerData->id;
                $unique_key = $CustomerData->unique_key;

                $customerid = $CustomerData->id;


                $total_sale_amt = Sales::whereBetween('date', [$fromdate, $todate])->where('soft_delete', '!=', 1)->where('customer_id', '=', $CustomerData->id)->sum('gross_amount');
                if($total_sale_amt != ""){
                    $tot_saleAmount = $total_sale_amt;
                }else {
                    $tot_saleAmount = '0';
                }


                // Total Paid
                $total_paid = Sales::whereBetween('date', [$fromdate, $todate])->where('soft_delete', '!=', 1)->where('customer_id', '=', $CustomerData->id)->sum('paid_amount');
                if($total_paid != ""){
                    $total_paid_Amount = $total_paid;
                }else {
                    $total_paid_Amount = '0';
                }
                $payment_total_paid = Salespayment::whereBetween('date', [$fromdate, $todate])->where('soft_delete', '!=', 1)->where('customer_id', '=', $CustomerData->id)->sum('amount');
                if($payment_total_paid != ""){
                    $total_payment_paid = $payment_total_paid;
                }else {
                    $total_payment_paid = '0';
                }


                $payment_discount = Salespayment::whereBetween('date', [$fromdate, $todate])->where('soft_delete', '!=', 1)->where('customer_id', '=', $CustomerData->id)->sum('salespayment_discount');
                if($payment_discount != ""){
                    $totpayment_discount = $payment_discount;
                }else {
                    $totpayment_discount = '0';
                }
                $total_amount_paid = $total_paid_Amount + $total_payment_paid + $totpayment_discount;
                $total_balance = $tot_saleAmount - $total_amount_paid;


                // $tot_saleAmount = BranchwiseBalance::where('customer_id', '=', $CustomerData->id)->sum('sales_amount');
                // $total_amount_paid = BranchwiseBalance::where('customer_id', '=', $CustomerData->id)->sum('sales_paid');
                // $total_balance = BranchwiseBalance::where('customer_id', '=', $CustomerData->id)->sum('sales_balance');
    
                $payment_sale_discount = Salespayment::whereBetween('date', [$fromdate, $todate])->where('customer_id', '=', $CustomerData->id)->where('soft_delete', '!=', 1)->sum('salespayment_discount');
                if($payment_sale_discount != ""){
                    $paymentsale_discount = $payment_sale_discount;
                }else {
                    $paymentsale_discount = '0';
                }

            }

        }


        usort($Sales_data, function($a1, $a2) {
            $value1 = strtotime($a1['datetime']);
            $value2 = strtotime($a2['datetime']);
            return ($value1 < $value2) ? 1 : -1;
         });


            return view('page.backend.customer.view', compact('CustomerData', 'Sales_data', 'Customer', 'Customername', 'customer_id', 'unique_key',
            'branchid', 'customerid', 'tot_saleAmount', 'total_amount_paid', 'total_balance', 'GETBranchname', 'last_word', 'fromdate', 'todate', 'paymentsale_discount'));
    }




    public function delete($unique_key)
    {
        $data = Customer::where('unique_key', '=', $unique_key)->first();

        $data->soft_delete = 1;

        $data->update();

        return redirect()->route('customer.index')->with('soft_destroy', 'Successfully deleted the customer !');
    }



    public function getCustomers()
    {
        $GetCustomer = Customer::orderBy('name', 'ASC')->where('soft_delete', '!=', 1)->get();
        $userData['data'] = $GetCustomer;
        echo json_encode($userData);
    }


    public function checkduplicate(Request $request)
    {
        if(request()->get('query'))
        {
            $query = request()->get('query');
            $supplierdata = Customer::where('contact_number', '=', $query)->first();

            $userData['data'] = $supplierdata;
            echo json_encode($userData);
        }
    }



    public function customer_pdf_export($last_word) {
        $data = Customer::where('soft_delete', '!=', 1)->get();

        $customerarr_data = [];
        foreach ($data as $key => $datas) {
            $Customer_name = Customer::findOrFail($datas->id);

            // Total Sale
            $total_sale_amt = Sales::where('soft_delete', '!=', 1)->where('customer_id', '=', $datas->id)->where('branch_id', '=', $last_word)->sum('gross_amount');
            if($total_sale_amt != ""){
                $tot_saleAmount = $total_sale_amt;
            }else {
                $tot_saleAmount = '0';
            }


            // Total Paid
            $total_paid = Sales::where('soft_delete', '!=', 1)->where('customer_id', '=', $datas->id)->where('branch_id', '=', $last_word)->sum('paid_amount');
            if($total_paid != ""){
                $total_paid_Amount = $total_paid;
            }else {
                $total_paid_Amount = '0';
            }
            $payment_total_paid = Salespayment::where('soft_delete', '!=', 1)->where('customer_id', '=', $datas->id)->where('branch_id', '=', $last_word)->sum('amount');
            if($payment_total_paid != ""){
                $total_payment_paid = $payment_total_paid;
            }else {
                $total_payment_paid = '0';
            }


            $payment_discount = Salespayment::where('soft_delete', '!=', 1)->where('customer_id', '=', $datas->id)->where('branch_id', '=', $last_word)->sum('salespayment_discount');
            if($payment_discount != ""){
                $totpayment_discount = $payment_discount;
            }else {
                $totpayment_discount = '0';
            }
            $total_amount_paid = $total_paid_Amount + $total_payment_paid + $totpayment_discount;


            // Total Balance
            $total_balance = $tot_saleAmount - $total_amount_paid;


            $totalsaleAmt = BranchwiseBalance::where('customer_id', '=', $datas->id)->where('branch_id', '=', $last_word)->first();
            if($totalsaleAmt != ""){
                $totalsale = $totalsaleAmt->sales_amount;
                $totalpaidsale = $totalsaleAmt->sales_paid;
                $totalsalebla = $totalsaleAmt->sales_balance;
            }else {
                $totalsale = '';
                $totalpaidsale = '';
                $totalsalebla = '';
            }

            $customerarr_data[] = array(
                'unique_key' => $datas->unique_key,
                'name' => $Customer_name->name,
                'contact_number' => $datas->contact_number,
                'shop_name' => $datas->shop_name,
                'status' => $datas->status,
                'id' => $datas->id,
                'email_address' => $datas->email_address,
                'shop_address' => $datas->shop_address,
                'shop_contact_number' => $datas->shop_contact_number,
                'total_sale_amt' => $totalsale,
                'total_paid' => $totalpaidsale,
                'balance_amount' => $totalsalebla,
                'totpayment_discount' => $totpayment_discount,
            );


            $price = array();
            foreach ($customerarr_data as $key => $row)
            {
                $price[$key] = $row['balance_amount'];
            }
            array_multisort($price, SORT_DESC, $customerarr_data);
        }


        $total_sale_amount = Sales::where('soft_delete', '!=', 1)->where('branch_id', '=', $last_word)->sum('gross_amount');
            if($total_sale_amount != ""){
                $totsaleAmount = $total_sale_amount;
            }else {
                $totsaleAmount = '0';
            }

            $CustomerOldbalanceTot = Customer::where('soft_delete', '!=', 1)->sum('old_balance');

            $TotalSale = $totsaleAmount + $CustomerOldbalanceTot;


            // Total Paid
            $total_salepaid = Sales::where('soft_delete', '!=', 1)->where('branch_id', '=', $last_word)->sum('paid_amount');
            if($total_salepaid != ""){
                $total_salepaid_Amount = $total_salepaid;
            }else {
                $total_salepaid_Amount = '0';
            }
            $payment_saletotal_paid = Salespayment::where('soft_delete', '!=', 1)->where('branch_id', '=', $last_word)->sum('amount');
            if($payment_saletotal_paid != ""){
                $total_sakepayment_paid = $payment_saletotal_paid;
            }else {
                $total_sakepayment_paid = '0';
            }

            $payment_sale_discount = Salespayment::where('soft_delete', '!=', 1)->where('branch_id', '=', $last_word)->sum('salespayment_discount');
            if($payment_sale_discount != ""){
                $paymentsale_discount = $payment_sale_discount;
            }else {
                $paymentsale_discount = '0';
            }
            $total_saleamount_paid = $total_salepaid_Amount + $total_sakepayment_paid + $paymentsale_discount;


            // Total Balance
            $saletotal_balance = $TotalSale - $total_saleamount_paid;
            $branch_name = Branch::findOrFail($last_word);

            $today = Carbon::now()->format('Y-m-d');
            $current_date = date('d-m-Y', strtotime($today));


            

            return view('page.backend.customer.pdfview', compact('customerarr_data', 'TotalSale', 'total_saleamount_paid', 'saletotal_balance', 'current_date', 'totsaleAmount'));
    }







}
