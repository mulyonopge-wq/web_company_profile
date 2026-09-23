/**
 * Front-end Main JavaScript
 * PT Solusi Tekno Nusantara
 */

document.addEventListener('DOMContentLoaded', () => {
    initThemeToggle();
    initLiveSearch();
    initAjaxAddToCart();
    initCartQuantityUpdater();
});

/**
 * Live search dropdown with debouncing
 */
function initLiveSearch() {
    const searchInput = document.querySelector('#header-search-input');
    const searchDropdown = document.querySelector('#header-search-dropdown');

    if (!searchInput || !searchDropdown) return;

    let debounceTimer;

    searchInput.addEventListener('input', (e) => {
        const query = e.target.value.trim();
        clearTimeout(debounceTimer);

        if (query.length < 2) {
            searchDropdown.style.display = 'none';
            searchDropdown.innerHTML = '';
            return;
        }

        debounceTimer = setTimeout(async () => {
            try {
                const response = await fetch(`${window.APP_URL || ''}/api/products/search?q=${encodeURIComponent(query)}`, {
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                });
                const data = await response.json();

                if (data.results && data.results.length > 0) {
                    let html = '';
                    data.results.forEach(item => {
                        html += `
                            <a href="${item.url}" class="search-item">
                                <img src="${item.image}" alt="${item.name}">
                                <div>
                                    <div class="fw-semibold text-truncate" style="max-width: 300px;">${item.name}</div>
                                    <small class="text-muted">${item.category} &bull; SKU: ${item.sku}</small>
                                    <div class="text-danger fw-bold fs-7">${item.price_formatted}</div>
                                </div>
                            </a>
                        `;
                    });
                    searchDropdown.innerHTML = html;
                    searchDropdown.style.display = 'block';
                } else {
                    searchDropdown.innerHTML = `<div class="p-3 text-muted text-center">Tidak ada produk yang cocok dengan "${query}"</div>`;
                    searchDropdown.style.display = 'block';
                }
            } catch (err) {
                console.error('Error searching products:', err);
            }
        }, 300);
    });

    // Hide dropdown when clicking outside
    document.addEventListener('click', (e) => {
        if (!searchInput.contains(e.target) && !searchDropdown.contains(e.target)) {
            searchDropdown.style.display = 'none';
        }
    });
}

/**
 * Intercept Add-to-Cart with AJAX & show Toast
 */
function initAjaxAddToCart() {
    document.addEventListener('submit', async (e) => {
        const form = e.target.closest('.ajax-add-to-cart');
        if (!form) return;

        e.preventDefault();
        const submitBtn = form.querySelector('button[type="submit"]');
        const originalText = submitBtn ? submitBtn.innerHTML : '';
        if (submitBtn) {
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status"></span> Menambahkan...';
        }

        const formData = new FormData(form);

        try {
            const response = await fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            });
            const data = await response.json();

            if (data.success) {
                // Update badge count
                document.querySelectorAll('.cart-badge-count').forEach(el => {
                    el.textContent = data.cart_count;
                    el.style.display = data.cart_count > 0 ? 'inline-block' : 'none';
                });

                showToast(data.message || 'Produk ditambahkan ke keranjang!', 'success');
            } else {
                showToast(data.message || 'Gagal menambahkan produk.', 'warning');
            }
        } catch (error) {
            console.error('Cart add error:', error);
            showToast('Terjadi kesalahan saat menambahkan ke keranjang.', 'danger');
        } finally {
            if (submitBtn) {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
            }
        }
    });
}

/**
 * Live update quantity on Cart page
 */
function initCartQuantityUpdater() {
    document.querySelectorAll('.cart-qty-input').forEach(input => {
        input.addEventListener('change', async () => {
            const productId = input.dataset.productId;
            const quantity = parseInt(input.value) || 1;
            const updateUrl = input.dataset.updateUrl;

            const formData = new FormData();
            formData.append('product_id', productId);
            formData.append('quantity', quantity);

            try {
                const response = await fetch(updateUrl, {
                    method: 'POST',
                    body: formData,
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                });
                const data = await response.json();

                if (data.success) {
                    // Update item subtotal
                    const itemSubtotalEl = document.querySelector(`#subtotal-${productId}`);
                    if (itemSubtotalEl) {
                        itemSubtotalEl.textContent = data.item_subtotal_formatted;
                    }

                    // Update grand total
                    const grandTotalEl = document.querySelector('#cart-grand-total');
                    if (grandTotalEl) {
                        grandTotalEl.textContent = data.subtotal_formatted;
                    }

                    // Update navbar badge
                    document.querySelectorAll('.cart-badge-count').forEach(el => {
                        el.textContent = data.cart_count;
                    });
                }
            } catch (err) {
                console.error('Update qty error:', err);
            }
        });
    });
}

/**
 * Dynamic Bootstrap Toast
 */
function showToast(message, type = 'success') {
    let container = document.querySelector('.toast-container');
    if (!container) {
        container = document.createElement('div');
        container.className = 'toast-container position-fixed bottom-0 end-0 p-3';
        document.body.appendChild(container);
    }

    const toastId = 'toast-' + Date.now();
    const bgClass = type === 'success' ? 'bg-success text-white' : (type === 'danger' ? 'bg-danger text-white' : 'bg-warning text-dark');

    const toastHtml = `
        <div id="${toastId}" class="toast align-items-center ${bgClass} border-0 shadow" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body d-flex align-items-center gap-2">
                    <i class="bi bi-check-circle-fill"></i>
                    <div>${message}</div>
                </div>
                <button type="button" class="btn-close ${type !== 'warning' ? 'btn-close-white' : ''} me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    `;

    container.insertAdjacentHTML('beforeend', toastHtml);
    const toastElement = document.getElementById(toastId);
    const bsToast = new bootstrap.Toast(toastElement, { delay: 3500 });
    bsToast.show();

    toastElement.addEventListener('hidden.bs.toast', () => {
        toastElement.remove();
    });
}

/**
 * Dark / Light Theme Mode Switcher
 */
function initThemeToggle() {
    const getStoredTheme = () => localStorage.getItem('theme');
    const setStoredTheme = theme => localStorage.setItem('theme', theme);

    const getPreferredTheme = () => {
        const storedTheme = getStoredTheme();
        if (storedTheme) {
            return storedTheme;
        }
        return window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
    };

    const updateToggleIcons = (theme) => {
        document.querySelectorAll('.theme-toggle-btn').forEach(btn => {
            const moonIcon = btn.querySelector('.theme-icon-moon');
            const sunIcon = btn.querySelector('.theme-icon-sun');
            if (moonIcon && sunIcon) {
                if (theme === 'dark') {
                    moonIcon.classList.add('d-none');
                    sunIcon.classList.remove('d-none');
                    btn.setAttribute('aria-label', 'Ganti ke Mode Terang');
                    btn.setAttribute('title', 'Ganti ke Mode Terang');
                } else {
                    moonIcon.classList.remove('d-none');
                    sunIcon.classList.add('d-none');
                    btn.setAttribute('aria-label', 'Ganti ke Mode Gelap');
                    btn.setAttribute('title', 'Ganti ke Mode Gelap');
                }
            }
        });
    };

    const setTheme = (theme) => {
        document.documentElement.setAttribute('data-bs-theme', theme);
        updateToggleIcons(theme);
    };

    const activeTheme = document.documentElement.getAttribute('data-bs-theme') || getPreferredTheme();
    setTheme(activeTheme);

    document.querySelectorAll('.theme-toggle-btn').forEach(btn => {
        btn.addEventListener('click', (e) => {
            e.preventDefault();
            const currentTheme = document.documentElement.getAttribute('data-bs-theme') || 'light';
            const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
            setStoredTheme(newTheme);
            setTheme(newTheme);
        });
    });

    if (window.matchMedia) {
        window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', () => {
            const storedTheme = getStoredTheme();
            if (!storedTheme) {
                setTheme(getPreferredTheme());
            }
        });
    }
}
