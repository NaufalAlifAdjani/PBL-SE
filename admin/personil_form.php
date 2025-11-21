<?php
include 'includes/header_admin.php'; 
include '../includes/db.php';

// Ambil parameter ID dan Tipe
$id = $_GET['id'] ?? null;
$type = $_GET['type'] ?? 'dosen'; // Default tipe dosen jika mau tambah baru

// Konfigurasi Judul & Folder Upload
$page_title = ($type == 'dosen') ? "Form Data Dosen" : "Form Data Mahasiswa";
$upload_dir = __DIR__ . '../uploads_personil';

// --- 1. LOGIKA GET DATA (EDIT) ---
$data = null;
if ($id) {
    if ($type == 'dosen') {
        $query = "SELECT * FROM dosen WHERE id_dosen = $1";
    } else {
        $query = "SELECT * FROM pendaftaran_user WHERE id_pendaftaran_user = $1";
    }
    $result = pg_query_params($conn, $query, [$id]);
    $data = pg_fetch_assoc($result);
}

// --- 2. LOGIKA SIMPAN DATA ---
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_post = $_POST['id'] ?? null;
    $type_post = $_POST['type']; // Ambil tipe dari hidden input
    
    // Field Umum
    $nama = $_POST['nama'];
    $email = $_POST['email'];
    
    // Upload Foto (Hanya Dosen yang punya fitur upload foto di tabel, mahasiswa pakai default/null dulu utk simplifikasi)
    // Jika mahasiswa ingin punya foto, pastikan tabel pendaftaran_user punya kolom foto_profil
    $foto_profil_name = $_POST['foto_lama'] ?? null;
    
  if (isset($_FILES['foto_profil']) && $_FILES['foto_profil']['error'] == 0) {

    // Ambil info file
    $file_tmp = $_FILES['foto_profil']['tmp_name'];
    $file_ext = strtolower(pathinfo($_FILES['foto_profil']['name'], PATHINFO_EXTENSION));

    // Nama file baru
    $new_file_name = uniqid('p_', true) . '.' . $file_ext;

    // Simpan file
    if (move_uploaded_file($file_tmp, $upload_dir . '/' . $new_file_name)) {
        $foto_profil_name = $new_file_name;
    } else {
        echo "<div class='alert alert-danger'>Gagal upload file!</div>";
    }
}

    if ($type_post == 'dosen') {
        // === SIMPAN DOSEN ===
        $nip = $_POST['nip'];
        $nidn = $_POST['nidn'];
        $bid_kemahiran = $_POST['bid_kemahiran'];
        $mk = $_POST['mata_kuliah_diampu'];

        if ($id_post) { // Update
            $q = "UPDATE dosen SET nama_dosen=$1, nip=$2, nidn=$3, email_dosen=$4, bid_kemahiran=$5, mata_kuliah=$6, foto_profil=$7 WHERE id_dosen=$8";
            $res = pg_query_params($conn, $q, [$nama, $nip, $nidn, $email, $bid_kemahiran, $mk, $foto_profil_name, $id_post]);
        } else { // Insert
            $q = "INSERT INTO dosen (nama_dosen, nip, nidn, email_dosen, bid_kemahiran, mata_kuliah, foto_profil) VALUES ($1, $2, $3, $4, $5, $6, $7)";
            $res = pg_query_params($conn, $q, [$nama, $nip, $nidn, $email, $bid_kemahiran, $mk, $foto_profil_name]);
        }

    } else {
        // === SIMPAN MAHASISWA ===
        $jurusan = $_POST['jurusan'];
        $angkatan = $_POST['angkatan'];
        $portofolio = $_POST['portofolio'];

        if ($id_post) { // Update
            // Catatan: Tabel pendaftaran_user di ERD tidak ada kolom foto, jadi kita skip update foto utk mahasiswa
            $q = "UPDATE pendaftaran_user SET nama_pendaftar=$1, email=$2, jurusan=$3, angkatan=$4, portofolio=$5 WHERE id_pendaftaran_user=$6";
            $res = pg_query_params($conn, $q, [$nama, $email, $jurusan, $angkatan, $portofolio, $id_post]);
        } else {
            // Insert Mahasiswa Manual (Status lgsg Diterima)
            $q = "INSERT INTO pendaftaran_user (nama_pendaftar, email, jurusan, angkatan, portofolio, status) VALUES ($1, $2, $3, $4, $5, 'Diterima')";
            $res = pg_query_params($conn, $q, [$nama, $email, $jurusan, $angkatan, $portofolio]);
        }
    }

    if ($res) {
        header("Location: manage_personil.php?status=success");
        exit;
    } else {
        echo "<div class='alert alert-danger'>Error: " . pg_last_error($conn) . "</div>";
    }
}
?>

<h1 class="fw-bold"><?php echo $page_title; ?></h1>
<a href="manage_personil.php" class="btn btn-outline-secondary mb-3">&laquo; Kembali</a>

<div class="card card-admin">
    <div class="card-body p-4">
        <form action="personil_form.php" method="POST" enctype="multipart/form-data">
            
            <input type="hidden" name="id" value="<?php echo ($type == 'dosen') ? ($data['id_dosen']??'') : ($data['id_pendaftaran_user']??''); ?>">
            <input type="hidden" name="type" value="<?php echo $type; ?>">
            <input type="hidden" name="foto_lama" value="<?php echo $data['foto_profil'] ?? ''; ?>">
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-semibold">Nama Lengkap</label>
                    <input type="text" class="form-control" name="nama" value="<?php echo htmlspecialchars(($type=='dosen') ? ($data['nama_dosen']??'') : ($data['nama_pendaftar']??'')); ?>" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-semibold">Email</label>
                    <input type="email" class="form-control" name="email" value="<?php echo htmlspecialchars(($type=='dosen') ? ($data['email_dosen']??'') : ($data['email']??'')); ?>">
                </div>
            </div>

            <?php if ($type == 'dosen'): ?>
            <div class="p-3 bg-light rounded mb-3">
                <h6 class="fw-bold text-primary">Informasi Akademik Dosen</h6>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">NIP</label>
                        <input type="text" class="form-control" name="nip" value="<?php echo htmlspecialchars($data['nip'] ?? ''); ?>">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">NIDN</label>
                        <input type="text" class="form-control" name="nidn" value="<?php echo htmlspecialchars($data['nidn'] ?? ''); ?>">
                    </div>
                    <div class="col-md-12 mb-3">
                        <label class="form-label">Posisi</label>
                        <input type="text" class="form-control" name="bid_kemahiran" value="<?php echo htmlspecialchars($data['bid_kemahiran'] ?? ''); ?>" placeholder="Cth: Lab Director">
                    </div>
                    <div class="col-md-12 mb-3">
                        <label class="form-label">Mata Kuliah Diampu</label>
                        <textarea class="form-control" name="mata_kuliah_diampu" rows="2"><?php echo htmlspecialchars($data['mata_kuliah_diampu'] ?? ''); ?></textarea>
                    </div>
                    <div class="col-md-12 mb-3">
                        <label class="form-label">Foto Profil</label>
                        <input type="file" class="form-control" name="foto_profil">

                          <?php if(!empty($data['foto_profil'])): ?>
                            <img src="../uploads_personil/<?php echo $data['foto_profil']; ?>" 
                                alt="Foto Profil" 
                                style="max-width:150px; margin-top:10px;">
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <?php if ($type == 'mahasiswa'): ?>
            <div class="p-3 bg-light rounded mb-3">
                <h6 class="fw-bold text-success">Informasi Akademik Mahasiswa</h6>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Jurusan</label>
                        <input type="text" class="form-control" name="jurusan" value="<?php echo htmlspecialchars($data['jurusan'] ?? ''); ?>">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Angkatan</label>
                        <input type="number" class="form-control" name="angkatan" value="<?php echo htmlspecialchars($data['angkatan'] ?? ''); ?>">
                    </div>
                    <div class="col-md-12 mb-3">
                        <label class="form-label">Link Portofolio</label>
                        <input type="text" class="form-control" name="portofolio" value="<?php echo htmlspecialchars($data['portofolio'] ?? ''); ?>">
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <button type="submit" class="btn btn-primary-admin">Simpan Data</button>
        </form>
    </div>
</div>

<?php include 'includes/footer_admin.php'; ?>