<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>داشبورد | حسابیر</title>
    <link rel="stylesheet" href="../../fonts/fonts.css">
    <link rel="stylesheet" href="../../css/dashboard.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" integrity="sha512-xs8dF..." crossorigin="anonymous" referrerpolicy="no-referrer" />

    <link rel="stylesheet" href="{{ asset('css/sidebar-custom.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/animate.css@4.1.1/animate.min.css" rel="stylesheet">


    <!-- در head -->



    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script>
        tailwind.config = {
            theme: { fontFamily: { 'sans': ['AnjomanMax', 'Tahoma', 'sans-serif'] }, },
            rtl: true,
        }
    </script>
    <style>
        body { background: #f9fafb; }

    </style>
    @yield('head')
    @stack('styles')
</head>
<body>
    @include('layouts.sidebar')
    <div class="main-content" id="main-content">
        @yield('content')
    </div>
<!-- قبل از </body> -->
<script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script src="{{ asset('js/sidebar-custom.js') }}"></script>
@yield('scripts')
@stack('scripts')
</body>
</html>
