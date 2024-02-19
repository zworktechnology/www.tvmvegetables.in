<!DOCTYPE html>
<html lang="en">

<head>

    @include('layout.backend.components.auth.head')

</head>

<body style="text-transform: uppercase;">

    <div class="main-wrapper page-wrapper">

        <section class="preloader">
            {{-- @include('layout.backend.components.auth.loader') --}}
        </section>

        <section class="top-bar">
            @include('layout.backend.components.auth.top-bar')
        </section>

        <section class="sidebar">
            @include('layout.backend.components.auth.side-bar')
        </section>

        <section class="main">
            @yield('content')
        </section>

    </div>

    @include('layout.backend.components.auth.script')

</body>

</html>
