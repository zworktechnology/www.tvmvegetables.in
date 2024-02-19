@extends('layout.backend.guest')

@section('content')
    <div class="error_container">
        <div class="error-box">
            <img src="{{ asset('assets/backend/img/access_denied.png') }}"
                style="width: 400px; display: block; margin: auto;">
            <h2 class="h2 mb-3 text-center" style="font-size: 30px;"> Access Denied</h2>
            <p class="h5 font-weight-normal text-center" style="color:grey">Please contact <span style="color: #0f3800">Zwork Technology</span> for assistance <br> or refersh the page to retry.</p>
            <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="">
                <button class="btn btn-login" style="display: block; margin: auto; margin-top: 50px; border: 2px solid#0f3800; background-color:#0f3800; color:white; font-size: 16px;">Login as a different user</button>   
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="">
                @csrf
            </form>
        </div>
    </div>
@endsection
