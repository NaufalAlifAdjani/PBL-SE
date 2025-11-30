<?php
// Data sudah dikirim dari controller: $dosen dan $member.
?>

<div class="container-fluid py-4" id="personil-admin">

    <h2 class="fw-bold mb-4">Dosen</h2>

    <div class="mb-3">
        <a href="manage_personil.php?action=form" class="btn btn-primary-admin mb-3">
            Tambah Dosen
        </a>
    </div>

    <?php if (!isset($dosen) || !$dosen || pg_num_rows($dosen) === 0): ?>
        <p class="text-muted">Belum ada data dosen.</p>
    <?php else: ?>
        <table class="table table-striped table-bordered align-middle">
            <thead class="table-light">
                <tr>
                    <th>Nama Dosen</th>
                    <th>Jabatan</th>
                    <th style="width:180px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($d = pg_fetch_assoc($dosen)): ?>
                    <?php
                        $id_dosen = $d['id_dosen'] ?? null;
                        $nama     = $d['nama_dosen'] ?? '';
                        $jabatan  = $d['jabatan'] ?? '';
                    ?>
                    <tr>
                        <td><?= htmlspecialchars($nama); ?></td>
                        <td><?= htmlspecialchars($jabatan); ?></td>
                        <td>
                            <?php if ($id_dosen): ?>
                                <a href="manage_personil.php?action=form&id=<?= (int)$id_dosen; ?>"
                                class="btn btn-sm btn-success me-2">
                                    Edit
                                </a>

                                <a href="manage_personil.php?action=delete_dosen&id=<?= (int)$id_dosen; ?>"
                                class="btn btn-sm btn-danger"
                                onclick="return confirm('Yakin ingin menghapus dosen ini?');">
                                    Delete
                                </a>
                            <?php else: ?>
                                <span class="text-muted small">ID tidak valid</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php endif; ?>


    <h2 class="fw-bold mt-5 mb-4">Member</h2>

    <?php if (!isset($member) || !$member || pg_num_rows($member) === 0): ?>
        <p class="text-muted">Belum ada data member.</p>
    <?php else: ?>
        <table class="table table-striped table-bordered align-middle">
            <thead class="table-light">
                <tr>
                    <th>Nama</th>
                    <th>NIM</th>
                    <th>Jurusan</th>
                    <th style="width:180px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($m = pg_fetch_assoc($member)): ?>
                    <?php
                        $id_member = $m['id_pendaftaran_user'] ?? null;
                        $nama_m    = $m['nama'] ?? '';
                        $nim_m     = $m['nim'] ?? '';
                        $jurusan_m = $m['jurusan'] ?? '';
                    ?>
                    <tr>
                        <td><?= htmlspecialchars($nama_m); ?></td>
                        <td><?= htmlspecialchars($nim_m); ?></td>
                        <td><?= htmlspecialchars($jurusan_m); ?></td>
                       <td>
                        <?php if ($id_member): ?>
                            <a href="manage_personil.php?action=form_member&id=<?= (int)$id_member; ?>"
                                class="btn btn-sm btn-success me-2">
                                    Edit
                            </a>

                            <a href="manage_personil.php?action=delete_member&id=<?= (int)$id_member; ?>"
                            class="btn btn-sm btn-danger"
                            onclick="return confirm('Yakin ingin menghapus member ini?');">
                                Delete
                            </a>
                        <?php else: ?>
                            <span class="text-muted small">ID tidak valid</span>
                        <?php endif; ?>
                    </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php endif; ?>

</div>





