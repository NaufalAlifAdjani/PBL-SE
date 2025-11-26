<?php include __DIR__ . '/../includes/header_admin.php'; ?>

<main class="main-content">

    <a href="manage_profile.php" class="btn btn-outline-secondary mb-3">&laquo; Kembali ke Daftar</a>
    <h1 class="fw-bold mb-4"><?= $page_title; ?></h1>

    <div class="card-admin">
        <div class="card-body p-4">
            <form action="profile_form.php" method="POST">

                <input type="hidden" name="id" value="<?= $data['id'] ?? ''; ?>">

                <div class="row">
                    <div class="col-md-8">
                        <div class="mb-3">
                            <label for="title" class="form-label fw-semibold">Judul Halaman</label>
                            <input type="text" class="form-control" id="title" name="title"
                                   value="<?= htmlspecialchars($data['title'] ?? ''); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="slug" class="form-label fw-semibold">Slug (URL)</label>
                            <input type="text" class="form-control" id="slug" name="slug"
                                   value="<?= htmlspecialchars($data['slug'] ?? ''); ?>"
                                   placeholder="cth: tentang-lab-kami" required>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="menu_group" class="form-label fw-semibold">Grup Menu</label>
                            <input type="text" class="form-control" id="menu_group" name="menu_group"
                                   value="<?= htmlspecialchars($data['menu_group'] ?? 'profile_dropdown'); ?>">
                            <small class="text-muted">Gunakan 'profile_dropdown' agar muncul di navbar.</small>
                        </div>
                        <div class="mb-3">
                            <label for="display_order" class="form-label fw-semibold">Urutan</label>
                            <input type="number" class="form-control" id="display_order" name="display_order"
                                   value="<?= htmlspecialchars($data['display_order'] ?? '0'); ?>">
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="editor_content" class="form-label fw-semibold">Konten</label>
                    <textarea class="form-control" id="editor_content" name="content" rows="15">
                        <?= htmlspecialchars($data['content'] ?? ''); ?>
                    </textarea>
                </div>

                <div class="form-check form-switch mb-3">
                    <input class="form-check-input" type="checkbox" role="switch" id="is_published" name="is_published"
                           <?= ($data && ($data['is_published'] === 't' || $data['is_published'] == 1)) ? 'checked' : ''; ?>>
                    <label class="form-check-label" for="is_published">Publikasikan Halaman</label>
                </div>

                <button type="submit" class="btn-primary-admin">Simpan Halaman</button>
            </form>
        </div>
    </div>

</main>

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

<?php include __DIR__ . '/../includes/footer_admin.php'; ?>
