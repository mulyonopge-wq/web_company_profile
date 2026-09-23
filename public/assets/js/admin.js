/**
 * Admin Panel JavaScript
 * PT Solusi Tekno Nusantara
 */

document.addEventListener('DOMContentLoaded', () => {
    initThemeToggle();
    initAdminSidebar();
    initAutoSlug();
    initDeleteConfirmations();
});

/**
 * Responsive Sidebar Toggle
 */
function initAdminSidebar() {
    const toggleBtn = document.querySelector('#sidebarToggleBtn');
    const sidebar = document.querySelector('.admin-sidebar');
    const backdrop = document.querySelector('.sidebar-backdrop');

    if (toggleBtn && sidebar) {
        toggleBtn.addEventListener('click', () => {
            sidebar.classList.toggle('show');
            if (backdrop) backdrop.classList.toggle('show');
        });
    }

    if (backdrop) {
        backdrop.addEventListener('click', () => {
            sidebar.classList.remove('show');
            backdrop.classList.remove('show');
        });
    }
}

/**
 * Auto slug generation from title / name input
 */
function initAutoSlug() {
    const sourceInput = document.querySelector('[data-slug-source]');
    const targetInput = document.querySelector('[data-slug-target]');

    if (sourceInput && targetInput) {
        sourceInput.addEventListener('input', () => {
            // Only auto-generate if user hasn't typed custom slug or if target is empty
            if (!targetInput.dataset.manualEdited) {
                targetInput.value = slugify(sourceInput.value);
            }
        });

        targetInput.addEventListener('input', () => {
            targetInput.dataset.manualEdited = 'true';
        });
    }
}

function slugify(text) {
    return text.toString().toLowerCase().trim()
        .replace(/\s+/g, '-')
        .replace(/[^\w\-]+/g, '')
        .replace(/\-\-+/g, '-')
        .replace(/^-+/, '')
        .replace(/-+$/, '');
}

/**
 * Generic delete confirmation
 */
function initDeleteConfirmations() {
    document.addEventListener('click', (e) => {
        const btn = e.target.closest('[data-confirm-delete]');
        if (btn) {
            const message = btn.dataset.confirmDelete || 'Apakah Anda yakin ingin menghapus data ini? Tindakan ini tidak dapat dibatalkan.';
            if (!confirm(message)) {
                e.preventDefault();
            }
        }
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
        return 'light';
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
}

