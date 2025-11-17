<?php
<<<<<<< HEAD
// Data koneksi PostgreSQL
$host = "localhost";
$port = "5432";
$dbname = "web_se"; // Ganti dengan nama database-mu
$user = "postgres"; // User default Postgres
$password = "secret"; // Ganti dengan password-mu

// String koneksi
$conn_string = "host={$host} port={$port} dbname={$dbname} user={$user} password={$password}";

// Buat koneksi
$conn = pg_connect($conn_string);

// Cek koneksi
if (!$conn) {
    die("Koneksi ke PostgreSQL gagal: " . pg_last_error());
}
?>
=======
$host = "localhost";
$port = "5432";
$dbname = "PBL";       
$user = "postgres";     
$pass = "12345678"; 

try {
    $conn = new PDO("pgsql:host=$host;port=$port;dbname=$dbname", $user, $pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} 
catch (PDOException $e) {
    die("Koneksi database gagal: " . $e->getMessage());
}
?>
>>>>>>> 7e2e61013c6e947fbb8a505c811cfe95b09c1202
