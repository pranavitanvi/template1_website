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

    <!-- Google Fonts (Cinzel, Playfair Display, Cormorant Garamond, Bodoni Moda, Prata, Inter, Poppins, Montserrat) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bodoni+Moda:ital,opsz,wght@0,6..96,400..700;1,6..96,400..700&family=Cinzel:wght@400;500;600;700&family=Cormorant+Garamond:wght@400;500;600;700&family=Inter:wght@300;400;500;600;700&family=Montserrat:wght@300;400;500;600;700&family=Playfair+Display:wght@400;500;600;700&family=Poppins:wght@300;400;500;600;700&family=Prata&display=swap" rel="stylesheet">

    <!-- Stylesheets -->
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}?v=7">
    <link rel="stylesheet" href="{{ asset('assets/css/components_v4.css') }}?v=1787381501">
    <link rel="stylesheet" href="{{ asset('assets/css/arc_carousel.css') }}?v=1787380892">
    <link rel="stylesheet" href="{{ asset('assets/css/responsive.css') }}?v=5">
    @stack('styles')

    <!-- Dynamic Storefront Theme from ERP (Always loaded after stylesheets for full priority) -->
    @if(!empty($cmsTheme))
    @php
        $accentGold = $cmsTheme['accent_gold'] ?? '#C2A878';
        $accentGoldHover = $cmsTheme['accent_gold_hover'] ?? '#A68F63';
        $bgPrimary = $cmsTheme['bg_primary'] ?? '#FCFBFA';
        $bgSecondary = $cmsTheme['bg_secondary'] ?? '#F5F3F0';
        $textPrimary = $cmsTheme['text_primary'] ?? '#2C2C2C';
        $textSecondary = $cmsTheme['text_secondary'] ?? '#595959';
        $footerBg = $cmsTheme['footer_bg'] ?? '#2a2422';
        $fontHeading = !empty($cmsTheme['font_heading']) ? $cmsTheme['font_heading'] : 'Cinzel';
        $fontBody = !empty($cmsTheme['font_body']) ? $cmsTheme['font_body'] : 'Montserrat';
        $btnRadius = $cmsTheme['button_radius'] ?? 'rounded-sm';
        $btnRadiusPx = ($btnRadius === 'pill' ? '50px' : ($btnRadius === 'rounded-sm' ? '6px' : '0px'));
    @endphp
    <style id="erp-dynamic-theme">
        :root, html, body {
            --accent-gold: {{ $accentGold }} !important;
            --accent-gold-hover: {{ $accentGoldHover }} !important;
            --bg-primary: {{ $bgPrimary }} !important;
            --bg-secondary: {{ $bgSecondary }} !important;
            --text-primary: {{ $textPrimary }} !important;
            --text-secondary: {{ $textSecondary }} !important;
            --footer-bg: {{ $footerBg }} !important;
            --font-heading: '{{ $fontHeading }}', 'Cinzel', serif !important;
            --font-secondary: '{{ $fontHeading }}', 'Cinzel', serif !important;
            --font-body: '{{ $fontBody }}', 'Montserrat', sans-serif !important;
            --btn-radius: {{ $btnRadiusPx }} !important;
        }

        /* 1. Global Body & Backgrounds */
        body {
            background-color: {{ $bgPrimary }} !important;
            color: {{ $textPrimary }} !important;
            font-family: '{{ $fontBody }}', 'Montserrat', sans-serif !important;
        }

        /* 2. Global Headings & Typography */
        h1, h2, h3, h4, h5, h6,
        .brand-logo,
        .section-title,
        .section-title-fancy,
        .hero-title,
        .craft-hero h1,
        .editorial-title,
        .footer h4,
        .page-title,
        .product-title,
        .mega-group h4 {
            font-family: '{{ $fontHeading }}', 'Cinzel', serif !important;
        }

        /* 3. Primary & Action Buttons */
        .btn-primary,
        .btn-gold,
        button[type="submit"].btn-primary,
        .hero-btn-primary,
        .co-submit-btn,
        .add-to-cart-btn:hover {
            background-color: {{ $accentGold }} !important;
            border-color: {{ $accentGold }} !important;
            color: #ffffff !important;
        }
        .btn-primary:hover,
        .btn-gold:hover,
        button[type="submit"].btn-primary:hover,
        .hero-btn-primary:hover,
        .co-submit-btn:hover {
            background-color: {{ $accentGoldHover }} !important;
            border-color: {{ $accentGoldHover }} !important;
            color: #ffffff !important;
        }

        /* 4. Outline Buttons */
        .btn-outline,
        .btn-outline-light:hover {
            border-color: {{ $accentGold }} !important;
            color: {{ $accentGold }} !important;
        }

        /* 5. Button Corner Radius */
        .btn,
        button.btn,
        .btn-primary,
        .btn-outline,
        .btn-gold,
        .hero-btn,
        .hero-btn-primary,
        .co-submit-btn,
        .add-to-cart-btn {
            border-radius: {{ $btnRadiusPx }} !important;
        }

        /* 6. Navigation, Header & Search */
        .nav-link:hover,
        .nav-link.active,
        .icon-btn:hover,
        .icon-btn[title] {
            color: {{ $accentGold }} !important;
        }
        .nav-link::after {
            background-color: {{ $accentGold }} !important;
        }
        .cart-count,
        .wishlist-count {
            background-color: {{ $accentGold }} !important;
            color: #ffffff !important;
        }
        .header-search-form:focus-within {
            border-color: {{ $accentGold }} !important;
            box-shadow: 0 4px 14px {{ $accentGold }}30 !important;
        }
        .header-search-submit:hover,
        .search-view-all-btn,
        .search-empty-icon {
            color: {{ $accentGold }} !important;
        }
        .search-quick-tag:hover {
            background: {{ $accentGold }} !important;
            border-color: {{ $accentGold }} !important;
            color: #ffffff !important;
        }

        /* 7. Promotional Bar */
        .promo-bar {
            background-color: {{ $accentGold }} !important;
            color: #ffffff !important;
        }

        /* 8. Tabs, Badges & Highlights */
        .new-arrivals-tab.active,
        .new-arrivals-tab:hover {
            color: {{ $accentGold }} !important;
        }
        .new-arrivals-tab.active {
            border-bottom-color: {{ $accentGold }} !important;
        }
        .section-subtitle-fancy,
        .badge-occasion,
        .arc-category-badge,
        .coll-gold,
        .cont-gold {
            color: {{ $accentGold }} !important;
        }
        .arc-indicator-bar,
        .arc-progress-fill,
        .slider-dot.active {
            background-color: {{ $accentGold }} !important;
        }
        .slider-dot.active {
            border-color: {{ $accentGold }} !important;
        }

        /* 9. Product Cards & Shop Grid */
        .product-card:hover .product-title {
            color: {{ $accentGold }} !important;
        }
        .wishlist-btn.active,
        .wishlist-btn:hover {
            color: {{ $accentGold }} !important;
        }
        .filter-chip button,
        .clear-all-btn,
        .clear-search-chip {
            color: {{ $accentGold }} !important;
        }
        .page-btn.active {
            background-color: {{ $accentGold }} !important;
            border-color: {{ $accentGold }} !important;
            color: #ffffff !important;
        }
        .page-btn:hover:not(:disabled) {
            border-color: {{ $accentGold }} !important;
            color: {{ $accentGold }} !important;
        }

        /* 10. Footer Styling */
        .footer {
            background-color: {{ $footerBg }} !important;
        }
        .footer h4 {
            color: {{ $accentGold }} !important;
            font-family: '{{ $fontHeading }}', 'Cinzel', serif !important;
        }
        .footer-links a:hover {
            color: {{ $accentGold }} !important;
        }
        .footer button[type="submit"] {
            background-color: {{ $accentGold }} !important;
            border-color: {{ $accentGold }} !important;
            color: #ffffff !important;
        }
        .footer-bottom a i,
        .footer-bottom div a i,
        .footer-bottom a:hover {
            color: {{ $accentGold }} !important;
        }
        .footer-bottom {
            border-top: 1px solid {{ $accentGold }}33 !important;
        }

        /* 11. Checkout, Auth & Badges */
        .co-step.active,
        .co-pay-opt:hover {
            color: {{ $accentGold }} !important;
            border-color: {{ $accentGold }} !important;
        }
        .badge-new-orange {
            background: {{ $accentGold }} !important;
        }
    </style>
    @endif

    <!-- Icons -->
    <script src="https://unpkg.com/@phosphor-icons/web"></script>

    <!-- Global Customer Auth State -->
    <script>
        window.IS_CUSTOMER_LOGGED_IN = {{ session()->has('customer') ? 'true' : 'false' }};
        window.LOGIN_URL = "{{ route('login') }}";
        window.CUSTOMER_DATA = @json(session('customer'));
    </script>
</head>
<body class="@yield('body_class', '')">

    @include('partials.header')

    <main id="main-content" style="@yield('main_style', 'margin-top: 110px;')">
        @yield('content')
    </main>

    @include('partials.footer')

    <!-- Global JavaScript -->
    <script src="{{ asset('assets/js/main.js') }}?v=5"></script>
    <script src="{{ asset('assets/js/cart.js') }}?v=5"></script>
    <script src="{{ asset('assets/js/wishlist.js') }}?v=4"></script>
    @stack('scripts')

</body>
</html>
