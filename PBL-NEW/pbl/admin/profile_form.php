<?php
include 'includes/header_admin.php'; 
include '../includes/db.php';

$page_title = "Tambah Halaman Profile";
$data = null; // Data untuk form
$id = $_GET['id'] ?? null; // Cek apakah ini mode Edit

// 1. LOGIKA UNTUK MODE EDIT (Ambil data)
if ($id) {
    $page_title = "Edit Halaman Profile";
    $query_get = "SELECT * FROM Profile WHERE id = $1";
    $result_get = pg_query_params($conn, $query_get, [$id]);
    $data = pg_fetch_assoc($result_get);
    if (!$data) {
        echo "<div class='alert alert-danger'>Data tidak ditemukan.</div>";
        include 'includes/footer_admin.php';
        exit;
    }
}

// 2. LOGIKA UNTUK SIMPAN DATA (Create atau Update)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_post = $_POST['id'] ?? null;
    $title = $_POST['title'];
    $slug = $_POST['slug'];
    $content = $_POST['content'];
    $menu_group = $_POST['menu_group'];
    $display_order = (int)$_POST['display_order'];
    $is_published = isset($_POST['is_published']) ? 't' : 'f'; // 't' for true, 'f' for false

    if ($id_post) {
        // --- Proses UPDATE ---
        $query_update = "UPDATE Profile SET title = $1, slug = $2, content = $3, menu_group = $4, display_order = $5, is_published = $6, updated_at = NOW()
                         WHERE id = $7";
        $result = pg_query_params($conn, $query_update, [$title, $slug, $content, $menu_group, $display_order, $is_published, $id_post]);
        $message = "Data berhasil diperbarui!";
    } else {
        // --- Proses CREATE (INSERT) ---
        $query_insert = "INSERT INTO Profile (title, slug, content, menu_group, display_order, is_published)
                         VALUES ($1, $2, $3, $4, $5, $6)";
        $result = pg_query_params($conn, $query_insert, [$title, $slug, $content, $menu_group, $display_order, $is_published]);
        $message = "Data berhasil ditambahkan!";
    }

    if ($result) {
        // Redirect kembali ke halaman list jika sukses
        header("Location: manage_profile.php?status=success");
        exit;
    } else {
        echo "<div class='alert alert-danger'>Error: " . pg_last_error($conn) . "</div>";
    }
}
?>

<h1 class="fw-bold"><?php echo $page_title; ?></h1>
<a href="manage_profile.php" class="btn btn-outline-secondary mb-3">&laquo; Kembali ke Daftar</a>

<div class="card card-admin">
    <div class="card-body p-4">
        <form action="profile_form.php" method="POST">
            <input type="hidden" name="id" value="<?php echo $data['id'] ?? ''; ?>">
            
            <div class="row">
                <div class="col-md-8">
                    <div class="mb-3">
                        <label for="title" class="form-label fw-semibold">Judul Halaman</label>
                        <input type="text" class="form-control" id="title" name="title" value="<?php echo htmlspecialchars($data['title'] ?? ''); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="slug" class="form-label fw-semibold">Slug (URL)</label>
                        <input type="text" class="form-control" id="slug" name="slug" value="<?php echo htmlspecialchars($data['slug'] ?? ''); ?>" placeholder="cth: tentang-lab-kami" required>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="menu_group" class="form-label fw-semibold">Grup Menu</label>
                        <input type="text" class="form-control" id="menu_group" name="menu_group" value="<?php echo htmlspecialchars($data['menu_group'] ?? 'profile_dropdown'); ?>">
                        <small class="text-muted">Gunakan 'profile_dropdown' agar muncul di navbar.</small>
                    </div>
                    <div class="mb-3">
                        <label for="display_order" class="form-label fw-semibold">Urutan</label>
                        <input type="number" class="form-control" id="display_order" name="display_order" value="<?php echo htmlspecialchars($data['display_order'] ?? '0'); ?>">
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <label for="editor_content" class="form-label fw-semibold">Konten</label>
                <textarea class="form-control" id="editor_content" name="content" rows="15"><?php echo htmlspecialchars($data['content'] ?? ''); ?></textarea>
            </div>

            <div class="form-check form-switch mb-3">
                <input class="form-check-input" type="checkbox" role="switch" id="is_published" name="is_published" <?php echo ($data && $data['is_published'] == 't') ? 'checked' : ''; ?>>
                <label class="form-check-label" for="is_published">Publikasikan Halaman</Slabel>
            </div>

            <button type="submit" class="btn btn-primary-admin">Simpan Halaman</button>
        </form>
    </div>
</div>

<?php 
// Panggil skrip Summernote SEBELUM footer
?>
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
<script>
    $(document).ready(function() {
        $('#editor_content').summernote({
            placeholder: 'Tulis konten halaman di sini...',
            height: 350,
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'italic', 'underline', 'clear']],
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