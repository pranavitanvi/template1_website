// Wishlist logic with localStorage

let wishlist = JSON.parse(localStorage.getItem('jewellery_wishlist')) || [];
if (!Array.isArray(wishlist)) wishlist = [];
wishlist = wishlist.map(id => parseInt(id, 10)).filter(id => !isNaN(id));

function toggleWishlist(productId) {
  productId = parseInt(productId, 10);
  const index = wishlist.indexOf(productId);
  const isAdding = index === -1;

  if (isAdding) {
    wishlist.push(productId);
  } else {
    wishlist.splice(index, 1);
  }
  
  localStorage.setItem('jewellery_wishlist', JSON.stringify(wishlist));
  
  // Update UI if on wishlist page
  if(typeof renderWishlistPage === 'function') {
    renderWishlistPage();
  }
  
  // Update button states
  updateWishlistButtons();
  
  if (typeof updateWishlistCount === 'function') {
    updateWishlistCount();
  }

  // Toast notification
  if (typeof showToast === 'function') {
    if (isAdding) {
      showToast(
        'Added to <strong>Wishlist</strong>',
        'wishlist',
        '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20.84 4.61a5.5 5.5 0 00-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 00-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 000-7.78z"/></svg>'
      );
    } else {
      showToast(
        'Removed from <strong>Wishlist</strong>',
        'remove',
        '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20.84 4.61a5.5 5.5 0 00-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 00-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 000-7.78z"/></svg>'
      );
    }
  }
}

function updateWishlistButtons() {
  const buttons = document.querySelectorAll('.wishlist-btn');
  buttons.forEach(btn => {
    let pid = btn.dataset.id;
    if (!pid) {
      const card = btn.closest('.product-card');
      if (card) pid = card.dataset.id;
    }
    
    if(pid) {
      pid = parseInt(pid, 10);
      const isLiked = wishlist.includes(pid);
      if(isLiked) {
        btn.innerHTML = '<i class="ph-fill ph-heart" style="color: #e74c3c;"></i>';
        btn.style.color = '#e74c3c';
        btn.classList.add('active');
        if (btn.classList.contains('btn-outline-light')) {
          btn.style.borderColor = '#e74c3c';
        }
      } else {
        btn.innerHTML = '<i class="ph ph-heart"></i>';
        btn.style.color = '';
        btn.classList.remove('active');
        if (btn.classList.contains('btn-outline-light')) {
          btn.style.borderColor = 'var(--border-light)';
        }
      }
    }
  });
}

document.addEventListener('DOMContentLoaded', () => {
  updateWishlistButtons();
});
