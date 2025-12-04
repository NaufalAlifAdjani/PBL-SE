<?php
class ProfileModel
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    // 1. Ambil Semua Data (Menggunakan VIEW)
    public function getAllProfiles()
    {
        // Kita cukup SELECT * dari VIEW yang sudah dibuat
        // View 'view_profile_list' sudah mengandung ORDER BY di dalamnya
        $sql = "SELECT * FROM view_profile_list";
        return pg_query($this->conn, $sql);
    }

    // 2. Ambil Satu Data berdasarkan ID (Tetap Query Biasa / Prepared Statement)
    // Kenapa? Karena View biasanya untuk list massal, dan SP untuk aksi. 
    // Mengambil 1 baris untuk diedit tetap paling efisien pakai SELECT biasa.
    public function getProfileById($id)
    {
        $sql = "SELECT * FROM Profile WHERE id = $1";
        $result = pg_query_params($this->conn, $sql, [$id]);
        return pg_fetch_assoc($result);
    }

    // 3. Tambah Data Baru (Menggunakan STORED PROCEDURE)
    public function createProfile($data)
    {
        // Panggil procedure sp_create_profile
        $sql = "CALL sp_create_profile($1, $2, $3, $4, $5, $6)";
        
        $params = [
            $data['title'],
            $data['slug'],
            $data['content'],
            $data['menu_group'],
            (int)$data['display_order'], // Pastikan integer
            $data['is_published']        // Akan dikirim sebagai string 't'/'f' atau boolean, Postgre paham
        ];

        return pg_query_params($this->conn, $sql, $params);
    }

    // 4. Update Data (Menggunakan STORED PROCEDURE)
    public function updateProfile($id, $data)
    {
        // Panggil procedure sp_update_profile
        // Urutan parameter harus sama dengan definisi di SQL tadi
        $sql = "CALL sp_update_profile($1, $2, $3, $4, $5, $6, $7)";

        $params = [
            $id, // Parameter 1 adalah ID
            $data['title'],
            $data['slug'],
            $data['content'],
            $data['menu_group'],
            (int)$data['display_order'],
            $data['is_published']
        ];

        return pg_query_params($this->conn, $sql, $params);
    }

    // 5. Hapus Data (Menggunakan STORED PROCEDURE)
    public function deleteProfile($id)
    {
        // Panggil procedure sp_delete_profile
        $sql = "CALL sp_delete_profile($1)";
        return pg_query_params($this->conn, $sql, [$id]);
    }
}
?>