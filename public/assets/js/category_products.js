document.addEventListener('DOMContentLoaded', () => {
    // 1. Dropdown Toggle Logic
    const dropdowns = document.querySelectorAll('.filter-dropdown');
    
    dropdowns.forEach(dropdown => {
        const btn = dropdown.querySelector('.filter-btn');
        if (!btn) return;
        btn.addEventListener('click', (e) => {
            e.stopPropagation();
            dropdowns.forEach(d => {
                if (d !== dropdown) d.classList.remove('active');
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

    // 2. DOM Elements & State
    let allProducts = [];
    const ITEMS_PER_PAGE = 12;
    let currentPage = 1;
    let totalFilteredCount = 0;

    const shopGrid = document.getElementById('shop-product-grid');
    const productCount = document.getElementById('product-count');
    const activeFiltersContainer = document.querySelector('.active-filters-container');
    const filterCheckboxes = document.querySelectorAll('.cat-filter-cb');
    const sortSelect = document.getElementById('sort-select');

    if (!shopGrid) return;

    // Normalization helper for jewellery categories (handles singular/plural/case)
    function normalizeCategory(str) {
        if (!str) return '';
        const s = str.toLowerCase().trim();
        if (s === 'necklaces' || s === 'necklace') return 'necklace';
        if (s === 'rings' || s === 'ring') return 'ring';
        if (s === 'earrings' || s === 'earring') return 'earring';
        if (s === 'bracelets' || s === 'bracelet') return 'bracelet';
        if (s === 'bangles' || s === 'bangle') return 'bangle';
        if (s === 'mangalsutras' || s === 'mangalsutra') return 'mangalsutra';
        if (s === 'pendants' || s === 'pendant') return 'pendant';
        if (s === 'chains' || s === 'chain') return 'chain';
        if (s === 'mens' || s === 'men') return 'men';
        if (s === 'womens' || s === 'women') return 'women';
        return s.replace(/s$/, '');
    }

    // Extract base category from data-attribute or URL path
    const pathname = window.location.pathname.toLowerCase();
    let currentCategory = (shopGrid && shopGrid.dataset.category) ? shopGrid.dataset.category : 'all';
    if (currentCategory === 'all') {
        if (pathname.includes('/category/chains') || pathname.includes('/chains')) currentCategory = 'chains';
        else if (pathname.includes('/category/rings') || pathname.includes('/rings')) currentCategory = 'rings';
        else if (pathname.includes('/category/earrings') || pathname.includes('/earrings')) currentCategory = 'earrings';
        else if (pathname.includes('/category/bracelets') || pathname.includes('/bracelets')) currentCategory = 'bracelets';
        else if (pathname.includes('/category/bangles') || pathname.includes('/bangles')) currentCategory = 'bangles';
        else if (pathname.includes('/category/pendants') || pathname.includes('/pendants')) currentCategory = 'pendants';
        else if (pathname.includes('/category/mangalsutras') || pathname.includes('/mangalsutra')) currentCategory = 'mangalsutra';
        else if (pathname.includes('/category/necklaces') || pathname.includes('/necklaces') || pathname.includes('/necklace')) currentCategory = 'necklaces';
        else if (pathname.includes('/category/mens') || pathname.includes('/mens')) currentCategory = 'mens';
        else if (pathname.includes('/category/womens') || pathname.includes('/womens')) currentCategory = 'womens';
    }

    // Extract base collection from data-attribute or URL path
    let currentCollection = (shopGrid && shopGrid.dataset.collection) ? shopGrid.dataset.collection.toLowerCase().trim() : '';
    let currentCollectionTitle = (shopGrid && shopGrid.dataset.collectionTitle) ? shopGrid.dataset.collectionTitle : '';
    if (!currentCollection) {
        if (pathname.includes('/collection/')) {
            const parts = pathname.split('/collection/');
            if (parts[1]) currentCollection = decodeURIComponent(parts[1].split('/')[0].split('?')[0]).toLowerCase().trim();
        } else if (pathname.includes('/collections/')) {
            const parts = pathname.split('/collections/');
            if (parts[1] && parts[1] !== '') currentCollection = decodeURIComponent(parts[1].split('/')[0].split('?')[0]).toLowerCase().trim();
        }
    }

    // 3. Initialize Checkboxes and Page from URL Query Parameters
    function initFiltersFromUrl() {
        const urlParams = new URLSearchParams(window.location.search);
        
        // Page param
        const pageParam = urlParams.get('page');
        if (pageParam && parseInt(pageParam, 10) > 0) {
            currentPage = parseInt(pageParam, 10);
        }

        // Category param (e.g. ?category=rings or ?category=rings,necklaces)
        const catParam = urlParams.get('category');
        if (catParam) {
            catParam.split(',').forEach(val => {
                const norm = normalizeCategory(val);
                const cbs = document.querySelectorAll('.cat-filter-cb[data-filter="category"]');
                cbs.forEach(cb => {
                    if (normalizeCategory(cb.value) === norm) cb.checked = true;
                });
            });
        }

        // Collection param (e.g. ?collection=everyday or from collection route)
        const colParam = urlParams.get('collection') || currentCollection;
        if (colParam) {
            colParam.split(',').forEach(val => {
                const target = val.toLowerCase().trim();
                let found = false;
                const cbs = document.querySelectorAll('.cat-filter-cb[data-filter="collection"]');
                cbs.forEach(cb => {
                    if (cb.value.toLowerCase().trim() === target) {
                        cb.checked = true;
                        found = true;
                    }
                });

                // If collection checkbox does not exist in the popover HTML, dynamically register it
                if (!found && target) {
                    const popoverList = document.querySelector('.cat-filter-cb[data-filter="collection"]')?.closest('.filter-list')
                        || document.querySelector('.filter-popover .filter-list');
                    if (popoverList) {
                        const li = document.createElement('li');
                        const labelText = currentCollectionTitle || target.replace(/[-_]/g, ' ').replace(/\b\w/g, l => l.toUpperCase());
                        li.innerHTML = `<label><input type="checkbox" class="cat-filter-cb" data-filter="collection" value="${target}" checked> ${labelText}</label>`;
                        popoverList.appendChild(li);
                        const newCb = li.querySelector('input');
                        newCb.addEventListener('change', () => {
                            applyFilters(true);
                            updateUrlState();
                        });
                    }
                }
            });
        }

        // Gender param (e.g. ?gender=women or ?gender=her or ?gender=men or ?gender=him)
        const genParam = urlParams.get('gender');
        if (genParam) {
            genParam.split(',').forEach(val => {
                const v = val.toLowerCase().trim();
                const targetVal = (v === 'women' || v === 'womens' || v === 'female' || v === 'her') ? 'her' : 'him';
                const cbs = document.querySelectorAll('.cat-filter-cb[data-filter="gender"]');
                cbs.forEach(cb => {
                    if (cb.value === targetVal || cb.value === v) cb.checked = true;
                });
            });
        }

        // Occasion param (e.g. ?occasion=wedding)
        const occParam = urlParams.get('occasion');
        if (occParam) {
            occParam.split(',').forEach(val => {
                const target = val.toLowerCase().trim();
                const cbs = document.querySelectorAll('.cat-filter-cb[data-filter="occasion"]');
                cbs.forEach(cb => {
                    if (cb.value.toLowerCase().trim() === target) cb.checked = true;
                });
            });
        }

        // Price param (e.g. ?price=25000-50000)
        const priceParam = urlParams.get('price');
        if (priceParam) {
            priceParam.split(',').forEach(val => {
                const cbs = document.querySelectorAll('.cat-filter-cb[data-filter="price"]');
                cbs.forEach(cb => {
                    if (cb.value === val) cb.checked = true;
                });
            });
        }

        // Metal / Type param (e.g. ?type=gold)
        const typeParam = urlParams.get('type') || urlParams.get('metal');
        if (typeParam) {
            typeParam.split(',').forEach(val => {
                const target = val.toLowerCase().trim();
                const cbs = document.querySelectorAll('.cat-filter-cb[data-filter="type"]');
                cbs.forEach(cb => {
                    if (cb.value.toLowerCase().trim() === target) cb.checked = true;
                });
            });
        }

        // Sort param (e.g. ?sort=price-low)
        const sortParam = urlParams.get('sort');
        if (sortParam && sortSelect) {
            sortSelect.value = sortParam;
        }
    }

    // 4. Synchronize URL Address Bar with Active Filters & Page
    function updateUrlState() {
        const urlParams = new URLSearchParams(window.location.search);
        const searchQ = urlParams.get('q'); // Preserve search query if present

        const newParams = new URLSearchParams();
        if (searchQ) newParams.set('q', searchQ);

        // Checked Categories
        const checkedCategories = Array.from(document.querySelectorAll('.cat-filter-cb[data-filter="category"]:checked')).map(cb => cb.value);
        if (checkedCategories.length > 0) newParams.set('category', checkedCategories.join(','));

        // Checked Collections
        const checkedCollections = Array.from(document.querySelectorAll('.cat-filter-cb[data-filter="collection"]:checked')).map(cb => cb.value);
        if (pathname.includes('/collection/') || pathname.includes('/collections/')) {
            const otherCols = checkedCollections.filter(c => c.toLowerCase() !== currentCollection);
            if (otherCols.length > 0) newParams.set('collection', otherCols.join(','));
        } else {
            if (checkedCollections.length > 0) newParams.set('collection', checkedCollections.join(','));
        }

        // Checked Occasions
        const checkedOccasions = Array.from(document.querySelectorAll('.cat-filter-cb[data-filter="occasion"]:checked')).map(cb => cb.value);
        if (checkedOccasions.length > 0) newParams.set('occasion', checkedOccasions.join(','));

        // Checked Genders
        const checkedGenders = Array.from(document.querySelectorAll('.cat-filter-cb[data-filter="gender"]:checked')).map(cb => cb.value);
        if (checkedGenders.length > 0) {
            const mapped = checkedGenders.map(g => (g === 'her' ? 'women' : (g === 'him' ? 'men' : g)));
            newParams.set('gender', mapped.join(','));
        }

        // Checked Prices
        const checkedPrices = Array.from(document.querySelectorAll('.cat-filter-cb[data-filter="price"]:checked')).map(cb => cb.value);
        if (checkedPrices.length > 0) newParams.set('price', checkedPrices.join(','));

        // Checked Metal / Types
        const checkedTypes = Array.from(document.querySelectorAll('.cat-filter-cb[data-filter="type"]:checked')).map(cb => cb.value);
        if (checkedTypes.length > 0) newParams.set('type', checkedTypes.join(','));

        // Sort option
        if (sortSelect && sortSelect.value && sortSelect.value !== 'recommended') {
            newParams.set('sort', sortSelect.value);
        }

        // Current Page
        if (currentPage > 1) {
            newParams.set('page', currentPage);
        }

        const queryString = newParams.toString();
        const cleanPath = window.location.pathname;
        const newUrl = cleanPath + (queryString ? '?' + queryString : '');
        window.history.replaceState(null, '', newUrl);
    }

    // 5. Fetch Live Products from API with real-time refresh (no caching)
    fetch('/api/products?_t=' + Date.now(), {
        cache: 'no-store',
        headers: {
            'Cache-Control': 'no-cache, no-store, must-revalidate',
            'Pragma': 'no-cache'
        }
    })
        .then(res => res.json())
        .then(data => {
            const raw = Array.isArray(data) ? data : (data.data || []);
            allProducts = raw.filter(p => !p.is_sold_out && p.product_sold_out_status !== 1 && p.product_sold_out_status !== true && p.availability !== 'sold_out' && p.availability !== 'out_of_stock');
            initFiltersFromUrl();
            applyFilters(false); // keep page from URL if present
        })
        .catch(err => {
            console.error('Error fetching products from API:', err);
            shopGrid.innerHTML = '<div style="grid-column: 1/-1; text-align: center; padding: 4rem; color: var(--text-secondary);">Unable to load products. Please check your connection.</div>';
        });

    function formatPrice(price) {
        if (!price && price !== 0) return '';
        return '₹' + Math.round(Number(price) || 0).toLocaleString('en-IN');
    }

    function getPlaceholderImage(cat) {
        if (!cat) cat = '';
        cat = cat.toLowerCase();
        if (cat.includes('ring')) return '/assets/images/placeholders/ring.jpg';
        if (cat.includes('earring')) return '/assets/images/placeholders/earring.jpg';
        if (cat.includes('bangle') || cat.includes('bracelet') || cat.includes('kada')) return '/assets/images/placeholders/bangle.jpg';
        if (cat.includes('necklace') || cat.includes('chain') || cat.includes('choker')) return '/assets/images/placeholders/necklace.jpg';
        if (cat.includes('pendant')) return '/assets/images/placeholders/pendant.jpg';
        return '/assets/images/placeholders/default.jpg';
    }

    function renderProducts(products, totalCount) {
        if (productCount) {
            productCount.textContent = totalCount;
        }
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
                ? `${formatPrice(product.price)} <span style="text-decoration: line-through; color: var(--text-secondary); font-size: 0.9em;">${formatPrice(product.salePrice)}</span>` 
                : `${formatPrice(product.price)}`;
                
            let starsHtml = '';
            for (let i = 1; i <= 5; i++) {
                if (i <= (product.rating || 5)) {
                    starsHtml += '<i class="ph-fill ph-star"></i>';
                } else {
                    starsHtml += '<i class="ph ph-star"></i>';
                }
            }

            const placeholder = getPlaceholderImage(product.category);
            const imgSrc = product.image ? (product.image.startsWith('/') || product.image.startsWith('http') ? product.image : '/' + product.image) : placeholder;
            const hasSecondary = (product.images && product.images.length > 1);
            const secondarySrc = hasSecondary ? (product.images[1].startsWith('/') || product.images[1].startsWith('http') ? product.images[1] : '/' + product.images[1]) : '';

            const productLink = product.slug ? `/product/${product.slug}` : `/product-details?id=${product.id}`;

            const card = `
                <div class="product-card" data-id="${product.id}">
                    <div class="product-image-wrap ${hasSecondary ? 'has-secondary-image' : ''}">
                        ${badges}
                        <button class="wishlist-btn" onclick="toggleWishlist(${product.id})" aria-label="Add to Wishlist">
                            <i class="ph ph-heart"></i>
                        </button>
                        <a href="${productLink}">
                            <img src="${imgSrc}" alt="${product.name}" class="product-image primary-img" onerror="this.onerror=null; this.src='${placeholder}';">
                            ${hasSecondary ? `<img src="${secondarySrc}" alt="${product.name}" class="product-image secondary-img" onerror="this.onerror=null; this.src='${placeholder}';">` : ''}
                        </a>
                        <div class="product-actions">
                            <button class="btn add-to-cart-btn" onclick="addToCart(${product.id}, 1, this)">Add to Bag</button>
                        </div>
                    </div>
                    <div class="product-info">
                        <div class="product-category">${product.category || ''}</div>
                        <h3 class="product-title"><a href="${productLink}">${product.name}</a></h3>
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

    // 6. Pagination UI & Navigation
    function renderPagination(totalPages, page) {
        const paginationContainer = document.getElementById('shop-pagination');
        if (!paginationContainer) return;

        if (totalPages <= 1) {
            paginationContainer.innerHTML = '';
            paginationContainer.style.display = 'none';
            return;
        }

        paginationContainer.style.display = 'flex';
        let html = '';

        // Previous button
        html += `
            <button type="button" class="page-btn prev-btn" ${page <= 1 ? 'disabled' : ''} aria-label="Previous Page">
                &larr; Prev
            </button>
        `;

        // Calculate page numbers
        const maxVisible = 5;
        let startPage = Math.max(1, page - 2);
        let endPage = Math.min(totalPages, startPage + maxVisible - 1);
        if (endPage - startPage < maxVisible - 1) {
            startPage = Math.max(1, endPage - maxVisible + 1);
        }

        if (startPage > 1) {
            html += `<button type="button" class="page-btn num-btn" data-page="1">1</button>`;
            if (startPage > 2) {
                html += `<span class="page-btn ellipsis">&hellip;</span>`;
            }
        }

        for (let i = startPage; i <= endPage; i++) {
            html += `
                <button type="button" class="page-btn num-btn ${page === i ? 'active' : ''}" data-page="${i}">
                    ${i}
                </button>
            `;
        }

        if (endPage < totalPages) {
            if (endPage < totalPages - 1) {
                html += `<span class="page-btn ellipsis">&hellip;</span>`;
            }
            html += `<button type="button" class="page-btn num-btn" data-page="${totalPages}">${totalPages}</button>`;
        }

        // Next button
        html += `
            <button type="button" class="page-btn next-btn" ${page >= totalPages ? 'disabled' : ''} aria-label="Next Page">
                Next &rarr;
            </button>
        `;

        paginationContainer.innerHTML = html;

        // Button Click Listeners
        paginationContainer.querySelectorAll('.page-btn[data-page]').forEach(btn => {
            btn.addEventListener('click', (e) => {
                const targetPage = parseInt(e.currentTarget.dataset.page, 10);
                if (targetPage !== currentPage) {
                    goToPage(targetPage);
                }
            });
        });

        const prevBtn = paginationContainer.querySelector('.prev-btn');
        if (prevBtn && page > 1) {
            prevBtn.addEventListener('click', () => goToPage(page - 1));
        }

        const nextBtn = paginationContainer.querySelector('.next-btn');
        if (nextBtn && page < totalPages) {
            nextBtn.addEventListener('click', () => goToPage(page + 1));
        }
    }

    function goToPage(targetPage) {
        currentPage = targetPage;
        applyFilters(false); // do not reset page
        updateUrlState();
        const grid = document.getElementById('shop-product-grid');
        if (grid) {
            const topOffset = grid.getBoundingClientRect().top + window.pageYOffset - 140;
            window.scrollTo({ top: Math.max(0, topOffset), behavior: 'smooth' });
        }
    }

    // 7. Main Filter, Slicing & Matching Logic
    function applyFilters(resetPage = true) {
        if (resetPage) {
            currentPage = 1;
        }

        let filtered = allProducts.filter(p => !p.is_sold_out && p.availability !== 'sold_out' && p.availability !== 'out_of_stock');
        const urlParams = new URLSearchParams(window.location.search);

        // A. Search Query Match
        const searchQuery = urlParams.get('q');
        if (searchQuery) {
            const tokens = searchQuery.toLowerCase().trim().split(/\s+/).filter(Boolean);
            filtered = filtered.filter(p => {
                const stones = Array.isArray(p.stones) ? p.stones.map(s => s.stone_name || '').join(' ') : '';
                const hay = `${p.name || ''} ${p.code || ''} ${p.category || ''} ${p.categoryId || ''} ${p.metal || ''} ${p.material || ''} ${p.stone || ''} ${stones} ${p.purity || ''} ${p.collection || ''} ${p.occasion || ''} ${p.gender || ''} ${p.description || ''}`.toLowerCase();
                return tokens.every(tok => hay.includes(tok));
            });
        }

        // B. Base Category Filter (from route /category/{slug} or /slug)
        if (currentCategory !== 'all') {
            const normBase = normalizeCategory(currentCategory);
            if (normBase === 'men') {
                filtered = filtered.filter(p => (p.gender || '').toLowerCase() === 'him' || (p.gender || '').toLowerCase() === 'men');
            } else if (normBase === 'women') {
                filtered = filtered.filter(p => (p.gender || '').toLowerCase() !== 'him' && (p.gender || '').toLowerCase() !== 'men');
            } else {
                filtered = filtered.filter(p => {
                    const pCat = normalizeCategory(p.categoryId || p.category);
                    return pCat === normBase || pCat.includes(normBase) || normBase.includes(pCat);
                });
            }
        }

        // C. Category Dropdown Checkboxes (on /shop)
        const checkedCategories = Array.from(document.querySelectorAll('.cat-filter-cb[data-filter="category"]:checked')).map(cb => normalizeCategory(cb.value));
        if (checkedCategories.length > 0) {
            filtered = filtered.filter(p => {
                const pCat = normalizeCategory(p.categoryId || p.category);
                return checkedCategories.some(target => pCat === target || pCat.includes(target) || target.includes(pCat));
            });
        }

        // D. Metal / Type Checkboxes
        const checkedTypes = Array.from(document.querySelectorAll('.cat-filter-cb[data-filter="type"]:checked')).map(cb => cb.value.toLowerCase());
        if (checkedTypes.length > 0) {
            filtered = filtered.filter(p => {
                const material = (p.material || p.metal || '').toLowerCase();
                const stone = (p.stone || '').toLowerCase();
                const stones = Array.isArray(p.stones) ? p.stones.map(s => (s.stone_name || '').toLowerCase()).join(' ') : '';
                return checkedTypes.some(type => {
                    if (type === 'diamond') return stone.includes('diamond') || stones.includes('diamond') || (p.name || '').toLowerCase().includes('solitaire') || (p.name || '').toLowerCase().includes('diamond');
                    if (type === 'gold') return material.includes('gold') || (p.purity && Number(p.purity) > 0);
                    if (type === 'platinum') return material.includes('platinum');
                    return false;
                });
            });
        }

        // E. Price Range Checkboxes
        const checkedPrices = Array.from(document.querySelectorAll('.cat-filter-cb[data-filter="price"]:checked')).map(cb => cb.value);
        if (checkedPrices.length > 0) {
            filtered = filtered.filter(p => {
                const price = Number(p.price) || 0;
                return checkedPrices.some(range => {
                    const [min, max] = range.split('-').map(Number);
                    return price >= min && price <= max;
                });
            });
        }

        // F. Collection Checkboxes & Route Collection
        const checkedCollections = Array.from(document.querySelectorAll('.cat-filter-cb[data-filter="collection"]:checked')).map(cb => cb.value.toLowerCase().trim());
        if (currentCollection && !checkedCollections.includes(currentCollection)) {
            checkedCollections.push(currentCollection);
        }

        if (checkedCollections.length > 0) {
            filtered = filtered.filter(p => {
                const pCol = (p.collection || '').toLowerCase().trim();
                const pOcc = (p.occasion || '').toLowerCase().trim();
                return checkedCollections.some(col => {
                    if (col === 'new') return !!p.isNew;
                    if (col === 'bestsellers') return !!p.isBestseller || (p.rating && p.rating >= 4.5);
                    if (col === 'bridal' || col === 'wedding') {
                        return pCol.includes('bridal') || pCol.includes('wedding') || pOcc.includes('wedding') || pOcc.includes('bridal') || normalizeCategory(p.categoryId) === 'mangalsutra';
                    }
                    if (col === 'everyday') {
                        return pCol.includes('everyday') || pOcc.includes('everyday') || pCol === 'everyday';
                    }
                    if (col === 'festive') {
                        return pCol.includes('festive') || pOcc.includes('festive');
                    }
                    return pCol === col || pCol.includes(col) || col.includes(pCol) || pOcc === col || pOcc.includes(col) || col.includes(pOcc);
                });
            });
        }

        // G. Occasion Checkboxes
        const checkedOccasions = Array.from(document.querySelectorAll('.cat-filter-cb[data-filter="occasion"]:checked')).map(cb => cb.value.toLowerCase());
        if (checkedOccasions.length > 0) {
            filtered = filtered.filter(p => {
                const pOcc = (p.occasion || '').toLowerCase();
                const pCol = (p.collection || '').toLowerCase();
                return checkedOccasions.some(occ => pOcc.includes(occ) || pCol.includes(occ) || occ.includes(pOcc));
            });
        }

        // H. Gender Checkboxes
        const checkedGenders = Array.from(document.querySelectorAll('.cat-filter-cb[data-filter="gender"]:checked')).map(cb => cb.value.toLowerCase());
        if (checkedGenders.length > 0) {
            filtered = filtered.filter(p => {
                const pGen = (p.gender || 'unspecified').toLowerCase();
                return checkedGenders.some(g => {
                    if (g === 'her' || g === 'women') {
                        return pGen === 'her' || pGen === 'women' || pGen === 'unisex' || pGen === 'unspecified';
                    }
                    if (g === 'him' || g === 'men') {
                        return pGen === 'him' || pGen === 'men' || pGen === 'unisex';
                    }
                    return pGen === g || pGen === 'unisex';
                });
            });
        }

        // I. Sorting
        const sortVal = sortSelect ? sortSelect.value : 'recommended';
        if (sortVal === 'price-low' || sortVal === 'Price: Low to High') {
            filtered.sort((a, b) => (Number(a.price) || 0) - (Number(b.price) || 0));
        } else if (sortVal === 'price-high' || sortVal === 'Price: High to Low') {
            filtered.sort((a, b) => (Number(b.price) || 0) - (Number(a.price) || 0));
        } else if (sortVal === 'newest' || sortVal === 'Newest Arrivals') {
            filtered.sort((a, b) => (b.isNew ? 1 : 0) - (a.isNew ? 1 : 0));
        }

        // Total count and page bounds
        totalFilteredCount = filtered.length;
        const totalPages = Math.ceil(totalFilteredCount / ITEMS_PER_PAGE);

        if (currentPage > totalPages && totalPages > 0) {
            currentPage = totalPages;
        }
        if (currentPage < 1) {
            currentPage = 1;
        }

        // Slice products for page (12 per page)
        const startIndex = (currentPage - 1) * ITEMS_PER_PAGE;
        const pagedProducts = filtered.slice(startIndex, startIndex + ITEMS_PER_PAGE);

        renderProducts(pagedProducts, totalFilteredCount);
        renderPagination(totalPages, currentPage);
        renderChips();
    }

    // 8. Render Active Filter Chips
    function renderChips() {
        if (!activeFiltersContainer) return;
        activeFiltersContainer.innerHTML = '';
        const checked = document.querySelectorAll('.cat-filter-cb:checked');
        const urlParams = new URLSearchParams(window.location.search);
        const searchQ = urlParams.get('q');
        
        if (checked.length > 0 || searchQ) {
            const labelSpan = document.createElement('span');
            labelSpan.style.fontSize = '0.85rem';
            labelSpan.style.fontWeight = '600';
            labelSpan.style.color = 'var(--text-primary)';
            labelSpan.style.marginRight = '1rem';
            labelSpan.style.textTransform = 'uppercase';
            labelSpan.textContent = 'FILTERED BY:';
            activeFiltersContainer.appendChild(labelSpan);
        }

        // Active Search Query Chip
        if (searchQ) {
            const searchChip = document.createElement('div');
            searchChip.className = 'filter-chip';
            searchChip.innerHTML = `Search: "${searchQ}" <button type="button" class="clear-search-chip" aria-label="Remove search">&times;</button>`;
            searchChip.querySelector('button').addEventListener('click', () => {
                const searchInput = document.getElementById('header-search-input');
                if (searchInput) searchInput.value = '';
                const searchClear = document.getElementById('header-search-clear');
                if (searchClear) searchClear.style.display = 'none';

                const shopTitle = document.getElementById('shop-page-title');
                if (shopTitle) shopTitle.textContent = 'Shop Jewellery';
                const shopSubtitle = document.getElementById('shop-page-subtitle');
                if (shopSubtitle) shopSubtitle.textContent = 'Discover our exquisite collections crafted for timeless elegance.';

                const currentUrl = new URL(window.location.href);
                currentUrl.searchParams.delete('q');
                window.history.replaceState(null, '', currentUrl.pathname + (currentUrl.search ? currentUrl.search : ''));
                applyFilters(true);
            });
            activeFiltersContainer.appendChild(searchChip);
        }

        checked.forEach(cb => {
            const label = cb.closest('label') ? cb.closest('label').textContent.trim() : cb.value;
            const chip = document.createElement('div');
            chip.className = 'filter-chip';
            chip.innerHTML = `${label} <button type="button" data-val="${cb.value}" data-filter="${cb.dataset.filter}">&times;</button>`;
            
            chip.querySelector('button').addEventListener('click', (e) => {
                const f = e.currentTarget.dataset.filter;
                const v = e.currentTarget.dataset.val;
                const targetCb = document.querySelector(`.cat-filter-cb[data-filter="${f}"][value="${v}"]`);
                if (targetCb) {
                    targetCb.checked = false;
                    applyFilters(true);
                    updateUrlState();
                }
            });
            
            activeFiltersContainer.appendChild(chip);
        });
        
        if (checked.length > 0 || searchQ) {
            const clearAll = document.createElement('button');
            clearAll.type = 'button';
            clearAll.className = 'clear-all-btn';
            clearAll.textContent = 'Clear all';
            clearAll.addEventListener('click', () => {
                filterCheckboxes.forEach(c => c.checked = false);
                const searchInput = document.getElementById('header-search-input');
                if (searchInput) searchInput.value = '';
                const searchClear = document.getElementById('header-search-clear');
                if (searchClear) searchClear.style.display = 'none';

                const shopTitle = document.getElementById('shop-page-title');
                if (shopTitle) shopTitle.textContent = 'Shop Jewellery';
                const shopSubtitle = document.getElementById('shop-page-subtitle');
                if (shopSubtitle) shopSubtitle.textContent = 'Discover our exquisite collections crafted for timeless elegance.';

                const currentUrl = new URL(window.location.href);
                currentUrl.searchParams.delete('q');
                window.history.replaceState(null, '', currentUrl.pathname + (currentUrl.search ? currentUrl.search : ''));

                applyFilters(true);
                updateUrlState();
            });
            activeFiltersContainer.appendChild(clearAll);
        }
    }

    // 9. Event Listeners for Filters & Dropdowns
    filterCheckboxes.forEach(cb => {
        cb.addEventListener('change', () => {
            applyFilters(true); // reset to page 1
            updateUrlState();
        });
    });

    if (sortSelect) {
        sortSelect.addEventListener('change', () => {
            applyFilters(true); // reset to page 1
            updateUrlState();
        });
    }

    // Apply button inside popover
    const applyBtns = document.querySelectorAll('.btn-apply-dropdown');
    applyBtns.forEach(btn => {
        btn.addEventListener('click', (e) => {
            e.stopPropagation();
            applyFilters(true);
            updateUrlState();
            const popover = btn.closest('.filter-popover');
            if (popover) popover.classList.remove('active');
            dropdowns.forEach(d => d.classList.remove('active'));
        });
    });

    // Clear button inside popover
    const clearBtns = document.querySelectorAll('.btn-clear-dropdown');
    clearBtns.forEach(btn => {
        btn.addEventListener('click', (e) => {
            e.stopPropagation();
            const popover = btn.closest('.filter-popover');
            if (popover) {
                const cbs = popover.querySelectorAll('.cat-filter-cb');
                cbs.forEach(cb => cb.checked = false);
                applyFilters(true);
                updateUrlState();
            }
        });
    });

    // Listen for searchCleared event from header search bar
    window.addEventListener('searchCleared', () => {
        const shopTitle = document.getElementById('shop-page-title');
        if (shopTitle) shopTitle.textContent = 'Shop Jewellery';
        const shopSubtitle = document.getElementById('shop-page-subtitle');
        if (shopSubtitle) shopSubtitle.textContent = 'Discover our exquisite collections crafted for timeless elegance.';
        applyFilters(true);
    });
});
