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
    public function index()
    {
        $result = $this->model->getAllProfiles();
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

        if ($id_post) {
            // Update
            $result = $this->model->updateProfile($id_post, $data);
        } else {
            // Insert
            $result = $this->model->createProfile($data);
        }

        // Redirect hasil
        if ($result) {
            header("Location: manage_profile.php?status=success");
        } else {
            header("Location: manage_profile.php?status=error");
        }
        exit;
    }

    // --- Proses Hapus ---
    public function delete($id)
    {
        if ($id) {
            $result = $this->model->deleteProfile($id);
            if ($result) {
                header("Location: manage_profile.php?status=deleted");
            } else {
                header("Location: manage_profile.php?status=error");
            }
        } else {
            header("Location: manage_profile.php?status=no_id");
        }
        exit;
    }
}
?>