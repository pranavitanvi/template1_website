@extends('layouts.app')

@section('title', 'Product Details | Aura Fine Jewellery')
@section('meta_description', 'View fine jewelry details, specifications, gold purity, and diamond clarity.')
@section('main_style', 'margin-top: 140px;')

@section('content')
<div class="container">
    <div class="breadcrumb" id="pd-breadcrumb" style="margin-bottom: 2rem; font-size: 0.9rem; color: var(--text-secondary);">
        <a href="{{ route('home') }}" style="color: var(--text-primary); text-decoration: none;">Home</a> / 
        <a href="{{ route('shop') }}" style="color: var(--text-primary); text-decoration: none;">Shop</a> / 
        <span id="pd-title-crumb">Loading...</span>
    </div>

    <div class="product-detail-grid" id="product-container">
        <!-- Rendered dynamically -->
        <div style="text-align:center; padding: 5rem; color: var(--text-secondary);">
            <i class="ph ph-spinner ph-spin" style="font-size: 2rem; margin-bottom: 1rem; display: block;"></i>
            Loading product details...
        </div>
    </div>
    
    <div class="section" style="margin-top: 4rem; margin-bottom: 4rem;">
        <h3 class="section-title text-center">You May Also Like</h3>
        <div id="related-products" class="grid responsive-product-grid" style="margin-top: var(--space-md);">
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('assets/js/products_v4.js') }}?v=11"></script>
<script>
    document.addEventListener('DOMContentLoaded', async () => {
        const urlParams = new URLSearchParams(window.location.search);
        let productId = urlParams.get('id') || '{{ $productId ?? "" }}';
        
        // If still empty, check URL path /product/{id}
        if (!productId) {
            const parts = window.location.pathname.split('/');
            const lastPart = parts[parts.length - 1];
            if (lastPart && !isNaN(lastPart)) {
                productId = lastPart;
            }
        }
        
        if (!productId) {
            productId = '1'; // Default fallback product
        }
        
        const products = await fetchProducts();
        const product = products.find(p => p.id == productId) || products[0];
        
        if (product) {
            document.title = product.name + " | Aura Fine Jewellery";
            document.getElementById('pd-title-crumb').textContent = product.name;
            
            const priceHtml = product.isSale 
                ? `${formatPrice(product.price)} <span style="text-decoration: line-through; color: var(--text-secondary); font-size: 0.8em; margin-left:10px;">${formatPrice(product.salePrice)}</span>` 
                : formatPrice(product.price);
                
            const placeholder = getPlaceholderImage(product.category);
            const mainImgSrc = product.image || placeholder;
            
            const imagesHtml = (product.images && product.images.length > 0) 
                ? product.images.map((img, i) => 
                    `<img src="${img}" class="thumb ${i===0?'active':''}" onerror="this.onerror=null; this.src='${placeholder}';" onclick="document.getElementById('main-img').src='${img}'; document.querySelectorAll('.thumb').forEach(t=>t.classList.remove('active')); this.classList.add('active');">`
                ).join('')
                : `<img src="${placeholder}" class="thumb active">`;

            const html = `
                <div>
                    <img src="${mainImgSrc}" id="main-img" class="gallery-main" onerror="this.onerror=null; this.src='${placeholder}';" style="width: 100%; border-radius: 8px;">
                    <div class="gallery-thumbs" style="display: flex; gap: 10px; margin-top: 15px;">
                        ${imagesHtml}
                    </div>
                </div>
                <div>
                    <div style="color: var(--text-secondary); text-transform: uppercase; letter-spacing: 0.1em; font-size: 0.8rem; margin-bottom: 0.5rem;">${product.category}</div>
                    <h1 style="font-size: 2.5rem; margin-bottom: 0.5rem; font-family: var(--font-secondary);">${product.name}</h1>
                    <div style="color: var(--accent-gold); margin-bottom: 0.5rem;">
                        <i class="ph-fill ph-star"></i><i class="ph-fill ph-star"></i><i class="ph-fill ph-star"></i><i class="ph-fill ph-star"></i><i class="ph-fill ph-star"></i>
                        <span style="color: var(--text-secondary); font-size: 0.9rem; margin-left: 0.5rem;">(12 Reviews)</span>
                    </div>
                    <div style="font-size: 1.5rem; margin-bottom: 1rem; display: flex; align-items: baseline; gap: 10px; font-weight: 600;">
                        ${priceHtml} <span style="font-size: 0.9rem; font-weight: 400; color: #888;">+ 3% GST</span>
                    </div>
                    
                    <p style="margin-bottom: 1.5rem; color: #555; line-height: 1.6;">${product.description || 'Crafted with peerless precision and timeless distinction.'}</p>
                    
                    <div style="margin-bottom: 1.5rem; background: #faf8f5; padding: 1rem 1.5rem; border-radius: 8px;">
                        <div style="margin-bottom: 0.5rem;"><strong>Material:</strong> ${product.material || '18K Yellow Gold'}</div>
                        ${product.stone ? `<div><strong>Stone:</strong> ${product.stone}</div>` : ''}
                    </div>
                    
                    <div style="display: flex; gap: 1rem; margin-bottom: 2rem;">
                        <button class="btn btn-primary" style="flex: 1;" onclick="addToCart(${product.id}, 1)">Add to Bag</button>
                        <button class="btn btn-outline-light wishlist-btn" data-id="${product.id}" style="color: var(--text-primary); border-color: var(--border-light); padding: 0 1.5rem;" onclick="toggleWishlist(${product.id})">
                            <i class="ph ph-heart" style="font-size: 1.2rem;"></i>
                        </button>
                    </div>
                    
                    <div style="font-size: 0.9rem; color: var(--text-secondary); border-top: 1px solid var(--border-light); padding-top: 1.5rem; display: flex; flex-direction: column; gap: 0.5rem;">
                        <p><i class="ph ph-truck" style="color: var(--accent-gold); margin-right: 5px;"></i> Complimentary Insured Shipping & Easy Returns</p>
                        <p><i class="ph ph-certificate" style="color: var(--accent-gold); margin-right: 5px;"></i> 100% Certified Diamonds & BIS Hallmarked Gold</p>
                    </div>
                </div>
            `;
            document.getElementById('product-container').innerHTML = html;
            
            // Related products
            const related = products.filter(p => p.id != product.id).slice(0, 4);
            const relatedContainer = document.getElementById('related-products');
            if (relatedContainer) {
                relatedContainer.innerHTML = related.map(renderProductCard).join('');
            }
        }
    });
</script>
@endpush
