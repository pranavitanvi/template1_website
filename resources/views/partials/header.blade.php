<!-- HEADER -->
<header class="header">
    @if(!empty($cmsHeader['promo_bar']['enabled']))
        <div class="promo-bar">
            @if(!empty($cmsHeader['promo_bar']['link']))
                <a href="{{ $cmsHeader['promo_bar']['link'] }}" style="color: inherit; text-decoration: none;">
                    {{ $cmsHeader['promo_bar']['text'] }}
                </a>
            @else
                {{ $cmsHeader['promo_bar']['text'] }}
            @endif
        </div>
    @endif
    <div class="container main-nav">
        <div class="nav-container">
            <button class="mobile-menu-btn" aria-label="Open Menu">
                <i class="ph ph-list"></i>
            </button>
            
            <a href="{{ route('home') }}" class="brand-logo" style="display: flex; align-items: center;">
                @php
                    $logoUrl = $cmsHeader['logo_url'] ?? ($cmsHeader['store_logo_url'] ?? null);
                    $storeName = $cmsHeader['store_name'] ?? ($cmsHeader['brand_name'] ?? 'AURA');
                @endphp
                @if(!empty($logoUrl))
                    <img src="{{ $logoUrl }}" alt="{{ $storeName }}" style="max-height: 38px; width: auto; object-fit: contain;">
                @else
                    {{ $storeName }}
                @endif
            </a>
            
            <nav class="nav-links">
                <a href="{{ route('home') }}" class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}">Home</a>
                
                <div class="nav-item-dropdown">
                    <a href="{{ route('shop') }}" class="nav-link {{ request()->routeIs('shop') || request()->routeIs('category') ? 'active' : '' }}" style="display: flex; align-items: center; gap: 5px;">
                        {{ $cmsHeader['shop_menu']['label'] ?? ($cmsHeader['shop_menu']['title'] ?? 'Shop') }} <i class="ph ph-caret-down" style="font-size: 0.8rem;"></i>
                    </a>
                    
                    <div class="bluestone-mega-menu" style="width: 800px; grid-template-columns: 1fr 1fr 250px;">
                        <!-- Column 1: Shop By Category -->
                        <div class="mega-column">
                            <div class="mega-group">
                                <h4>Shop By Category</h4>
                                <ul class="mega-list">
                                    @if(!empty($cmsHeader['shop_menu']['categories']) && is_array($cmsHeader['shop_menu']['categories']))
                                        @foreach($cmsHeader['shop_menu']['categories'] as $cat)
                                            <li>
                                                <a href="{{ $cat['url'] ?? route('category', $cat['slug'] ?? 'all') }}">
                                                    {{ $cat['name'] }}
                                                    @if(!empty($cat['badge']))
                                                        <span class="badge-new-orange">{{ $cat['badge'] }}</span>
                                                    @endif
                                                </a>
                                            </li>
                                        @endforeach
                                    @else
                                        <li><a href="{{ route('category', 'rings') }}">Rings <span class="badge-new-orange">New</span></a></li>
                                        <li><a href="{{ route('category', 'earrings') }}">Earrings</a></li>
                                        <li><a href="{{ route('category', 'necklaces') }}">Necklaces</a></li>
                                        <li><a href="{{ route('category', 'bracelets') }}">Bracelets</a></li>
                                        <li><a href="{{ route('category', 'bangles') }}">Bangles</a></li>
                                        <li><a href="{{ route('category', 'mangalsutras') }}">Mangalsutras</a></li>
                                    @endif
                                </ul>
                            </div>
                        </div>
                        
                        <!-- Column 2: By Collection -->
                        <div class="mega-column">
                            <div class="mega-group">
                                <h4>By Collection</h4>
                                <ul class="mega-list">
                                    @if(!empty($cmsHeader['shop_menu']['collections']) && is_array($cmsHeader['shop_menu']['collections']))
                                        @foreach($cmsHeader['shop_menu']['collections'] as $col)
                                            <li>
                                                <a href="{{ $col['url'] ?? route('shop', ['collection' => $col['slug'] ?? 'all']) }}">
                                                    {{ $col['name'] }}
                                                </a>
                                            </li>
                                        @endforeach
                                    @else
                                        <li><a href="{{ route('shop', ['collection' => 'everyday']) }}">Everyday Elegance</a></li>
                                        <li><a href="{{ route('bridal') }}">The Bridal Edit</a></li>
                                        <li><a href="{{ route('shop', ['collection' => 'festive']) }}">Festive Collection</a></li>
                                        <li><a href="{{ route('category', 'mens') }}">Men's Jewellery</a></li>
                                        <li><a href="{{ route('shop', ['collection' => 'gifting']) }}">The Gifting Hub</a></li>
                                    @endif
                                </ul>
                            </div>
                        </div>
                        
                        <!-- Column 3 Image -->
                        <div class="mega-column" style="padding-left: 1rem;">
                            <img src="{{ !empty($cmsHeader['shop_menu']['promo_image_url']) ? $cmsHeader['shop_menu']['promo_image_url'] : (!empty($cmsHeader['shop_menu']['featured_image_url']) ? $cmsHeader['shop_menu']['featured_image_url'] : asset('assets/images/categories/RING.png')) }}" 
                                 alt="Featured Jewellery" 
                                 class="mega-promo-img" 
                                 style="border-radius: 8px; width: 100%; height: auto; max-height: 200px; object-fit: cover;">
                        </div>
                    </div>
                </div>
                
                <a href="{{ route('collections') }}" class="nav-link {{ request()->routeIs('collections') ? 'active' : '' }}">Collections</a>
                <a href="{{ route('new-arrivals') }}" class="nav-link {{ request()->routeIs('new-arrivals') ? 'active' : '' }}">New Arrivals</a>
                <a href="{{ route('bridal') }}" class="nav-link {{ request()->routeIs('bridal') ? 'active' : '' }}">Bridal</a>
                <a href="{{ route('about') }}" class="nav-link {{ request()->routeIs('about') ? 'active' : '' }}">About</a>
                <a href="{{ route('contact') }}" class="nav-link {{ request()->routeIs('contact') ? 'active' : '' }}">Contact</a>
            </nav>
            
            <div class="nav-icons">
                <!-- Search Bar -->
                <form action="{{ route('shop') }}" method="GET" style="display: flex; align-items: center; background: #f5f5f5; border-radius: 20px; padding: 6px 15px; margin-right: 10px; border: 1px solid #e0e0e0; transition: all 0.3s ease;">
                    <input type="text" name="q" value="{{ request('q') }}" placeholder="Search jewellery..." style="border: none; outline: none; background: transparent; font-size: 0.85rem; width: 180px; color: var(--text-primary);">
                    <button type="submit" style="background: none; border: none; cursor: pointer; color: var(--text-secondary); padding: 0; display: flex; align-items: center; justify-content: center;" aria-label="Submit search">
                        <i class="ph ph-magnifying-glass" style="font-size: 1.2rem;"></i>
                    </button>
                </form>
                @if(session()->has('customer'))
                    <a href="{{ route('account') }}" class="icon-btn" aria-label="My Account" title="{{ session('customer.name') }}" style="display: inline-flex; align-items: center; gap: 4px; text-decoration: none; font-size: 0.82rem; font-weight: 600; color: #c0a062;">
                        <i class="ph ph-user-circle" style="font-size: 1.3rem;"></i>
                        <span style="font-size: 0.8rem; letter-spacing: 0.05em;">{{ Str::words(session('customer.name'), 1, '') }}</span>
                    </a>
                @else
                    <a href="{{ route('login') }}" class="icon-btn" aria-label="Account"><i class="ph ph-user"></i></a>
                @endif
                <a href="{{ route('wishlist') }}" class="icon-btn" aria-label="Wishlist">
                    <i class="ph ph-heart"></i>
                    <span class="wishlist-count" style="display: none;">0</span>
                </a>
                <a href="{{ route('cart') }}" class="icon-btn" aria-label="Shopping Bag">
                    <i class="ph ph-handbag"></i>
                    <span class="cart-count" style="display: none;">0</span>
                </a>
            </div>
        </div>
    </div>
</header>
