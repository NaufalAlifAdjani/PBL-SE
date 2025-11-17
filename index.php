<?php
include 'includes/header.php'; // Memanggil header (sudah ada koneksi DB)

// Ambil data 'Tentang Lab' dari DB
$slug = 'tentang-lab'; // Slug dari data dummy
$data = getProfileSection($conn, $slug);
?>

<div class="profile-container">
    <h1 class="mb-4 fw-bold">Profil Lab SE</h1>
    
    <?php if ($data): ?>
        <h3 class="mb-3 fw-semibold"><?php echo htmlspecialchars($data['title']); ?></h3>
        
        <div class="lead content-container">
            <?php echo $data['content']; ?>
        </div>
        
    <?php else: ?>
        <div class="alert alert-warning">Konten 'Tentang Lab' tidak ditemukan.</div>
    <?php endif; ?>
    
</div>

<?php
include 'includes/footer.php';
?>