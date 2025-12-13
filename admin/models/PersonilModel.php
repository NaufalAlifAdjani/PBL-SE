<?php
require_once __DIR__ . '/LogModel.php';
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

    // Cari function getList, dan ubah parameternya serta isinya
    public function getList($type, $keyword = null, $angkatan_filter = null) // Tambah parameter ke-3
    {
        // 1. LOGIKA UNTUK DOSEN (Tidak berubah, hanya return query lama)
        if ($type === 'dosen') {
            $sql = "SELECT id_dosen, nama_dosen, jabatan, email_dosen, foto_profil, slug 
                    FROM v_dosen_list WHERE 1=1";
            
            $params = [];
            $idx = 1;

            if (!empty($keyword)) {
                $sql .= " AND (nama_dosen ILIKE $" . $idx . " OR jabatan ILIKE $" . $idx . ")";
                $params[] = "%" . $keyword . "%";
                $idx++;
            }

            $sql .= " ORDER BY nama_dosen ASC";

            if (!empty($params)) {
                return pg_query_params($this->conn, $sql, $params);
            } else {
                return pg_query($this->conn, $sql);
            }
        }

        // 2. LOGIKA UNTUK MEMBER (Diupdate)
        if ($type === 'member') {
            // TAMBAHKAN 'angkatan' di SELECT
            $sql = "SELECT id_pendaftaran_member, nama, nim, jurusan, portofolio, angkatan 
                    FROM pendaftaran_member 
                    WHERE status = 'Diterima'";

            $params = [];
            $idx = 1;

            // Filter Keyword
            if (!empty($keyword)) {
                $sql .= " AND (nama ILIKE $" . $idx . " OR nim ILIKE $" . $idx . " OR jurusan ILIKE $" . $idx . ")";
                $params[] = "%" . $keyword . "%";
                $idx++;
            }

            // TAMBAHAN: Filter Angkatan
            if (!empty($angkatan_filter)) {
                $sql .= " AND angkatan = $" . $idx;
                $params[] = $angkatan_filter;
                $idx++;
            }

            $sql .= " ORDER BY angkatan DESC, nama ASC"; // Urutkan angkatan terbaru dulu

            if (!empty($params)) {
                return pg_query_params($this->conn, $sql, $params);
            } else {
                return pg_query($this->conn, $sql);
            }
        }

        return false;
    }

    // TAMBAHKAN FUNCTION INI DI BAWAH getList
    // Gunanya untuk mengambil daftar tahun (angkatan) yang ada di database agar dropdown filter otomatis terisi
    public function getListAngkatan() {
        $sql = "SELECT DISTINCT angkatan FROM pendaftaran_member WHERE status='Diterima' ORDER BY angkatan DESC";
        return pg_query($this->conn, $sql);
    }

    /* =========================================
       DATA FORM DOSEN + RELASI
       ========================================= */

    public function getDosenById($id_dosen)
    {
        $sql = "SELECT 
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
        $sql = "SELECT
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
        $sql = "SELECT 
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

    // public function saveDosen($id_dosen, $nip, $nidn, $nama_dosen, $jabatan, $email_dosen, $slug)
    // {
    //     // --- TAMBAHAN KODE DI SINI ---
    //     // Jika email kosong, ubah jadi NULL agar tidak kena error unique constraint
    //     if (empty($email_dosen)) {
    //         $email_dosen = null;
    //     }
    //     // -----------------------------

    //     if ($id_dosen > 0) {
    //         $sql = "UPDATE dosen
    //             SET nip = $1,
    //                 nidn = $2,
    //                 nama_dosen = $3,
    //                 jabatan = $4,
    //                 email_dosen = $5,
    //                 slug = $6
    //             WHERE id_dosen = $7
    //         ";
    //         $params = [$nip, $nidn, $nama_dosen, $jabatan, $email_dosen, $slug, $id_dosen];
            
    //         // Debugging (opsional, hapus jika sudah fix)
    //         // var_dump($params); die(); 

    //         $res = pg_query_params($this->conn, $sql, $params);
    //         if (!$res) {
    //             die('Update dosen gagal: ' . pg_last_error($this->conn));
    //         }
    //         return $id_dosen;
    //     }

    //     $sql = "INSERT INTO dosen (nip, nidn, nama_dosen, jabatan, email_dosen, slug)
    //         VALUES ($1, $2, $3, $4, $5, $6)
    //         RETURNING id_dosen
    //     ";
    //     $params = [$nip, $nidn, $nama_dosen, $jabatan, $email_dosen, $slug];
        
    //     $res = pg_query_params($this->conn, $sql, $params);
    //     if (!$res) {
    //         die('Insert dosen gagal: ' . pg_last_error($this->conn));
    //     }
    //     $row = pg_fetch_assoc($res);
    //     return (int)$row['id_dosen'];
    // }

        public function saveDosen($id_dosen, $nip, $nidn, $nama_dosen, $jabatan, $email_dosen, $slug)
    {
        $logger = new LogModel($this->conn);
        // --- TAMBAHAN KODE DI SINI ---
        // Jika email kosong, ubah jadi NULL agar tidak kena error unique constraint
        if (empty($email_dosen)) {
            $email_dosen = null;
        }
        // -----------------------------

        if ($id_dosen > 0) {
            $sql = "UPDATE dosen
                SET nip = $1,
                    nidn = $2,
                    nama_dosen = $3,
                    jabatan = $4,
                    email_dosen = $5,
                    slug = $6
                WHERE id_dosen = $7
            ";
            $params = [$nip, $nidn, $nama_dosen, $jabatan, $email_dosen, $slug, $id_dosen];
            
            // Debugging (opsional, hapus jika sudah fix)
            // var_dump($params); die(); 

            $res = pg_query_params($this->conn, $sql, $params);
            if ($res) {
                // === LOG ACTIVITY: UPDATE ===
                $logger->catat(
                    'UPDATE', 
                    'dosen', 
                    $id_dosen, 
                    "Mengupdate data dosen: $nama_dosen"
                );
            }
            return $id_dosen;
        }

        $sql = "INSERT INTO dosen (nip, nidn, nama_dosen, jabatan, email_dosen, slug)
            VALUES ($1, $2, $3, $4, $5, $6)
            RETURNING id_dosen";
        $params = [$nip, $nidn, $nama_dosen, $jabatan, $email_dosen, $slug];
        
        $res = pg_query_params($this->conn, $sql, $params);
        if (!$res) {
            die('Insert dosen gagal: ' . pg_last_error($this->conn));
        }
        $row = pg_fetch_assoc($res);
        $new_id = (int)$row['id_dosen'];

        // === LOG ACTIVITY: CREATE ===
        $logger->catat(
            'CREATE', 
            'dosen', 
            $new_id, 
            "Menambahkan dosen baru: $nama_dosen"
        );

        return $new_id;
    }

    public function deleteRiwayatByNip($nip)
    {
        pg_query_params($this->conn, "DELETE FROM riwayat_pendidikan WHERE nip = $1", [$nip]);
    }

    public function insertRiwayat($nip, $jenjang, $program_studi, $nama_kampus, $thn_lulus)
    {
        $sql = "INSERT INTO riwayat_pendidikan (nip, jenjang, program_studi, nama_kampus, thn_lulus)
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
        $sql = "INSERT INTO publikasi (nip, judul, thn_terbit, link_publikasi)
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
        $sql = "INSERT INTO kbm (id_dosen, id_matkul)
            VALUES ($1, $2)
        ";
        pg_query_params($this->conn, $sql, [$id_dosen, $id_matkul]);
    }

    /* =========================================
       DETAIL & DELETE DOSEN
       ========================================= */

    public function getDosenDetailById($id)
    {
        $sql = "SELECT 
                id_dosen,
                nip,
                nidn,
                nama_dosen,
                jabatan,
                email_dosen,
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
        $logger = new LogModel($this->conn); // Init Logger

        // Ambil nama dulu buat log
        $q = pg_query_params($this->conn, "SELECT nama_dosen, nip FROM dosen WHERE id_dosen = $1", [$id]);
        $d = pg_fetch_assoc($q);
        $nama_dosen = $d['nama_dosen'] ?? 'Unknown';

        // Hapus child data
        pg_query_params($this->conn, "DELETE FROM kbm WHERE id_dosen = $1", [$id]);
        if ($d && !empty($d['nip'])) {
            pg_query_params($this->conn, "DELETE FROM riwayat_pendidikan WHERE nip = $1", [$d['nip']]);
            pg_query_params($this->conn, "DELETE FROM publikasi WHERE nip = $1", [$d['nip']]);
        }

        $res = pg_query_params($this->conn, "DELETE FROM dosen WHERE id_dosen = $1", [$id]);
        
        if (!$res) {
            die('Delete dosen gagal: ' . pg_last_error($this->conn));
        } else {
            // LOG ACTIVITY
            $logger->catat('DELETE', 'dosen', $id, "Menghapus data dosen: $nama_dosen");
        }
    }

    /* =========================================
       MEMBER (ADMIN)
       ========================================= */

    public function callDeleteMemberSP($id)
    {
        $logger = new LogModel($this->conn); // Init Logger

        // Ambil data buat log (karena setelah dihapus datanya hilang)
        $q = pg_query_params($this->conn, "SELECT nama FROM pendaftaran_member WHERE id_pendaftaran_member = $1", [$id]);
        $m = pg_fetch_assoc($q);
        $nama_member = $m['nama'] ?? 'Unknown';

        $res = pg_query_params($this->conn, "CALL sp_delete_member($1)", [$id]);
        
        if (!$res) {
            die('Delete member gagal: ' . pg_last_error($this->conn));
        } else {
            // LOG ACTIVITY
            $logger->catat('DELETE', 'pendaftaran_member', $id, "Menghapus member lab: $nama_member");
        }
    }

    public function getMemberById($id)
    {
        $sql = "SELECT 
                id_pendaftaran_member,
                nama,
                nim,
                portofolio
            FROM pendaftaran_member
            WHERE id_pendaftaran_member = $1
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
        $logger = new LogModel($this->conn); // Init Logger

        $sql = "UPDATE pendaftaran_member SET nama = $1, nim = $2, portofolio = $3 WHERE id_pendaftaran_member = $4";
        $params = [$nama, $nim, $link, $id];

        $res = pg_query_params($this->conn, $sql, $params);
        
        if (!$res) {
            die('Update member gagal: ' . pg_last_error($this->conn));
        } else {
            // LOG ACTIVITY
            $logger->catat('UPDATE', 'pendaftaran_member', $id, "Mengupdate data member: $nama");
        }
    }
}
