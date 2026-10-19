/* =============================================
   POPUP_TOLAK.JS
   Logika Interaksi Popup Tolak Penayangan
   ============================================= */

document.addEventListener('DOMContentLoaded', function () {
    const overlay = document.querySelector('.modal-overlay');

    // Tutup modal jika mengklik area gelap di luar kotak modal
    if (overlay) {
        overlay.addEventListener('click', function (e) {
            if (e.target === this) {
                window.location.href = 'detail_barang.html';
            }
        });
    }

    // Tutup modal jika menekan tombol Escape (ESC) pada keyboard
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
            window.location.href = 'detail_barang.html';
        }
    });
});
