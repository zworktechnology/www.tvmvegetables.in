@extends('layout.backend.auth')

@section('content')
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h4>Expense</h4>
            </div>
            <div class="page-btn">
                <div class="row">
                    <div style="display: flex;">
                        <form autocomplete="off" method="POST" action="{{ route('expence.datefilter') }}">
                            @method('PUT')
                            @csrf
                            <div style="display: flex">
                                <div style="margin-right: 10px;"><input type="date" name="from_date" required
                                        class="form-control from_date" value="{{ $today }}"></div>
                                <div style="margin-right: 10px;"><input type="submit" class="btn btn-success"
                                        value="Search" /></div>
                            </div>
                        </form>
                        <a href="{{ route('expence.create') }}" class="btn btn-added" style="margin-right: 10px;">
                            Add Expense</a>
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
                <a href="{{ route('expence.index') }}">
                    <div class="dash-widget" @if ($pos == "expence")
                    style="border-color:red; background-color: red; margin-bottom:18px;"
                    @endif>
                        <div class="dash-widgetcontent">
                            <h6 @if ($pos == "expence") style="font-weight: bold; color:white" @endif>All</h6>
                        </div>
                    </div>
                </a>
            </div>
                            @php
                            $lastword = Request::url();
                            preg_match("/[^\/]+$/", $lastword, $matches);
                            $last_word = $matches[0];
                            @endphp
            @foreach ($branch as $keydata => $allbranches)
                <div class="col-lg-2 col-sm-4 col-6">
                    <a href="/expensedata_branch/{{$today}}/{{ $allbranches->id }}">
                        <div class="dash-widget" @if ($last_word == $allbranches->id)
                            style="border-color:red; background-color: red;"
                    @endif>
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
                                {{-- <th>Branch</th> --}}
                                <th>Date & Time</th>
                                <th>Grand Total</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($expense_data as $keydata => $expenceData)
                                <tr>
                                    <td>{{ ++$keydata }}</td>
                                    {{-- <td>{{ $expenceData['branch_name'] }}</td> --}}
                                    <td>{{ date('d M Y', strtotime($expenceData['date'])) }} -
                                        {{ date('h:i A', strtotime($expenceData['time'])) }}</td>
                                    <td>{{ $expenceData['amount'] }}</td>
                                    <td>
                                        <ul class="list-unstyled hstack gap-1 mb-0">
                                            <li>
                                                <a href="#expenseview{{ $expenceData['unique_key'] }}"
                                                    data-bs-toggle="modal" data-id="{{ $expenceData['id'] }}"
                                                    data-bs-target=".expenseview-modal-xl{{ $expenceData['unique_key'] }}"
                                                    class="badges bg-lightgrey expenseview" style="color: white">View</a>

                                            </li>
                                            <li>
                                                <a href="{{ route('expence.edit', ['unique_key' => $expenceData['unique_key']]) }}"
                                                class="badges bg-lightyellow" style="color: white">Edit</a>
                                            </li>

                                            <li>
                                                <a href="#delete{{ $expenceData['unique_key'] }}" data-bs-toggle="modal"
                                                    data-id="{{ $expenceData['unique_key'] }}"
                                                    data-bs-target=".expencedelete-modal-xl{{ $expenceData['unique_key'] }}"
                                                    class="badges bg-lightred" style="color: white">Delete</a>
                                            </li>

                                        </ul>
                                    </td>
                                </tr>


                                <div class="modal fade expencedelete-modal-xl{{ $expenceData['unique_key'] }}"
                                    tabindex="-1" role="dialog"data-bs-backdrop="static"
                                    aria-labelledby="expencedeleteLargeModalLabel{{ $expenceData['unique_key'] }}"
                                    aria-hidden="true">
                                    @include('page.backend.expence.delete')
                                </div>
                                <div class="modal fade expenseview-modal-xl{{ $expenceData['unique_key'] }}"
                                    tabindex="-1" role="dialog" data-bs-backdrop="static"
                                    aria-labelledby="expenseviewLargeModalLabel{{ $expenceData['unique_key'] }}"
                                    aria-hidden="true">
                                    @include('page.backend.expence.view')
                                </div>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>




    </div>
@endsection
