<?php
// Lokasi: admin/services/EmailServices.php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/../../vendor/autoload.php';

class EmailServices {
    private $mail;
    private $model; // Variabel untuk menampung GeeksModel

    // CONSTRUCTOR MENERIMA MODEL (Dependency Injection)
    public function __construct($geeksModel) {
        $this->model = $geeksModel; // Simpan model yang dikirim dari action
        
        $this->mail = new PHPMailer(true);
        
        // --- KONFIGURASI SMTP ---
        $this->mail->isSMTP();
        $this->mail->Host       = 'smtp.gmail.com';
        $this->mail->SMTPAuth   = true;
        $this->mail->Username   = 'alexazriel33@gmail.com'; 
        $this->mail->Password   = 'lnsn ssyr ugvp nbxe'; // App Password
        $this->mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $this->mail->Port       = 465;
        $this->mail->setFrom('alexazriel33@gmail.com', 'Admin Komunitas SE');
        $this->mail->isHTML(true);
    }

    // --- FUNGSI KIRIM EMAIL DITERIMA ---
    public function sendApprovalEmail($idUser, $emailPenerima, $namaPenerima) {
        try {
            $this->mail->clearAddresses();
            $this->mail->addAddress($emailPenerima, $namaPenerima);
            $this->mail->Subject = 'Selamat! Pendaftaran Anda Diterima';
            $this->mail->Body    = "
                <h3>Halo, $namaPenerima!</h3>
                <p>Selamat, Anda telah <b>DITERIMA</b> menjadi member lab Software Engineering.</p>
                <br><p>Salam,<br>Admin</p>
            ";
            
            $this->mail->send();
            
            // Minta tolong Model untuk catat log (Sent)
            $this->model->catatLogEmail($idUser, $emailPenerima, 'Sent');
            return true;

        } catch (Exception $e) {
            // Minta tolong Model untuk catat log (Failed)
            $this->model->catatLogEmail($idUser, $emailPenerima, 'Failed');
            return false;
        }
    }

    // --- FUNGSI KIRIM EMAIL DITOLAK ---
    public function sendRejectionEmail($idUser, $emailPenerima, $namaPenerima) {
        try {
            $this->mail->clearAddresses();
            $this->mail->addAddress($emailPenerima, $namaPenerima);
            $this->mail->Subject = 'Update Status Pendaftaran SE Geeks';
            $this->mail->Body    = "
                <h3>Halo, $namaPenerima.</h3>
                <p>Terima kasih telah mendaftar. Mohon maaf, pendaftaran Anda <b>BELUM DAPAT KAMI TERIMA</b> saat ini.</p>
                <p>Tetap semangat dan coba lagi di kesempatan berikutnya.</p>
                <br><p>Salam,<br>Admin</p>
            ";
            
            $this->mail->send();
            
            // Minta tolong Model untuk catat log (Sent)
            $this->model->catatLogEmail($idUser, $emailPenerima, 'Sent');
            return true;

        } catch (Exception $e) {
            // Minta tolong Model untuk catat log (Failed)
            $this->model->catatLogEmail($idUser, $emailPenerima, 'Failed');
            return false;
        }
    }
}
?>