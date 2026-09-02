@extends('layouts.app')

@section('title', 'Shopping Bag | Aura Fine Jewellery')
@section('meta_description', 'Review your shopping bag and proceed to secure checkout.')
@section('main_style', 'margin-top: 140px;')

@section('content')
<div class="container section" style="max-width: 1200px; margin-left: auto; margin-right: auto; padding-bottom: 5rem;">
    <h1 class="text-center" style="margin-bottom: var(--space-xl); font-family: var(--font-secondary); text-transform: uppercase;">Your Shopping Bag</h1>
    
    <div class="cart-grid">
        <div id="cart-items-container">
            <!-- Injected dynamically via JS -->
            <p>Loading your shopping bag...</p>
        </div>
        
        <div class="cart-summary">
            <h3 style="margin-bottom: 1.5rem; font-family: var(--font-secondary); border-bottom: 1px solid var(--border-light); padding-bottom: 1rem;">Order Summary</h3>
            <div class="summary-line">
                <span>Subtotal</span>
                <span id="cart-subtotal">&#8377;0</span>
            </div>
            <div class="summary-line">
                <span>Shipping</span>
                <span style="color: #5a8a4e; font-weight: 500;">Complimentary</span>
            </div>
            <div class="summary-line" style="border-top: 1px solid var(--border-light); padding-top: 1rem; font-weight: 600; font-size: 1.2rem;">
                <span>Total</span>
                <span id="cart-total">&#8377;0</span>
            </div>
            
            <a href="{{ route('checkout') }}" id="btn-proceed-checkout" class="btn btn-primary" style="width: 100%; text-align: center; margin-top: 1.5rem; margin-bottom: 1rem;">Proceed to Checkout</a>
            <a href="{{ route('shop') }}" class="btn btn-secondary" style="width: 100%; text-align: center; border: none; text-decoration: underline;">Continue Shopping</a>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function renderCartPage() {
        const container = document.getElementById('cart-items-container');
        const localCart = JSON.parse(localStorage.getItem('jewellery_cart')) || [];
        
        if (localCart.length === 0) {
            container.innerHTML = `
                <div style="text-align: center; padding: 3rem 1rem;">
                    <i class="ph ph-handbag" style="font-size: 3rem; color: #ccc; margin-bottom: 1rem;"></i>
                    <p style="font-size: 1.1rem; color: var(--text-secondary); margin-bottom: 1.5rem;">Your bag is currently empty.</p>
                    <a href="{{ route('shop') }}" class="btn btn-secondary">Explore Collection</a>
                </div>
            `;
            document.getElementById('cart-subtotal').innerHTML = '&#8377;0';
            document.getElementById('cart-total').innerHTML = '&#8377;0';
            const checkoutBtn = document.getElementById('btn-proceed-checkout');
            if (checkoutBtn) checkoutBtn.style.pointerEvents = 'none';
            return;
        }

        let html = '';
        let total = 0;

        localCart.forEach(item => {
            const itemTotal = item.price * item.quantity;
            total += itemTotal;
            html += `
                <div class="cart-item">
                    <img src="${item.image}" alt="${item.name}" class="cart-item-img" onerror="this.onerror=null; this.src='{{ asset('assets/images/placeholders/default.jpg') }}';">
                    <div class="cart-item-details">
                        <div class="flex-between" style="align-items: flex-start;">
                            <div>
                                <h4 style="margin-bottom: 0.5rem; font-family: var(--font-heading);">${item.name}</h4>
                                <div style="color: var(--text-secondary); font-size: 0.9rem;">${item.category || ''}</div>
                            </div>
                            <div style="font-weight: 500;">${formatPrice(item.price)}</div>
                        </div>
                        
                        <div class="flex-between" style="align-items: center; margin-top: 1rem;">
                            <div style="display: flex; align-items: center; border: 1px solid var(--border-light); border-radius: 4px;">
                                <button onclick="updateCartQuantity(${item.id}, ${item.quantity - 1})" style="padding: 0.2rem 0.6rem; cursor:pointer; background:none; border:none; font-size: 1.1rem;">-</button>
                                <span style="padding: 0 1rem; font-weight: 500;">${item.quantity}</span>
                                <button onclick="updateCartQuantity(${item.id}, ${item.quantity + 1})" style="padding: 0.2rem 0.6rem; cursor:pointer; background:none; border:none; font-size: 1.1rem;">+</button>
                            </div>
                            <button onclick="removeFromCart(${item.id}); renderCartPage();" style="color: var(--text-secondary); text-decoration: underline; background:none; border:none; cursor:pointer;">Remove</button>
                        </div>
                    </div>
                </div>
            `;
        });

        container.innerHTML = html;
        document.getElementById('cart-subtotal').textContent = formatPrice(total);
        document.getElementById('cart-total').textContent = formatPrice(total);
        const checkoutBtn = document.getElementById('btn-proceed-checkout');
        if (checkoutBtn) checkoutBtn.style.pointerEvents = 'auto';
    }

    // Override original updateCartQuantity to re-render cart
    const origUpdateCartQuantity = window.updateCartQuantity;
    window.updateCartQuantity = function(id, qty) {
        if (origUpdateCartQuantity) origUpdateCartQuantity(id, qty);
        renderCartPage();
    };

    document.addEventListener('DOMContentLoaded', renderCartPage);
</script>
@endpush
