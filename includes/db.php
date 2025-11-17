<?php
$host = "localhost";
$port = "5432";
$dbname = "pbl";
$user = "postgres";
$pass = "awsome";

try {
    $conn = new PDO("pgsql:host=$host;port=$port;dbname=$dbname", $user, $pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $conn->exec("SET TIMEZONE = 'Asia/Jakarta'");
}
catch (PDOException $e) {
    die("Koneksi database gagal: " . $e->getMessage());
}
?>
