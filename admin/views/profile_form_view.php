<?php
// admin/views/profile_form_view.php


// PENTING:
// Karena file ini dipanggil (include) oleh 'admin/profile_form.php',
// maka path 'includes/header_admin.php' itu relatif terhadap folder 'admin/', bukan 'views/'.
include 'includes/header_admin.php'; 
?>

<h1 class="fw-bold"><?php echo $page_title; ?></h1>
<a href="manage_profile.php" class="btn btn-outline-secondary mb-3">&laquo; Kembali ke Daftar</a>

<div class="card card-admin">
    <div class="card-body p-4">
        <form action="manage_profile.php?action=save" method="POST">
            
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
                <input class="form-check-input" type="checkbox" role="switch" id="is_published" name="is_published" <?php echo (isset($data['is_published']) && $data['is_published'] == 't') ? 'checked' : ''; ?>>
                <label class="form-check-label" for="is_published">Publikasikan Halaman</label>
            </div>

            <button type="submit" class="btn btn-primary-admin">Simpan Halaman</button>
        </form>
    </div>
</div>

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
// Include footer (path relatif terhadap admin/profile_form.php)
include 'includes/footer_admin.php'; 
?>

