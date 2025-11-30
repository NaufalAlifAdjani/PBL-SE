<?php
include 'includes/header.php';

// Query ambil data yang STATUS = 'Diterima'
$query = "SELECT * FROM pendaftaran_user WHERE status = 'Diterima' ORDER BY angkatan DESC, nama ASC";
$result = pg_query($conn, $query);
?>

<div class="container my-5">
    <div class="text-center mb-5">
        <h1 class="fw-bold">Komunitas SE Geeks</h1>
        <p class="lead text-muted">Daftar mahasiswa yang tergabung dalam komunitas riset kami.</p>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Nama Mahasiswa</th>
                            <th>Jurusan</th>
                            <th>Angkatan</th>
                            <th>Portofolio</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($result && pg_num_rows($result) > 0) {
                            while($row = pg_fetch_assoc($result)) {
                        ?>
                        <tr>
                            <td class="fw-semibold"><?php echo htmlspecialchars($row['nama']); ?></td>
                            <td><?php echo htmlspecialchars($row['jurusan']); ?></td>
                            <td><?php echo htmlspecialchars($row['angkatan']); ?></td>
                            <td>
                                <?php if(!empty($row['portofolio'])): ?>
                                    <a href="<?php echo htmlspecialchars($row['portofolio']); ?>" target="_blank" class="btn btn-sm btn-outline-primary">
                                        Lihat Portofolio
                                    </a>
                                <?php else: ?>
                                    <span class="text-muted">-</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php
                            }
                        } else {
                            echo "<tr><td colspan='4' class='text-center py-4'>Belum ada anggota aktif.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>