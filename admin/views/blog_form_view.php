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

<!-- styling untuk summernot -->
<style>
    .note-editor.note-frame.fullscreen {
        /* untuk posisi floating */
        position: fixed !important;
        top: 50% !important;
        left: 50% !important;
        transform: translate(-50%, -50%) !important;

        width: 90vw !important;
        max-width: 1000px !important;
        height: 85vh !important;

        /* card */
        background-color: #fff !important;
        border-radius: 12px !important;
        border: none !important;

        /* efek backdrop */
        box-shadow:
            0 20px 50px rgba(0,0,0,0.3),
            0 0 0 9999px rgba(0,0,0,0.6) !important;

        z-index: 99999 !important; /* untuk memastikan ada di depan */
    }

    /* text area */
    .note-editor.note-frame.fullscreen .note-editing-area {
        height: calc(100% - 50px) !important;
        overflow-y: auto !important;
    }

    /* Memperbaiki Toolbar agar sudut atasnya juga bulat */
    .note-editor.note-frame.fullscreen .note-toolbar {
        border-radius: 12px 12px 0 0 !important;
        background: #f8f9fa !important;
        border-bottom: 1px solid #ddd !important;
    }

    /* Menyembunyikan resize bar di bawah saat mode ini */
    .note-editor.note-frame.fullscreen .note-resizebar {
        display: none !important;
    }

    /* Memastikan editable area punya padding enak dibaca */
    .note-editor.note-frame.fullscreen .note-editable {
        padding: 40px !important;
        background-color: #fff !important;
    }
</style>

<script>
$(document).ready(function() {
    $('#summernote').summernote({
        placeholder: 'Tulis konten artikel...',
        tabsize: 2,
        height: 300
    });
});
</script>
