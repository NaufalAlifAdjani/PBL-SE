<?php
include 'includes/header.php'; // Memanggil header (sudah ada koneksi DB)

// 1. Ambil slug dari URL
$slug = $_GET['slug'] ?? null;
$data = null;

if ($slug) {
    // 2. Ambil data berdasarkan slug
    $data = getProfileSection($conn, $slug);
}
?>

<div class="profile-container">
    
    <?php if ($data): ?>
        <h1 class="mb-4 fw-bold"><?php echo htmlspecialchars($data['title']); ?></h1>
        
        <div class="content-container">
            <?php echo $data['content']; ?>
        </div>
        
    <?php else: ?>
        <h1 class="mb-4 fw-bold">Halaman Tidak Ditemukan</h1>
        <div class="alert alert-danger">
            Maaf, konten yang kamu cari tidak dapat ditemukan.
        </div>
    <?php endif; ?>
    
</div>

<?php
include 'includes/footer.php';
?>