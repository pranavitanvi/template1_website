// Cart logic with localStorage

let cart = JSON.parse(localStorage.getItem('jewellery_cart')) || [];

/* ---- Toast Notification System ---- */
function showToast(message, type = 'cart', icon = '') {
  const existing = document.querySelector('.aura-success-banner');
  if (existing) existing.remove();

  const banner = document.createElement('div');
  banner.className = 'aura-success-banner';
  
  // Use a simple checkmark instead of passed icon
  const checkIcon = '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>';
  
  banner.innerHTML = `
    <div class="aura-banner-content" style="max-width: 1200px; margin: 0 auto; display: flex; align-items: center; gap: 10px;">
        ${checkIcon}
        <span style="font-size: 0.9rem; font-weight: 500;">${message.replace(/<[^>]*>?/gm, '')}</span>
    </div>
  `;
  
  banner.style.cssText = `
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      background-color: #dcfce7;
      color: #166534;
      padding: 12px 20px;
      z-index: 99999;
      transform: translateY(-100%);
      transition: transform 0.4s ease;
      box-shadow: 0 4px 12px rgba(0,0,0,0.05);
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
  }, 4000);
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

  const srcRect  = sourceEl.getBoundingClientRect();
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
    top:  ${srcRect.top  + srcRect.height/ 2}px;
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
      flyer.style.left   = (destRect.left + destRect.width  / 2) + 'px';
      flyer.style.top    = (destRect.top  + destRect.height / 2) + 'px';
      flyer.style.width  = '10px';
      flyer.style.height = '10px';
      flyer.style.opacity = '0';
    });
  });

  setTimeout(() => flyer.remove(), 800);
}

async function addToCart(productId, quantity = 1, sourceEl) {
  const products = await fetchProducts();
  const product = products.find(p => p.id == productId);
  
  if (!product) return;
  
  const existingItemIndex = cart.findIndex(item => item.id == productId);
  
  if (existingItemIndex > -1) {
    cart[existingItemIndex].quantity += quantity;
  } else {
    cart.push({ ...product, quantity });
  }
  
  saveCart();

  // Fly animation
  flyToCart(sourceEl);

  // Toast
  showToast(
    `<strong>${product.name}</strong> added to bag!`,
    'cart',
    '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 01-8 0"/></svg>'
  );
}

function removeFromCart(productId) {
  cart = cart.filter(item => item.id != productId);
  saveCart();
  if(typeof renderCartPage === 'function') renderCartPage();
}

function updateCartQuantity(productId, newQuantity) {
  if (newQuantity < 1) return;
  const item = cart.find(item => item.id == productId);
  if (item) {
    item.quantity = newQuantity;
    saveCart();
    if(typeof renderCartPage === 'function') renderCartPage();
  }
}

function saveCart() {
  localStorage.setItem('jewellery_cart', JSON.stringify(cart));
  if (typeof updateCartCount === 'function') {
    updateCartCount();
  }
}

function getCartTotal() {
  return cart.reduce((total, item) => total + (item.price * item.quantity), 0);
}
