@extends('layouts.app')

@section('title', 'Shopping Bag | Aura Fine Jewellery')
@section('meta_description', 'Review your shopping bag, apply promo codes, and proceed to secure checkout.')
@section('main_style', 'margin-top: 130px;')

@push('styles')
<style>
    .cart-page-wrapper {
        max-width: 1240px;
        margin: 0 auto;
        padding: 1rem 1.5rem 5rem 1.5rem;
    }

    .cart-header-section {
        margin-bottom: 2.5rem;
        text-align: center;
        position: relative;
    }

    .cart-title {
        font-family: var(--font-secondary, 'Cinzel', serif);
        font-size: 2.2rem;
        font-weight: 600;
        letter-spacing: 0.06em;
        color: var(--text-primary, #1a1a1a);
        text-transform: uppercase;
        margin-bottom: 0.5rem;
    }

    .cart-subtitle {
        font-size: 0.95rem;
        color: var(--text-secondary, #777);
    }

    .cart-grid-layout {
        display: grid;
        grid-template-columns: 1fr 380px;
        gap: 2.5rem;
        align-items: start;
    }

    /* Free Shipping Progress Banner */
    .shipping-perk-banner {
        background: linear-gradient(135deg, #faf7f2 0%, #f4ede2 100%);
        border: 1px solid rgba(194, 168, 120, 0.35);
        border-radius: 10px;
        padding: 1rem 1.25rem;
        margin-bottom: 1.75rem;
        display: flex;
        align-items: center;
        gap: 12px;
        color: #5c4b31;
        font-size: 0.9rem;
    }

    .shipping-perk-banner i {
        font-size: 1.4rem;
        color: var(--accent-gold, #c0a062);
        flex-shrink: 0;
    }

    /* Cart Item Card */
    .cart-card-item {
        background: #fff;
        border: 1px solid var(--border-light, #eee);
        border-radius: 10px;
        padding: 1.5rem;
        margin-bottom: 1.25rem;
        display: grid;
        grid-template-columns: 110px 1fr auto;
        gap: 1.5rem;
        align-items: center;
        transition: box-shadow 0.25s ease, border-color 0.25s ease;
    }

    .cart-card-item:hover {
        border-color: rgba(194, 168, 120, 0.4);
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.04);
    }

    .cart-item-image-box {
        width: 110px;
        height: 110px;
        border-radius: 8px;
        overflow: hidden;
        background: #faf8f5;
        border: 1px solid #f0ede8;
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
    }

    .cart-item-image-box img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.3s ease;
    }

    .cart-item-image-box:hover img {
        transform: scale(1.05);
    }

    .cart-item-info {
        display: flex;
        flex-direction: column;
        gap: 0.35rem;
    }

    .cart-item-category {
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        color: var(--accent-gold, #c0a062);
        font-weight: 600;
    }

    .cart-item-title {
        font-family: var(--font-secondary, 'Cinzel', serif);
        font-size: 1.15rem;
        font-weight: 600;
        color: var(--text-primary, #222);
        margin: 0;
        line-height: 1.35;
    }

    .cart-item-title a {
        color: inherit;
        text-decoration: none;
        transition: color 0.2s;
    }

    .cart-item-title a:hover {
        color: var(--accent-gold, #c0a062);
    }

    .cart-item-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 0.75rem;
        font-size: 0.82rem;
        color: #666;
        margin-top: 0.2rem;
    }

    .cart-item-meta span {
        background: #f8f6f2;
        padding: 2px 8px;
        border-radius: 4px;
        border: 1px solid #eee7db;
    }

    .cart-item-controls {
        display: flex;
        align-items: center;
        gap: 1.5rem;
        margin-top: 0.75rem;
    }

    /* Quantity Control */
    .qty-stepper {
        display: inline-flex;
        align-items: center;
        border: 1px solid #ddd;
        border-radius: 6px;
        background: #fff;
        overflow: hidden;
    }

    .qty-btn {
        background: transparent;
        border: none;
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        font-size: 1rem;
        color: #444;
        transition: background-color 0.2s, color 0.2s;
    }

    .qty-btn:hover {
        background-color: #f5f2eb;
        color: var(--accent-gold, #c0a062);
    }

    .qty-display {
        min-width: 34px;
        text-align: center;
        font-size: 0.9rem;
        font-weight: 600;
        color: #222;
    }

    /* Action Buttons */
    .cart-action-btn {
        background: none;
        border: none;
        color: #888;
        font-size: 0.85rem;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 4px 6px;
        border-radius: 4px;
        transition: all 0.2s ease;
    }

    .cart-action-btn:hover {
        color: #c0392b;
        background-color: #fdf2f2;
    }

    .cart-action-btn.wishlist-save:hover {
        color: var(--accent-gold, #c0a062);
        background-color: #faf6ee;
    }

    /* Item Price Column */
    .cart-item-pricing {
        text-align: right;
        display: flex;
        flex-direction: column;
        justify-content: center;
        gap: 0.25rem;
        min-width: 140px;
    }

    .cart-item-total-price {
        font-size: 1.25rem;
        font-weight: 600;
        color: var(--text-primary, #1a1a1a);
        font-family: var(--font-primary, sans-serif);
    }

    .cart-item-unit-price {
        font-size: 0.82rem;
        color: #888;
    }

    /* Order Summary Card */
    .cart-summary-card {
        background: #faf8f5;
        border: 1px solid rgba(194, 168, 120, 0.3);
        border-radius: 12px;
        padding: 1.75rem;
        position: sticky;
        top: 130px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03);
    }

    .cart-summary-card h3 {
        font-family: var(--font-secondary, 'Cinzel', serif);
        font-size: 1.2rem;
        font-weight: 600;
        color: #222;
        letter-spacing: 0.05em;
        text-transform: uppercase;
        margin-bottom: 1.25rem;
        padding-bottom: 0.75rem;
        border-bottom: 1px solid rgba(0, 0, 0, 0.08);
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .summary-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 0.95rem;
        margin-bottom: 0.85rem;
        color: #555;
    }

    .summary-row strong {
        color: #222;
    }

    .summary-free-badge {
        background-color: #e8f5e9;
        color: #2e7d32;
        font-weight: 600;
        font-size: 0.8rem;
        padding: 2px 8px;
        border-radius: 4px;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }

    .summary-divider {
        height: 1px;
        background: rgba(0, 0, 0, 0.08);
        margin: 1.25rem 0;
    }

    .summary-total-row {
        display: flex;
        justify-content: space-between;
        align-items: baseline;
        font-size: 1.35rem;
        font-weight: 700;
        color: #1a1a1a;
        margin-bottom: 0.4rem;
    }

    .summary-total-amount {
        color: var(--accent-gold, #c0a062);
        font-size: 1.45rem;
    }

    .summary-tax-note {
        font-size: 0.78rem;
        color: #888;
        text-align: right;
        margin-bottom: 1.5rem;
    }

    .btn-checkout-primary {
        width: 100%;
        background: #1a1a1a;
        color: #fff;
        border: 1px solid #1a1a1a;
        padding: 1rem 1.5rem;
        font-size: 0.95rem;
        font-weight: 600;
        letter-spacing: 0.06em;
        text-transform: uppercase;
        border-radius: 6px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        text-decoration: none;
        transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }

    .btn-checkout-primary:hover {
        background: var(--accent-gold, #c0a062);
        border-color: var(--accent-gold, #c0a062);
        color: #fff;
        transform: translateY(-1px);
        box-shadow: 0 6px 16px rgba(192, 160, 98, 0.35);
    }

    .btn-continue-shopping {
        display: block;
        text-align: center;
        margin-top: 1rem;
        font-size: 0.88rem;
        color: #666;
        text-decoration: none;
        transition: color 0.2s ease;
    }

    .btn-continue-shopping:hover {
        color: var(--accent-gold, #c0a062);
        text-decoration: underline;
    }

    /* Promo code box */
    .coupon-box {
        margin: 1.25rem 0;
        background: #fff;
        border: 1px solid #e5dfd5;
        border-radius: 8px;
        padding: 0.75rem;
        display: flex;
        gap: 6px;
    }

    .coupon-box input {
        border: none;
        outline: none;
        font-family: inherit;
        font-size: 0.85rem;
        flex: 1;
        background: transparent;
        text-transform: uppercase;
    }

    .coupon-box button {
        background: #f0ebe1;
        border: none;
        padding: 0.4rem 0.85rem;
        border-radius: 4px;
        font-size: 0.8rem;
        font-weight: 600;
        color: #555;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .coupon-box button:hover {
        background: var(--accent-gold, #c0a062);
        color: #fff;
    }

    /* Trust & Assurance Badges */
    .trust-badges-grid {
        margin-top: 1.5rem;
        padding-top: 1.5rem;
        border-top: 1px solid rgba(0, 0, 0, 0.08);
        display: grid;
        grid-template-columns: 1fr;
        gap: 0.75rem;
    }

    .trust-badge-item {
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 0.82rem;
        color: #555;
    }

    .trust-badge-item i {
        font-size: 1.15rem;
        color: var(--accent-gold, #c0a062);
        flex-shrink: 0;
    }

    /* Empty state */
    .empty-cart-state {
        text-align: center;
        padding: 5rem 2rem;
        background: #fff;
        border: 1px solid var(--border-light, #eee);
        border-radius: 12px;
        grid-column: 1 / -1;
    }

    .empty-cart-icon-wrap {
        width: 90px;
        height: 90px;
        margin: 0 auto 1.5rem auto;
        background: #faf8f5;
        border: 1px solid #efe8dc;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--accent-gold, #c0a062);
        font-size: 2.5rem;
    }

    .empty-cart-state h2 {
        font-family: var(--font-secondary, 'Cinzel', serif);
        font-size: 1.8rem;
        margin-bottom: 0.75rem;
        color: #222;
    }

    .empty-cart-state p {
        color: #777;
        max-width: 480px;
        margin: 0 auto 2rem auto;
        line-height: 1.6;
        font-size: 0.95rem;
    }

    .empty-cart-actions {
        display: flex;
        justify-content: center;
        gap: 1rem;
        flex-wrap: wrap;
    }

    @media (max-width: 960px) {
        .cart-grid-layout {
            grid-template-columns: 1fr;
            gap: 2rem;
        }

        .cart-summary-card {
            position: static;
        }
    }

    @media (max-width: 580px) {
        .cart-card-item {
            grid-template-columns: 80px 1fr;
            gap: 1rem;
        }

        .cart-item-image-box {
            width: 80px;
            height: 80px;
        }

        .cart-item-pricing {
            grid-column: 1 / -1;
            text-align: left;
            flex-direction: row;
            justify-content: space-between;
            align-items: center;
            border-top: 1px dashed #eee;
            padding-top: 0.75rem;
            margin-top: 0.5rem;
        }
    }
</style>
@endpush

@section('content')
<div class="cart-page-wrapper">
    
    <!-- Breadcrumbs -->
    <div style="margin-bottom: 1.5rem; font-size: 0.85rem; color: var(--text-secondary);">
        <a href="{{ route('home') }}" style="color: var(--text-primary); text-decoration: none;">Home</a> / 
        <span style="color: var(--accent-gold, #c0a062); font-weight: 500;">Shopping Bag</span>
    </div>

    <!-- Page Header -->
    <div class="cart-header-section">
        <h1 class="cart-title">Your Shopping Bag</h1>
        <p class="cart-subtitle" id="cart-item-count-label">Review your handcrafted jewellery selections</p>
    </div>

    <!-- Main Cart Layout -->
    <div class="cart-grid-layout">
        
        <!-- Left Column: Items List -->
        <div class="cart-items-column">
            
            <!-- Shipping Promo Perk -->
            <div class="shipping-perk-banner">
                <i class="ph-fill ph-truck"></i>
                <div>
                    <strong>Complimentary Insured Delivery:</strong> Every order is covered with 100% insured transit & discreet luxury packaging.
                </div>
            </div>

            <!-- Items Container (Injected dynamically) -->
            <div id="cart-items-container">
                <div style="text-align: center; padding: 4rem 1rem; color: #888;">
                    <i class="ph ph-spinner ph-spin" style="font-size: 2rem; margin-bottom: 1rem; display: block; color: var(--accent-gold);"></i>
                    Loading your shopping bag...
                </div>
            </div>

        </div>

        <!-- Right Column: Order Summary Card -->
        <div class="cart-summary-column" id="cart-summary-container">
            <div class="cart-summary-card">
                <h3>
                    Order Summary
                    <span id="summary-items-badge" style="font-size: 0.8rem; font-family: var(--font-primary); font-weight: 500; background: #eee7db; color: #5c4b31; padding: 2px 8px; border-radius: 12px;">0 items</span>
                </h3>

                <div class="summary-row">
                    <span>Bag Subtotal</span>
                    <strong id="cart-subtotal">&#8377;0</strong>
                </div>

                <div class="summary-row">
                    <span>Insured Shipping</span>
                    <span class="summary-free-badge"><i class="ph ph-check-circle"></i> FREE</span>
                </div>

                <div class="summary-row">
                    <span>Applicable GST (3%)</span>
                    <span style="color: #666; font-size: 0.88rem;">Included in price</span>
                </div>

                <!-- Promo Code -->
                <div class="coupon-box">
                    <input type="text" id="coupon-input" placeholder="Promo or Gift Card Code">
                    <button type="button" onclick="applyPromoCode()">Apply</button>
                </div>
                <div id="coupon-message" style="display: none; font-size: 0.8rem; margin-top: -0.5rem; margin-bottom: 0.75rem;"></div>

                <div class="summary-divider"></div>

                <div class="summary-total-row">
                    <span>Total Amount</span>
                    <span id="cart-total" class="summary-total-amount">&#8377;0</span>
                </div>
                <div class="summary-tax-note">All taxes & insured delivery included</div>

                <a href="{{ route('checkout') }}" id="btn-proceed-checkout" class="btn-checkout-primary">
                    <i class="ph ph-lock-key" style="font-size: 1.15rem;"></i>
                    Proceed to Checkout
                </a>

                <a href="{{ route('shop') }}" class="btn-continue-shopping">
                    &larr; Continue Shopping
                </a>

                <!-- Trust Badges -->
                <div class="trust-badges-grid">
                    <div class="trust-badge-item">
                        <i class="ph-fill ph-certificate"></i>
                        <span>100% Certified Diamonds & BIS Hallmarked Gold</span>
                    </div>
                    <div class="trust-badge-item">
                        <i class="ph-fill ph-shield-check"></i>
                        <span>Secure 256-Bit SSL Encrypted Checkout</span>
                    </div>
                    <div class="trust-badge-item">
                        <i class="ph-fill ph-arrows-counter-clockwise"></i>
                        <span>15-Day Hassle-Free Returns & Lifetime Exchange</span>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection

@push('scripts')
<script>
    function applyPromoCode() {
        const input = document.getElementById('coupon-input');
        const msg = document.getElementById('coupon-message');
        if (!input || !msg) return;
        const code = (input.value || '').trim().toUpperCase();
        if (!code) {
            msg.style.display = 'block';
            msg.style.color = '#c0392b';
            msg.textContent = 'Please enter a coupon code.';
            return;
        }
        if (code === 'AURA10' || code === 'WELCOME10') {
            msg.style.display = 'block';
            msg.style.color = '#166534';
            msg.textContent = 'Coupon ' + code + ' applied! Discount reflected at checkout.';
        } else {
            msg.style.display = 'block';
            msg.style.color = '#c0392b';
            msg.textContent = 'Invalid or expired coupon code.';
        }
    }

    function renderCartPage() {
        const container = document.getElementById('cart-items-container');
        const summaryCard = document.getElementById('cart-summary-container');
        const countLabel = document.getElementById('cart-item-count-label');
        const summaryBadge = document.getElementById('summary-items-badge');
        if (!container) return;

        const currentCart = (typeof cart !== 'undefined' && Array.isArray(cart)) ? cart : (JSON.parse(localStorage.getItem('jewellery_cart')) || []);
        const totalItemsCount = currentCart.reduce((acc, item) => acc + (parseInt(item.quantity, 10) || 1), 0);

        if (countLabel) {
            countLabel.textContent = totalItemsCount > 0 
                ? `You have ${totalItemsCount} ${totalItemsCount === 1 ? 'item' : 'items'} in your shopping bag`
                : 'Your shopping bag is currently empty';
        }

        if (summaryBadge) {
            summaryBadge.textContent = `${totalItemsCount} ${totalItemsCount === 1 ? 'item' : 'items'}`;
        }

        if (currentCart.length === 0) {
            container.innerHTML = `
                <div class="empty-cart-state">
                    <div class="empty-cart-icon-wrap">
                        <i class="ph ph-handbag"></i>
                    </div>
                    <h2>Your Bag is Empty</h2>
                    <p>It seems you haven't added any jewellery pieces yet. Explore our handcrafted collections to find timeless elegance tailored just for you.</p>
                    <div class="empty-cart-actions">
                        <a href="{{ route('shop') }}" class="btn btn-primary" style="padding: 0.85rem 2rem;">Explore Collections</a>
                        <a href="{{ route('new-arrivals') }}" class="btn btn-outline" style="padding: 0.85rem 2rem; border-color: #ddd; color: #333;">View New Arrivals</a>
                    </div>
                </div>
            `;
            if (summaryCard) summaryCard.style.display = 'none';
            return;
        }

        if (summaryCard) summaryCard.style.display = 'block';

        let html = '';
        let total = 0;
        let hasSoldOutItems = false;

        currentCart.forEach(item => {
            const isSoldOut = Boolean(item.is_sold_out || item.availability === 'sold_out' || item.availability === 'out_of_stock');
            if (isSoldOut) hasSoldOutItems = true;
            const itemQty = parseInt(item.quantity, 10) || 1;
            const itemPrice = Number(item.price) || 0;
            const itemTotal = itemPrice * itemQty;
            total += itemTotal;
            const keyId = item.itemId || item.id;
            const placeholder = '{{ asset('assets/images/placeholders/default.jpg') }}';
            const imgSrc = item.image 
                ? (item.image.startsWith('http') || item.image.startsWith('/') ? item.image : '/' + item.image) 
                : placeholder;
            const itemLink = item.slug ? `/product/${item.slug}` : `/product-details?id=${item.id}`;

            html += `
                <div class="cart-card-item" id="cart-card-${keyId}">
                    <!-- Image Box -->
                    <a href="${itemLink}" class="cart-item-image-box">
                        <img src="${imgSrc}" alt="${item.name}" onerror="this.onerror=null; this.src='${placeholder}';">
                    </a>

                    <!-- Details Box -->
                    <div class="cart-item-info">
                        <div class="cart-item-category">${item.category || 'Fine Jewellery'}</div>
                        <h4 class="cart-item-title"><a href="${itemLink}">${item.name}</a></h4>
                        
                        <div class="cart-item-meta">
                            <span><i class="ph ph-sparkle" style="color: var(--accent-gold);"></i> 100% Certified</span>
                            ${isSoldOut ? `<span style="background: #fee2e2; color: #b91c1c; border-color: #fca5a5; font-weight: 600;"><i class="ph ph-warning-circle"></i> Sold Out</span>` : `<span>In Stock</span>`}
                        </div>

                        <!-- Stepper and Actions -->
                        <div class="cart-item-controls">
                            <div class="qty-stepper">
                                <button type="button" class="qty-btn" onclick="updateCartQuantity('${keyId}', ${itemQty - 1})" aria-label="Decrease quantity">
                                    <i class="ph ph-minus"></i>
                                </button>
                                <span class="qty-display">${itemQty}</span>
                                <button type="button" class="qty-btn" onclick="updateCartQuantity('${keyId}', ${itemQty + 1})" aria-label="Increase quantity">
                                    <i class="ph ph-plus"></i>
                                </button>
                            </div>

                            <button type="button" class="cart-action-btn wishlist-save" onclick="if(typeof toggleWishlist === 'function') toggleWishlist(${item.id});" title="Save for Later">
                                <i class="ph ph-heart"></i> Save for later
                            </button>

                            <button type="button" class="cart-action-btn" onclick="removeFromCart('${keyId}')" title="Remove Item">
                                <i class="ph ph-trash"></i> Remove
                            </button>
                        </div>
                    </div>

                    <!-- Price Box -->
                    <div class="cart-item-pricing">
                        <div class="cart-item-total-price">${formatPrice(itemTotal)}</div>
                        ${itemQty > 1 ? `<div class="cart-item-unit-price">${formatPrice(itemPrice)} each</div>` : ''}
                    </div>
                </div>
            `;
        });

        if (hasSoldOutItems) {
            html = `
                <div class="sold-out-cart-alert" style="background: #fef2f2; border: 1px solid #f87171; color: #991b1b; padding: 1rem 1.25rem; border-radius: 8px; margin-bottom: 1.5rem; display: flex; align-items: center; gap: 12px;">
                    <i class="ph-fill ph-warning-octagon" style="font-size: 1.5rem; color: #dc2626; flex-shrink: 0;"></i>
                    <div>
                        <strong>Sold Out Item in Bag:</strong> One or more items in your shopping bag are sold out. Please remove them to proceed with checkout.
                    </div>
                </div>
            ` + html;
        }
        container.innerHTML = html;
        const subtotalEl = document.getElementById('cart-subtotal');
        const totalEl = document.getElementById('cart-total');
        if (subtotalEl) subtotalEl.textContent = formatPrice(total);
        if (totalEl) totalEl.textContent = formatPrice(total);
        
        const checkoutBtn = document.getElementById('btn-proceed-checkout');
        if (checkoutBtn) {
            if (hasSoldOutItems) {
                checkoutBtn.setAttribute('disabled', 'true');
                checkoutBtn.style.pointerEvents = 'none';
                checkoutBtn.style.opacity = '0.5';
                checkoutBtn.style.background = '#9ca3af';
                checkoutBtn.style.borderColor = '#9ca3af';
                checkoutBtn.innerHTML = '<i class="ph ph-prohibit"></i> Remove Sold-out Items to Checkout';
            } else {
                checkoutBtn.removeAttribute('disabled');
                checkoutBtn.style.pointerEvents = 'auto';
                checkoutBtn.style.opacity = '1';
                checkoutBtn.style.background = '';
                checkoutBtn.style.borderColor = '';
                checkoutBtn.innerHTML = '<i class="ph ph-lock-key" style="font-size: 1.15rem;"></i> Proceed to Checkout';
            }
        }
    }

    document.addEventListener('DOMContentLoaded', () => {
        renderCartPage();
        if (typeof fetchServerCart === 'function') {
            fetchServerCart().then(() => renderCartPage());
        }
    });
</script>
@endpush
