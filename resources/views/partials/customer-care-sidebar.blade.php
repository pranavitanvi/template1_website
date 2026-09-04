<aside class="cc-sidebar">
    <ul class="cc-nav cc-sans">
        <li><a href="{{ route('shipping') }}" class="cc-nav-link {{ request()->routeIs('shipping') ? 'active' : '' }}">Shipping & Delivery</a></li>
        <li><a href="{{ route('returns') }}" class="cc-nav-link {{ request()->routeIs('returns') ? 'active' : '' }}">Returns & Exchanges</a></li>
        <li><a href="{{ route('size-guide') }}" class="cc-nav-link {{ request()->routeIs('size-guide') ? 'active' : '' }}">Size Guide</a></li>
        <li><a href="{{ route('jewellery-care') }}" class="cc-nav-link {{ request()->routeIs('jewellery-care') ? 'active' : '' }}">Jewellery Care</a></li>
        <li><a href="{{ route('faqs') }}" class="cc-nav-link {{ request()->routeIs('faqs') ? 'active' : '' }}">FAQs</a></li>
        <li><a href="{{ route('privacy-policy') }}" class="cc-nav-link {{ request()->routeIs('privacy-policy') ? 'active' : '' }}">Privacy Policy</a></li>
        <li><a href="{{ route('terms-conditions') }}" class="cc-nav-link {{ request()->routeIs('terms-conditions') ? 'active' : '' }}">Terms &amp; Conditions</a></li>
    </ul>
</aside>
