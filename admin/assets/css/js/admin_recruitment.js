// File: assets/js/admin_recruitment.js

document.addEventListener("DOMContentLoaded", function() {
    
    // 1. Script Bersihkan URL (Hapus ?status=error... saat refresh)
    if (window.history.replaceState) {
        if (window.location.search.length > 0) {
            var cleanUrl = window.location.protocol + "//" + window.location.host + window.location.pathname;
            window.history.replaceState(null, null, cleanUrl);
        }
    }

    // 2. Inisialisasi Tooltip Bootstrap (Jika pakai tooltip hover)
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    });

});

// 3. Fungsi Konfirmasi Delete (Opsional, biar HTML lebih bersih)
function confirmAction(message) {
    return confirm(message);
}