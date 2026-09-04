// Cart logic with ERP API and localStorage synchronization

let cart = JSON.parse(localStorage.getItem('jewellery_cart')) || [];
let cartSessionId = localStorage.getItem('aura_cart_session_id') || '';

function getCartSessionId() {
    if (!cartSessionId) {
        cartSessionId = 'aura_' + Math.random().toString(36).substring(2, 15) + Math.random().toString(36).substring(2, 15);
        localStorage.setItem('aura_cart_session_id', cartSessionId);
    }
    return cartSessionId;
}

/* ---- Toast Notification System ---- */
function showToast(message, type = 'cart') {
    const existing = document.querySelector('.aura-success-banner');
    if (existing) existing.remove();

    const banner = document.createElement('div');
    banner.className = 'aura-success-banner';
    
    const isAuth = (type === 'auth' || type === 'warning');
    const icon = isAuth 
        ? '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>'
        : '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>';
    
    const actionLink = isAuth
        ? `<a href="${(window.LOGIN_URL || '/login')}?redirect=${encodeURIComponent(window.location.href)}" style="font-size: 0.85rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; color: #92400e; text-decoration: underline;">Sign In &rarr;</a>`
        : `<a href="/cart" style="font-size: 0.85rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; color: #166534; text-decoration: underline;">View Bag &rarr;</a>`;

    const bgColor = isAuth ? '#fef3c7' : '#dcfce7';
    const textColor = isAuth ? '#92400e' : '#166534';
    const iconBg = isAuth ? '#b45309' : '#166534';

    banner.innerHTML = `
        <div class="aura-banner-content" style="max-width: 1200px; margin: 0 auto; display: flex; align-items: center; justify-content: space-between; gap: 15px;">
            <div style="display: flex; align-items: center; gap: 10px;">
                <span style="display: flex; align-items: center; justify-content: center; background: ${iconBg}; color: #fff; border-radius: 50%; width: 22px; height: 22px;">${icon}</span>
                <span style="font-size: 0.95rem; font-weight: 500;">${message}</span>
            </div>
            ${actionLink}
        </div>
    `;
    
    banner.style.cssText = `
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        background-color: ${bgColor};
        color: ${textColor};
        padding: 14px 20px;
        z-index: 99999;
        transform: translateY(-100%);
        transition: transform 0.4s cubic-bezier(0.16, 1, 0.3, 1);
        box-shadow: 0 4px 16px rgba(0,0,0,0.08);
        font-family: 'Montserrat', sans-serif;
    `;

    document.body.appendChild(banner);

    requestAnimationFrame(() => {
        requestAnimationFrame(() => { 
            banner.style.transform = 'translateY(0)'; 
        });
    });

    setTimeout(() => {
        banner.style.transform = 'translateY(-100%)';
        setTimeout(() => banner.remove(), 400);
    }, 4500);
}

/* ---- Fly-to-cart animation ---- */
function flyToCart(sourceEl, isWishlist = false) {
    if (!sourceEl) return;
    
    let targetIcon;
    if (isWishlist) {
        targetIcon = document.querySelector('.icon-btn[aria-label="Wishlist"]') ||
                     document.querySelector('.icon-btn .ph-heart')?.closest('a');
    } else {
        targetIcon = document.querySelector('.icon-btn[aria-label="Shopping Bag"]') ||
                     document.querySelector('.icon-btn .ph-handbag')?.closest('a');
    }
    if (!targetIcon) return;

    const srcRect = sourceEl.getBoundingClientRect();
    const destRect = targetIcon.getBoundingClientRect();

    const flyer = document.createElement('div');
    flyer.className = 'cart-flyer';
    
    if (isWishlist) {
        flyer.innerHTML = '<svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>';
    } else {
        flyer.innerHTML = '<svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4zM3 6h18M16 10a4 4 0 01-8 0"/></svg>';
    }
    
    flyer.style.cssText = `
        position: fixed;
        z-index: 9999;
        left: ${srcRect.left + srcRect.width / 2}px;
        top: ${srcRect.top + srcRect.height / 2}px;
        width: 28px; height: 28px;
        border-radius: 50%;
        background: #c0a062;
        color: #fff;
        display: flex; align-items: center; justify-content: center;
        pointer-events: none;
        transition: all 0.7s cubic-bezier(0.4, 0, 0.2, 1);
    `;
    document.body.appendChild(flyer);

    requestAnimationFrame(() => {
        requestAnimationFrame(() => {
            flyer.style.left = (destRect.left + destRect.width / 2) + 'px';
            flyer.style.top = (destRect.top + destRect.height / 2) + 'px';
            flyer.style.width = '10px';
            flyer.style.height = '10px';
            flyer.style.opacity = '0';
        });
    });

    setTimeout(() => flyer.remove(), 800);
}

/* ---- Fetch cart from server on load ---- */
async function fetchServerCart() {
    try {
        const sid = getCartSessionId();
        const res = await fetch(`/api/cart?session_id=${encodeURIComponent(sid)}&_t=${Date.now()}`, {
            cache: 'no-store',
            headers: {
                'Accept': 'application/json',
                'Cache-Control': 'no-cache, no-store, must-revalidate',
                'Pragma': 'no-cache',
                'X-Cart-Session': sid
            }
        });
        if (!res.ok) return;
        const json = await res.json();
        if (json && json.data) {
            if (json.session_id) {
                cartSessionId = json.session_id;
                localStorage.setItem('aura_cart_session_id', cartSessionId);
            }
            if (Array.isArray(json.data.items)) {
                cart = json.data.items.map(item => ({
                    id: item.product_id || item.id,
                    itemId: item.item_id,
                    name: item.name,
                    category: item.category,
                    price: Number(item.unit_price || item.price || 0),
                    quantity: Number(item.quantity || 1),
                    image: item.image,
                    slug: item.slug,
                    is_sold_out: Boolean(item.is_sold_out || item.availability === 'sold_out' || item.availability === 'out_of_stock'),
                    availability: item.availability || (item.is_sold_out ? 'sold_out' : 'in_stock')
                }));
                saveCartLocally();
                if (typeof renderCartPage === 'function') renderCartPage();
            }
        }
    } catch (err) {
        console.warn('Could not sync cart with server:', err);
    }
}

/* ---- Add to Cart ---- */
async function addToCart(productIdOrObj, quantity = 1, sourceEl) {
    let productId = (typeof productIdOrObj === 'object' && productIdOrObj !== null) ? (productIdOrObj.id || productIdOrObj.product_id) : productIdOrObj;
    let productObj = (typeof productIdOrObj === 'object' && productIdOrObj !== null) ? productIdOrObj : null;
    
    quantity = parseInt(quantity, 10) || 1;
    if (!productId) {
        console.error('Invalid product ID for addToCart');
        return;
    }

    // Restrict adding to cart until customer is logged in
    if (!window.IS_CUSTOMER_LOGGED_IN) {
        const returnUrl = window.location.href;
        try {
            sessionStorage.setItem('pending_cart_add', JSON.stringify({
                id: productId,
                quantity: quantity,
                product: productObj
            }));
        } catch (e) {}

        showToast('Please sign in to add items to your shopping bag.', 'auth');
        const loginUrl = (window.LOGIN_URL || '/login') + '?redirect=' + encodeURIComponent(returnUrl);
        setTimeout(() => {
            window.location.href = loginUrl;
        }, 1200);
        return false;
    }

    if (sourceEl) flyToCart(sourceEl);

    try {
        const sid = getCartSessionId();
        const response = await fetch('/api/cart/add', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-Cart-Session': sid
            },
            body: JSON.stringify({
                product_id: parseInt(productId, 10),
                quantity: quantity,
                session_id: sid
            })
        });

        const result = await response.json();

        // Check if server returned 401 / requires_login
        if (result && (result.requires_login || response.status === 401)) {
            showToast(result.message || 'Please sign in to add items to your shopping bag.', 'auth');
            const returnUrl = window.location.href;
            const loginUrl = (result.redirect || window.LOGIN_URL || '/login') + '?redirect=' + encodeURIComponent(returnUrl);
            setTimeout(() => {
                window.location.href = loginUrl;
            }, 1200);
            return false;
        }

        if (result && !result.success) {
            alert(result.message || 'Sorry, this product is currently sold out and unavailable.');
            return;
        }

        if (result && (result.success || result.data)) {
            if (result.session_id) {
                cartSessionId = result.session_id;
                localStorage.setItem('aura_cart_session_id', cartSessionId);
            }

            if (result.data && Array.isArray(result.data.items)) {
                cart = result.data.items.map(item => ({
                    id: item.product_id || item.id,
                    itemId: item.item_id,
                    name: item.name,
                    category: item.category,
                    price: Number(item.unit_price || item.price || 0),
                    quantity: Number(item.quantity || 1),
                    image: item.image,
                    slug: item.slug,
                    is_sold_out: Boolean(item.is_sold_out || item.availability === 'sold_out' || item.availability === 'out_of_stock'),
                    availability: item.availability || (item.is_sold_out ? 'sold_out' : 'in_stock')
                }));
            }
            saveCartLocally();

            const addedItem = (result.data && result.data.items) 
                ? (result.data.items.find(i => i.product_id == productId) || result.data.items[result.data.items.length - 1]) 
                : null;
            const itemName = (addedItem && addedItem.name) || (productObj && productObj.name) || 'Product';

            showToast(`<strong>${itemName}</strong> added to bag!`);
            if (typeof renderCartPage === 'function') renderCartPage();
            return;
        }
    } catch (err) {
        console.warn('API addToCart failed, using local fallback:', err);
    }

    // Local fallback if API is unreachable
    let product = productObj;
    if (!product && typeof fetchProduct === 'function') {
        product = await fetchProduct(productId);
    }
    if (!product && typeof fetchProducts === 'function') {
        const products = await fetchProducts();
        if (Array.isArray(products)) {
            product = products.find(p => p.id == productId);
        }
    }

    const existingIndex = cart.findIndex(item => item.id == productId);
    if (existingIndex > -1) {
        cart[existingIndex].quantity += quantity;
    } else if (product) {
        cart.push({
            id: product.id,
            name: product.name,
            category: product.category,
            price: Number(product.price || 0),
            quantity: quantity,
            image: product.image,
            slug: product.slug
        });
    } else {
        cart.push({
            id: productId,
            name: 'Item #' + productId,
            category: '',
            price: 0,
            quantity: quantity,
            image: '/assets/images/placeholders/default.jpg'
        });
    }

    saveCartLocally();
    showToast(`<strong>${(product && product.name) || 'Product'}</strong> added to bag!`);
    if (typeof renderCartPage === 'function') renderCartPage();
}

/* ---- Remove from Cart ---- */
async function removeFromCart(productIdOrItemId) {
    const item = cart.find(i => i.itemId == productIdOrItemId || i.id == productIdOrItemId);
    const targetId = (item && item.itemId) ? item.itemId : productIdOrItemId;

    cart = cart.filter(i => i.itemId != productIdOrItemId && i.id != productIdOrItemId);
    saveCartLocally();
    if (typeof renderCartPage === 'function') renderCartPage();

    try {
        const sid = getCartSessionId();
        await fetch(`/api/cart/${targetId}?session_id=${encodeURIComponent(sid)}`, {
            method: 'DELETE',
            headers: {
                'Accept': 'application/json',
                'X-Cart-Session': sid
            }
        });
    } catch (err) {
        console.warn('API removeFromCart failed:', err);
    }
}

/* ---- Update Cart Quantity ---- */
async function updateCartQuantity(productIdOrItemId, newQuantity) {
    newQuantity = parseInt(newQuantity, 10);
    if (newQuantity < 1) {
        return removeFromCart(productIdOrItemId);
    }

    const item = cart.find(i => i.itemId == productIdOrItemId || i.id == productIdOrItemId);
    if (item) {
        item.quantity = newQuantity;
        saveCartLocally();
        if (typeof renderCartPage === 'function') renderCartPage();
    }

    const targetId = (item && item.itemId) ? item.itemId : productIdOrItemId;
    try {
        const sid = getCartSessionId();
        await fetch(`/api/cart/${targetId}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-Cart-Session': sid
            },
            body: JSON.stringify({
                quantity: newQuantity,
                session_id: sid
            })
        });
    } catch (err) {
        console.warn('API updateCartQuantity failed:', err);
    }
}

/* ---- Clear Cart ---- */
async function clearCart() {
    cart = [];
    saveCartLocally();
    if (typeof renderCartPage === 'function') renderCartPage();

    try {
        const sid = getCartSessionId();
        await fetch('/api/cart/clear', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-Cart-Session': sid
            },
            body: JSON.stringify({ session_id: sid })
        });
    } catch (err) {
        console.warn('API clearCart failed:', err);
    }
}

/* ---- Save and update UI ---- */
function saveCartLocally() {
    localStorage.setItem('jewellery_cart', JSON.stringify(cart));
    updateCartCount();
}

function updateCartCount() {
    const countElements = document.querySelectorAll('.cart-count');
    const totalItems = cart.reduce((total, item) => total + (parseInt(item.quantity, 10) || 0), 0);
    countElements.forEach(el => {
        el.textContent = totalItems;
        el.style.display = totalItems > 0 ? 'flex' : 'none';
    });
}

function getCartTotal() {
    return cart.reduce((total, item) => total + ((Number(item.price) || 0) * (parseInt(item.quantity, 10) || 1)), 0);
}

// Global initialization
document.addEventListener('DOMContentLoaded', () => {
    // If guest user is not logged in, clear any unauthenticated local cart
    if (!window.IS_CUSTOMER_LOGGED_IN) {
        if (cart && cart.length > 0) {
            cart = [];
            try { localStorage.removeItem('jewellery_cart'); } catch (e) {}
        }
    }

    updateCartCount();
    if (window.IS_CUSTOMER_LOGGED_IN) {
        fetchServerCart();
    }

    // Automatically resume pending add-to-bag after customer completes login
    if (window.IS_CUSTOMER_LOGGED_IN && typeof sessionStorage !== 'undefined') {
        const pendingStr = sessionStorage.getItem('pending_cart_add');
        if (pendingStr) {
            try {
                sessionStorage.removeItem('pending_cart_add');
                const pending = JSON.parse(pendingStr);
                if (pending && (pending.id || pending.product)) {
                    setTimeout(() => {
                        addToCart(pending.product || pending.id, pending.quantity || 1);
                    }, 500);
                }
            } catch (e) {
                sessionStorage.removeItem('pending_cart_add');
            }
        }
    }
});
