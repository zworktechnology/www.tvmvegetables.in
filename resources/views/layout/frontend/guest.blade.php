<!DOCTYPE html>
<html lang="en">

<head>

    @include('layout.frontend.components.head')

</head>

<body clas="custom-cursor">

        {{-- @include('layout.frontend.components.cursor') --}}

        @include('layout.frontend.components.loader')

    <div class="page-wrapper">

            @include('layout.frontend.components.navbar')

            @yield('content')

            @include('layout.frontend.components.footer')

    </div>

        @include('layout.frontend.components.mobile-navbar')

        @include('layout.frontend.components.script')

</body>

</html>
