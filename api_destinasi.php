<?php
// Tahap 2: Konfigurasi CORS & JSON
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");

require_once 'config.php';

// Tahap 3: Logika Endpoint
$id = isset($_GET['id']) ? (int)$_GET['id'] : null;
$search = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : null;
$limit = isset($_GET['limit']) ? (int)$_GET['limit'] : null;

// Endpoint 3: Detail berdasarkan ID
if ($id) {
    $sql = "SELECT * FROM destinasi WHERE id = $id";
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        echo json_encode($result->fetch_assoc()); // Mengirim 1 object, bukan array
    } else {
        http_response_code(404);
        echo json_encode(["status" => false, "message" => "Data tidak ditemukan"]);
    }
} 
// Endpoint 1 & 2: Tampil Semua, Pencarian, & Limit
else {
    $sql = "SELECT * FROM destinasi";
    
    if ($search) {
        $sql .= " WHERE nama LIKE '%$search%'";
    }
    
    if ($limit) {
        $sql .= " LIMIT $limit";
    }
    
    $result = $conn->query($sql);
    $data = [];
    
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
    }
    
    echo json_encode($data);
}

$conn->close();
?>