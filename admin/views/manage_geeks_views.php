<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="fw-bold">Kelola Pendaftaran SE Geeks</h1>
</div>
<p class="text-muted">Review pendaftar masuk dan kelola anggota komunitas.</p>

<?php if (isset($_GET['msg'])): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?php echo htmlspecialchars($_GET['msg']); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<form action="manage_recruitment.php" method="POST">

    <!-- <div class="mb-3 d-flex gap-2">
        <button type="submit" name="bulk_action" value="approve_selected" class="btn btn-success" onclick="return confirm('Yakin ingin MENERIMA semua user yang dipilih?')">
            <i class="bi bi-check-all"></i> Terima Terpilih
        </button>
        
        <button type="submit" name="bulk_action" value="reject_selected" class="btn btn-warning" onclick="return confirm('Yakin ingin MENOLAK semua user yang dipilih?')">
            <i class="bi bi-x-circle"></i> Tolak Terpilih
        </button>
        
        <button type="submit" name="bulk_action" value="delete_selected" class="btn btn-danger" onclick="return confirm('Yakin ingin MENGHAPUS semua user yang dipilih?')">
            <i class="bi bi-trash"></i> Hapus Terpilih
        </button>
    </div> -->

    <div class="card card-admin">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>

                            <th>Nama & Email</th>
                            <th>NIM</th>
                            <th>Info Akademik</th>
                            <th>Portofolio</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Cek apakah ada data dari Controller
                        if ($result && pg_num_rows($result) > 0) {
                            while($row = pg_fetch_assoc($result)) {
                        ?>
                        <tr>

                            <td>
                                <div class="fw-bold"><?php echo htmlspecialchars($row['nama']); ?></div>
                                <small class="text-muted"><?php echo htmlspecialchars($row['email']); ?></small>
                            </td>
                            <td>
                                <div class="fw-bold"><?php echo htmlspecialchars($row['nim']); ?></div>
                            </td>
                            <td>
                                <div><?php echo htmlspecialchars($row['jurusan']); ?></div>
                                <small class="text-muted">Angkatan: <?php echo htmlspecialchars($row['angkatan']); ?></small>
                            </td>
                            <td>
                                <?php if($row['portofolio']): ?>
                                    <a href="<?php echo htmlspecialchars($row['portofolio']); ?>" target="_blank" class="btn btn-sm btn-outline-secondary">
                                        <i class="bi bi-link-45deg"></i> Link
                                    </a>
                                <?php else: ?> - <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($row['status'] == 'Pending'): ?>
                                    <span class="badge bg-warning text-dark">Pending</span>
                                <?php elseif ($row['status'] == 'Diterima'): ?>
                                    <span class="badge bg-success">Diterima</span>
                                <?php else: ?>
                                    <span class="badge bg-danger">Ditolak</span>
                                <?php endif; ?>
                            </td>
                            
                            <td>
                                <div class="btn-group" role="group">
                                    <?php if ($row['status'] != 'Diterima'): ?>
                                        <a href="manage_recruitment.php?action=approve&id=<?php echo $row['id_pendaftaran_user']; ?>" class="btn btn-sm btn-success" title="Terima">
                                            <i class="bi bi-check-lg"></i>
                                        </a>
                                    <?php endif; ?>

                                    <?php if ($row['status'] == 'Pending'): ?>
                                        <a href="manage_recruitment.php?action=reject&id=<?php echo $row['id_pendaftaran_user']; ?>" class="btn btn-sm btn-warning" title="Tolak">
                                            <i class="bi bi-x-lg"></i>
                                        </a>
                                    <?php endif; ?>

                                    <a href="manage_recruitment.php?action=delete&id=<?php echo $row['id_pendaftaran_user']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Hapus data ini permanen?')" title="Hapus">
                                        <i class="bi bi-trash"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <?php
                            }
                        } else {
                            // Colspan jadi 6 karena ada tambahan kolom checkbox
                            echo "<tr><td colspan='6' class='text-center py-4'>Belum ada data pendaftar.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</form> ```

<!-- ### Apa yang Berubah dari File Lama Anda?

1.  **Tag `<form>`:** Saya menambahkan pembuka form sebelum tombol aksi dan penutup `</form>` di bagian paling bawah. Ini wajib agar data checkbox bisa dikirim.
2.  **Tombol Bulk Action:** Menambahkan 3 tombol (Terima, Tolak, Hapus Terpilih) di atas tabel.
3.  **Kolom Checkbox:**
    * Di `<thead>`: Checkbox untuk "Pilih Semua".
    * Di `<tbody>`: Checkbox untuk setiap baris user (dengan `name="pilih_id[]"`).
4.  **JavaScript:** Fungsi kecil di paling atas agar checkbox header bisa mencentang semua checkbox di bawahnya.

Sekarang halaman admin Anda sudah siap untuk melakukan proses massal! -->