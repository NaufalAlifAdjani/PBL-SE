<?php

class Peruser_model
{

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

       //LIST PERSONIL UNTUK USER (DOSEN + MEMBER)
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

       //DETAIL DOSEN UNTUK USER
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
             ORDER BY thn_lulus DESC",
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
}

