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
                                            @php
                                                $colSlug = !empty($col['slug']) ? $col['slug'] : \Illuminate\Support\Str::slug($col['name'] ?? '');
                                                $colUrl = (!empty($col['url']) && !in_array($col['url'], ['#', '', 'shop.html', '/shop', 'shop']))
                                                    ? $col['url']
                                                    : route('collection', $colSlug ?: 'all');
                                            @endphp
                                            <li>
                                                <a href="{{ $colUrl }}">
                                                    {{ $col['name'] }}
                                                </a>
                                            </li>
                                        @endforeach
                                    @else
                                        <li><a href="{{ route('collection', 'everyday') }}">Everyday Elegance</a></li>
                                        <li><a href="{{ route('bridal') }}">The Bridal Edit</a></li>
                                        <li><a href="{{ route('collection', 'festive') }}">Festive Collection</a></li>
                                        <li><a href="{{ route('category', 'mens') }}">Men's Jewellery</a></li>
                                        <li><a href="{{ route('collection', 'gifting') }}">The Gifting Hub</a></li>
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
                <!-- Search Bar with Live Autocomplete Dropdown -->
                <div class="header-search-wrap" style="position: relative;">
                    <form action="{{ route('shop') }}" method="GET" class="header-search-form" autocomplete="off" role="search" style="display: flex; align-items: center; background: #f5f5f5; border-radius: 20px; padding: 6px 15px; margin-right: 10px; border: 1px solid #e0e0e0; transition: all 0.3s ease;">
                        <input 
                            type="text" 
                            name="q" 
                            id="header-search-input" 
                            class="header-search-input" 
                            value="{{ request('q') }}" 
                            placeholder="Search jewellery..." 
                            autocomplete="off" 
                            autocorrect="off" 
                            autocapitalize="off" 
                            spellcheck="false"
                            style="border: none; outline: none; background: transparent; font-size: 0.85rem; width: 180px; color: var(--text-primary); font-family: inherit;"
                        >
                        <button type="button" id="header-search-clear" class="header-search-clear" aria-label="Clear search" style="background: none; border: none; cursor: pointer; color: #999; padding: 0 4px; display: none; align-items: center; justify-content: center; font-size: 1rem; line-height: 1;">
                            <i class="ph ph-x"></i>
                        </button>
                        <button type="submit" class="header-search-submit" aria-label="Submit search" style="background: none; border: none; cursor: pointer; color: var(--text-secondary); padding: 0; display: flex; align-items: center; justify-content: center;">
                            <i class="ph ph-magnifying-glass" style="font-size: 1.2rem;"></i>
                        </button>
                    </form>
                    <div id="header-search-dropdown" class="header-search-dropdown" style="display: none;"></div>
                </div>

                <style>
                .header-search-wrap { position: relative; }
                .header-search-form:focus-within {
                    border-color: var(--accent-gold, #c0a062) !important;
                    background: #ffffff !important;
                    box-shadow: 0 4px 14px rgba(192, 160, 98, 0.18) !important;
                }
                .header-search-dropdown {
                    position: absolute;
                    top: calc(100% + 8px);
                    right: 0;
                    width: 380px;
                    max-width: calc(100vw - 32px);
                    background: #ffffff;
                    border-radius: 12px;
                    box-shadow: 0 12px 36px rgba(0, 0, 0, 0.12), 0 2px 6px rgba(0, 0, 0, 0.04);
                    border: 1px solid #ebebeb;
                    z-index: 1000;
                    overflow: hidden;
                }
                .search-dropdown-header {
                    padding: 10px 16px;
                    background: #faf8f5;
                    border-bottom: 1px solid #f0eee9;
                    display: flex;
                    justify-content: space-between;
                    align-items: center;
                    font-size: 0.75rem;
                    font-weight: 600;
                    letter-spacing: 0.05em;
                    text-transform: uppercase;
                    color: #8c7b64;
                }
                .search-dropdown-list {
                    max-height: 360px;
                    overflow-y: auto;
                    list-style: none;
                    margin: 0;
                    padding: 4px 0;
                }
                .search-dropdown-item {
                    display: flex;
                    align-items: center;
                    gap: 12px;
                    padding: 10px 16px;
                    text-decoration: none;
                    color: inherit;
                    border-bottom: 1px solid #f7f7f7;
                    transition: background-color 0.15s ease;
                }
                .search-dropdown-item:last-child { border-bottom: none; }
                .search-dropdown-item:hover, .search-dropdown-item.is-selected { background-color: #fcf9f4; }
                .search-item-img {
                    width: 48px;
                    height: 48px;
                    border-radius: 6px;
                    object-fit: cover;
                    background-color: #f5f5f5;
                    flex-shrink: 0;
                    border: 1px solid #eee;
                }
                .search-item-info { flex: 1; min-width: 0; }
                .search-item-title {
                    font-size: 0.88rem;
                    font-weight: 600;
                    color: #222;
                    margin: 0 0 2px 0;
                    white-space: nowrap;
                    overflow: hidden;
                    text-overflow: ellipsis;
                }
                .search-item-title mark {
                    background: #fdf3d7;
                    color: #8c6a1e;
                    padding: 0 2px;
                    border-radius: 2px;
                }
                .search-item-meta {
                    font-size: 0.75rem;
                    color: #888;
                    margin: 0 0 2px 0;
                    display: flex;
                    align-items: center;
                    gap: 6px;
                }
                .search-item-badge {
                    display: inline-block;
                    background: #f0f0f0;
                    padding: 1px 6px;
                    border-radius: 4px;
                    font-size: 0.7rem;
                    color: #555;
                    text-transform: capitalize;
                }
                .search-item-price { font-size: 0.85rem; font-weight: 700; color: #1a1a1a; }
                .search-dropdown-footer {
                    padding: 10px 16px;
                    background: #fafafa;
                    border-top: 1px solid #eee;
                    text-align: center;
                }
                .search-view-all-btn {
                    display: inline-flex;
                    align-items: center;
                    gap: 6px;
                    font-size: 0.82rem;
                    font-weight: 600;
                    color: var(--accent-gold, #c0a062);
                    text-decoration: none;
                    transition: gap 0.2s ease;
                }
                .search-view-all-btn:hover { gap: 10px; }
                .search-dropdown-empty { padding: 24px 16px; text-align: center; }
                .search-empty-icon { font-size: 2rem; color: #c0a062; margin-bottom: 6px; }
                .search-empty-title { font-size: 0.9rem; font-weight: 600; color: #333; margin-bottom: 4px; }
                .search-empty-sub { font-size: 0.8rem; color: #777; margin-bottom: 12px; }
                .search-quick-tags { display: flex; flex-wrap: wrap; justify-content: center; gap: 6px; }
                .search-quick-tag {
                    font-size: 0.75rem;
                    padding: 3px 10px;
                    background: #f2ede4;
                    border-radius: 12px;
                    color: #6d5b45;
                    text-decoration: none;
                    cursor: pointer;
                    transition: all 0.2s;
                    border: 1px solid #e5ddcf;
                }
                .search-quick-tag:hover { background: #c0a062; color: #fff; border-color: #c0a062; }
                .search-loading-state {
                    padding: 24px;
                    text-align: center;
                    color: #888;
                    font-size: 0.85rem;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    gap: 8px;
                }
                </style>
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
