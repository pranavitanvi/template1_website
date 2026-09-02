<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
    <meta http-equiv="Pragma" content="no-cache" />
    <meta http-equiv="Expires" content="0" />
    <meta name="description" content="@yield('meta_description', 'Discover timeless luxury jewellery pieces crafted to celebrate every moment. Explore our elegant collections.')">
    <title>@yield('title', 'Aura Fine Jewellery | Luxury E-Commerce')</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;500;600;700&family=Montserrat:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Stylesheets -->
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}?v=6">
    <link rel="stylesheet" href="{{ asset('assets/css/components_v4.css') }}?v=1787381408">
    <link rel="stylesheet" href="{{ asset('assets/css/arc_carousel.css') }}?v=1787380891">
    <link rel="stylesheet" href="{{ asset('assets/css/responsive.css') }}?v=4">
    @stack('styles')

    <!-- Icons -->
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
</head>
<body class="@yield('body_class', '')">

    @include('partials.header')

    <main id="main-content" style="@yield('main_style', 'margin-top: 110px;')">
        @yield('content')
    </main>

    @include('partials.footer')

    <!-- Global JavaScript -->
    <script src="{{ asset('assets/js/main.js') }}?v=4"></script>
    <script src="{{ asset('assets/js/cart.js') }}?v=4"></script>
    <script src="{{ asset('assets/js/wishlist.js') }}?v=4"></script>
    @stack('scripts')

</body>
</html>
