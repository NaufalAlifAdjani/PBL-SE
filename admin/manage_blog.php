<?php
include 'includes/header_admin.php';
// (Query untuk ambil data dari tbl_articles)
// $result = pg_query($conn, "SELECT * FROM tbl_articles ORDER BY created_at DESC");
?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="fw-bold">Kelola Blog</h1>
    <a href="#" class="btn btn-primary-admin"><i class="bi bi-plus-circle"></i> Tambah Blog</a>
</div>
<p class="text-muted">Tambah, edit, dan hapus artikel blog</p>

<div class="card card-admin mb-3">
    <div class="card-body p-4">
        <div class="d-flex justify-content-between">
            <div>
                <h5 class="fw-semibold">Introduksi Software Engineering</h5>
                <p class="text-muted mb-1">Memahami dasar-dasar SE</p>
                <small class="text-muted">Penulis: Admin &nbsp;&bull;&nbsp; Tanggal: 2024-01-15</small>
            </div>
            <div class="d-flex align-items-center">
                <a href="#" class="btn btn-action-edit me-2"><i class="bi bi-pencil-fill"></i> Edit</a>
                <a href="#" class="btn btn-action-delete"><i class="bi bi-trash-fill"></i> Hapus</a>
            </div>
        </div>
    </div>
</div>

<div class="card card-admin mb-3">
    <div class="card-body p-4">
        <div class="d-flex justify-content-between">
            <div>
                <h5 class="fw-semibold">Best Practices dalam Coding</h5>
                <p class="text-muted mb-1">Tips dan trik coding yang baik</p>
                <small class="text-muted">Penulis: Admin &nbsp;&bull;&nbsp; Tanggal: 2024-01-10</small>
            </div>
            <div class="d-flex align-items-center">
                <a href="#" class="btn btn-action-edit me-2"><i class="bi bi-pencil-fill"></i> Edit</a>
                <a href="#" class="btn btn-action-delete"><i class="bi bi-trash-fill"></i> Hapus</a>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer_admin.php'; ?>