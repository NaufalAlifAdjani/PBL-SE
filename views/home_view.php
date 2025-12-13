<?php include 'includes/header.php';

$is_recruitment_open = false; // Default tertutup
$query_status = "SELECT value FROM settings WHERE key_name = 'recruitment_status'";
$result_status = pg_query($conn, $query_status);

if ($result_status && pg_num_rows($result_status) > 0) {
    $row_status = pg_fetch_assoc($result_status);
    // Jika value '1', berarti BUKA
    if ($row_status['value'] == '1') {
        $is_recruitment_open = true;
    }
}
?>


<!-- banner -->
<section class="banner">
    <div class="container">
        <h1 class="fw-bold">Welcome to</h1>
        <h1 class="fw-bold">Software Engineering Lab</h1>
        <p class="lead my-3">We build, test, and refine software solutions that shape the future of technology.</p>
        <?php if ($is_recruitment_open): ?>
            <a href="recruitment.php" class="btn btn-white mt-3 mb-0">Join Us</a>
        <?php endif; ?>

    </div>
</section>

<?php
include 'home_profile_view.php';
include 'home_personil_view.php';
include 'home_blog_view.php';
?>

<?php include 'includes/footer.php'; ?>
