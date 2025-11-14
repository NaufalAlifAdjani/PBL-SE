<?php
$host = "localhost";
$port = "5432";
$dbname = "segr";       // ganti dengan nama database kamu
$user = "postgres";     // username PostgreSQL
$pass = "your_password"; // password PostgreSQL

try {
    $conn = new PDO("pgsql:host=$host;port=$port;dbname=$dbname", $user, $pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} 
catch (PDOException $e) {
    die("Koneksi database gagal: " . $e->getMessage());
}
?>
