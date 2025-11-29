<?php
// Data koneksi PostgreSQL
$host = "localhost";
$port = "5432";
$dbname = "web_se"; // Ganti dengan nama database-mu
$user = "postgres"; // User default Postgres
$password = "12345678"; // Ganti dengan password-mu

date_default_timezone_set('Asia/Jakarta'); // menambahkan timezone
// String koneksi
$conn_string = "host={$host} port={$port} dbname={$dbname} user={$user} password={$password}";

// Buat koneksi
$conn = pg_connect($conn_string);

// Cek koneksi
if (!$conn) {
    die("Koneksi ke PostgreSQL gagal: " . pg_last_error());
}
?>
