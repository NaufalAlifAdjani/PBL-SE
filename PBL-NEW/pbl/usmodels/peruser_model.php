<?php

class peruser_model
{
    // =============================
    // HELPER GENERIC
    // =============================
    public static function fetchAllAssoc($res)
    {
        $data = [];
        if (!$res) {
            return $data;
        }
        while ($row = pg_fetch_assoc($res)) {
            $data[] = $row;
        }
        return $data;
    }

    // =============================
    // LIST PERSONIL UNTUK USER (LAMA)
    // =============================

    public static function getAllDosen($conn)
    {
        // $sql = "
        //     SELECT 
        //         id_dosen    AS id,
        //         nama_dosen  AS nama,
        //         jabatan     AS posisi,
        //         foto_profil AS foto,
        //         slug
        //     FROM dosen
        //     ORDER BY nama_dosen ASC
        // ";
        $sql = "SELECT * FROM v_dosen_list";
        return pg_query($conn, $sql);

        return pg_query($conn, $sql);
    }

    public static function getAllMember($conn)
    {
        $sql = "
            SELECT 
                id_pendaftaran_user AS id,
                nama,
                nim
            FROM pendaftaran_user
            WHERE status = 'Diterima'
            ORDER BY nama ASC
        ";

        return pg_query($conn, $sql);
    }

    // =============================
    // DETAIL DOSEN UNTUK USER (LAMA)
    // =============================

    // Data utama dosen berdasarkan slug
    public static function getDosenBySlug($conn, $slug)
    {
        $res = pg_query_params(
            $conn,
            "SELECT * FROM dosen WHERE slug = $1",
            [$slug]
        );

        return pg_fetch_assoc($res) ?: null;
    }

    // Riwayat pendidikan berdasarkan NIP
    public static function getRiwayatPendidikan($conn, $nip)
    {
        if (empty($nip)) {
            return [];
        }

        $res = pg_query_params(
            $conn,
            "SELECT *
             FROM riwayat_pendidikan
             WHERE nip = $1
             ORDER BY jenjang ASC, thn_lulus ASC",
            [$nip]
        );

        return self::fetchAllAssoc($res);
    }

    // Publikasi berdasarkan NIP
    public static function getPublikasi($conn, $nip)
    {
        if (empty($nip)) {
            return [];
        }

        $res = pg_query_params(
            $conn,
            "SELECT *
             FROM publikasi
             WHERE nip = $1
             ORDER BY thn_terbit DESC",
            [$nip]
        );

        return self::fetchAllAssoc($res);
    }

    // KBM (mata kuliah diampu) berdasarkan id_dosen
    public static function getKBM($conn, $id_dosen)
    {
        if (empty($id_dosen)) {
            return [];
        }

        $res = pg_query_params(
            $conn,
            "SELECT 
                 m.nama_matkul
             FROM kbm k
             JOIN mata_kuliah m ON m.id_matkul = k.id_matkul
             WHERE k.id_dosen = $1",
            [$id_dosen]
        );

        return self::fetchAllAssoc($res);
    }

    // ============================================================
    // TAMBAHAN: FUNGSI-FUNGSI YANG DIAMBIL DARI CONTROLLER BARU
    // (tidak mengubah function lama, hanya menambah)
    // ============================================================

    // 1) List dosen (versi lengkap dari v_dosen_list + urutan kepala dulu)
    public static function getDosenList($conn)
    {
        $sql = "
            SELECT * 
            FROM v_dosen_list 
            ORDER BY 
                CASE 
                    WHEN jabatan ILIKE '%kepala%' THEN 1
                    ELSE 2
                END,
                nama_dosen ASC
        ";

        return pg_query($conn, $sql);
    }

    // 2) List member (lengkap: jurusan + portofolio)
    public static function getMemberList($conn)
    {
        $sql = "
            SELECT 
                id_pendaftaran_user,
                nama,
                nim,
                jurusan,
                portofolio
            FROM pendaftaran_user
            WHERE status = 'Diterima'
            ORDER BY nama ASC
        ";

        return pg_query($conn, $sql);
    }

    // 3) Data utama dosen lengkap berdasarkan slug
    public static function getDosenDetailBySlug($conn, $slug)
    {
        $sql = "
            SELECT 
                id_dosen,
                nip,
                nidn,
                nama_dosen,
                jabatan,
                email_dosen,
                bid_kemahiran,
                foto_profil,
                slug
            FROM dosen
            WHERE slug = $1
            LIMIT 1
        ";

        $result = pg_query_params($conn, $sql, [$slug]);
        if (!$result) {
            echo "<div class='container py-5'><h3>Query gagal: " 
                 . htmlspecialchars(pg_last_error($conn)) . "</h3></div>";
            return null;
        }

        $row = pg_fetch_assoc($result);
        return $row ?: null;
    }

    // 4) Riwayat pendidikan (result set, bukan array) berdasarkan NIP
    public static function getPendidikanByNip($conn, $nip)
    {
        if (empty($nip)) {
            return false;
        }

        $sql_pend = "
            SELECT 
                program_studi,
                nama_kampus,
                thn_lulus,
                jenjang
            FROM riwayat_pendidikan
            WHERE nip = $1
            ORDER BY jenjang ASC, thn_lulus DESC
        ";

        return pg_query_params($conn, $sql_pend, [$nip]);
    }

    // 5) Publikasi (result set) berdasarkan NIP
    public static function getPublikasiByNip($conn, $nip)
    {
        if (empty($nip)) {
            return false;
        }

        $sql_pub = "
            SELECT 
                judul,
                thn_terbit,
                link_publikasi
            FROM publikasi
            WHERE nip = $1
            ORDER BY thn_terbit DESC
        ";

        return pg_query_params($conn, $sql_pub, [$nip]);
    }

    // 6) KBM (result set) berdasarkan id_dosen
    public static function getKbmByDosenId($conn, $id_dosen)
    {
        if (empty($id_dosen)) {
            return false;
        }

        $sql_kbm = "
            SELECT 
                kbm.id_matkul,
                mk.nama_matkul
            FROM kbm
            JOIN mata_kuliah mk ON mk.id_matkul = kbm.id_matkul
            WHERE kbm.id_dosen = $1
            ORDER BY mk.nama_matkul ASC
        ";

        return pg_query_params($conn, $sql_kbm, [$id_dosen]);
    }
}


