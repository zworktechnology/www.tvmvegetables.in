@extends('layout.backend.auth')

@section('content')
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h4>Manager</h4>
            </div>
            <div class="page-btn">
                <button type="button" class="btn btn-primary waves-effect waves-light btn-added" data-bs-toggle="modal" data-bs-target=".bs-example-modal-xl">Add Manager</button>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table  datanew">
                        <thead>
                            <tr>
                                <th>Sl. No</th>
                                <th>Name</th>
                                <th>Mail Address</th>
                                <th>Contact Number</th>
                                <th>Role</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data as $keydata => $invitetdata)
                            <tr>
                                <td>{{ ++$keydata }}</td>
                                <td>{{ $invitetdata->name }}</td>
                                <td>{{ $invitetdata->email }}</td>
                                <td>{{ $invitetdata->contact_number }}</td>
                                <td>{{ $invitetdata->role->name }}</td>
                                @if ( $invitetdata->invite_accepted_at == '')
                                <td><span class="badges bg-lightred">Pending</span></td>
                                @else
                                <td><span class="badges bg-lightgreen">Accepted</span></td>
                                @endif
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="modal fade bs-example-modal-xl" tabindex="-1" role="dialog" aria-labelledby="myExtraLargeModalLabel" aria-hidden="true">
            @include('page.backend.manager.create')
        </div>

    </div>
@endsection
