import './bootstrap';

import * as Turbo from '@hotwired/turbo';
import Alpine from 'alpinejs';
import 'instant.page';

window.Alpine = Alpine;
window.Turbo = Turbo;

// Bersihkan state modal/dropdown sebelum Turbo menyimpan snapshot cache
document.addEventListener('turbo:before-cache', () => {
    document.querySelectorAll('[x-data]').forEach((el) => {
        if (el._x_dataStack && el._x_dataStack[0]) {
            const data = el._x_dataStack[0];
            if (typeof data.open !== 'undefined') data.open = false;
            if (typeof data.mobileMenuOpen !== 'undefined') data.mobileMenuOpen = false;
            if (typeof data.userDropdownOpen !== 'undefined') data.userDropdownOpen = false;
            if (typeof data.showWaModal !== 'undefined') data.showWaModal = false;
        }
    });
});

// Update pelacakan Microsoft Clarity di setiap pergantian halaman SPA
document.addEventListener('turbo:load', () => {
    if (window.clarity) {
        window.clarity('set', 'page', window.location.pathname);
    }
});

Alpine.start();
