@extends('layout.backend.auth')

@section('content')
    <div class="content">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-xl-12">
                        <form method="POST" action="{{ route('change.profile') }}" autocomplete="off">
                            @csrf
                            <div class="col-sm-9">
                                @foreach ($errors->all() as $error)
                                    <p class="text-danger">{{ $error }}</p>
                                @endforeach
                            </div>

                            <div class="row mb-4">
                                <label for="horizontal-firstname-input" class="col-sm-3 col-form-label">
                                    Name </label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" id="name" value="{{ auth()->user()->name }}"
                                        name="name" placeholder="Enter Your ">
                                </div>
                            </div>
                            <div class="row justify-content-end">
                                <div class="col-sm-9">
                                    <div>
                                        <button type="submit" class="btn btn-primary w-md">Update</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
