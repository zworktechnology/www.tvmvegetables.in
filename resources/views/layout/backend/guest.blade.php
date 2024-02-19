<!DOCTYPE html>
<html lang="en">

<head>

    @include('layout.backend.components.guest.head')

</head>

<body class="">

    <div class="main-wrapper">

        <section class="preloader">
            @include('layout.backend.components.guest.loader')
        </section>

        <section class="main main-wrapper">
            @yield('content')
        </section>

        @include('layout.backend.components.guest.script')

    </div>

</body>

</html>
