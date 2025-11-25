<?php
// admin/debug.php
ini_set('display_errors', 1);
error_reporting(E_ALL);

echo "<h2>🔍 DIAGNOSA DATABASE POSTGRESQL</h2>";

// 1. Cek Koneksi
echo "1. Cek File db.php... ";
if (file_exists('../includes/db.php')) {
    include '../includes/db.php';
    echo "<span style='color:green'>DITEMUKAN.</span><br>";
} else {
    die("<span style='color:red'>GAGAL. File ../includes/db.php tidak ada.</span>");
}

echo "2. Cek Status Koneksi... ";
if ($conn) {
    echo "<span style='color:green'>BERHASIL TERHUBUNG.</span><br>";
    echo "Status: " . pg_connection_status($conn) . "<br>";
} else {
    die("<span style='color:red'>GAGAL KONEKSI.</span>");
}

// 3. Cek Daftar Tabel yang Ada
echo "<hr><h3>3. Cek Tabel di Database ini:</h3>";
$q_tables = "SELECT table_name FROM information_schema.tables WHERE table_schema = 'public'";
$res_tables = pg_query($conn, $q_tables);
$found_profile = false;

echo "<ul>";
while ($t = pg_fetch_assoc($res_tables)) {
    echo "<li>" . $t['table_name'] . "</li>";
    // Cek apakah ada tabel bernama 'profile' atau 'Profile'
    if (strtolower($t['table_name']) == 'profile') {
        $found_profile = true;
        $real_table_name = $t['table_name'];
    }
}
echo "</ul>";

if (!$found_profile) {
    die("<h3 style='color:red'>❌ FATAL: Tabel 'Profile' TIDAK DITEMUKAN di database! Kamu harus buat tabel dulu.</h3>");
} else {
    echo "<h3 style='color:green'>✅ Tabel ditemukan dengan nama asli: '$real_table_name'</h3>";
}

// 4. Cek Isi Data
echo "<hr><h3>4. Cek Isi Data Tabel '$real_table_name':</h3>";
// Kita pakai nama tabel asli yang ditemukan tadi
$q_data = "SELECT * FROM \"$real_table_name\""; 
$res_data = pg_query($conn, $q_data);
$jumlah_data = pg_num_rows($res_data);

echo "Jumlah Baris Data: <strong>$jumlah_data</strong><br><br>";

if ($jumlah_data == 0) {
    echo "<div style='background:yellow; padding:10px; border:1px solid orange;'>
            ⚠️ <strong>PERINGATAN:</strong> Tabel ada, tapi <strong>KOSONG</strong>.<br>
            Inilah sebabnya tidak ada yang muncul di website.<br>
            Silakan jalankan perintah <strong>INSERT</strong> SQL di pgAdmin/DBeaver.
          </div>";
} else {
    echo "<table border='1' cellpadding='5'>";
    echo "<tr><th>ID</th><th>Title</th><th>Slug</th><th>Menu Group</th></tr>";
    while ($row = pg_fetch_assoc($res_data)) {
        echo "<tr>";
        echo "<td>" . $row['id'] . "</td>";
        echo "<td>" . $row['title'] . "</td>";
        echo "<td>" . $row['slug'] . "</td>";
        echo "<td>" . $row['menu_group'] . "</td>";
        echo "</tr>";
    }
    echo "</table>";
    echo "<br><h3 style='color:green'>✅ Data ADA! Jika di sini muncul tapi di MVC tidak, berarti masalah di Kode Model.</h3>";
}
?>