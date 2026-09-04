// Main Javascript for UI interactions

document.addEventListener('DOMContentLoaded', () => {
  initMobileMenu();
  initHeaderScroll();
  updateCartCount();
  updateWishlistCount();
  initScrollAnimations();
  initHeroSlider();
  initHeaderSearch();
});

function initScrollAnimations() {
  const observerOptions = {
    root: null,
    rootMargin: '0px',
    threshold: 0.15
  };

  const observer = new IntersectionObserver((entries, observer) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        entry.target.classList.add('visible');
        // Optional: unobserve if we only want it to animate once
        // observer.unobserve(entry.target);
      }
    });
  }, observerOptions);

  const elements = document.querySelectorAll('.scroll-reveal, .zoom-in, .product-card, .category-card');
  elements.forEach(el => {
    if(el.classList.contains('product-card') || el.classList.contains('category-card')) {
        el.classList.add('scroll-reveal'); // dynamically add class to cards
    }
    observer.observe(el);
  });
}

function initMobileMenu() {
  const menuBtn = document.querySelector('.mobile-menu-btn');
  const navLinks = document.querySelector('.nav-links');
  
  if (menuBtn && navLinks) {
    menuBtn.addEventListener('click', () => {
      navLinks.classList.toggle('active');
    });
  }
}

function initHeaderScroll() {
  const header = document.querySelector('.header');
  let lastScrollY = window.scrollY;

  window.addEventListener('scroll', () => {
    if (window.scrollY > 50) {
      header.style.boxShadow = '0 4px 10px rgba(0,0,0,0.08)';
    } else {
      header.style.boxShadow = '0 2px 10px rgba(0,0,0,0.05)';
    }
    
    // Optional: Hide header on scroll down, show on scroll up
    /*
    if (window.scrollY > lastScrollY && window.scrollY > 100) {
      header.style.transform = 'translateY(-100%)';
    } else {
      header.style.transform = 'translateY(0)';
    }
    lastScrollY = window.scrollY;
    */
  });
}

function updateCartCount() {
  const cart = JSON.parse(localStorage.getItem('jewellery_cart')) || [];
  const countElements = document.querySelectorAll('.cart-count');
  const totalItems = cart.reduce((total, item) => total + item.quantity, 0);
  
  countElements.forEach(el => {
    el.textContent = totalItems;
    el.style.display = totalItems > 0 ? 'flex' : 'none';
  });
}

function updateWishlistCount() {
  const wishlist = JSON.parse(localStorage.getItem('jewellery_wishlist')) || [];
  const countElements = document.querySelectorAll('.wishlist-count');
  const totalItems = wishlist.length;
  
  countElements.forEach(el => {
    el.textContent = totalItems;
    el.style.display = totalItems > 0 ? 'flex' : 'none';
  });
}

// Global utility for formatting currency
function formatPrice(price) {
  return '\u20b9' + Math.round(price).toLocaleString('en-IN');
}

function initHeroSlider() {
  const slider = document.querySelector('.hero-slider');
  const slides = document.querySelectorAll('.hero-slide');
  if (!slider || slides.length === 0) return;
  
  const dotsContainer = document.createElement('div');
  dotsContainer.className = 'hero-slider-dots';
  
  slides.forEach((_, index) => {
      const dot = document.createElement('div');
      dot.className = `slider-dot ${index === 0 ? 'active' : ''}`;
      dot.addEventListener('click', () => goToSlide(index));
      dotsContainer.appendChild(dot);
  });
  slider.appendChild(dotsContainer);
  
  let currentSlide = 0;
  const dots = document.querySelectorAll('.slider-dot');
  let slideInterval;
  
  function goToSlide(index) {
      slides[currentSlide].classList.remove('active');
      dots[currentSlide].classList.remove('active');
      currentSlide = index;
      slides[currentSlide].classList.add('active');
      dots[currentSlide].classList.add('active');
      resetInterval();
  }
  
  function resetInterval() {
      clearInterval(slideInterval);
      slideInterval = setInterval(() => {
          let nextSlide = (currentSlide + 1) % slides.length;
          goToSlide(nextSlide);
      }, 5000);
  }
  
  resetInterval();
}

// Global Header Live Search with Autocomplete Dropdown
function initHeaderSearch() {
  const searchInput = document.getElementById('header-search-input');
  const searchClear = document.getElementById('header-search-clear');
  const searchDropdown = document.getElementById('header-search-dropdown');
  const searchWrap = searchInput ? searchInput.closest('.header-search-wrap') : null;

  if (!searchInput || !searchDropdown) return;

  let searchCatalog = [];
  let isCatalogLoaded = false;
  let isLoadingCatalog = false;
  let debounceTimer = null;
  let activeIndex = -1;

  function updateClearButton() {
    if (searchClear) {
      searchClear.style.display = searchInput.value.trim().length > 0 ? 'flex' : 'none';
    }
  }

  updateClearButton();

  async function loadCatalog() {
    if (isCatalogLoaded || isLoadingCatalog) return;
    isLoadingCatalog = true;
    try {
      const res = await fetch('/api/products?_t=' + Date.now(), {
        cache: 'no-store',
        headers: {
          'Cache-Control': 'no-cache, no-store, must-revalidate',
          'Pragma': 'no-cache'
        }
      });
      if (res.ok) {
        const data = await res.json();
        const raw = Array.isArray(data) ? data : (data.data || []);
        searchCatalog = raw.filter(p => !p.is_sold_out && p.product_sold_out_status !== 1 && p.availability !== 'sold_out' && p.availability !== 'out_of_stock');
        isCatalogLoaded = true;
      }
    } catch (e) {
      console.warn('Could not prefetch product catalog for search:', e);
    } finally {
      isLoadingCatalog = false;
    }
  }

  searchInput.addEventListener('focus', () => {
    loadCatalog();
    if (searchInput.value.trim().length >= 1) {
      performSearch(searchInput.value.trim());
    }
  });

  searchInput.addEventListener('mouseenter', loadCatalog, { once: true });

  function escapeHtml(str) {
    if (!str) return '';
    return String(str)
      .replace(/&/g, '&amp;')
      .replace(/</g, '&lt;')
      .replace(/>/g, '&gt;')
      .replace(/"/g, '&quot;')
      .replace(/'/g, '&#039;');
  }

  function highlightMatches(text, tokens) {
    if (!text) return '';
    let escaped = escapeHtml(text);
    tokens.forEach(tok => {
      if (!tok) return;
      const regex = new RegExp('(' + tok.replace(/[.*+?^${}()|[\]\\]/g, '\\$&') + ')', 'gi');
      escaped = escaped.replace(regex, '<mark>$1</mark>');
    });
    return escaped;
  }

  function formatInr(num) {
    return '₹' + Math.round(Number(num) || 0).toLocaleString('en-IN');
  }

  function getThumb(p) {
    if (p.image && typeof p.image === 'string' && p.image.trim() !== '') {
      return p.image;
    }
    const cat = (p.categoryId || p.category || '').toLowerCase();
    if (cat.includes('ring')) return '/assets/images/placeholders/ring.jpg';
    if (cat.includes('earring')) return '/assets/images/placeholders/earring.jpg';
    if (cat.includes('bangle') || cat.includes('bracelet')) return '/assets/images/placeholders/bangle.jpg';
    if (cat.includes('necklace') || cat.includes('chain')) return '/assets/images/placeholders/necklace.jpg';
    if (cat.includes('pendant')) return '/assets/images/placeholders/pendant.jpg';
    return '/assets/images/placeholders/default.jpg';
  }

  async function performSearch(query) {
    const q = query.trim();
    if (q.length === 0) {
      searchDropdown.innerHTML = '';
      searchDropdown.style.display = 'none';
      return;
    }

    if (!isCatalogLoaded) {
      searchDropdown.style.display = 'block';
      searchDropdown.innerHTML = `
        <div class="search-loading-state">
          <i class="ph-bold ph-spinner ph-spin" style="font-size: 1.2rem; color: var(--accent-gold, #c0a062);"></i>
          <span>Searching jewellery...</span>
        </div>
      `;
      await loadCatalog();
    }

    const tokens = q.toLowerCase().split(/\s+/).filter(Boolean);
    const matches = searchCatalog.filter(p => {
      const stones = Array.isArray(p.stones) ? p.stones.map(s => s.stone_name || '').join(' ') : '';
      const hay = `${p.name || ''} ${p.code || ''} ${p.category || ''} ${p.categoryId || ''} ${p.metal || ''} ${p.material || ''} ${p.stone || ''} ${stones} ${p.purity || ''} ${p.collection || ''} ${p.occasion || ''} ${p.gender || ''}`.toLowerCase();
      return tokens.every(t => hay.includes(t));
    });

    activeIndex = -1;

    if (matches.length === 0) {
      searchDropdown.style.display = 'block';
      searchDropdown.innerHTML = `
        <div class="search-dropdown-empty">
          <i class="ph ph-magnifying-glass search-empty-icon"></i>
          <div class="search-empty-title">No jewellery found for "${escapeHtml(q)}"</div>
          <div class="search-empty-sub">Try searching our popular categories:</div>
          <div class="search-quick-tags">
            <span class="search-quick-tag" data-tag="Rings">Rings</span>
            <span class="search-quick-tag" data-tag="Necklaces">Necklaces</span>
            <span class="search-quick-tag" data-tag="Earrings">Earrings</span>
            <span class="search-quick-tag" data-tag="Bracelets">Bracelets</span>
            <span class="search-quick-tag" data-tag="Gold">Gold</span>
            <span class="search-quick-tag" data-tag="Diamond">Diamond</span>
          </div>
        </div>
      `;

      searchDropdown.querySelectorAll('.search-quick-tag').forEach(tag => {
        tag.addEventListener('click', (e) => {
          e.stopPropagation();
          const target = tag.getAttribute('data-tag');
          searchInput.value = target;
          updateClearButton();
          performSearch(target);
          searchInput.focus();
        });
      });
      return;
    }

    const topMatches = matches.slice(0, 5);
    const shopAllUrl = '/shop?q=' + encodeURIComponent(q);

    let html = `
      <div class="search-dropdown-header">
        <span>Matching Products (${matches.length})</span>
        <span style="font-weight: 400; font-size: 0.72rem; text-transform: none; color: #999;">Press Enter to view all</span>
      </div>
      <ul class="search-dropdown-list">
    `;

    topMatches.forEach((p, idx) => {
      const prodUrl = '/product-details?id=' + encodeURIComponent(p.id || p.slug);
      const thumb = getThumb(p);
      const highlightedName = highlightMatches(p.name, tokens);
      const catText = p.category || p.categoryId || 'Jewellery';
      const metalText = p.metal || p.material || '';
      const badgeText = metalText ? `${metalText} • ${catText}` : catText;
      const priceText = formatInr(p.price);

      html += `
        <li>
          <a href="${prodUrl}" class="search-dropdown-item" data-index="${idx}">
            <img src="${thumb}" alt="${escapeHtml(p.name)}" class="search-item-img" onerror="this.src='/assets/images/placeholders/default.jpg'">
            <div class="search-item-info">
              <div class="search-item-title">${highlightedName}</div>
              <div class="search-item-meta">
                <span class="search-item-badge">${escapeHtml(badgeText)}</span>
                ${p.code ? `<span style="font-size: 0.7rem; color: #aaa;">${escapeHtml(p.code)}</span>` : ''}
              </div>
              <div class="search-item-price">${priceText}</div>
            </div>
            <i class="ph ph-caret-right" style="color: #ccc; font-size: 0.9rem;"></i>
          </a>
        </li>
      `;
    });

    html += `
      </ul>
      <div class="search-dropdown-footer">
        <a href="${shopAllUrl}" class="search-view-all-btn">
          <span>View all ${matches.length} results for "${escapeHtml(q)}"</span>
          <i class="ph-bold ph-arrow-right"></i>
        </a>
      </div>
    `;

    searchDropdown.innerHTML = html;
    searchDropdown.style.display = 'block';
  }

  searchInput.addEventListener('input', () => {
    updateClearButton();
    clearTimeout(debounceTimer);
    debounceTimer = setTimeout(() => {
      performSearch(searchInput.value);
    }, 180);
  });

  if (searchClear) {
    searchClear.addEventListener('click', (e) => {
      e.preventDefault();
      e.stopPropagation();
      searchInput.value = '';
      updateClearButton();
      searchDropdown.innerHTML = '';
      searchDropdown.style.display = 'none';
      searchInput.focus();

      if (window.location.pathname.includes('/shop')) {
        const currentUrl = new URL(window.location.href);
        if (currentUrl.searchParams.has('q')) {
          currentUrl.searchParams.delete('q');
          window.history.replaceState(null, '', currentUrl.pathname + (currentUrl.search ? currentUrl.search : ''));
          window.dispatchEvent(new Event('searchCleared'));
        }
      }
    });
  }

  searchInput.addEventListener('keydown', (e) => {
    const items = searchDropdown.querySelectorAll('.search-dropdown-item');
    if (searchDropdown.style.display !== 'block' || items.length === 0) {
      if (e.key === 'Escape') {
        searchDropdown.style.display = 'none';
      }
      return;
    }

    if (e.key === 'ArrowDown') {
      e.preventDefault();
      activeIndex = (activeIndex + 1) % items.length;
      updateActiveItem(items);
    } else if (e.key === 'ArrowUp') {
      e.preventDefault();
      activeIndex = (activeIndex - 1 + items.length) % items.length;
      updateActiveItem(items);
    } else if (e.key === 'Enter') {
      if (activeIndex >= 0 && items[activeIndex]) {
        e.preventDefault();
        window.location.href = items[activeIndex].getAttribute('href');
      }
    } else if (e.key === 'Escape') {
      searchDropdown.style.display = 'none';
    }
  });

  function updateActiveItem(items) {
    items.forEach((it, idx) => {
      if (idx === activeIndex) {
        it.classList.add('is-selected');
        it.scrollIntoView({ block: 'nearest' });
      } else {
        it.classList.remove('is-selected');
      }
    });
  }

  document.addEventListener('click', (e) => {
    if (searchWrap && !searchWrap.contains(e.target)) {
      searchDropdown.style.display = 'none';
    }
  });
}
