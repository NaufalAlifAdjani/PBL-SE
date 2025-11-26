<?php
include 'includes/header.php'; // Memanggil header

// 1. Query BARU: Menggabungkan tabel 'dosen' dan 'pendaftaran_user'
$query_all = "
    (SELECT
         id_dosen AS id,
         nama_dosen AS nama,
         bid_kemahiran AS posisi, -- 'bid_kemahiran' akan jadi Posisi
         foto_profil AS foto,
         'dosen' AS tipe, -- Penanda bahwa ini adalah Dosen
         1 AS urutan_grup -- Dosen akan di atas
     FROM dosen)

    UNION ALL

    (SELECT
         id_pendaftaran_user AS id,
         nama AS nama,
         'Anggota SE Geeks' AS posisi, -- Kita beri Posisi default
         NULL AS foto, -- Tabel pendaftaran_user tidak punya foto
         'mahasiswa' AS tipe, -- Penanda bahwa ini Mahasiswa
         2 AS urutan_grup -- Mahasiswa akan di bawah Dosen
     FROM pendaftaran_user
     WHERE status = 'Diterima')

    ORDER BY urutan_grup ASC, id ASC; -- Urutkan Dosen dulu, baru Mahasiswa
";

// $all_personil = pg_query($conn, $query_all); error jd tak hide
?>

<style>
    .card-personil {
        border: 1px solid #e9ecef;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 4px 12px rgba(0,0,0,0.03);
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .card-personil:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 20px rgba(0,0,0,0.07);
    }
    .card-personil img {
        width: 100%;
        height: 300px; /* Tinggi gambar seragam */
        object-fit: cover; /* Biar gambar tidak gepeng */
    }
    .card-personil .card-body {
        padding: 20px;
    }
</style>

<div class="container my-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="fw-bold">Tim Lab SE</h1>
            <p class="lead text-muted">Kenali para profesional, peneliti, dan anggota komunitas di Laboratorium Software Engineering kami.</p>
        </div>
    </div>

    <div class="row g-4">
        <?php
        if ($all_personil && pg_num_rows($all_personil) > 0) {
            while($row = pg_fetch_assoc($all_personil)) {

                // 2. Logika Path Gambar (BARU)
                // Jika ini Dosen & fotonya ada, pakai foto itu.
                // Jika tidak (misal Mahasiswa), pakai default.
                $foto_path = 'uploads/personil/default.png'; // Asumsi kamu punya default.png
                if ($row['tipe'] == 'dosen' && !empty($row['foto'])) {
                    $foto_path = 'uploads/personil/' . htmlspecialchars($row['foto']);
                }

                // 3. Buat Anchor ID unik
                $anchor_id = 'personil-' . $row['tipe'] . '-' . $row['id'];
        ?>

        <div class="col-12 col-sm-6 col-lg-4">
            <div id="<?php echo $anchor_id; ?>">
                <div class="card card-personil h-100">
                    <img src="<?php echo $foto_path; ?>" class="card-img-top" alt="<?php echo htmlspecialchars($row['nama']); ?>">
                    <div class="card-body">
                        <h5 class="fw-bold mb-1"><?php echo htmlspecialchars($row['nama']); ?></h5>
                        <p class="text-muted"><?php echo htmlspecialchars($row['posisi']); ?></p>
                    </div>
                </div>
            </div>
        </div>

        <?php
            }
        } else {
            echo "<div class='alert alert-info'>Belum ada data personil atau anggota.</div>";
        }
        ?>
    </div>
</div>

<?php
include 'includes/footer.php';
?>
