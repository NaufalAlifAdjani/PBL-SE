<?php
// Wajib: Import class PHPMailer dari namespace
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// Wajib: Load library via Composer Autoloader
require '../../vendor/autoload.php';

// Instansiasi objek PHPMailer
$mail = new PHPMailer(true);

try {
    // --- 1. Konfigurasi Server SMTP (Contoh pakai Gmail) ---
    $mail->SMTPDebug = SMTP::DEBUG_SERVER;      // Aktifkan ini jika ingin lihat log error lengkap
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    
    // GANTI DENGAN EMAIL & APP PASSWORD ANDA
    $mail->Username   = 'alexazriel33@gmail.com';     
    $mail->Password   = 'lnsn ssyr ugvp nbxe';     // Pakai App Password, BUKAN password login biasa!
    
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS; // Untuk Port 465
    $mail->Port       = 465;

    // --- 2. Pengirim & Penerima ---
    $mail->setFrom('alexazriel33@gmail.com', 'Nama Pengirim'); 
    $mail->addAddress('dzikra.zen@gmail.com', 'Nama Penerima'); 

    // --- 3. Isi Email ---
    $mail->isHTML(true);
    $mail->Subject = 'Test Email dari Laragon';
    $mail->Body    = 'Halo! Ini adalah email tes menggunakan <b>PHPMailer via Composer</b>.';

    // Kirim
    $mail->send();
    echo 'Sukses! Email berhasil dikirim.';

} catch (Exception $e) {
    echo "Gagal mengirim email. Error: {$mail->ErrorInfo}";
}