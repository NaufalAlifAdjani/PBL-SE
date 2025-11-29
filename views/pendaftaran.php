<?php 
// Perbaiki path include karena sekarang file ini ada di dalam folder views
include '../includes/header.php'; 
?>

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            
            <div class="text-center mb-4">
                <h2 class="fw-bold">Form Pendaftaran Anggota</h2>
                <p class="text-muted">Bergabunglah dengan komunitas SE Geeks</p>
            </div>

            <?php if (isset($_GET['status'])): ?>
                <?php if ($_GET['status'] == 'success'): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <strong>Berhasil!</strong> Data Anda sedang direview oleh admin.
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php elseif ($_GET['status'] == 'error'): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Gagal!</strong> <?php echo isset($_GET['msg']) ? htmlspecialchars($_GET['msg']) : 'Terjadi kesalahan.'; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
            <div class="card shadow-sm border-0">
                <div class="card-body p-4">
                    
                    <form action="../controllers/proses_pendaftaran.php" method="POST">
                        
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Nama Lengkap</label>
                            <input type="text" class="form-control" name="nama" placeholder="Masukkan nama lengkap" required>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">NIM</label>
                                <input type="text" class="form-control" name="nim" placeholder="Nomor Induk Mahasiswa" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Email</label>
                                <input type="email" class="form-control" name="email" placeholder="email@student.com" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Jurusan</label>
                                <select class="form-select" name="jurusan" required>
                                    <option value="">Pilih Jurusan</option>
                                    <option value="Sistem Informasi">Sistem Informasi</option>
                                    <option value="Teknik Informatika">Teknik Informatika</option>
                                    <option value="Rekayasa Perangkat Lunak">Rekayasa Perangkat Lunak</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Angkatan</label>
                                <input type="number" class="form-control" name="angkatan" placeholder="Contoh: 2023" required>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold">Link Portofolio (Github/Behance/Drive)</label>
                            <input type="url" class="form-control" name="portofolio" placeholder="https://..." required>
                            <small class="text-muted">Sertakan link hasil karyamu.</small>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-login btn-lg">Kirim Pendaftaran</button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>