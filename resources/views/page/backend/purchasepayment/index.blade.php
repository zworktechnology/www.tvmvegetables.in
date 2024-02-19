@extends('layout.backend.auth')

@section('content')
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h4>Payment Purchase</h4>
            </div>
            <div class="page-btn">
                <div class="row">
                    <div style="display: flex;">
                        <form autocomplete="off" method="POST" action="{{ route('purchasepayment.datefilter') }}">
                            @method('PUT')
                            @csrf
                            <div style="display: flex">
                                <div style="margin-right: 10px;"><input type="date" name="from_date" required
                                        class="form-control from_date" value="{{ $today }}"></div>
                                <div style="margin-right: 10px;"><input type="submit" class="btn btn-success"
                                        value="Search" /></div>
                            </div>
                        </form>
                        <a href="{{ route('purchasepayment.create') }}" class="btn btn-added" style="margin-right: 10px;">Add
                            Purchase Payment</a>
                    </div>
                </div>
            </div>
        </div>

        {{-- <div class="row">
        @php

           preg_match("/[^\/]+$/", Request::url(), $matches);
       $pos = $matches[0];
       @endphp
            <div class="col-lg-2 col-sm-4 col-6">
                <a href="{{ route('purchasepayment.index') }}" style="color: black">
                    <div class="dash-widget"@if ($pos == "purchasepayment")
                    style="border-color:red; background-color: red;"
                    @endif>
                        <div class="dash-widgetcontent">
                            <h6 @if ($pos == "purchasepayment") style="font-weight: bold; color:white" @endif>All</h6>
                        </div>
                    </div>
                </a>
            </div>
                            @php
                            $lastword = Request::url();
                            preg_match("/[^\/]+$/", $lastword, $matches);
                            $last_word = $matches[0];
                            @endphp
            @foreach ($allbranch as $keydata => $allbranches)
                <div class="col-lg-2 col-sm-4 col-6">
                    <a href="/purchasepayment_branchdata/{{$today}}/{{ $allbranches->id }}" style="color: black">
                        <div class="dash-widget" @if ($last_word == $allbranches->id)
                            style="border-color:red; background-color: red;"
                    @endif >
                            <div class="dash-widgetcontent">
                                <h6 @if ($last_word == $allbranches->id) style="font-weight: bold; color:white" @endif>{{ $allbranches->shop_name }}</h6>
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach
        </div> --}}

        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table customerdatanew">
                        <thead>
                            <tr>
                                <th>Sl. No</th>
                                <th>Time</th>
                                {{-- <th>Branch</th> --}}
                                <th>Supplier</th>
                                <th>Old Balance</th>
                                <th>Discount</th>
                                <th>Paid</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data as $keydata => $P_PaymentData)
                                <tr>
                                    <td>{{ ++$keydata }}</td>
                                    <td>{{ date('h:i A', strtotime($P_PaymentData->time)) }}</td>
                                    {{-- <td>{{ $P_PaymentData->branch->shop_name }}</td> --}}
                                    <td>{{$P_PaymentData->supplier->name }}</td>
                                    <td>{{$P_PaymentData->oldblance }}</td>
                                    <td>{{$P_PaymentData->purchasepayment_discount }}</td>
                                    <td>{{ $P_PaymentData->amount }}</td>
                                    <td>
                                        <ul class="list-unstyled hstack gap-1 mb-0">
                                            <li>
                                                <a href="{{ route('purchasepayment.edit', ['unique_key' => $P_PaymentData->unique_key]) }}"
                                                class="badges bg-lightyellow" style="color: white">Edit</a>
                                            </li>
                                        </ul>
                                    </td>


                                </tr>



                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>




    </div>
@endsection
