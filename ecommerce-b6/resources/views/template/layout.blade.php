<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'E-Commerce')</title>

    {{-- Bootstrap --}}
    <link rel="stylesheet" href="{{ asset('bootstrap-5.3.8-dist/css/bootstrap.min.css') }}">

    {{-- Custom CSS --}}
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    {{-- Page specific css --}}
    @stack('styles')
</head>

<body class="bg-light d-flex flex-column min-vh-100">

    {{-- NAVBAR --}}
    @include('template.navbar')

    {{-- MAIN CONTENT --}}
    <main class="flex-fill py-4">
        <div class="container">
            @yield('content')
        </div>
    </main>

    {{-- FOOTER --}}
    @include('template.footer')

    {{-- Bootstrap JS --}}
    <script src="{{ asset('bootstrap-5.3.8-dist/js/bootstrap.bundle.min.js') }}"></script>

    {{-- Chart.js (for dashboard) --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    {{-- Page specific scripts --}}
    @stack('scripts')

</body>
</html>
