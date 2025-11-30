</main> 
</div> 
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
        document.addEventListener('DOMContentLoaded', function() {
            
            // --- LOGIC ALERT SUKSES/GAGAL ---
            const urlParams = new URLSearchParams(window.location.search);
            const status = urlParams.get('status');

            if (status) {
                let title = '';
                let text = '';
                let icon = '';

                if (status === 'success') {
                    icon = 'success';
                    title = 'Berhasil!';
                    text = 'Data berhasil disimpan.';
                } else if (status === 'deleted') {
                    icon = 'success';
                    title = 'Dihapus!';
                    text = 'Data berhasil dihapus.';
                } else if (status === 'error') {
                    icon = 'error';
                    title = 'Gagal!';
                    text = 'Terjadi kesalahan saat memproses data.';
                }

                if (icon) {
                    Swal.fire({
                        icon: icon,
                        title: title,
                        text: text,
                        timer: 2000,
                        showConfirmButton: false
                    });
                    
                    // Bersihkan URL agar kalau di-refresh alert tidak muncul lagi
                    const newUrl = window.location.protocol + "//" + window.location.host + window.location.pathname;
                    window.history.replaceState({ path: newUrl }, '', newUrl);
                }
            }

            // --- LOGIC KONFIRMASI DELETE GLOBAL ---
            // Kode ini akan mencari SEMUA tombol dengan class 'btn-delete' di halaman mana saja
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