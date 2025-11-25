<?php
class ProfileModel
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    // 1. Ambil Semua Data (Untuk List)
    public function getAllProfiles()
    {
        $sql = "SELECT id, title, slug, menu_group, is_published, updated_at
                FROM Profile
                ORDER BY display_order ASC";
        return pg_query($this->conn, $sql);
    }

    // 2. Ambil Satu Data berdasarkan ID (Untuk Edit)
    public function getProfileById($id)
    {
        $sql = "SELECT * FROM Profile WHERE id = $1";
        $result = pg_query_params($this->conn, $sql, [$id]);
        return pg_fetch_assoc($result);
    }

    // 3. Tambah Data Baru
    public function createProfile($data)
    {
        $sql = "INSERT INTO Profile (title, slug, content, menu_group, display_order, is_published)
                VALUES ($1, $2, $3, $4, $5, $6)";
        
        $params = [
            $data['title'],
            $data['slug'],
            $data['content'],
            $data['menu_group'],
            $data['display_order'],
            $data['is_published']
        ];

        return pg_query_params($this->conn, $sql, $params);
    }

    // 4. Update Data
    public function updateProfile($id, $data)
    {
        $sql = "UPDATE Profile 
                SET title = $1, slug = $2, content = $3, menu_group = $4, display_order = $5, is_published = $6, updated_at = NOW()
                WHERE id = $7";

        $params = [
            $data['title'],
            $data['slug'],
            $data['content'],
            $data['menu_group'],
            $data['display_order'],
            $data['is_published'],
            $id
        ];

        return pg_query_params($this->conn, $sql, $params);
    }

    // 5. Hapus Data
    public function deleteProfile($id)
    {
        $sql = "DELETE FROM Profile WHERE id = $1";
        return pg_query_params($this->conn, $sql, [$id]);
    }
}
?>