<?php
include 'includes/header_admin.php'; 
include '../includes/db.php'; 

// Query GABUNGAN (UNION)
// Kita samakan nama kolomnya menggunakan alias (AS)
$query = "
    (SELECT 
        id_dosen AS id, 
        nama_dosen AS nama, 
        bid_kemahiran AS posisi, 
        email_dosen AS email, 
        'dosen' AS tipe  -- Penanda tabel
     FROM dosen)
    UNION ALL
    (SELECT 
        id_pendaftaran_user AS id, 
        nama AS nama, 
        'Anggota SE Geeks' AS posisi, -- Posisi default untuk mahasiswa
        email AS email, 
        'mahasiswa' AS tipe -- Penanda tabel
     FROM pendaftaran_user 
     WHERE status = 'Diterima')
    ORDER BY tipe ASC, id ASC
";

$result = pg_query($conn, $query);
?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="fw-bold">Kelola Personil & Anggota</h1>
    <a href="personil_form.php?type=dosen" class="btn-primary-admin"><i class="bi bi-plus-circle"></i> Tambah Data</a>
</div>
<p class="text-muted">Kelola data dosen lab dan anggota SE Geeks yang diterima.</p>

<div class="card card-admin">
    <div class="card-body">
        <table class="table table-hover align-middle">
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Posisi / Status</th>
                    <th>Email</th>
                    <th>Tipe</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result && pg_num_rows($result) > 0) {
                    while($row = pg_fetch_assoc($result)) {
                ?>
                <tr>
                    <td class="fw-semibold"><?php echo htmlspecialchars($row['nama']); ?></td>
                    <td><?php echo htmlspecialchars($row['posisi']); ?></td>
                    <td><?php echo htmlspecialchars($row['email']); ?></td>
                    <td>
                        <?php if($row['tipe'] == 'dosen'): ?>
                            <span class="badge bg-primary">Dosen</span>
                        <?php else: ?>
                            <span class="badge bg-success">Mahasiswa</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <a href="personil_form.php?id=<?php echo $row['id']; ?>&type=<?php echo $row['tipe']; ?>" class="btn btn-action-edit me-2"><i class="bi bi-pencil-fill"></i> Edit</a>
                        
                        <a href="personil_delete.php?id=<?php echo $row['id']; ?>&type=<?php echo $row['tipe']; ?>" class="btn btn-action-delete" onclick="return confirm('Yakin ingin menghapus data ini?')"><i class="bi bi-trash-fill"></i> Hapus</a>
                    </td>
                </tr>
                <?php
                    }
                } else {
                    echo "<tr><td colspan='5' class='text-center'>Belum ada data.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<?php include 'includes/footer_admin.php'; ?>