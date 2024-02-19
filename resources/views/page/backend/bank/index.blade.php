@extends('layout.backend.auth')

@section('content')
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h4>Bank</h4>
            </div>
            <div class="page-btn">
               <button type="button" class="btn btn-primary waves-effect waves-light btn-added" data-bs-toggle="modal"
                    data-bs-target=".bank-modal-xl">Add Bank</button>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table  supplierdatanew">
                        <thead>
                            <tr>
                                <th>Sl. No</th>
                                <th>Bank</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data as $keydata => $bankdata)
                                <tr>
                                    <td>{{ ++$keydata }}</td>
                                    <td>{{ $bankdata->name }}</td>
                                    <td>
                                        <ul class="list-unstyled hstack gap-1 mb-0">
                                            <li>
                                                <a href="#edit{{ $bankdata->unique_key }}" data-bs-toggle="modal"
                                                    data-id="{{ $bankdata->unique_key }}"
                                                    data-bs-target=".bankedit-modal-xl{{ $bankdata->unique_key }}" class="badges bg-lightyellow" style="color: white">Edit</a>
                                            </li>
                                            <li>
                                                <a href="#delete{{ $bankdata->unique_key }}" data-bs-toggle="modal"
                                                    data-id="{{ $bankdata->unique_key }}"
                                                    data-bs-target=".bankdelete-modal-xl{{ $bankdata->unique_key }}" class="badges bg-lightgrey" style="color: white">Delete</a>
                                            </li>
                                        </ul>

                                    </td>

                                </tr>

                                <div class="modal fade bankedit-modal-xl{{ $bankdata->unique_key }}"
                                    tabindex="-1" role="dialog" data-bs-backdrop="static"
                                    aria-labelledby="bankeditLargeModalLabel{{ $bankdata->unique_key }}"
                                    aria-hidden="true">
                                    @include('page.backend.bank.edit')
                                </div>

                                <div class="modal fade bankdelete-modal-xl{{ $bankdata->unique_key }}"
                                    tabindex="-1" role="dialog"data-bs-backdrop="static"
                                    aria-labelledby="bankdeleteLargeModalLabel{{ $bankdata->unique_key }}"
                                    aria-hidden="true">
                                    @include('page.backend.bank.delete')
                                </div>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="modal fade bank-modal-xl" data-bs-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="bankLargeModalLabel"
            aria-hidden="true">
            @include('page.backend.bank.create')
        </div>

    </div>
@endsection



