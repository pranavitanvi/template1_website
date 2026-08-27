document.addEventListener('DOMContentLoaded', () => {
    // 1. Dropdown Toggle Logic
    const dropdowns = document.querySelectorAll('.filter-dropdown');
    
    dropdowns.forEach(dropdown => {
        const btn = dropdown.querySelector('.filter-btn');
        btn.addEventListener('click', (e) => {
            e.stopPropagation();
            // Close other dropdowns
            dropdowns.forEach(d => {
                if(d !== dropdown) d.classList.remove('active');
            });
            dropdown.classList.toggle('active');
        });
    });

    document.addEventListener('click', () => {
        dropdowns.forEach(d => d.classList.remove('active'));
    });

    const popovers = document.querySelectorAll('.filter-popover');
    popovers.forEach(p => {
        p.addEventListener('click', (e) => e.stopPropagation());
    });

    // 2. Fetch and Render Products
    let allProducts = [];
    const shopGrid = document.getElementById('shop-product-grid');
    const productCount = document.getElementById('product-count');
    const activeFiltersContainer = document.getElementById('active-filter-chips');
    const filterCheckboxes = document.querySelectorAll('.cat-filter-cb');
    const sortSelect = document.getElementById('sort-select');

    // Extract category from URL
    const pathname = window.location.pathname;
    let currentCategory = 'all';
    if(pathname.includes('chains.html')) currentCategory = 'chains';
    if(pathname.includes('rings.html')) currentCategory = 'rings';
    if(pathname.includes('earrings.html')) currentCategory = 'earrings';
    if(pathname.includes('bracelets.html')) currentCategory = 'bracelets';
    if(pathname.includes('bangles.html')) currentCategory = 'bangles';
    if(pathname.includes('pendants.html')) currentCategory = 'pendants';
    if(pathname.includes('mangalsutras.html')) currentCategory = 'mangalsutra';

    fetch('data/products.json')
        .then(res => res.json())
        .then(data => {
            allProducts = data;
            applyFilters();
        });

        function formatPrice(price) {
        return new Intl.NumberFormat('en-IN', { style: 'currency', currency: 'INR', minimumFractionDigits: 0, maximumFractionDigits: 0 }).format(price).replace('INR', '').trim();
    }

    function getPlaceholderImage(cat) {
        if (!cat) cat = '';
        cat = cat.toLowerCase();
        if (cat.includes('ring')) return 'assets/images/placeholders/ring.jpg';
        if (cat.includes('earring')) return 'assets/images/placeholders/earring.jpg';
        if (cat.includes('bangle') || cat.includes('bracelet') || cat.includes('kada')) return 'assets/images/placeholders/bangle.jpg';
        if (cat.includes('necklace') || cat.includes('chain') || cat.includes('choker')) return 'assets/images/placeholders/necklace.jpg';
        if (cat.includes('pendant')) return 'assets/images/placeholders/pendant.jpg';
        return 'assets/images/placeholders/default.jpg';
    }

    function renderProducts(products) {
        if (productCount) productCount.textContent = products.length;
        if (!shopGrid) return;
        
        shopGrid.innerHTML = '';
        if (products.length === 0) {
            shopGrid.innerHTML = '<div style="grid-column: 1/-1; text-align: center; padding: 4rem; color: var(--text-secondary);">No products found matching your criteria.</div>';
            return;
        }

        products.forEach(product => {
            const isSale = product.isSale ? `<span class="badge sale">Sale</span>` : '';
            const isNew = product.isNew ? `<span class="badge">New</span>` : '';
            const badges = (isSale || isNew) ? `<div class="product-badges">${isNew}${isSale}</div>` : '';
            
            const priceDisplay = product.isSale 
                ? `\u20b9${formatPrice(product.price)} <span style="text-decoration: line-through; color: var(--text-secondary); font-size: 0.9em;">\u20b9${formatPrice(product.salePrice)}</span>` 
                : `\u20b9${formatPrice(product.price)}`;
                
            let starsHtml = '';
            for(let i=1; i<=5; i++) {
                if(i <= product.rating) {
                starsHtml += '<i class="ph-fill ph-star"></i>';
                } else {
                starsHtml += '<i class="ph ph-star"></i>';
                }
            }

            const placeholder = getPlaceholderImage(product.category);
            const imgSrc = product.image || placeholder;
            const hasSecondary = (product.images && product.images.length > 1);

            const card = `
                <div class="product-card" data-id="${product.id}">
                    <div class="product-image-wrap ${hasSecondary ? 'has-secondary-image' : ''}">
                        ${badges}
                        <button class="wishlist-btn" onclick="toggleWishlist(${product.id})" aria-label="Add to Wishlist">
                            <i class="ph ph-heart"></i>
                        </button>
                        <a href="product-details.html?id=${product.id}">
                            <img src="${imgSrc}" alt="${product.name}" class="product-image primary-img" onerror="this.onerror=null; this.src='${placeholder}';">
                            ${hasSecondary ? `<img src="${product.images[1]}" alt="${product.name} worn" class="product-image secondary-img" onerror="this.onerror=null; this.src='${placeholder}';">` : ''}
                        </a>
                        <div class="product-actions">
                            <button class="btn add-to-cart-btn" onclick="addToCart(${product.id}, 1, this)">Add to Bag</button>
                        </div>
                    </div>
                    <div class="product-info">
                        <div class="product-category">${product.category || ''}</div>
                        <h3 class="product-title"><a href="product-details.html?id=${product.id}">${product.name}</a></h3>
                        <div class="product-price">${priceDisplay}</div>
                        <div class="product-rating">
                            ${starsHtml}
                        </div>
                    </div>
                </div>
            `;
            shopGrid.insertAdjacentHTML('beforeend', card);
        });
    }
    function applyFilters() {
        let filtered = allProducts;

        // Base category filter
        if (currentCategory !== 'all') {
            filtered = filtered.filter(p => p.categoryId === currentCategory);
        }

        const checkedTypes = Array.from(document.querySelectorAll('.cat-filter-cb[data-filter="type"]:checked')).map(cb => cb.value);
        const checkedPrices = Array.from(document.querySelectorAll('.cat-filter-cb[data-filter="price"]:checked')).map(cb => cb.value);
        const checkedCollections = Array.from(document.querySelectorAll('.cat-filter-cb[data-filter="collection"]:checked')).map(cb => cb.value);
        const checkedOccasions = Array.from(document.querySelectorAll('.cat-filter-cb[data-filter="occasion"]:checked')).map(cb => cb.value);
        const checkedGenders = Array.from(document.querySelectorAll('.cat-filter-cb[data-filter="gender"]:checked')).map(cb => cb.value);

        if (checkedTypes.length > 0) {
            filtered = filtered.filter(p => {
                const material = (p.material || '').toLowerCase();
                const stone = (p.stone || '').toLowerCase();
                return checkedTypes.some(type => {
                    if (type === 'diamond') return stone.includes('diamond');
                    if (type === 'gold') return material.includes('gold');
                    if (type === 'platinum') return material.includes('platinum');
                    return false;
                });
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

        if (checkedCollections.length > 0) {
            filtered = filtered.filter(p => {
                if (checkedCollections.includes('new') && p.isNew) return true;
                if (checkedCollections.includes('bestsellers') && p.rating >= 4.5) return true;
                const coll = (p.collection || '').toLowerCase();
                return checkedCollections.some(c => coll.includes(c) || c.includes(coll));
            });
        }
        
        if (checkedOccasions.length > 0) {
            filtered = filtered.filter(p => checkedOccasions.includes((p.collection || '').toLowerCase()));
        }
        
        if (checkedGenders.length > 0) {
            filtered = filtered.filter(p => checkedGenders.includes(p.gender) || p.gender === 'unisex');
        }

        // Sorting
        const sortVal = sortSelect ? sortSelect.value : 'Recommended';
        if (sortVal === 'Price: Low to High') {
            filtered.sort((a, b) => (a.price || 0) - (b.price || 0));
        } else if (sortVal === 'Price: High to Low') {
            filtered.sort((a, b) => (b.price || 0) - (a.price || 0));
        } else if (sortVal === 'Newest Arrivals') {
            filtered.sort((a, b) => (b.isNew ? 1 : 0) - (a.isNew ? 1 : 0));
        }

        renderProducts(filtered);
        renderChips();
    }

    
    function renderChips() {
        if (!activeFiltersContainer) return;
        activeFiltersContainer.innerHTML = '';
        const checked = document.querySelectorAll('.cat-filter-cb:checked');
        
        if (checked.length > 0) {
            const labelSpan = document.createElement('span');
            labelSpan.style.fontSize = '0.85rem';
            labelSpan.style.fontWeight = '600';
            labelSpan.style.color = 'var(--text-primary)';
            labelSpan.style.marginRight = '1rem';
            labelSpan.style.textTransform = 'uppercase';
            labelSpan.textContent = 'FILTERED BY:';
            activeFiltersContainer.appendChild(labelSpan);
        }

        checked.forEach(cb => {
            const label = cb.closest('label').textContent.trim();
            const chip = document.createElement('div');
            chip.className = 'filter-chip';
            chip.innerHTML = `${label} <button data-val="${cb.value}" data-filter="${cb.dataset.filter}">&times;</button>`;
            
            chip.querySelector('button').addEventListener('click', (e) => {
                const f = e.currentTarget.dataset.filter;
                const v = e.currentTarget.dataset.val;
                const targetCb = document.querySelector(`.cat-filter-cb[data-filter="${f}"][value="${v}"]`);
                if (targetCb) {
                    targetCb.checked = false;
                    applyFilters();
                }
            });
            
            activeFiltersContainer.appendChild(chip);
        });
        
        if(checked.length > 0) {
            const clearAll = document.createElement('button');
            clearAll.className = 'clear-all-btn';
            clearAll.textContent = 'Clear all';
            clearAll.addEventListener('click', () => {
                filterCheckboxes.forEach(c => c.checked = false);
                applyFilters();
            });
            activeFiltersContainer.appendChild(clearAll);
        }
    }

    // New Event Listeners
    if (typeof sortSelect !== 'undefined' && sortSelect) sortSelect.addEventListener('change', applyFilters);

    // Apply button logic
    const applyBtns = document.querySelectorAll('.btn-apply-dropdown');
    applyBtns.forEach(btn => {
        btn.addEventListener('click', (e) => {
            e.stopPropagation();
            applyFilters();
            // close popover
            const popover = btn.closest('.filter-popover');
            if (popover) popover.classList.remove('active');
        });
    });

    // Clear button logic
    const clearBtns = document.querySelectorAll('.btn-clear-dropdown');
    clearBtns.forEach(btn => {
        btn.addEventListener('click', (e) => {
            e.stopPropagation();
            const popover = btn.closest('.filter-popover');
            if (popover) {
                const checkboxes = popover.querySelectorAll('.cat-filter-cb');
                checkboxes.forEach(cb => cb.checked = false);
                applyFilters();
            }
        });
    });


    

});
