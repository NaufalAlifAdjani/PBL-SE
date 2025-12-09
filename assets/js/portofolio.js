function filterPortofolio(kategori, element) {
    // Mencegah perilaku default jika element adalah link (a href="#")
    // Tapi karena kita pakai onclick di element, preventDefault opsional tergantung struktur
    if (element.tagName === 'A') {
        event.preventDefault(); 
    }

    // ---------------------------------------------------------
    // 1. BAGIAN VISUAL (Memindahkan Warna Aktif)
    // ---------------------------------------------------------

    // A. JIKA YANG DIKLIK ADALAH LINK SIDEBAR (DESKTOP)
    if (element.tagName === 'A') {
        // Cari semua link di dalam sidebar
        const sidebarLinks = document.querySelectorAll('.custom-sidebar a');
        
        // Reset style semua link
        sidebarLinks.forEach(link => {
            link.classList.remove('active-item', 'bg-primary', 'text-white');
            link.classList.add('text-dark');
            
            // Reset badge
            const badge = link.querySelector('.badge');
            if(badge) {
                badge.classList.remove('bg-light', 'text-dark');
                badge.classList.add('bg-secondary', 'text-white');
            }
        });

        // Set style aktif ke link yang diklik
        element.classList.remove('text-dark');
        element.classList.add('active-item'); 
        
        // Ubah warna badge aktif
        const activeBadge = element.querySelector('.badge');
        if(activeBadge) {
            activeBadge.classList.remove('bg-secondary', 'text-white');
            activeBadge.classList.add('bg-light', 'text-dark');
        }
    } 
    
    // B. JIKA YANG DIKLIK ADALAH TOMBOL (MOBILE)
    else if (element.tagName === 'BUTTON') {
        // Reset semua tombol mobile
        const mobileButtons = document.querySelectorAll('.d-lg-none button');
        mobileButtons.forEach(btn => {
            btn.classList.remove('btn-outline-primary', 'active-mobile-filter');
            btn.classList.add('btn-outline-secondary');
        });

        // Aktifkan tombol yang diklik
        element.classList.remove('btn-outline-secondary');
        element.classList.add('btn-outline-primary', 'active-mobile-filter');
    }

    // ---------------------------------------------------------
    // 2. BAGIAN FILTER DATA (Menampilkan/Menyembunyikan Card)
    // ---------------------------------------------------------
    
    // Update Judul Halaman
    const judul = document.getElementById('judul-kategori');
    if(judul) {
        const textMap = {
            'all': 'Semua Data',
            'publikasi': 'Publikasi',
            'produk': 'Produk Inovasi',
            'penelitian': 'Penelitian',
            'pengabdian': 'Pengabdian'
        };
        judul.innerText = textMap[kategori] || 'Data Portofolio';
    }

    // Filter Card Items
    const items = document.querySelectorAll('.portfolio-item');
    items.forEach(item => {
        const itemCat = item.getAttribute('data-category');
        
        // Logika Show/Hide
        if (kategori === 'all' || itemCat === kategori) {
            item.classList.remove('d-none');
            
            // Animasi Fade In
            item.style.opacity = '0';
            setTimeout(() => item.style.opacity = '1', 50);
        } else {
            item.classList.add('d-none');
        }
    });
}