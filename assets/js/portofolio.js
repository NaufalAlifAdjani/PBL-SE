/* File: assets/js/portofolio.js */

function filterPortofolio(kategori, element) {
    event.preventDefault();

    // 1. Reset Sidebar Style
    const allLinks = document.querySelectorAll('.custom-sidebar .list-group-item');
    allLinks.forEach(link => {
        link.classList.remove('active-item');
        const badge = link.querySelector('.badge');
        if(badge) {
            badge.classList.remove('bg-light', 'text-dark');
            badge.classList.add('bg-light', 'text-dark');
        }
    });

    // 2. Set Active Style pada tombol yang diklik
    element.classList.add('active-item');
    const activeBadge = element.querySelector('.badge');
    if(activeBadge) {
        activeBadge.classList.remove('bg-light', 'text-dark');
        activeBadge.classList.add('bg-white', 'text-primary'); 
    }

    // 3. Update Judul Halaman
    const judul = document.getElementById('judul-kategori');
    if (kategori === 'all') judul.innerText = 'Semua Data';
    else if (kategori === 'publikasi') judul.innerText = 'Publikasi Ilmiah';
    else if (kategori === 'produk') judul.innerText = 'Produk Inovasi';
    else if (kategori === 'penelitian') judul.innerText = 'Penelitian';
    else if (kategori === 'pengabdian') judul.innerText = 'Pengabdian Masyarakat';

    // 4. Logic Show/Hide Kartu
    const items = document.querySelectorAll('.portfolio-item');
    items.forEach(item => {
        if (kategori === 'all' || item.getAttribute('data-category') === kategori) {
            item.classList.remove('d-none');
            // Efek animasi fade-in sederhana
            item.style.opacity = '0';
            setTimeout(() => item.style.opacity = '1', 50);
        } else {
            item.classList.add('d-none');
        }
    });
}