<?php
include 'includes/header_admin.php';
include '../includes/db.php';

$judul = '';
$isi_konten = '';
$status_artikel = 'Draft';
$gambar_sekarang = '';
$mode = 'create';
$id_artikel = 0;

// edit
if (isset($_GET['id'])) {
    $mode = 'update';
    $id_artikel = $_GET['id'];

    $sql = "SELECT * FROM artikel WHERE id_artikel = $1";
    $hasil = pg_query_params($conn, $sql, array($id_artikel));

    if ($hasil && pg_num_rows($hasil) > 0) {
        $artikel = pg_fetch_assoc($hasil);
        $judul = $artikel['judul'];
        $isi_konten = $artikel['isi_konten'];
        $status_artikel = $artikel['status_artikel'];
        $gambar_sekarang = $artikel['gambar_artikel'];
    } else {
        echo "<script>window.location='manage_blog.php';</script>";
        exit;
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $judul_post = $_POST['judul'];
    $isi_konten_post = $_POST['isi_konten'];
    $status_artikel_post = $_POST['status_artikel'];

    // ID admin
    $id_admin_post = 1;

    $slug_post = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $judul_post)));

    // upload
    $nama_file_gambar = $_POST['gambar_sekarang_hidden'] ?? '';
    $upload_dir = '../uploads/';

    if (isset($_FILES['gambar_artikel']) && $_FILES['gambar_artikel']['error'] == 0) {
        $nama_file_asli = basename($_FILES['gambar_artikel']['name']);
        $nama_file_unik = uniqid() . '-' . $nama_file_asli;
        $target_file = $upload_dir . $nama_file_unik;

        if (move_uploaded_file($_FILES['gambar_artikel']['tmp_name'], $target_file)) {
            $nama_file_gambar = $nama_file_unik;
            if ($mode == 'update' && !empty($_POST['gambar_sekarang_hidden'])) {
                $gambar_lama = $upload_dir . $_POST['gambar_sekarang_hidden'];
                if (file_exists($gambar_lama)) unlink($gambar_lama);
            }
        }
    }

    // update
    if ($mode == 'update') {
        $id_artikel_post = $_POST['id_artikel'];

        $sql = "UPDATE artikel
                SET id_admin = $1, judul = $2, slug = $3, isi_konten = $4,
                    gambar_artikel = $5, status_artikel = $6, tgl_diperbarui = CURRENT_TIMESTAMP
                WHERE id_artikel = $7";

        $params = array( $id_admin_post, $judul_post, $slug_post, $isi_konten_post, $nama_file_gambar, $status_artikel_post, $id_artikel_post);

    } else {
        // create
        $sql = "INSERT INTO artikel (id_admin, judul, slug, isi_konten, gambar_artikel, status_artikel, tgl_dibuat, tgl_diperbarui)
                VALUES ($1, $2, $3, $4, $5, $6, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP)";

        $params = array( $id_admin_post, $judul_post, $slug_post, $isi_konten_post, $nama_file_gambar, $status_artikel_post);
    }

    $hasil = pg_query_params($conn, $sql, $params);

    if ($hasil) {
        echo "<script>alert('Berhasil disimpan!'); window.location='manage_blog.php';</script>";
        exit;
    } else {
        die("Error Saving Data: " . pg_last_error($conn));
    }
}
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0"><?php echo ($mode == 'update') ? 'Edit Artikel' : 'Tambah Artikel Baru'; ?></h1>
    <a href="manage_blog.php" class="btn btn-outline-secondary">Batal</a>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-4">
        <form method="POST" enctype="multipart/form-data">
            <?php if ($mode == 'update'): ?>
                <input type="hidden" name="id_artikel" value="<?php echo $id_artikel; ?>">
                <input type="hidden" name="gambar_sekarang_hidden" value="<?php echo htmlspecialchars($gambar_sekarang); ?>">
            <?php endif; ?>

            <div class="mb-3">
                <label class="form-label fw-bold">Judul Artikel</label>
                <input type="text" class="form-control" name="judul" value="<?php echo htmlspecialchars($judul); ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">Isi Konten</label>
                <textarea class="form-control" id="summernote" name="isi_konten" rows="10"><?php echo htmlspecialchars($isi_konten); ?></textarea>
            </div>

            <div class="row">
                <div class="col-md-8">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Gambar Utama</label>
                        <input type="file" class="form-control" name="gambar_artikel" accept="image/*">
                        <?php if ($mode == 'update' && !empty($gambar_sekarang)): ?>
                            <div class="mt-2">
                                <small>Gambar saat ini:</small><br>
                                <img src="../uploads/<?php echo htmlspecialchars($gambar_sekarang); ?>" style="height: 80px;">
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Status</label>
                        <select class="form-select" name="status_artikel">
                            <option value="Draft" <?php echo ($status_artikel == 'Draft') ? 'selected' : ''; ?>>Draft</option>
                            <option value="Published" <?php echo ($status_artikel == 'Published') ? 'selected' : ''; ?>>Published</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="text-end mt-3">
                <button type="submit" class="btn btn-primary btn-lg">Simpan</button>
            </div>
        </form>
    </div>
</div>

<script>
$(document).ready(function() {
    $('#summernote').summernote({
        placeholder: 'Tulis konten artikel...',
        tabsize: 2,
        height: 300
    });
});
</script>

<?php include 'includes/footer_admin.php'; ?>
