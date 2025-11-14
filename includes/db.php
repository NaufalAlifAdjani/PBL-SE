<?php
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
