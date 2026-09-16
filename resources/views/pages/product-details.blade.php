@extends('layouts.app')

@section('title', isset($product['name']) ? ($product['name'] . ' | Aura Fine Jewellery') : 'Product Details | Aura Fine Jewellery')
@section('meta_description', isset($product['seo']['meta_description']) && $product['seo']['meta_description'] ? $product['seo']['meta_description'] : (isset($product['description']) && $product['description'] ? \Illuminate\Support\Str::limit($product['description'], 160) : 'View fine jewelry details, specifications, gold purity, and diamond clarity.'))
@section('main_style', 'margin-top: 140px;')

@push('styles')
<style>
    .product-detail-grid {
        display: grid;
        grid-template-columns: 450px 1fr;
        gap: var(--space-xl, 3rem);
        margin-bottom: var(--space-xl, 3rem);
        align-items: start;
    }
    
    .gallery-main {
        width: 100%;
        aspect-ratio: 1 / 1;
        object-fit: cover;
        margin-bottom: 1rem;
        background-color: var(--bg-secondary, #faf8f5);
        border-radius: 8px;
    }

    .gallery-thumbs {
        display: flex;
        gap: 10px;
        margin-top: 15px;
        flex-wrap: wrap;
    }

    .thumb {
        width: 80px;
        height: 80px;
        object-fit: cover;
        cursor: pointer;
        border: 1px solid var(--border-light, #eee);
        border-radius: 6px;
        transition: border-color 0.2s ease;
    }

    .thumb.active {
        border-color: var(--accent-gold, #c0a062);
    }
    
    @media (max-width: 900px) {
        .product-detail-grid {
            grid-template-columns: 1fr;
            gap: var(--space-md, 1.5rem);
        }
    }
</style>
@endpush

@section('content')
<div class="container">
    <div class="breadcrumb" id="pd-breadcrumb" style="margin-bottom: 2rem; font-size: 0.9rem; color: var(--text-secondary);">
        <a href="{{ route('home') }}" style="color: var(--text-primary); text-decoration: none;">Home</a> / 
        <a href="{{ route('shop') }}" style="color: var(--text-primary); text-decoration: none;">Shop</a> / 
        <span id="pd-title-crumb">{{ $product['name'] ?? 'Loading...' }}</span>
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
<script src="{{ asset('assets/js/products_v4.js') }}?v=14"></script>
<script>
    document.addEventListener('DOMContentLoaded', async () => {
        const urlParams = new URLSearchParams(window.location.search);
        let productId = urlParams.get('id') || urlParams.get('slug') || '{{ $productId ?? "" }}';
        
        // If still empty, check URL path /product/{id}
        if (!productId) {
            const parts = window.location.pathname.split('/');
            const lastPart = parts[parts.length - 1];
            if (lastPart && lastPart !== 'product' && lastPart !== 'product-details') {
                productId = lastPart;
            }
        }
        
        if (!productId) {
            productId = '1'; // Default fallback product
        }
        
        let product = @json($product ?? null);
        if (!product && productId) {
            product = await fetchProduct(productId);
        }
        if (!product) {
            const products = await fetchProducts();
            product = products.find(p => p.id == productId || p.slug == productId);
        }
        
        if (product) {
            const isSoldOut = Boolean(product.is_sold_out || product.availability === 'sold_out' || product.availability === 'out_of_stock');
            document.title = (isSoldOut ? "[Sold Out] " : "") + product.name + " | Aura Fine Jewellery";
            document.getElementById('pd-title-crumb').textContent = (isSoldOut ? "[Sold Out] " : "") + product.name;
            
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

            // Stone Details
            let stonesHtml = '';
            if (product.stones && product.stones.length > 0) {
                stonesHtml = `
                    <div style="margin-top: 0.6rem; padding-top: 0.6rem; border-top: 1px solid rgba(0,0,0,0.06);">
                        <div style="margin-bottom: 0.3rem;"><strong>Stone Details:</strong></div>
                        <ul style="margin: 0; padding-left: 1.2rem; font-size: 0.9rem; color: #444; line-height: 1.6;">
                            ${product.stones.map(s => {
                                let parts = [];
                                if (s.stone_shape) parts.push(s.stone_shape);
                                if (s.stone_clarity) parts.push(s.stone_clarity);
                                if (s.stone_color) parts.push(s.stone_color);
                                if (s.stone_quantity) parts.push(s.stone_quantity + ' pcs');
                                if (s.stone_gross_weight) parts.push(s.stone_gross_weight + ' ' + (s.stone_gross_weight_type || 'CT'));
                                return `<li><strong>${s.stone_name || 'Stone'}</strong>${parts.length > 0 ? ': ' + parts.join(' ') : ''}</li>`;
                            }).join('')}
                        </ul>
                    </div>
                `;
            } else if (product.stone) {
                stonesHtml = `<div style="margin-top: 0.4rem;"><strong>Stone:</strong> ${product.stone}</div>`;
            }

            let pricingBreakdownHtml = '';
            if (product.pricing) {
                pricingBreakdownHtml = `
                    <div style="margin-bottom: 1.5rem; background: #faf9f6; border: 1px solid rgba(212,175,55,0.25); border-radius: 8px; padding: 1rem 1.25rem;">
                        <div style="font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.08em; font-weight: 700; color: var(--accent-gold); margin-bottom: 0.6rem; display: flex; justify-content: space-between;">
                            <span>Live Price Breakdown</span>
                            <span style="font-weight: 500; text-transform: none; color: #888;">(Calculated from Metal & Stones)</span>
                        </div>
                        <div style="display: flex; justify-content: space-between; font-size: 0.88rem; margin-bottom: 0.35rem; color: #555;">
                            <span>Gold Value (${product.pricing.valuation_weight || product.net_weight}g @ ₹${Number(product.pricing.rate_1gm || 0).toLocaleString('en-IN')}/g):</span>
                            <strong style="color: #222;">₹${Number(product.pricing.metal_val || 0).toLocaleString('en-IN', {minimumFractionDigits: 2, maximumFractionDigits: 2})}</strong>
                        </div>
                        ${product.pricing.stone_val > 0 ? `
                        <div style="display: flex; justify-content: space-between; font-size: 0.88rem; margin-bottom: 0.35rem; color: #555;">
                            <span>Stones & Diamonds Value:</span>
                            <strong style="color: #222;">₹${Number(product.pricing.stone_val).toLocaleString('en-IN', {minimumFractionDigits: 2, maximumFractionDigits: 2})}</strong>
                        </div>` : ''}
                        ${product.pricing.making_val > 0 ? `
                        <div style="display: flex; justify-content: space-between; font-size: 0.88rem; margin-bottom: 0.35rem; color: #555;">
                            <span>Making Charges:</span>
                            <strong style="color: #222;">₹${Number(product.pricing.making_val).toLocaleString('en-IN', {minimumFractionDigits: 2, maximumFractionDigits: 2})}</strong>
                        </div>` : ''}
                        ${product.pricing.hallmark_val > 0 ? `
                        <div style="display: flex; justify-content: space-between; font-size: 0.88rem; margin-bottom: 0.35rem; color: #555;">
                            <span>Hallmarking:</span>
                            <strong style="color: #222;">₹${Number(product.pricing.hallmark_val).toLocaleString('en-IN', {minimumFractionDigits: 2, maximumFractionDigits: 2})}</strong>
                        </div>` : ''}
                        ${product.pricing.other_val > 0 ? `
                        <div style="display: flex; justify-content: space-between; font-size: 0.88rem; margin-bottom: 0.35rem; color: #555;">
                            <span>Other Charges:</span>
                            <strong style="color: #222;">₹${Number(product.pricing.other_val).toLocaleString('en-IN', {minimumFractionDigits: 2, maximumFractionDigits: 2})}</strong>
                        </div>` : ''}
                        <div style="display: flex; justify-content: space-between; font-size: 0.95rem; font-weight: 700; border-top: 1px dashed rgba(0,0,0,0.1); padding-top: 0.5rem; margin-top: 0.4rem; color: #222;">
                            <span>Total Estimated Value:</span>
                            <span style="color: var(--accent-gold); font-size: 1.05rem;">₹${Number(product.price).toLocaleString('en-IN', {minimumFractionDigits: 2, maximumFractionDigits: 2})}</span>
                        </div>
                    </div>
                `;
            }

            const isLiked = (typeof wishlist !== 'undefined' && Array.isArray(wishlist)) ? wishlist.includes(parseInt(product.id, 10)) : false;
            const heartIcon = isLiked ? '<i class="ph-fill ph-heart" style="font-size: 1.2rem; color: #e74c3c;"></i>' : '<i class="ph ph-heart" style="font-size: 1.2rem;"></i>';
            const wishlistBtnStyle = isLiked ? 'color: #e74c3c; border-color: #e74c3c; padding: 0 1.5rem;' : 'color: var(--text-primary); border-color: var(--border-light); padding: 0 1.5rem;';

            const html = `
                <div>
                    <img src="${mainImgSrc}" id="main-img" class="gallery-main" onerror="this.onerror=null; this.src='${placeholder}';" style="width: 100%; border-radius: 8px;">
                    <div class="gallery-thumbs" style="display: flex; gap: 10px; margin-top: 15px;">
                        ${imagesHtml}
                    </div>
                </div>
                <div>
                    <div style="color: var(--text-secondary); text-transform: uppercase; letter-spacing: 0.1em; font-size: 0.8rem; margin-bottom: 0.5rem;">${product.category || ''}</div>
                    <h1 style="font-size: 2.5rem; margin-bottom: 0.5rem; font-family: var(--font-secondary);">${product.name}</h1>
                    <div style="color: var(--accent-gold); margin-bottom: 0.5rem;">
                        <i class="ph-fill ph-star"></i><i class="ph-fill ph-star"></i><i class="ph-fill ph-star"></i><i class="ph-fill ph-star"></i><i class="ph-fill ph-star"></i>
                        <span style="color: var(--text-secondary); font-size: 0.9rem; margin-left: 0.5rem;">(12 Reviews)</span>
                    </div>
                    <div style="font-size: 1.5rem; margin-bottom: 1rem; display: flex; align-items: baseline; gap: 10px; font-weight: 600;">
                        ${priceHtml} <span style="font-size: 0.9rem; font-weight: 400; color: #888;">+ 3% GST</span>
                    </div>
                    
                    ${pricingBreakdownHtml}

                    <p style="margin-bottom: 1.5rem; color: #555; line-height: 1.6;">${product.description || 'Crafted with peerless precision and timeless distinction.'}</p>
                    
                    <div style="margin-bottom: 1.5rem; background: #faf8f5; padding: 1rem 1.5rem; border-radius: 8px;">
                        <div style="margin-bottom: 0.4rem;"><strong>Material:</strong> ${product.material || (product.purity ? product.purity + '% ' + (product.metal || 'Gold') : '18K Yellow Gold')}</div>
                        ${product.gross_weight ? `<div style="margin-bottom: 0.4rem;"><strong>Gross Weight:</strong> ${product.gross_weight} g</div>` : ''}
                        ${product.net_weight ? `<div style="margin-bottom: 0.4rem;"><strong>Net Weight:</strong> ${product.net_weight} g</div>` : ''}
                        ${stonesHtml}
                    </div>
                    
                    ${isSoldOut ? `
                    <div style="margin-bottom: 1.5rem; background: #fef2f2; border: 1px solid #fecaca; color: #b91c1c; padding: 0.9rem 1.25rem; border-radius: 8px; font-size: 0.92rem;">
                        <i class="ph-fill ph-warning-circle" style="vertical-align: middle; margin-right: 6px;"></i>
                        <strong>Sold Out:</strong> This piece has been sold out and is currently unavailable for purchase.
                    </div>
                    ` : ''}
                    <div style="display: flex; gap: 1rem; margin-bottom: 2rem;">
                        ${isSoldOut ? `
                        <button class="btn btn-secondary" style="flex: 1; background: #9ca3af; border-color: #9ca3af; color: #fff; cursor: not-allowed; opacity: 0.8;" disabled>
                            <i class="ph ph-prohibit"></i> Sold Out
                        </button>
                        ` : `
                        <button class="btn btn-primary" style="flex: 1;" onclick="addToCart(${product.id}, 1, this)">Add to Bag</button>
                        `}
                        <button class="btn btn-outline-light wishlist-btn ${isLiked ? 'active' : ''}" data-id="${product.id}" style="${wishlistBtnStyle}" onclick="toggleWishlist(${product.id})">
                            ${heartIcon}
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
            const products = await fetchProducts();
            if (products && products.length > 0) {
                const related = products.filter(p => p.id != product.id).slice(0, 4);
                const relatedContainer = document.getElementById('related-products');
                if (relatedContainer) {
                    relatedContainer.innerHTML = related.map(renderProductCard).join('');
                }
            }

            if (typeof updateWishlistButtons === 'function') {
                updateWishlistButtons();
            }
        } else {
            document.getElementById('product-container').innerHTML = `
                <div style="text-align:center; padding: 5rem 1rem; color: var(--text-secondary); width: 100%; grid-column: 1 / -1;">
                    <i class="ph ph-warning-circle" style="font-size: 3rem; color: var(--accent-gold); margin-bottom: 1rem; display: block;"></i>
                    <h2 style="font-family: var(--font-secondary); margin-bottom: 0.5rem; color: var(--text-primary); font-size: 2rem;">Product Not Found</h2>
                    <p style="margin-bottom: 1.5rem; color: var(--text-secondary);">The product you are looking for is unavailable or does not exist.</p>
                    <a href="{{ route('shop') }}" class="btn btn-primary">Explore Catalog</a>
                </div>
            `;
            document.getElementById('pd-title-crumb').textContent = 'Product Not Found';
        }
    });
</script>
@endpush
