<?php

class PersonilModel
{
    /** @var resource PostgreSQL connection */
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    /* =========================================
       LIST (dipakai index admin)  -> getList()
       ========================================= */

    public function getList($type)
    {
        if ($type === 'dosen') {
            $sql = "
                SELECT 
                    id_dosen,
                    nama_dosen,
                    jabatan,
                    email_dosen,
                    foto_profil,
                    slug
                FROM v_dosen_list
                ORDER BY nama_dosen ASC
            ";
            return pg_query($this->conn, $sql);
        }

        if ($type === 'member') {
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
            return pg_query($this->conn, $sql);
        }

        return false;
    }

    /* =========================================
       DATA FORM DOSEN + RELASI
       ========================================= */

    public function getDosenById($id_dosen)
    {
        $sql = "
            SELECT 
                id_dosen,
                nip,
                nidn,
                nama_dosen,
                jabatan,
                email_dosen,
                slug,
                foto_profil
            FROM dosen
            WHERE id_dosen = $1
            LIMIT 1
        ";
        $res = pg_query_params($this->conn, $sql, [$id_dosen]);
        if (!$res) {
            return null;
        }
        $row = pg_fetch_assoc($res);
        return $row ?: null;
    }

    public function getRiwayatByNip($nip)
    {
        $sql = "
            SELECT
                jenjang,
                program_studi,
                nama_kampus,
                thn_lulus
            FROM riwayat_pendidikan
            WHERE nip = $1
            ORDER BY thn_lulus ASC
        ";
        $res = pg_query_params($this->conn, $sql, [$nip]);
        if (!$res) {
            return [];
        }
        return pg_fetch_all($res) ?: [];
    }

    public function getPublikasiByNip($nip)
    {
        $sql = "
            SELECT
                judul,
                thn_terbit,
                link_publikasi
            FROM publikasi
            WHERE nip = $1
            ORDER BY thn_terbit DESC
        ";
        $res = pg_query_params($this->conn, $sql, [$nip]);
        if (!$res) {
            return [];
        }
        return pg_fetch_all($res) ?: [];
    }

    public function getKbmByDosenId($id_dosen)
    {
        $sql = "
            SELECT
                id_matkul
            FROM kbm
            WHERE id_dosen = $1
        ";
        $res = pg_query_params($this->conn, $sql, [$id_dosen]);
        if (!$res) {
            return [];
        }
        return pg_fetch_all($res) ?: [];
    }

    public function getAllMatkul()
    {
        $sql = "
            SELECT 
                id_matkul,
                nama_matkul
            FROM mata_kuliah
            ORDER BY nama_matkul ASC
        ";
        $res = pg_query($this->conn, $sql);
        if (!$res) {
            return [];
        }
        return pg_fetch_all($res) ?: [];
    }

    /* =========================================
       SIMPAN DOSEN + RELASI
       (logika sama kayak di controller lama)
       ========================================= */

    public function saveDosen($id_dosen, $nip, $nidn, $nama_dosen, $jabatan, $email_dosen, $bid_kemahiran, $slug)
    {
        if ($id_dosen > 0) {
            $sql = "
                UPDATE dosen
                SET nip = $1,
                    nidn = $2,
                    nama_dosen = $3,
                    jabatan = $4,
                    email_dosen = $5,
                    bid_kemahiran = $6,
                    slug = $7
                WHERE id_dosen = $8
            ";
            $params = [$nip, $nidn, $nama_dosen, $jabatan, $email_dosen, $bid_kemahiran, $slug, $id_dosen];
            $res = pg_query_params($this->conn, $sql, $params);
            if (!$res) {
                die('Update dosen gagal: ' . pg_last_error($this->conn));
            }
            return $id_dosen;
        }

        $sql = "
            INSERT INTO dosen (nip, nidn, nama_dosen, jabatan, email_dosen, bid_kemahiran, slug)
            VALUES ($1, $2, $3, $4, $5, $6, $7)
            RETURNING id_dosen
        ";
        $params = [$nip, $nidn, $nama_dosen, $jabatan, $email_dosen, $bid_kemahiran, $slug];
        $res = pg_query_params($this->conn, $sql, $params);
        if (!$res) {
            die('Insert dosen gagal: ' . pg_last_error($this->conn));
        }
        $row = pg_fetch_assoc($res);
        return (int)$row['id_dosen'];
    }

    public function deleteRiwayatByNip($nip)
    {
        pg_query_params($this->conn, "DELETE FROM riwayat_pendidikan WHERE nip = $1", [$nip]);
    }

    public function insertRiwayat($nip, $jenjang, $program_studi, $nama_kampus, $thn_lulus)
    {
        $sql = "
            INSERT INTO riwayat_pendidikan (nip, jenjang, program_studi, nama_kampus, thn_lulus)
            VALUES ($1, $2, $3, $4, $5)
        ";
        pg_query_params($this->conn, $sql, [$nip, $jenjang, $program_studi, $nama_kampus, $thn_lulus]);
    }

    public function deletePublikasiByNip($nip)
    {
        pg_query_params($this->conn, "DELETE FROM publikasi WHERE nip = $1", [$nip]);
    }

    public function insertPublikasi($nip, $judul, $thn, $link)
    {
        $sql = "
            INSERT INTO publikasi (nip, judul, thn_terbit, link_publikasi)
            VALUES ($1, $2, $3, $4)
        ";
        pg_query_params($this->conn, $sql, [$nip, $judul, $thn, $link]);
    }

    public function deleteKbmByDosen($id_dosen)
    {
        pg_query_params($this->conn, "DELETE FROM kbm WHERE id_dosen = $1", [$id_dosen]);
    }

    public function insertKbm($id_dosen, $id_matkul)
    {
        $sql = "
            INSERT INTO kbm (id_dosen, id_matkul)
            VALUES ($1, $2)
        ";
        pg_query_params($this->conn, $sql, [$id_dosen, $id_matkul]);
    }

    /* =========================================
       DETAIL & DELETE DOSEN
       ========================================= */

    public function getDosenDetailById($id)
    {
        $sql = "
            SELECT 
                id_dosen,
                nip,
                nidn,
                nama_dosen,
                jabatan,
                email_dosen,
                bid_kemahiran
            FROM dosen
            WHERE id_dosen = $1
            LIMIT 1
        ";
        $result = pg_query_params($this->conn, $sql, [$id]);
        if (!$result) {
            return null;
        }
        $row = pg_fetch_assoc($result);
        return $row ?: null;
    }

    public function deleteDosenCascade($id)
    {
        // hapus KBM
        pg_query_params($this->conn, "DELETE FROM kbm WHERE id_dosen = $1", [$id]);

        // ambil nip utk hapus riwayat & publikasi
        $q = pg_query_params($this->conn, "SELECT nip, foto_profil FROM dosen WHERE id_dosen = $1", [$id]);
        $d = pg_fetch_assoc($q);

        if ($d && !empty($d['nip'])) {
            pg_query_params($this->conn, "DELETE FROM riwayat_pendidikan WHERE nip = $1", [$d['nip']]);
            pg_query_params($this->conn, "DELETE FROM publikasi          WHERE nip = $1", [$d['nip']]);
        }

        $res = pg_query_params($this->conn, "DELETE FROM dosen WHERE id_dosen = $1", [$id]);
        if (!$res) {
            die('Delete dosen gagal: ' . pg_last_error($this->conn));
        }
    }

    /* =========================================
       MEMBER (ADMIN)
       ========================================= */

    public function callDeleteMemberSP($id)
    {
        $res = pg_query_params($this->conn, "CALL sp_delete_member($1)", [$id]);
        if (!$res) {
            die('Delete member gagal: ' . pg_last_error($this->conn));
        }
    }

    public function getMemberById($id)
    {
        $sql = "
            SELECT 
                id_pendaftaran_user,
                nama,
                nim,
                portofolio
            FROM pendaftaran_user
            WHERE id_pendaftaran_user = $1
            LIMIT 1
        ";
        $res = pg_query_params($this->conn, $sql, [$id]);
        if (!$res) {
            return null;
        }
        $row = pg_fetch_assoc($res);
        return $row ?: null;
    }

    public function updateMember($id, $nama, $nim, $link)
    {
        $sql = "
            UPDATE pendaftaran_user
            SET nama = $1,
                nim  = $2,
                portofolio = $3
            WHERE id_pendaftaran_user = $4
        ";
        $params = [$nama, $nim, $link, $id];

        $res = pg_query_params($this->conn, $sql, $params);
        if (!$res) {
            die('Update member gagal: ' . pg_last_error($this->conn));
        }
    }
}
