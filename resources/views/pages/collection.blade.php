@extends('layouts.app')

@section('title', ($collectionInfo['title'] ?? 'Collection') . ' | Aura Fine Jewellery')
@section('meta_description', $collectionInfo['tagline'] ?? 'Discover fine jewellery pieces crafted with elegance.')
@section('main_style', 'margin-top: 140px;')

@section('content')
<div class="container">
    <!-- Breadcrumbs -->
    <div style="font-size: 0.9rem; color: var(--text-secondary); margin-bottom: 2rem; text-align: center;">
        <a href="{{ route('home') }}" style="color: var(--text-primary); text-decoration: none;">Home</a> / 
        <a href="{{ route('collections') }}" style="color: var(--text-primary); text-decoration: none;">Collections</a> / 
        <span style="color: var(--text-secondary);">{{ $collectionInfo['title'] ?? 'Collection' }}</span>
    </div>

    <!-- Header -->
    <div style="text-align: center; margin-bottom: 3rem;">
        <h1 style="font-family: var(--font-secondary); font-size: 3rem; margin-bottom: 1rem; text-transform: uppercase;">{{ $collectionInfo['title'] ?? 'Collection' }}</h1>
        <p style="color: var(--text-secondary); font-size: 1.1rem; max-width: 650px; margin: 0 auto;">{{ $collectionInfo['tagline'] ?? 'Discover refined designs crafted for timeless elegance.' }}</p>
        <div style="margin-top: 1rem; color: var(--text-secondary); font-size: 0.9rem;"><span id="product-count">0</span> Products</div>
    </div>

    <!-- Horizontal Filter Bar -->
    <div class="horizontal-filter-bar">
        <div class="filter-controls">
            <span class="filter-label">FILTER BY</span>

            <!-- Category Filter -->
            <div class="filter-dropdown">
                <button class="filter-btn">Category <i class="ph ph-caret-down"></i></button>
                <div class="filter-popover">
                    <ul class="filter-list">
                        <li><label><input type="checkbox" class="cat-filter-cb" data-filter="category" value="rings"> Rings</label></li>
                        <li><label><input type="checkbox" class="cat-filter-cb" data-filter="category" value="earrings"> Earrings</label></li>
                        <li><label><input type="checkbox" class="cat-filter-cb" data-filter="category" value="necklaces"> Necklaces</label></li>
                        <li><label><input type="checkbox" class="cat-filter-cb" data-filter="category" value="bracelets"> Bracelets</label></li>
                        <li><label><input type="checkbox" class="cat-filter-cb" data-filter="category" value="bangles"> Bangles</label></li>
                        <li><label><input type="checkbox" class="cat-filter-cb" data-filter="category" value="mangalsutras"> Mangalsutras</label></li>
                        <li><label><input type="checkbox" class="cat-filter-cb" data-filter="category" value="pendants"> Pendants</label></li>
                        <li><label><input type="checkbox" class="cat-filter-cb" data-filter="category" value="chains"> Chains</label></li>
                    </ul>
                    <div class="filter-popover-footer">
                        <button class="btn-clear-dropdown">Clear</button>
                        <button class="btn-apply-dropdown">Apply</button>
                    </div>
                </div>
            </div>
            
            <!-- Price Filter -->
            <div class="filter-dropdown">
                <button class="filter-btn">Price <i class="ph ph-caret-down"></i></button>
                <div class="filter-popover">
                    <ul class="filter-list">
                        <li><label><input type="checkbox" class="cat-filter-cb" data-filter="price" value="0-25000"> Under &#8377;25,000</label></li>
                        <li><label><input type="checkbox" class="cat-filter-cb" data-filter="price" value="25000-50000"> &#8377;25,000 - &#8377;50,000</label></li>
                        <li><label><input type="checkbox" class="cat-filter-cb" data-filter="price" value="50000-100000"> &#8377;50,000 - &#8377;1 Lakh</label></li>
                        <li><label><input type="checkbox" class="cat-filter-cb" data-filter="price" value="100000-250000"> &#8377;1 Lakh - &#8377;2.5 Lakh</label></li>
                        <li><label><input type="checkbox" class="cat-filter-cb" data-filter="price" value="250000-9999999"> Above &#8377;2.5 Lakh</label></li>
                    </ul>
                    <div class="filter-popover-footer">
                        <button class="btn-clear-dropdown">Clear</button>
                        <button class="btn-apply-dropdown">Apply</button>
                    </div>
                </div>
            </div>

            <!-- Metal Filter -->
            <div class="filter-dropdown">
                <button class="filter-btn">Metal <i class="ph ph-caret-down"></i></button>
                <div class="filter-popover">
                    <ul class="filter-list">
                        <li><label><input type="checkbox" class="cat-filter-cb" data-filter="type" value="gold"> Gold</label></li>
                        <li><label><input type="checkbox" class="cat-filter-cb" data-filter="type" value="diamond"> Diamond</label></li>
                        <li><label><input type="checkbox" class="cat-filter-cb" data-filter="type" value="platinum"> Platinum</label></li>
                    </ul>
                    <div class="filter-popover-footer">
                        <button class="btn-clear-dropdown">Clear</button>
                        <button class="btn-apply-dropdown">Apply</button>
                    </div>
                </div>
            </div>

            <!-- Occasion Filter -->
            <div class="filter-dropdown">
                <button class="filter-btn">Occasion <i class="ph ph-caret-down"></i></button>
                <div class="filter-popover">
                    <ul class="filter-list">
                        <li><label><input type="checkbox" class="cat-filter-cb" data-filter="occasion" value="wedding"> Wedding</label></li>
                        <li><label><input type="checkbox" class="cat-filter-cb" data-filter="occasion" value="engagement"> Engagement</label></li>
                        <li><label><input type="checkbox" class="cat-filter-cb" data-filter="occasion" value="festive"> Festive</label></li>
                        <li><label><input type="checkbox" class="cat-filter-cb" data-filter="occasion" value="everyday"> Everyday</label></li>
                        <li><label><input type="checkbox" class="cat-filter-cb" data-filter="occasion" value="gifting"> Gifting</label></li>
                    </ul>
                    <div class="filter-popover-footer">
                        <button class="btn-clear-dropdown">Clear</button>
                        <button class="btn-apply-dropdown">Apply</button>
                    </div>
                </div>
            </div>

            <!-- Gender Filter -->
            <div class="filter-dropdown">
                <button class="filter-btn">Gender <i class="ph ph-caret-down"></i></button>
                <div class="filter-popover">
                    <ul class="filter-list">
                        <li><label><input type="checkbox" class="cat-filter-cb" data-filter="gender" value="her"> Women</label></li>
                        <li><label><input type="checkbox" class="cat-filter-cb" data-filter="gender" value="him"> Men</label></li>
                    </ul>
                    <div class="filter-popover-footer">
                        <button class="btn-clear-dropdown">Clear</button>
                        <button class="btn-apply-dropdown">Apply</button>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="sort-controls">
            <span class="filter-label">SORT BY:</span>
            <select id="sort-select" style="border: none; background: transparent; font-family: inherit; font-size: 0.9rem; cursor: pointer; outline: none; font-weight: 500;">
                <option value="recommended">Recommended</option>
                <option value="price-low">Price: Low to High</option>
                <option value="price-high">Price: High to Low</option>
                <option value="newest">Newest Arrivals</option>
            </select>
        </div>
    </div>

    <!-- Active Filters -->
    <div class="active-filters-container"></div>

    <!-- Product Grid -->
    <div id="shop-product-grid" class="grid responsive-product-grid" data-collection="{{ $collectionInfo['slug'] ?? $collectionSlug }}" data-collection-title="{{ $collectionInfo['title'] ?? 'Collection' }}" style="margin-top: 2rem; margin-bottom: 2rem;">
        <!-- Products dynamically loaded -->
    </div>

    <!-- Pagination -->
    <div id="shop-pagination" class="pagination-wrap"></div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('assets/js/products_v4.js') }}?v=15"></script>
<script src="{{ asset('assets/js/category_products.js') }}?v=15"></script>
@endpush
