<?php
$activePage = 'blog';
include 'includes/header_admin.php';
include '../includes/db.php';

$judul = '';
$isi_konten = '';
$status_artikel = 'Draft';
$gambar_sekarang = '';
$mode = 'create';
$id_artikel = 0;

// edit atau create
if (isset($_GET['id'])) {
    $mode = 'update';
    $id_artikel = $_GET['id'];

    try {
        $sql = "SELECT * FROM artikel WHERE id_artikel = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$id_artikel]);
        $artikel = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($artikel) {
            $judul = $artikel['judul'];
            $isi_konten = $artikel['isi_konten'];
            $status_artikel = $artikel['status_artikel'];
            $gambar_sekarang = $artikel['gambar_artikel'];
        } else {
            // Jika ID tidak ditemukan, kembalikan ke manage_blog
            header('Location: manage_blog.php');
            exit;
        }
    } catch (PDOException $e) {
        die("Error: " . $e->getMessage());
    }
}

// form di submit
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // ambil data dari form
    $judul_post = $_POST['judul'];
    $isi_konten_post = $_POST['isi_konten'];
    $status_artikel_post = $_POST['status_artikel'];

    // ambil ID admin
    $id_admin_post = 1;

    // buat slug dari judul
    $slug_post = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $judul_post)));

    // handle upload gambar
    $nama_file_gambar = $_POST['gambar_sekarang_hidden'];
    $upload_dir = '../uploads/';

    if (isset($_FILES['gambar_artikel']) && $_FILES['gambar_artikel']['error'] == 0) {
        // ada file baru yang diupload
        $nama_file_asli = basename($_FILES['gambar_artikel']['name']);
        $nama_file_unik = uniqid() . '-' . $nama_file_asli;
        $target_file = $upload_dir . $nama_file_unik;

        // pindahkan file yang diupload
        if (move_uploaded_file($_FILES['gambar_artikel']['tmp_name'], $target_file)) {
            $nama_file_gambar = $nama_file_unik;

            // jka ini edit dan ada gambar lama, hapus gambar lama
            if ($mode == 'update' && !empty($_POST['gambar_sekarang_hidden'])) {
                $gambar_lama_path = $upload_dir . $_POST['gambar_sekarang_hidden'];
                if (file_exists($gambar_lama_path)) {
                    unlink($gambar_lama_path);
                }
            }
        }
    }

    try {
        if ($mode == 'update') {
            // update
            $id_artikel_post = $_POST['id_artikel'];
            $sql = "UPDATE artikel
                    SET id_admin = ?, judul = ?, slug = ?, isi_konten = ?, gambar_artikel = ?, status_artikel = ?, tgl_diperbarui = NOW()
                    WHERE id_artikel = ?";
            $stmt = $conn->prepare($sql);
            $stmt->execute([
                $id_admin_post,
                $judul_post,
                $slug_post,
                $isi_konten_post,
                $nama_file_gambar,
                $status_artikel_post,
                $id_artikel_post
            ]);

        } else {
            // create
            $sql = "INSERT INTO artikel (id_admin, judul, slug, isi_konten, gambar_artikel, status_artikel, tgl_dibuat, tgl_diperbarui)
                    VALUES (?, ?, ?, ?, ?, ?, NOW(), NOW())";
            $stmt = $conn->prepare($sql);
            $stmt->execute([
                $id_admin_post,
                $judul_post,
                $slug_post,
                $isi_konten_post,
                $nama_file_gambar,
                $status_artikel_post
            ]);
        }

        // redirect ke halaman manage blog setelah sukses
        header('Location: manage_blog.php');
        exit;

    } catch (PDOException $e) {
        die("Error saat menyimpan data: " . $e->getMessage());
    }
}
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0"><?php echo ($mode == 'update') ? 'Edit Artikel' : 'Tambah Artikel Baru'; ?></h1>
    <a href="manage_blog.php" class="btn btn-outline-secondary">
        <i class="bi bi-x-lg me-2"></i> Batal
    </a>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-4">

        <form method="POST" action="blog_form.php<?php echo ($mode == 'update') ? '?id=' . $id_artikel : ''; ?>" enctype="multipart/form-data">

            <?php if ($mode == 'update'): ?>
                <input type="hidden" name="id_artikel" value="<?php echo $id_artikel; ?>">
                <input type="hidden" name="gambar_sekarang_hidden" value="<?php echo htmlspecialchars($gambar_sekarang); ?>">
            <?php endif; ?>

            <div class="mb-3">
                <label for="judul" class="form-label fw-bold">Judul Artikel</label>
                <input type="text" class="form-control" id="judul" name="judul"
                       value="<?php echo htmlspecialchars($judul); ?>" required>
            </div>

            <div class="mb-3">
                <label for="summernote" class="form-label fw-bold">Isi Konten</label>
                <textarea class="form-control" id="summernote" name="isi_konten" rows="10">
                    <?php echo htmlspecialchars($isi_konten); ?>
                </textarea>
            </div>

            <div class="row">
                <div class="col-md-8">
                    <div class="mb-3">
                        <label for="gambar_artikel" class="form-label fw-bold">Gambar Utama Artikel</label>
                        <input type="file" class="form-control" id="gambar_artikel" name="gambar_artikel" accept="image/*">
                        <div class="form-text">
                            <?php if ($mode == 'update' && !empty($gambar_sekarang)): ?>
                                Biarkan kosong jika tidak ingin mengganti gambar.
                            <?php else: ?>
                                Wajib diisi untuk artikel baru.
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php if ($mode == 'update' && !empty($gambar_sekarang)): ?>
                        <div class="mb-3">
                            <label class="form-label">Gambar Sekarang:</label><br>
                            <img src="../uploads/<?php echo htmlspecialchars($gambar_sekarang); ?>"
                                 alt="Gambar <?php echo htmlspecialchars($judul); ?>"
                                 class="img-thumbnail" style="max-height: 150px;">
                        </div>
                    <?php endif; ?>
                </div>

                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="status_artikel" class="form-label fw-bold">Status</label>
                        <select class="form-select" id="status_artikel" name="status_artikel" required>
                            <option value="Draft" <?php echo ($status_artikel == 'Draft') ? 'selected' : ''; ?>>
                                Draft
                            </option>
                            <option value="Published" <?php echo ($status_artikel == 'Published') ? 'selected' : ''; ?>>
                                Published
                            </option>
                        </select>
                    </div>
                </div>
            </div>

            <hr class="my-4">
            <div class="text-end">
                <button type="submit" class="btn btn-primary btn-lg">
                    <i class="bi bi-save-fill me-2"></i>
                    <?php echo ($mode == 'update') ? 'Update Artikel' : 'Simpan Artikel'; ?>
                </button>
            </div>
        </form>

    </div>
</div>

<script>
    // pastikan ini dieksekusi setelah DOM (dan jQuery) dimuat
    $(document).ready(function() {
        $('#summernote').summernote({
            placeholder: 'Tulis isi artikel Anda di sini...',
            tabsize: 2,
            height: 300,
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'italic', 'underline', 'clear']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['table', ['table']],
                ['insert', ['link', 'picture', 'video']],
                ['view', ['fullscreen', 'codeview', 'help']]
            ]
        });
    });
</script>

<?php
include 'includes/footer_admin.php';
?>
