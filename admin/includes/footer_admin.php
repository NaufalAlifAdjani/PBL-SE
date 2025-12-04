</main> 
</div> 

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        
        // --- LOGIC ALERT SUKSES/GAGAL (Setelah Redirect) ---
        const urlParams = new URLSearchParams(window.location.search);
        const status = urlParams.get('status');
        const msg = urlParams.get('msg');

        if (status) {
            let title = '';
            let text = '';
            let icon = '';

            // 1. STATUS CREATED (BARU TAMBAH)
            if (status === 'created') {
                icon = 'success';
                title = 'Berhasil Ditambahkan!';
                text = 'Halaman baru telah berhasil disimpan.';
            
            // 2. STATUS UPDATED (EDIT DATA)
            } else if (status === 'updated') {
                icon = 'success';
                title = 'Berhasil Diupdate!';
                text = 'Perubahan data telah berhasil disimpan.';

            // 3. STATUS DELETED (HAPUS)
            } else if (status === 'deleted') {
                icon = 'success';
                title = 'Berhasil Dihapus!';
                text = 'Data telah dihapus dari sistem.';

            // 4. STATUS ERROR
            } else if (status === 'error') {
                icon = 'error';
                title = 'Gagal!';
                text = msg || 'Terjadi kesalahan saat memproses data.';
            
            // 5. STATUS GENERIC (Cadangan jika pakai ?status=success)
            } else if (status === 'success') {
                icon = 'success';
                title = 'Berhasil!';
                text = msg || 'Aksi berhasil dilakukan.';
            }

            if (icon) {
                Swal.fire({
                    icon: icon,
                    title: title,
                    text: text,
                    showConfirmButton: false, // Hilangkan tombol OK biar estetik
                    timer: 2000 // Otomatis hilang dalam 2 detik
                });
                
                // Bersihkan URL (Hapus ?status=... agar bersih)
                const newUrl = window.location.protocol + "//" + window.location.host + window.location.pathname;
                window.history.replaceState({ path: newUrl }, '', newUrl);
            }
        }

        // --- LOGIC KONFIRMASI DELETE GLOBAL ---
        // Mencari tombol dengan class 'btn-delete' (pastikan tombol hapusmu punya class ini)
        const deleteButtons = document.querySelectorAll('.btn-delete');
        
        deleteButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault(); 
                const href = this.getAttribute('href'); 
                
                Swal.fire({
                    title: 'Yakin ingin menghapus?',
                    text: "Data yang dihapus tidak bisa dikembalikan!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#dc3545',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = href;
                    }
                });
            });
        });

    });
</script>
</body>
</html>