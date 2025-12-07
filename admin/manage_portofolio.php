<?php
// Include Header (sudah otomatis start session & include db.php)
include 'includes/header_admin.php';
// include '../includes/db.php';

// --- LOGIC PHP (CRUD) ---

// 1. Logic Tambah Data (Create)
if (isset($_POST['simpan_data'])) {
    $judul = pg_escape_string($conn, $_POST['judul']);
    $kategori = $_POST['kategori'];
    $tahun = $_POST['tahun'];
    $penulis = pg_escape_string($conn, $_POST['penulis']);
    $deskripsi = pg_escape_string($conn, $_POST['deskripsi']);
    $link = pg_escape_string($conn, $_POST['link_eksternal']);
    
    // Upload Gambar
    $gambar = "";
    if ($_FILES['gambar']['name']) {
        $nama_file = time() . '_' . $_FILES['gambar']['name']; // Rename agar unik
        $tmp_file = $_FILES['gambar']['tmp_name'];
        $path = "../uploads/portofolio/" . $nama_file;
        
        if (move_uploaded_file($tmp_file, $path)) {
            $gambar = $nama_file;
        }
    }

    $query = "INSERT INTO portofolio (judul, kategori, tahun, penulis, deskripsi, link_eksternal, gambar) 
              VALUES ('$judul', '$kategori', '$tahun', '$penulis', '$deskripsi', '$link', '$gambar')";
    
    if (pg_query($conn, $query)) {
        echo "<script>alert('Data berhasil ditambahkan!'); window.location='manage_portofolio.php';</script>";
    } else {
        echo "<script>alert('Gagal menambah data.');</script>";
    }
}

// 2. Logic Hapus Data (Delete)
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    
    // Ambil nama gambar dulu untuk dihapus dari folder
    $q_gambar = pg_query($conn, "SELECT gambar FROM portofolio WHERE id_portofolio = '$id'");
    $data_img = pg_fetch_assoc($q_gambar);
    
    // Hapus file gambar jika ada
    if ($data_img['gambar'] && file_exists("../uploads/portofolio/" . $data_img['gambar'])) {
        unlink("../uploads/portofolio/" . $data_img['gambar']);
    }

    // Hapus dari database
    $query = "DELETE FROM portofolio WHERE id_portofolio = '$id'";
    if (pg_query($conn, $query)) {
        echo "<script>alert('Data berhasil dihapus!'); window.location='manage_portofolio.php';</script>";
    }
}

// 3. Logic Edit Data (Update)
if (isset($_POST['update_data'])) {
    $id = $_POST['id_portofolio'];
    $judul = pg_escape_string($conn, $_POST['judul']);
    $kategori = $_POST['kategori'];
    $tahun = $_POST['tahun'];
    $penulis = pg_escape_string($conn, $_POST['penulis']);
    $deskripsi = pg_escape_string($conn, $_POST['deskripsi']);
    $link = pg_escape_string($conn, $_POST['link_eksternal']);
    
    $query_update = "UPDATE portofolio SET 
                     judul='$judul', kategori='$kategori', tahun='$tahun', 
                     penulis='$penulis', deskripsi='$deskripsi', link_eksternal='$link'";

    // Cek jika ada upload gambar baru
    if ($_FILES['gambar']['name']) {
        $nama_file = time() . '_' . $_FILES['gambar']['name'];
        $path = "../uploads/portofolio/" . $nama_file;
        
        if (move_uploaded_file($_FILES['gambar']['tmp_name'], $path)) {
            $query_update .= ", gambar='$nama_file'";
        }
    }

    $query_update .= " WHERE id_portofolio='$id'";
    
    if (pg_query($conn, $query_update)) {
        echo "<script>alert('Data berhasil diupdate!'); window.location='manage_portofolio.php';</script>";
    }
}
?>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-dark">Kelola Portofolio Lab</h2>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambah">
            <i class="bi bi-plus-lg"></i> Tambah Data
        </button>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>Gambar</th>
                            <th>Judul & Tahun</th>
                            <th>Kategori</th>
                            <th>Penulis (Tim)</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $no = 1;
                        $query = pg_query($conn, "SELECT * FROM portofolio ORDER BY id_portofolio DESC");
                        while ($row = pg_fetch_assoc($query)) :
                        ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td>
                                <?php if($row['gambar']): ?>
                                    <img src="../uploads/portofolio/<?= $row['gambar'] ?>" alt="img" class="rounded" style="width: 60px; height: 60px; object-fit: cover;">
                                <?php else: ?>
                                    <span class="text-muted small">No Image</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <span class="fw-bold"><?= $row['judul'] ?></span><br>
                                <small class="text-muted"><?= $row['tahun'] ?></small>
                            </td>
                            <td>
                                <?php 
                                    // Badge warna-warni sesuai kategori
                                    $badge = 'bg-secondary';
                                    if($row['kategori'] == 'publikasi') $badge = 'bg-primary';
                                    if($row['kategori'] == 'produk') $badge = 'bg-success';
                                    if($row['kategori'] == 'penelitian') $badge = 'bg-warning text-dark';
                                    if($row['kategori'] == 'pengabdian') $badge = 'bg-info text-dark';
                                ?>
                                <span class="badge <?= $badge ?> text-uppercase"><?= $row['kategori'] ?></span>
                            </td>
                            <td><small><?= $row['penulis'] ?></small></td>
                            <td>
                                <button class="btn btn-sm btn-warning btn-edit" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#modalEdit"
                                    data-id="<?= $row['id_portofolio'] ?>"
                                    data-judul="<?= $row['judul'] ?>"
                                    data-kategori="<?= $row['kategori'] ?>"
                                    data-tahun="<?= $row['tahun'] ?>"
                                    data-penulis="<?= $row['penulis'] ?>"
                                    data-deskripsi="<?= $row['deskripsi'] ?>"
                                    data-link="<?= $row['link_eksternal'] ?>">
                                    <i class="bi bi-pencil-square"></i>
                                </button>
                                
                                <a href="manage_portofolio.php?hapus=<?= $row['id_portofolio'] ?>" 
                                   class="btn btn-sm btn-danger"
                                   onclick="return confirm('Yakin ingin menghapus data ini?')">
                                    <i class="bi bi-trash"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalTambah" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Portofolio Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col-md-8">
                            <label>Judul Karya</label>
                            <input type="text" name="judul" class="form-control" required placeholder="Contoh: Sistem Pakar Penyakit Tanaman">
                        </div>
                        <div class="col-md-4">
                            <label>Tahun</label>
                            <input type="number" name="tahun" class="form-control" value="<?= date('Y') ?>" required>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label>Kategori</label>
                            <select name="kategori" class="form-select" required>
                                <option value="publikasi">Publikasi Ilmiah</option>
                                <option value="produk">Produk Inovasi</option>
                                <option value="penelitian">Penelitian</option>
                                <option value="pengabdian">Pengabdian Masyarakat</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label>Link Eksternal (Jurnal/Demo)</label>
                            <input type="url" name="link_eksternal" class="form-control" placeholder="https://...">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label>Penulis / Tim (Pisahkan dengan koma)</label>
                        <input type="text" name="penulis" class="form-control" placeholder="Contoh: Dr. Budi, Andi (Mhs), Siti (Mhs)" required>
                    </div>
                    <div class="mb-3">
                        <label>Deskripsi Singkat</label>
                        <textarea name="deskripsi" class="form-control" rows="3"></textarea>
                    </div>
                    <div class="mb-3">
                        <label>Upload Gambar (Cover/Screenshot)</label>
                        <input type="file" name="gambar" class="form-control" accept="image/*">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" name="simpan_data" class="btn btn-primary">Simpan Data</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modalEdit" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Portofolio</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    <input type="hidden" name="id_portofolio" id="edit_id">
                    
                    <div class="row mb-3">
                        <div class="col-md-8">
                            <label>Judul Karya</label>
                            <input type="text" name="judul" id="edit_judul" class="form-control" required>
                        </div>
                        <div class="col-md-4">
                            <label>Tahun</label>
                            <input type="number" name="tahun" id="edit_tahun" class="form-control" required>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label>Kategori</label>
                            <select name="kategori" id="edit_kategori" class="form-select" required>
                                <option value="publikasi">Publikasi Ilmiah</option>
                                <option value="produk">Produk Inovasi</option>
                                <option value="penelitian">Penelitian</option>
                                <option value="pengabdian">Pengabdian Masyarakat</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label>Link Eksternal</label>
                            <input type="url" name="link_eksternal" id="edit_link" class="form-control">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label>Penulis / Tim</label>
                        <input type="text" name="penulis" id="edit_penulis" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Deskripsi</label>
                        <textarea name="deskripsi" id="edit_deskripsi" class="form-control" rows="3"></textarea>
                    </div>
                    <div class="mb-3">
                        <label>Ganti Gambar (Biarkan kosong jika tidak ingin mengganti)</label>
                        <input type="file" name="gambar" class="form-control" accept="image/*">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" name="update_data" class="btn btn-primary">Update Data</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Saat tombol edit diklik, ambil data dari atribut dan masukkan ke form modal
    const editBtns = document.querySelectorAll('.btn-edit');
    
    editBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            document.getElementById('edit_id').value = btn.getAttribute('data-id');
            document.getElementById('edit_judul').value = btn.getAttribute('data-judul');
            document.getElementById('edit_kategori').value = btn.getAttribute('data-kategori');
            document.getElementById('edit_tahun').value = btn.getAttribute('data-tahun');
            document.getElementById('edit_penulis').value = btn.getAttribute('data-penulis');
            document.getElementById('edit_deskripsi').value = btn.getAttribute('data-deskripsi');
            document.getElementById('edit_link').value = btn.getAttribute('data-link');
        });
    });
</script>

</main>
</div>
</body>
</html>