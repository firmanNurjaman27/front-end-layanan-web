<?php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "ngulisik_tour";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Konfigurasi Upload Gambar Lokal
define('UPLOAD_DIR', __DIR__ . '/uploads/');
define('UPLOAD_URL', 'uploads/');

// Helper Format Rupiah
function format_rupiah($angka){
    return "Rp " . number_format($angka, 0, ',', '.');
}
?>
