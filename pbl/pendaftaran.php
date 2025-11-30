<?php
include 'includes/header.php';

$message = '';

// Proses Penyimpanan Data
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama = $_POST['nama_pendaftar'];
    $email = $_POST['email'];
    $jurusan = $_POST['jurusan'];
    $angkatan = $_POST['angkatan'];
    $portofolio = $_POST['portofolio'];

    // Insert data dengan status default 'Pending'
    $query_insert = "INSERT INTO pendaftaran_user (nama_pendaftar, email, jurusan, angkatan, portofolio, status) 
                     VALUES ($1, $2, $3, $4, $5, 'Pending')";
    
    $result = pg_query_params($conn, $query_insert, [$nama, $email, $jurusan, $angkatan, $portofolio]);

    if ($result) {
        $message = '<div class="alert alert-success">Pendaftaran berhasil! Data Anda sedang direview oleh admin.</div>';
    } else {
        $message = '<div class="alert alert-danger">Gagal mendaftar: ' . pg_last_error($conn) . '</div>';
    }
}
?>

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            
            <div class="text-center mb-4">
                <h1 class="fw-bold">Form Pendaftaran SE Geeks</h1>
                <p class="text-muted">Bergabunglah dengan kami untuk riset dan pengembangan.</p>
            </div>

            <?php echo $message; ?>

            <div class="card shadow border-0">
                <div class="card-body p-5">
                    <form action="pendaftaran.php" method="POST">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Nama Lengkap</label>
                            <input type="text" class="form-control" name="nama_pendaftar" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Email</label>
                            <input type="email" class="form-control" name="email" required>
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

<?php include 'includes/footer.php'; ?>