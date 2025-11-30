<div class="p-3 bg-light rounded mb-3">
    <h6 class="fw-bold text-black">Publikasi</h6>

    <?php
    $publikasi_list = (isset($publikasi_list) && is_array($publikasi_list))
        ? $publikasi_list
        : [];
    ?>

    <div id="publikasi-container">
        <?php foreach($publikasi_list as $p): ?>
        <div class="row mb-2 publikasi-item">
            <div class="col-md-4"><input type="text" class="form-control" name="judul_pub[]" value="<?= $p['judul'] ?>"></div>
            <div class="col-md-3"><input type="text" class="form-control" name="tahun_pub[]" value="<?= $p['thn_terbit'] ?>"></div>
            <div class="col-md-4"><input type="text" class="form-control" name="link_pub[]" value="<?= $p['link_publikasi'] ?>"></div>
            <div class="col-md-1"><button type="button" class="btn btn-danger btn-sm remove-pub">X</button></div>
        </div>
        <?php endforeach ?>

        <?php if(count($publikasi_list)==0): ?>
        <div class="row mb-2 publikasi-item">
            <div class="col-md-4"><input type="text" class="form-control" name="judul_pub[]" placeholder="Judul"></div>
            <div class="col-md-3"><input type="text" class="form-control" name="tahun_pub[]" placeholder="Tahun Terbit"></div>
            <div class="col-md-4"><input type="text" class="form-control" name="link_pub[]" placeholder="Link"></div>
            <div class="col-md-1"><button type="button" class="btn btn-danger btn-sm remove-pub">X</button></div>
        </div>
        <?php endif ?>
    </div>

    <button type="button" id="add-pub" class="fw-bold bg-black text-white btn btn-secondary btn-sm mt-2">+ Tambah Publikasi</button>
</div>
