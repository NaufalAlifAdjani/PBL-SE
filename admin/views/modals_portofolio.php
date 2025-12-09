<div class="modal fade" id="modalTambah" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Portofolio Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" enctype="multipart/form-data" action=""> 
                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col-md-8">
                            <label>Judul Karya</label>
                            <input type="text" name="judul" class="form-control" required placeholder="Contoh: Sistem Pakar">
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
                            <label>Link Eksternal</label>
                            <input type="url" name="link_eksternal" class="form-control" placeholder="https://...">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label>Penulis / Tim</label>
                        <input type="text" name="penulis" class="form-control" placeholder="Nama anggota tim" required>
                    </div>
                    <div class="mb-3">
                        <label>Deskripsi Singkat</label>
                        <textarea name="deskripsi" class="form-control" rows="3"></textarea>
                    </div>
                    <div class="mb-3">
                        <label>Upload Gambar</label>
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
            <form method="POST" enctype="multipart/form-data" action="">
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
                        <label>Ganti Gambar</label>
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