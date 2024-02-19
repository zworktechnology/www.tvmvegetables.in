<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


use App\Models\Purchase;
use App\Models\Sales;
use App\Models\Expence;
use App\Models\Branch;
use App\Models\SalesProduct;
use App\Models\PurchaseProduct;
use Carbon\Carbon;
use DateTime;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $today = Carbon::now()->format('Y-m-d');

        



        $total_purchase_amt_billing = Purchase::where('soft_delete', '!=', 1)->where('date', '=', $today)->sum('gross_amount');
            if($total_purchase_amt_billing != ""){
                $tot_purchaseAmount = $total_purchase_amt_billing;
            }else {
                $tot_purchaseAmount = '0';
            }

        $total_purchase_amt_payment = Purchase::where('soft_delete', '!=', 1)->where('date', '=', $today)->sum('paid_amount');
            if($total_purchase_amt_payment != ""){
                $total_purchase_payment = $total_purchase_amt_payment;
            }else {
                $total_purchase_payment = '0';
            }


        

        $total_sale_amt_billing = Sales::where('soft_delete', '!=', 1)->where('date', '=', $today)->sum('gross_amount');
            if($total_sale_amt_billing != ""){
                $tot_saleAmount = $total_sale_amt_billing;
            }else {
                $tot_saleAmount = '0';
            }

        $total_sale_amt_payment = Sales::where('soft_delete', '!=', 1)->where('date', '=', $today)->sum('paid_amount');
            if($total_sale_amt_payment != ""){
                $total_sale_payment = $total_sale_amt_payment;
            }else {
                $total_sale_payment = '0';
            }



        $total_expense_amt_billing = Expence::where('soft_delete', '!=', 1)->where('date', '=', $today)->sum('amount');
            if($total_expense_amt_billing != ""){
                $tot_expenseAmount = $total_expense_amt_billing;
            }else {
                $tot_expenseAmount = '0';
            }




            $dashbord_table = [];

            $allbranch = Branch::where('soft_delete', '!=', 1)->where('status', '!=', 1)->get();
            foreach ($allbranch as $key => $allbranchs) {

                $totalpurchaseamt_billing = Purchase::where('soft_delete', '!=', 1)->where('branch_id', '=', $allbranchs->id)->where('date', '=', $today)->sum('gross_amount');
                if($totalpurchaseamt_billing != 0){
                    $totpurchaseAmount = $totalpurchaseamt_billing;
                }else {
                    $totpurchaseAmount = '';
                }


                $totalpurchaseamt_payment = Purchase::where('soft_delete', '!=', 1)->where('branch_id', '=', $allbranchs->id)->where('date', '=', $today)->sum('paid_amount');
                if($totalpurchaseamt_payment != 0){
                    $totalpurchase_payment = $totalpurchaseamt_payment;
                }else {
                    $totalpurchase_payment = '';
                }


                $totalsaleamt_billing = Sales::where('soft_delete', '!=', 1)->where('branch_id', '=', $allbranchs->id)->where('date', '=', $today)->sum('gross_amount');
                if($totalsaleamt_billing != 0){
                    $totsaleAmount = $totalsaleamt_billing;
                }else {
                    $totsaleAmount = '';
                }

                $totalsaleamt_payment = Sales::where('soft_delete', '!=', 1)->where('branch_id', '=', $allbranchs->id)->where('date', '=', $today)->sum('paid_amount');
                if($totalsaleamt_payment != 0){
                    $totalsale_payment = $totalsaleamt_payment;
                }else {
                    $totalsale_payment = '';
                }



                $totalexpenseamt_billing = Expence::where('soft_delete', '!=', 1)->where('branch_id', '=', $allbranchs->id)->where('date', '=', $today)->sum('amount');
                if($totalexpenseamt_billing != 0){
                    $totexpenseAmount = $totalexpenseamt_billing;
                }else {
                    $totexpenseAmount = '';
                }

                $dashbord_table[] = array(
                    'branch' => $allbranchs->shop_name,
                    'today' => $today,
                    'totpurchaseAmount' => $totpurchaseAmount,
                    'totalpurchase_payment' => $totalpurchase_payment,
                    'totsaleAmount' => $totsaleAmount,
                    'totalsale_payment' => $totalsale_payment,
                    'totexpenseAmount' => $totexpenseAmount,

                );
                
            }

            $day = date('w');
            $week_start = date('Y-m-d', strtotime('-'.$day.' days'));
            $week_end = date('Y-m-d', strtotime('+'.(6-$day).' days'));


            $today_bills = Sales::where('soft_delete', '!=', 1)->where('date', '=', $today)->get();
            $today_generated_bills = count(collect($today_bills));

            $this_week_bills = Sales::whereBetween('date', [$week_start, $week_end])->where('soft_delete', '!=', 1)->get();
            $thisweek_bills = count(collect($this_week_bills));


            $first_day = date("Y-m-01", strtotime($today));
            $last_day = date("Y-m-t", strtotime($today));


            $this_month_bills = Sales::whereBetween('date', [$first_day, $last_day])->where('soft_delete', '!=', 1)->get();
            $thismonth_bills = count(collect($this_month_bills));

        return view('home', compact('today', 'tot_purchaseAmount', 'total_purchase_payment', 'tot_saleAmount', 'total_sale_payment', 'tot_expenseAmount', 'dashbord_table', 'week_start', 'week_end', 'today_generated_bills', 'thisweek_bills', 'thismonth_bills'));
    }


    public function datefilter(Request $request) {
        $today = $request->get('from_date');

        

        $total_purchase_amt_billing = Purchase::where('soft_delete', '!=', 1)->where('date', '=', $today)->sum('gross_amount');
            if($total_purchase_amt_billing != ""){
                $tot_purchaseAmount = $total_purchase_amt_billing;
            }else {
                $tot_purchaseAmount = '0';
            }

        $total_purchase_amt_payment = Purchase::where('soft_delete', '!=', 1)->where('date', '=', $today)->sum('paid_amount');
            if($total_purchase_amt_payment != ""){
                $total_purchase_payment = $total_purchase_amt_payment;
            }else {
                $total_purchase_payment = '0';
            }


        

        $total_sale_amt_billing = Sales::where('soft_delete', '!=', 1)->where('date', '=', $today)->sum('gross_amount');
            if($total_sale_amt_billing != ""){
                $tot_saleAmount = $total_sale_amt_billing;
            }else {
                $tot_saleAmount = '0';
            }

        $total_sale_amt_payment = Sales::where('soft_delete', '!=', 1)->where('date', '=', $today)->sum('paid_amount');
            if($total_sale_amt_payment != ""){
                $total_sale_payment = $total_sale_amt_payment;
            }else {
                $total_sale_payment = '0';
            }



        $total_expense_amt_billing = Expence::where('soft_delete', '!=', 1)->where('date', '=', $today)->sum('amount');
            if($total_expense_amt_billing != ""){
                $tot_expenseAmount = $total_expense_amt_billing;
            }else {
                $tot_expenseAmount = '0';
            }




            $dashbord_table = [];

            $allbranch = Branch::where('soft_delete', '!=', 1)->where('status', '!=', 1)->get();
            foreach ($allbranch as $key => $allbranchs) {

                $totalpurchaseamt_billing = Purchase::where('soft_delete', '!=', 1)->where('branch_id', '=', $allbranchs->id)->where('date', '=', $today)->sum('gross_amount');
                if($totalpurchaseamt_billing != 0){
                    $totpurchaseAmount = $totalpurchaseamt_billing;
                }else {
                    $totpurchaseAmount = '';
                }


                $totalpurchaseamt_payment = Purchase::where('soft_delete', '!=', 1)->where('branch_id', '=', $allbranchs->id)->where('date', '=', $today)->sum('paid_amount');
                if($totalpurchaseamt_payment != 0){
                    $totalpurchase_payment = $totalpurchaseamt_payment;
                }else {
                    $totalpurchase_payment = '';
                }


                $totalsaleamt_billing = Sales::where('soft_delete', '!=', 1)->where('branch_id', '=', $allbranchs->id)->where('date', '=', $today)->sum('gross_amount');
                if($totalsaleamt_billing != 0){
                    $totsaleAmount = $totalsaleamt_billing;
                }else {
                    $totsaleAmount = '';
                }

                $totalsaleamt_payment = Sales::where('soft_delete', '!=', 1)->where('branch_id', '=', $allbranchs->id)->where('date', '=', $today)->sum('paid_amount');
                if($totalsaleamt_payment != 0){
                    $totalsale_payment = $totalsaleamt_payment;
                }else {
                    $totalsale_payment = '';
                }



                $totalexpenseamt_billing = Expence::where('soft_delete', '!=', 1)->where('branch_id', '=', $allbranchs->id)->where('date', '=', $today)->sum('amount');
                if($totalexpenseamt_billing != 0){
                    $totexpenseAmount = $totalexpenseamt_billing;
                }else {
                    $totexpenseAmount = '';
                }

                $dashbord_table[] = array(
                    'branch' => $allbranchs->shop_name,
                    'today' => $today,
                    'totpurchaseAmount' => $totpurchaseAmount,
                    'totalpurchase_payment' => $totalpurchase_payment,
                    'totsaleAmount' => $totsaleAmount,
                    'totalsale_payment' => $totalsale_payment,
                    'totexpenseAmount' => $totexpenseAmount,

                );
                
            }
            $day = date('w', strtotime($today));
            $year = date('Y', strtotime($today));
            $date = new DateTime($today);
            $week = $date->format("W");

            function week_start_date($week, $year, $format = 'Y-m-d', $date = FALSE) {
   
                if ($date) {
                    $week = date("W", strtotime($date));
                    $year = date("o", strtotime($date));
                }
            
                $week = sprintf("%02s", $week);
            
                $desiredMonday = date($format, strtotime("$year-W$week-1"));
            
                return $desiredMonday;
            }
            

          
            $week_start = week_start_date($week, $year);
            $week_end = date('Y-m-d', strtotime('+6 days', strtotime($week_start)));

            $today_bills = Sales::where('soft_delete', '!=', 1)->where('date', '=', $today)->get();
            $today_generated_bills = count(collect($today_bills));


            $this_week_bills = Sales::whereBetween('date', [$week_start, $week_end])->where('soft_delete', '!=', 1)->get();
            $thisweek_bills = count(collect($this_week_bills));


            $first_day = date("Y-m-01", strtotime($today));
            $last_day = date("Y-m-t", strtotime($today));

            $this_month_bills = Sales::whereBetween('date', [$first_day, $last_day])->where('soft_delete', '!=', 1)->get();
            $thismonth_bills = count(collect($this_month_bills));

        return view('home', compact('today', 'tot_purchaseAmount', 'total_purchase_payment', 'tot_saleAmount', 'total_sale_payment', 'tot_expenseAmount', 'dashbord_table', 'week_start', 'week_end', 'today_generated_bills', 'thisweek_bills', 'thismonth_bills'));

    }
}
