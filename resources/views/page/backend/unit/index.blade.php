@extends('layout.backend.auth')

@section('content')
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h4>Unit</h4>
            </div>
            <div class="page-btn">             
               <button type="button" class="btn btn-primary waves-effect waves-light btn-added" data-bs-toggle="modal"
                    data-bs-target=".unit-modal-xl">Add Unit</button>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table  supplierdatanew">
                        <thead>
                            <tr>
                                <th>Sl. No</th>
                                <th>Name</th>
                                <th>Status</th>
                                <th>action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data as $keydata => $unitdata)
                                <tr>
                                    <td>{{ ++$keydata }}</td>
                                    <td>{{ $unitdata->name }}</td>
                                    @if ($unitdata->status == 0)
                                        <td><span class="badges bg-lightgreen">Active</span></td>
                                    @else
                                        <td><span class="badges bg-lightred">De-Active</span></td>
                                    @endif
                                    <td>
                                        <ul class="list-unstyled hstack gap-1 mb-0">
                                            <li>
                                                <a href="#edit{{ $unitdata->unique_key }}" data-bs-toggle="modal"
                                                    data-id="{{ $unitdata->unique_key }}"
                                                    data-bs-target=".unitedit-modal-xl{{ $unitdata->unique_key }}" class="badges bg-lightyellow" style="color: white">Edit</a>
                                            </li>
                                            <li>
                                                <a href="#delete{{ $unitdata->unique_key }}" data-bs-toggle="modal"
                                                    data-id="{{ $unitdata->unique_key }}"
                                                    data-bs-target=".unitdelete-modal-xl{{ $unitdata->unique_key }}" class="badges bg-lightgrey" style="color: white">Delete</a>
                                            </li>
                                        </ul>

                                    </td>

                                </tr>

                                <div class="modal fade unitedit-modal-xl{{ $unitdata->unique_key }}"
                                    tabindex="-1" role="dialog"
                                    aria-labelledby="uniteditLargeModalLabel{{ $unitdata->unique_key }}"
                                    aria-hidden="true">
                                    @include('page.backend.unit.edit')
                                </div>

                                <div class="modal fade unitdelete-modal-xl{{ $unitdata->unique_key }}"
                                    tabindex="-1" role="dialog"
                                    aria-labelledby="unitdeleteLargeModalLabel{{ $unitdata->unique_key }}"
                                    aria-hidden="true">
                                    @include('page.backend.unit.delete')
                                </div>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="modal fade unit-modal-xl" tabindex="-1" role="dialog" aria-labelledby="unitLargeModalLabel"
            aria-hidden="true">
            @include('page.backend.unit.create')
        </div>

    </div>
@endsection



