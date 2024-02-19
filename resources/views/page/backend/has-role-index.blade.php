@extends('layout.backend.auth')

@section('content')
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h4>Dashboard</h4>
            </div>
            <div class="page-btn">
                <div class="row">
                    <div style="display: flex;">
                        <form autocomplete="off" method="POST" action="{{ route('home.datefilter') }}">
                            @method('PUT')
                            @csrf
                            <div style="display: flex">
                                <div style="margin-right: 10px;"><input type="date" name="from_date" required
                                        class="form-control from_date" value="{{ $today }}"></div>
                                <div style="margin-right: 10px;"><input type="submit" class="btn btn-success"
                                        value="Search" /></div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>




        <div class="card">
            <div class="card-body">
                <div class="row" style="margin-top: 1%;">
                    <div class="col-lg-3 col-sm-6 col-12">
                        <div class="dash-widgetdashboard dash">
                            <div class="dash-widgetcontent">
                                <div class="row">
                                    <div class="col-lg-11  col-sm-11 col-11 py-3" style="padding:17px;margin-left:11px;border-bottom: 1px solid #0f3800;text-transform: uppercase;font-weight:700;"><span class="">Total Purchase Amount</span></div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-6  col-sm-3 col-6 py-2" style="border-right: 1px solid #0f3800;padding-left: 16%;">{{ $tot_purchaseAmount }}</div>
                                    <div class="col-lg-6 col-sm-3 col-6 py-2"><span style="font-weight:500;">{{ $total_purchase_payment }}</span></div>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="col-lg-3 col-sm-6 col-12">
                        <div class="dash-widgetdashboard dash">
                            <div class="dash-widgetcontent">
                                <div class="row">
                                    <div class="col-lg-11  col-sm-11 col-11 py-3" style="padding:17px;margin-left:11px;border-bottom: 1px solid #0f3800;text-transform: uppercase;font-weight:700;"><span class="">Total Sales Amount</span></div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-6  col-sm-3 col-6 py-2" style="border-right: 1px solid #0f3800;padding-left: 16%;font-weight:500;">{{ $tot_saleAmount }}</div>
                                    <div class="col-lg-6 col-sm-3 col-6 py-2"><span style="font-weight:500;">{{ $total_sale_payment }}</span></div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="col-lg-3 col-sm-6 col-12">
                        <div class="dash-widgetdashboard dash">
                            <div class="dash-widgetcontent">
                                <div class="row">
                                    <div class="col-lg-11  col-sm-11 col-11 py-3" style="padding:17px;margin-left:11px;border-bottom: 1px solid #0f3800;text-transform: uppercase;font-weight:700;"><span class="">Total Expense Amount</span></div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-11  col-sm-11 col-11 py-2" style="padding-left: 16%;"><span style="font-weight:500;">{{ $tot_expenseAmount }}</span></div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="row" style="margin-top: 1%;">
                    <div class="col-lg-4 col-sm-6 col-12 d-flex">
                        <div class="dash-count">
                            <div class="dash-counts">
                                <h4>{{$today_generated_bills}}</h4>
                                <h5>Today Genereated Bills</h5>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-sm-6 col-12 d-flex">
                        <div class="dash-count das1">
                            <div class="dash-counts">
                                <h4>{{$thisweek_bills}}</h4>
                                <h5>This Week Generated Bills - <br/>({{$week_start}} - {{$week_end}})</h5>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-sm-6 col-12 d-flex">
                        <div class="dash-count das2">
                            <div class="dash-counts">
                                <h4>{{$thismonth_bills}}</h4>
                                <h5>This Month Generated Bills</h5>
                            </div>
                        </div>
                    </div>
                </div>
                </div>
            </div>
        </div>




        <div class="card">
            <div class="card-body">
                <table class="table  border" style="margin-top:3%;">
                    <thead>
                        <tr>
                            <th rowspan="2" style="font-weight:700;text-transform: uppercase;border:1px solid #0f3800;">S. No</th>
                            <th rowspan="2" style="font-weight:700;text-transform: uppercase;border:1px solid #0f3800;">Branch</th>
                            <th colspan="2" style="font-weight:700;text-transform: uppercase;border:1px solid #0f3800;">Purchase Amount</th>
                            <th colspan="2" style="font-weight:700;text-transform: uppercase;border:1px solid #0f3800;">Sales Amount</th>
                            <th rowspan="2" style="font-weight:700;text-transform: uppercase;border:1px solid #0f3800;">Expense Amount</th>
                        </tr>
                        <tr>
                            <th style="font-weight:700;text-transform: uppercase;border:1px solid #0f3800;">Billing</th>
                            <th style="font-weight:700;text-transform: uppercase;border:1px solid #0f3800;">Payment</th>
                            <th style="font-weight:700;text-transform: uppercase;border:1px solid #0f3800;">Billing</th>
                            <th style="font-weight:700;text-transform: uppercase;border:1px solid #0f3800;">Payment</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach ($dashbord_table as $keydata => $dashbord_tablearr)
                        <tr>
                            <td style="font-weight:500;border:1px solid #0f3800;color:black;">{{ ++$keydata }}</td>
                            <td style="font-weight:500;border:1px solid #0f3800;color:black;">{{ $dashbord_tablearr['branch'] }}</td>
                            <td style="font-weight:500;border:1px solid #0f3800;color:black;">{{ $dashbord_tablearr['totpurchaseAmount'] }}</td>
                            <td style="font-weight:500;border:1px solid #0f3800;color:black;">{{ $dashbord_tablearr['totalpurchase_payment'] }}</td>
                            <td style="font-weight:500;border:1px solid #0f3800;color:black;">{{ $dashbord_tablearr['totsaleAmount'] }}</td>
                            <td style="font-weight:500;border:1px solid #0f3800;color:black;">{{ $dashbord_tablearr['totalsale_payment'] }}</td>
                            <td style="font-weight:500;border:1px solid #0f3800;color:black;">{{ $dashbord_tablearr['totexpenseAmount'] }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </div>


@endsection
