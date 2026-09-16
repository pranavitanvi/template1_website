// Product fetching and rendering logic

async function fetchProducts(params = {}) {
  try {
    const searchParams = new URLSearchParams(params);
    searchParams.set('_t', Date.now());
    const url = '/api/products?' + searchParams.toString();
    const response = await fetch(url, {
      cache: 'no-store',
      headers: {
        'Cache-Control': 'no-cache, no-store, must-revalidate',
        'Pragma': 'no-cache'
      }
    });
    if (!response.ok) throw new Error('Failed to fetch products');
    const data = await response.json();
    const items = Array.isArray(data) ? data : (data.data || []);
    return items.filter(p => !p.is_sold_out && p.product_sold_out_status !== 1 && p.product_sold_out_status !== true && p.availability !== 'sold_out' && p.availability !== 'out_of_stock');
  } catch (error) {
    console.error('Error fetching products:', error);
    return [];
  }
}

async function fetchProduct(idOrSlug) {
  try {
    const response = await fetch('/api/products/' + encodeURIComponent(idOrSlug) + '?_t=' + Date.now(), {
      cache: 'no-store',
      headers: {
        'Cache-Control': 'no-cache, no-store, must-revalidate',
        'Pragma': 'no-cache'
      }
    });
    if (!response.ok) {
      if (response.status === 404) {
        const errData = await response.json().catch(() => ({}));
        if (errData && errData.is_sold_out) {
          return { ...(errData.data || {}), is_sold_out: true, availability: 'sold_out' };
        }
      }
      return null;
    }
    const json = await response.json();
    const prod = json.data || json;
    if (prod && (prod.is_sold_out || prod.availability === 'sold_out' || prod.availability === 'out_of_stock')) {
      return { ...prod, is_sold_out: true, availability: 'sold_out' };
    }
    return prod;
  } catch (error) {
    console.error('Error fetching product details:', error);
    return null;
  }
}

// Function to get category-specific placeholder image
function getPlaceholderImage(category) {
  const cat = (category || '').toLowerCase();
  if (cat.includes('earring') || cat.includes('ear ') || cat.includes('studs') || cat.includes('jhumka')) return '/assets/images/placeholders/earring.jpg';
  if (cat.includes('mangalsutra')) return '/assets/images/placeholders/mangalsutra.jpg';
  if (cat.includes('ring') || cat.includes('engagement')) return '/assets/images/placeholders/ring.jpg';
  if (cat.includes('bangle') || cat.includes('bracelet') || cat.includes('kada')) return '/assets/images/placeholders/bangle.jpg';
  if (cat.includes('necklace') || cat.includes('chain') || cat.includes('choker')) return '/assets/images/placeholders/necklace.jpg';
  if (cat.includes('pendant')) return '/assets/images/placeholders/pendant.jpg';
  return '/assets/images/placeholders/default.jpg';
}

function isTruthyFlag(val) {
  return val === true || val === 1 || val === '1' || val === 'true';
}

// Function to render product cards
function renderProductCard(product) {
  const hasNew = isTruthyFlag(product.isNew) || isTruthyFlag(product.is_new) || isTruthyFlag(product.new_arrival);
  const hasFeatured = isTruthyFlag(product.isFeatured) || isTruthyFlag(product.is_featured) || isTruthyFlag(product.featured);
  const hasBestseller = isTruthyFlag(product.isBestseller) || isTruthyFlag(product.is_bestseller) || isTruthyFlag(product.is_best_seller) || isTruthyFlag(product.bestseller);
  const hasSale = isTruthyFlag(product.isSale) || isTruthyFlag(product.is_sale) || isTruthyFlag(product.sale);

  const badgeNew = hasNew ? `<span class="badge">New</span>` : '';
  const badgeFeatured = hasFeatured ? `<span class="badge featured">Featured</span>` : '';
  const badgeBestseller = hasBestseller ? `<span class="badge bestseller">Bestseller</span>` : '';
  const badgeSale = hasSale ? `<span class="badge sale">Sale</span>` : '';

  const badgesHtml = [badgeNew, badgeFeatured, badgeBestseller, badgeSale].filter(Boolean).join('');
  const badges = badgesHtml ? `<div class="product-badges">${badgesHtml}</div>` : '';
  
  const priceDisplay = product.isSale 
    ? `${formatPrice(product.price)} <span style="text-decoration: line-through; color: var(--text-secondary); font-size: 0.9em;">${formatPrice(product.salePrice)}</span>` 
    : formatPrice(product.price);
    
  let starsHtml = '';
  for(let i=1; i<=5; i++) {
    if(i <= product.rating) {
      starsHtml += '<i class="ph-fill ph-star"></i>';
    } else {
      starsHtml += '<i class="ph ph-star"></i>';
    }
  }

  const placeholder = getPlaceholderImage(product.category);
  const imgSrc = product.image ? (product.image.startsWith('/') || product.image.startsWith('http') ? product.image : '/' + product.image) : placeholder;

  const hasSecondary = (product.images && product.images.length > 1);

  const isWishlisted = (typeof wishlist !== 'undefined' && Array.isArray(wishlist)) 
    ? wishlist.includes(parseInt(product.id, 10)) 
    : false;
  const heartIcon = isWishlisted ? '<i class="ph-fill ph-heart" style="color: #e74c3c;"></i>' : '<i class="ph ph-heart"></i>';
  const heartClass = isWishlisted ? 'wishlist-btn active' : 'wishlist-btn';
  const heartStyle = isWishlisted ? ' style="color: #e74c3c;"' : '';

  return `
    <div class="product-card" data-id="${product.id}">
        <div class="product-image-wrap ${hasSecondary ? 'has-secondary-image' : ''}">
            ${badges}
            <button class="${heartClass}" data-id="${product.id}"${heartStyle} onclick="toggleWishlist(${product.id})" aria-label="Add to Wishlist">
                ${heartIcon}
            </button>
            <a href="/product-details?id=${product.id}">
                <img src="${imgSrc}" alt="${product.name}" class="product-image primary-img" onerror="this.onerror=null; this.src='${placeholder}';">
                ${hasSecondary ? `<img src="${product.images[1].startsWith('/') || product.images[1].startsWith('http') ? product.images[1] : '/' + product.images[1]}" alt="${product.name} worn" class="product-image secondary-img" onerror="this.onerror=null; this.src='${placeholder}';">` : ''}
            </a>
            <div class="product-actions">
                <button class="btn add-to-cart-btn" onclick="addToCart(${product.id})">Add to Bag</button>
            </div>
        </div>
        <div class="product-info">
            <div class="product-category">${product.category}</div>
            <h3 class="product-title"><a href="/product-details?id=${product.id}">${product.name}</a></h3>
            <div class="product-price">${priceDisplay}</div>
            <div class="product-rating">
                ${starsHtml}
            </div>
        </div>
    </div>
  `;
}

// Function to render paginated grid
function renderPaginatedGrid(products, gridElement, itemsPerPage = 12) {
    let currentPage = 1;
    const totalPages = Math.ceil(products.length / itemsPerPage);

    function renderPage() {
        const start = (currentPage - 1) * itemsPerPage;
        const end = start + itemsPerPage;
        const paginatedProducts = products.slice(start, end);
        
        let html = paginatedProducts.map(renderProductCard).join('');
        
        // Add pagination UI if totalPages > 1
        if (totalPages > 1) {
            let paginationHtml = '<div class="pagination" style="width: 100%; display: flex; justify-content: center; gap: 0.5rem; margin-top: 3rem; margin-bottom: 2rem; grid-column: 1 / -1;">';
            
            // Prev button
            paginationHtml += `<button class="page-btn prev-btn" ${currentPage === 1 ? 'disabled' : ''} style="padding: 0.5rem 1rem; border: 1px solid var(--border-light); background: var(--white); cursor: ${currentPage === 1 ? 'not-allowed' : 'pointer'}; border-radius: 4px;">&laquo; Prev</button>`;
            
            // Page numbers
            for (let i = 1; i <= totalPages; i++) {
                paginationHtml += `<button class="page-btn num-btn ${currentPage === i ? 'active' : ''}" data-page="${i}" style="padding: 0.5rem 1rem; border: 1px solid var(--border-light); background: ${currentPage === i ? 'var(--primary, #1a1a1a)' : 'var(--white)'}; color: ${currentPage === i ? 'var(--white, #fff)' : 'var(--text-main, #2c2c2c)'}; cursor: pointer; border-radius: 4px;">${i}</button>`;
            }
            
            // Next button
            paginationHtml += `<button class="page-btn next-btn" ${currentPage === totalPages ? 'disabled' : ''} style="padding: 0.5rem 1rem; border: 1px solid var(--border-light); background: var(--white); cursor: ${currentPage === totalPages ? 'not-allowed' : 'pointer'}; border-radius: 4px;">Next &raquo;</button>`;
            
            paginationHtml += '</div>';
            html += paginationHtml;
        }
        
        gridElement.innerHTML = html;
        
        // Attach event listeners to new buttons
        if (totalPages > 1) {
            const prevBtn = gridElement.querySelector('.prev-btn');
            const nextBtn = gridElement.querySelector('.next-btn');
            const numBtns = gridElement.querySelectorAll('.num-btn');
            
            if (prevBtn && currentPage > 1) {
                prevBtn.addEventListener('click', () => {
                    currentPage--;
                    renderPage();
                    gridElement.scrollIntoView({ behavior: 'smooth', block: 'start' });
                });
            }
            if (nextBtn && currentPage < totalPages) {
                nextBtn.addEventListener('click', () => {
                    currentPage++;
                    renderPage();
                    gridElement.scrollIntoView({ behavior: 'smooth', block: 'start' });
                });
            }
            numBtns.forEach(btn => {
                btn.addEventListener('click', (e) => {
                    currentPage = parseInt(e.target.getAttribute('data-page'));
                    renderPage();
                    gridElement.scrollIntoView({ behavior: 'smooth', block: 'start' });
                });
            });
        }
    }
    
    renderPage();
}

// Initialize shop page if we are on it
document.addEventListener('DOMContentLoaded', async () => {
  const shopGrid = document.getElementById('shop-product-grid');
  const featuredGrid = document.getElementById('featured-products');
  const newArrivalsGrid = document.getElementById('new-arrivals-grid');
  const bestsellersGrid = document.getElementById('bestsellers-grid');
  const mensNewGrid = document.getElementById('mens-new-grid');
  const mensBestsellersGrid = document.getElementById('mens-bestsellers-grid');
  const engagementMiniGrid = document.getElementById('engagement-mini-grid');
  const bridalHeirloomGrid = document.getElementById('bridal-heirloom-grid');
  const newArrivalsPageGrid = document.getElementById('new-arrivals-page-grid');
  
  if (shopGrid || featuredGrid || newArrivalsGrid || bestsellersGrid || mensNewGrid || mensBestsellersGrid || engagementMiniGrid || bridalHeirloomGrid || newArrivalsPageGrid) {
    const products = await fetchProducts();
    let allProducts = products;
    
    if (shopGrid && !document.querySelector('.horizontal-filter-bar')) {
      // 1. Initialize Checkboxes from URL or Pathname
      const urlParams = new URLSearchParams(window.location.search);
      let categoryParam = urlParams.get('category');
      const genderParam = urlParams.get('gender');
      const occasionParam = urlParams.get('occasion') || urlParams.get('collection');

      const pathName = window.location.pathname.toLowerCase();
      if (pathName.includes('rings.html')) categoryParam = 'rings';
      if (pathName.includes('earrings.html')) categoryParam = 'earrings';
      if (pathName.includes('necklaces.html')) categoryParam = 'necklaces';
      if (pathName.includes('bangles.html')) categoryParam = 'bangles';
      if (pathName.includes('pendants.html')) categoryParam = 'pendants';
      if (pathName.includes('mangalsutras.html')) categoryParam = 'mangalsutras';
      if (pathName.includes('bracelets.html')) categoryParam = 'bracelets';
      if (pathName.includes('chains.html')) categoryParam = 'chains';

      if (categoryParam && categoryParam !== 'collections' && categoryParam !== 'new' && categoryParam !== 'all') {
        const cb = document.querySelector(`.filter-cb[data-filter="category"][value="${categoryParam.toLowerCase()}"]`);
        if (cb) cb.checked = true;
        
        // Update page title dynamically
        const pageTitle = document.getElementById('shop-page-title');
        if (pageTitle) {
            const cleanTitle = categoryParam.replace(/\.html$/i, '');
            pageTitle.textContent = cleanTitle.charAt(0).toUpperCase() + cleanTitle.slice(1);
        }
      }
      if (genderParam) {
        const cb = document.querySelector(`.filter-cb[data-filter="gender"][value="${genderParam.toLowerCase()}"]`);
        if (cb) cb.checked = true;
      }
      if (occasionParam) {
        const cb = document.querySelector(`.filter-cb[data-filter="occasion"][value="${occasionParam.toLowerCase()}"]`);
        if (cb) cb.checked = true;
      }

      // 2. Filter Function
      const applyFilters = () => {
        const checkedGenders = Array.from(document.querySelectorAll('.filter-cb[data-filter="gender"]:checked')).map(cb => cb.value);
        const checkedCategories = Array.from(document.querySelectorAll('.filter-cb[data-filter="category"]:checked')).map(cb => cb.value);
        const checkedOccasions = Array.from(document.querySelectorAll('.filter-cb[data-filter="occasion"]:checked')).map(cb => cb.value);
        const checkedCollections = Array.from(document.querySelectorAll('.filter-cb[data-filter="collection"]:checked')).map(cb => cb.value);
        const checkedPrices = Array.from(document.querySelectorAll('.filter-cb[data-filter="price"]:checked')).map(cb => cb.value);
        const checkedTypes = Array.from(document.querySelectorAll('.filter-cb[data-filter="type"]:checked')).map(cb => cb.value);
        const checkedAvailabilities = Array.from(document.querySelectorAll('.filter-cb[data-filter="availability"]:checked')).map(cb => cb.value);

        let filtered = allProducts;

        // Apply search query
        const qParam = urlParams.get('q');
        if (qParam) {
            const term = qParam.toLowerCase().trim();
            // Create a regex to match the exact word (handling simple plurals)
            // so 'ring' matches 'ring' and 'rings' but NOT 'earrings'
            const stem = term.endsWith('s') ? term.slice(0, -1) : term;
            const regex = new RegExp('\\b' + stem + '(s)?\\b', 'i');
            
            filtered = filtered.filter(p => {
                const searchStr = `${p.name || ''} ${p.categoryId || ''} ${p.category || ''} ${p.description || ''}`;
                return regex.test(searchStr);
            });
        }

        // Apply URL overrides if it's "New Arrivals"
        if (categoryParam === 'new') {
            filtered = filtered.filter(p => p.isNew);
        }

        if (checkedGenders.length > 0) {
          filtered = filtered.filter(p => checkedGenders.includes(p.gender) || p.gender === 'unisex');
        }
        if (checkedCategories.length > 0) {
          filtered = filtered.filter(p => checkedCategories.includes(p.categoryId));
        } else if (categoryParam && categoryParam !== 'collections' && categoryParam !== 'new' && categoryParam !== 'all') {
          filtered = filtered.filter(p => p.categoryId === categoryParam.toLowerCase());
        }
        if (checkedOccasions.length > 0) {
          filtered = filtered.filter(p => checkedOccasions.includes((p.collection || '').toLowerCase()));
        }
        if (checkedCollections.length > 0) {
          filtered = filtered.filter(p => {
              if (checkedCollections.includes('new') && p.isNew) return true;
              if (checkedCollections.includes('bestsellers') && p.rating >= 4.5) return true;
              const coll = (p.collection || '').toLowerCase();
              return checkedCollections.some(c => coll.includes(c) || c.includes(coll));
          });
        }
        if (checkedPrices.length > 0) {
            filtered = filtered.filter(p => {
                const price = p.price || 0;
                return checkedPrices.some(range => {
                    const [min, max] = range.split('-').map(Number);
                    return price >= min && price <= max;
                });
            });
        }
        if (checkedTypes.length > 0) {
          filtered = filtered.filter(p => {
              const material = (p.material || '').toLowerCase();
              const stone = (p.stone || '').toLowerCase();
              return checkedTypes.some(type => {
                  if (type === 'diamond') return stone.includes('diamond');
                  if (type === 'gold') return material.includes('gold');
                  if (type === 'platinum') return material.includes('platinum');
                  if (type === 'gemstone') return stone.includes('gemstone') || stone.includes('ruby') || stone.includes('emerald') || stone.includes('sapphire') || stone.includes('precious');
                  return false;
              });
          });
        }
        if (checkedAvailabilities.length > 0) {
          filtered = filtered.filter(p => checkedAvailabilities.includes(p.availability));
        }

        renderPaginatedGrid(filtered, shopGrid, 12);
        
        const countEl = document.getElementById('product-count');
        if(countEl) countEl.textContent = `${filtered.length} Products`;
      };

      // 3. Attach Listeners
      const checkboxes = document.querySelectorAll('.filter-cb');
      checkboxes.forEach(cb => {
        cb.addEventListener('change', applyFilters);
      });

      // 4. Initial Render
      applyFilters();
    }
    
    function renderSectionGrid(gridEl, filterFn, maxCount = 4) {
      if (!gridEl) return;
      let tagged = products.filter(filterFn);
      
      if (tagged.length === 0) {
        tagged = products.slice(0, maxCount);
      } else {
        tagged = tagged.slice(0, maxCount);
      }

      gridEl.innerHTML = tagged.map(renderProductCard).join('');
      
      const count = tagged.length;
      gridEl.style.display = 'grid';
      gridEl.style.gridTemplateColumns = `repeat(auto-fill, minmax(260px, 1fr))`;
      gridEl.style.gap = '2rem';
      if (count < 4 && count > 0) {
        gridEl.style.maxWidth = `${count * 340}px`;
        gridEl.style.margin = '0 auto';
      } else {
        gridEl.style.maxWidth = 'none';
        gridEl.style.margin = '0';
      }
    }

    if (featuredGrid) {
      renderSectionGrid(featuredGrid, p => isTruthyFlag(p.isFeatured) || isTruthyFlag(p.is_featured) || isTruthyFlag(p.featured));
    }

    if (newArrivalsGrid) {
      renderSectionGrid(newArrivalsGrid, p => isTruthyFlag(p.isNew) || isTruthyFlag(p.is_new) || isTruthyFlag(p.new_arrival));
    }

    if (bestsellersGrid) {
      renderSectionGrid(bestsellersGrid, p => isTruthyFlag(p.isBestseller) || isTruthyFlag(p.is_bestseller) || isTruthyFlag(p.is_best_seller) || isTruthyFlag(p.bestseller) || p.rating >= 4.5);
    }

    if (mensNewGrid) {
      const mensProducts = products.filter(p => p.gender === 'him');
      mensNewGrid.innerHTML = mensProducts.slice(0, 4).map(renderProductCard).join('');
    }

    if (mensBestsellersGrid) {
      const mensProducts = products.filter(p => p.gender === 'him');
      mensBestsellersGrid.innerHTML = mensProducts.slice(0, 4).map(renderProductCard).join('');
    }

    if (engagementMiniGrid) {
      const engagementProducts = products.filter(p => {
        const cat = (p.category || p.categoryId || '').toLowerCase();
        const col = (p.collection || '').toLowerCase();
        const occ = (p.occasion || '').toLowerCase();
        const name = (p.name || '').toLowerCase();
        return col === 'engagement' || occ === 'engagement' || cat.includes('ring') || name.includes('ring') || name.includes('solitaire');
      });
      engagementMiniGrid.innerHTML = (engagementProducts.length >= 2 ? engagementProducts : products).slice(0, 2).map(renderProductCard).join('');
    }

    if (bridalHeirloomGrid) {
      const bridalProducts = products.filter(p => {
        const col = (p.collection || '').toLowerCase();
        const occ = (p.occasion || '').toLowerCase();
        const cat = (p.category || p.categoryId || '').toLowerCase();
        const name = (p.name || '').toLowerCase();
        return col === 'bridal' || col === 'wedding' || occ === 'bridal' || occ === 'wedding' || cat.includes('mangalsutra') || cat.includes('necklace') || name.includes('bridal') || name.includes('mangalsutra') || name.includes('trousseau') || name.includes('heirloom') || name.includes('necklace') || name.includes('kada') || name.includes('bangle') || name.includes('jhumka');
      });
      bridalHeirloomGrid.innerHTML = (bridalProducts.length >= 4 ? bridalProducts : products).slice(0, 4).map(renderProductCard).join('');
    }

    const newArrivalsPageGrid = document.getElementById('new-arrivals-page-grid');
    if (newArrivalsPageGrid) {
      const isNewProducts = products.filter(p => p.isNew);
      const basePool = isNewProducts.length > 0 ? isNewProducts : products;

      function getFilteredForTab(tabKey) {
        if (tabKey === 'women') {
          let res = basePool.filter(p => (p.gender || '').toLowerCase() !== 'him' && (p.gender || '').toLowerCase() !== 'men');
          if (res.length === 0) res = products.filter(p => (p.gender || '').toLowerCase() !== 'him' && (p.gender || '').toLowerCase() !== 'men');
          return res;
        }
        if (tabKey === 'men') {
          let res = basePool.filter(p => (p.gender || '').toLowerCase() === 'him' || (p.gender || '').toLowerCase() === 'men');
          if (res.length === 0) res = products.filter(p => (p.gender || '').toLowerCase() === 'him' || (p.gender || '').toLowerCase() === 'men');
          return res;
        }
        if (tabKey === 'diamonds') {
          const matchDiamond = p => {
            const stone = (p.stone || '').toLowerCase();
            const mat = (p.material || p.metal || '').toLowerCase();
            const name = (p.name || '').toLowerCase();
            const stones = Array.isArray(p.stones) ? p.stones.map(s => (s.stone_name || '').toLowerCase()).join(' ') : '';
            return stone.includes('diamond') || mat.includes('diamond') || stones.includes('diamond') || name.includes('diamond') || name.includes('solitaire');
          };
          let res = basePool.filter(matchDiamond);
          if (res.length === 0) res = products.filter(matchDiamond);
          return res;
        }
        if (tabKey === 'bridal') {
          const matchBridal = p => {
            const col = (p.collection || '').toLowerCase();
            const occ = (p.occasion || '').toLowerCase();
            const cat = (p.categoryId || p.category || '').toLowerCase();
            return col.includes('bridal') || col.includes('wedding') || occ.includes('wedding') || occ.includes('bridal') || cat.includes('mangalsutra');
          };
          let res = basePool.filter(matchBridal);
          if (res.length === 0) res = products.filter(matchBridal);
          return res;
        }
        return basePool;
      }

      function applyArrivalTab(tabKey) {
        const filtered = getFilteredForTab(tabKey);
        if (filtered.length === 0) {
          newArrivalsPageGrid.innerHTML = '<div style="grid-column: 1 / -1; text-align: center; padding: 4rem 1rem; color: var(--text-secondary); font-size: 1rem;">No new arrivals found matching this category.</div>';
        } else {
          renderPaginatedGrid(filtered, newArrivalsPageGrid, 12);
        }
      }

      // Initial render for 'all'
      applyArrivalTab('all');

      // Click handlers for new arrival tabs to filter on same page without redirect
      const arrivalTabs = document.querySelectorAll('.new-arrivals-tab');
      arrivalTabs.forEach(tab => {
        tab.addEventListener('click', function(e) {
          e.preventDefault();
          arrivalTabs.forEach(t => t.classList.remove('active'));
          this.classList.add('active');
          const selectedTab = this.getAttribute('data-tab') || 'all';
          applyArrivalTab(selectedTab);
        });
      });
    }
  }
});

// Touch handling for hover effects on mobile devices
document.addEventListener('click', function(e) {
  // Check if device supports touch/doesn't have a reliable hover
  if (window.matchMedia('(hover: none)').matches || ('ontouchstart' in window)) {
    const card = e.target.closest('.product-card');
    
    // Ignore if clicking wishlist or add to cart button directly
    if (e.target.closest('.wishlist-btn') || e.target.closest('.add-to-cart-btn')) {
      return;
    }

    if (card) {
      if (!card.classList.contains('hovered')) {
        e.preventDefault(); // Stop navigation on first tap
        
        // Remove hover state from all other cards
        document.querySelectorAll('.product-card.hovered').forEach(c => {
          c.classList.remove('hovered');
        });
        
        // Add hover state to this card
        card.classList.add('hovered');
      }
    } else {
      // Clicked outside a product card, remove hover states from all cards
      document.querySelectorAll('.product-card.hovered').forEach(c => {
        c.classList.remove('hovered');
      });
    }
  }
});

