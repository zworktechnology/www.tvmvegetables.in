@extends('layout.backend.auth')

@section('content')
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h4>Branch</h4>
            </div>
            <div class="page-btn">
                <button type="button" class="btn btn-primary waves-effect waves-light btn-added" data-bs-toggle="modal"
                    data-bs-target=".bs-example-modal-xl">Add Branch</button>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table datanew">
                        <thead>
                            <tr>
                                <th>Sl. No</th>
                                <th>Name</th>
                                <th>Shop Name</th>
                                <th>Address</th>
                                <th>Contact Number</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data as $keydata => $branchdata)
                                <tr>
                                    <td>{{ ++$keydata }}</td>
                                    <td>{{ $branchdata->name }}</td>
                                    <td>{{ $branchdata->shop_name }}</td>
                                    <td>{!! $branchdata->address !!}</td>
                                    <td>{{ $branchdata->contact_number }}</td>
                                    <td style="display: flex;">
                                        <ul class="list-unstyled hstack gap-1 mb-0">
                                            <li>
                                                <a href="#edit{{ $branchdata->unique_key }}" data-bs-toggle="modal"
                                                    data-id="{{ $branchdata->unique_key }}"
                                                    data-bs-target=".branchedit-modal-xl{{ $branchdata->unique_key }}" class="badges bg-lightyellow" style="color: white">Edit</a>
                                            </li>
                                            <li>
                                                <a href="#delete{{ $branchdata->unique_key }}" data-bs-toggle="modal"
                                                    data-id="{{ $branchdata->unique_key }}"
                                                    data-bs-target=".branchdelete-modal-xl{{ $branchdata->unique_key }}" class="badges bg-lightgrey" style="color: white">Delete</a>
                                            </li>
                                        </ul>
                                    </td>
                                </tr>

                                <div class="modal fade branchedit-modal-xl{{ $branchdata->unique_key }}" tabindex="-1"
                                    role="dialog" aria-labelledby="branchdeleteLargeModalLabel{{ $branchdata->unique_key }}"
                                    aria-hidden="true"data-bs-backdrop="static">
                                    @include('page.backend.branch.edit')
                                </div>

                                <div class="modal fade branchdelete-modal-xl{{ $branchdata->unique_key }}"
                                    tabindex="-1" role="dialog" data-bs-backdrop="static"
                                    aria-labelledby="branchdeleteLargeModalLabel{{ $branchdata->unique_key }}"
                                    aria-hidden="true">
                                    @include('page.backend.branch.delete')
                                </div>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="modal fade bs-example-modal-xl" tabindex="-1" role="dialog" aria-labelledby="myExtraLargeModalLabel"
            aria-hidden="true">
            @include('page.backend.branch.create')
        </div>

    </div>
@endsection
