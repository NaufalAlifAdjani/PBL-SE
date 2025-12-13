<?php
// Pastikan path ini sesuai dengan struktur foldermu
require_once __DIR__ . '/../models/ProfileModel.php';

class ProfileController
{
    private $model;

    public function __construct($conn)
    {
        $this->model = new ProfileModel($conn);
    }

    // --- Halaman List ---
// --- Halaman List ---
    public function index()
    {
        // 1. Ambil Resource dari Model
        $result_resource = $this->model->getAllProfiles();

        // 2. Konversi Resource menjadi Array Asosiatif
        $profiles = pg_fetch_all($result_resource);

        // 3. Jaga-jaga jika database kosong (agar tidak error di View)
        if (!$profiles) {
            $profiles = [];
        }

        // --- DEBUGGING (OPSIONAL) ---
        // Uncomment 2 baris di bawah ini untuk melihat isi data asli dari database
        // echo "<pre>"; print_r($profiles); echo "</pre>"; die();

        // 4. Kirim variabel $profiles ke View
        // Pastikan di View kamu mengubah loop-nya menjadi foreach ($profiles as ...)
        include __DIR__ . '/../views/manage_profile_view.php';
    }

    // --- Halaman Form (Tambah/Edit) ---
    public function form($id = null)
    {
        $data = null;
        $page_title = "Tambah Halaman Profile";

        // Jika ada ID, berarti ini EDIT
        if ($id) {
            $data = $this->model->getProfileById($id);
            if (!$data) {
                die("Data tidak ditemukan.");
            }
            $page_title = "Edit Halaman Profile";
        }

        // Panggil View Form
        include __DIR__ . '/../views/profile_form_view.php';
    }

    // --- Proses Simpan (Create/Update) ---
    public function save()
    {
        $id_post = $_POST['id'] ?? null;
        
        // Siapkan data array
        $data = [
            'title' => $_POST['title'],
            'slug' => $_POST['slug'],
            'content' => $_POST['content'],
            'menu_group' => $_POST['menu_group'],
            'display_order' => (int)$_POST['display_order'],
            'is_published' => isset($_POST['is_published']) ? 't' : 'f'
        ];

        $action_status = '';

        if ($id_post) {
            // Update
            $result = $this->model->updateProfile($id_post, $data);
            $action_status = 'updated';
        } else {
            // Insert
            $result = $this->model->createProfile($data);
            $action_status = 'created';
        }

        // Redirect hasil
        if ($result) {
            header("Location: manage_profile.php?msg_status=" . $action_status);
        } else {
            header("Location: manage_profile.php?msg_status=error");
        }
        exit;
    }

    // --- Proses Hapus ---
    public function delete($id) 
    {
        if ($id) {
            // Cukup kirim ID saja, biarkan Model yang mencari judulnya untuk log (sesuai logika di Model kamu)
            $result = $this->model->deleteProfile($id); 
            
            if ($result) {
                header("Location: manage_profile.php?msg_status=deleted");
            } else {
                header("Location: manage_profile.php?msg_status=error");
            }
        } else {
            header("Location: manage_profile.php?msg_status=no_id");
        }
        exit;
    }
}
?>