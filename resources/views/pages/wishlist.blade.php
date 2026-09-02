@extends('layouts.app')

@section('title', 'Your Wishlist | Aura Fine Jewellery')
@section('meta_description', 'View and manage your saved jewellery pieces in your personal Aura wishlist.')
@section('main_style', 'margin-top: 140px; min-height: 55vh;')

@section('content')
<div class="container section" style="padding-bottom: 5rem;">
    <h1 class="text-center" style="margin-bottom: var(--space-xl); font-family: var(--font-secondary); text-transform: uppercase;">Your Wishlist</h1>
    
    <div id="wishlist-grid" class="grid responsive-product-grid">
        <div style="grid-column: 1/-1; text-align: center; color: var(--text-secondary);">
            <i class="ph ph-spinner ph-spin" style="font-size: 2rem; margin-bottom: 1rem; display: block;"></i>
            Loading your wishlist...
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('assets/js/products_v4.js') }}"></script>
<script>
    async function renderWishlistPage() {
        const container = document.getElementById('wishlist-grid');
        const localWishlist = JSON.parse(localStorage.getItem('jewellery_wishlist')) || [];
        
        if (localWishlist.length === 0) {
            container.innerHTML = `
                <div style="grid-column: 1/-1; text-align: center; padding: 3rem 1rem;">
                    <i class="ph ph-heart" style="font-size: 3rem; color: #ccc; margin-bottom: 1rem; display: block;"></i>
                    <p style="font-size: 1.1rem; color: var(--text-secondary); margin-bottom: 1.5rem;">Your wishlist is currently empty.</p>
                    <a href="{{ route('shop') }}" class="btn btn-secondary">Discover Pieces</a>
                </div>
            `;
            return;
        }

        const products = await fetchProducts();
        const wishlistProducts = products.filter(p => localWishlist.includes(p.id));
        
        if (wishlistProducts.length === 0) {
            container.innerHTML = `
                <div style="grid-column: 1/-1; text-align: center; padding: 3rem 1rem;">
                    <p style="font-size: 1.1rem; color: var(--text-secondary); margin-bottom: 1.5rem;">No matching products found.</p>
                    <a href="{{ route('shop') }}" class="btn btn-secondary">Discover Pieces</a>
                </div>
            `;
            return;
        }

        container.innerHTML = wishlistProducts.map(renderProductCard).join('');
        if (typeof updateWishlistButtons === 'function') {
            updateWishlistButtons();
        }
    }

    document.addEventListener('DOMContentLoaded', renderWishlistPage);
</script>
@endpush
