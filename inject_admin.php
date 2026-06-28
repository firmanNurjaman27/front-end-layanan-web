<?php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "ngulisik_tour";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

$name = "Super Admin Baru";
$username = "superadmin";
$password = "SuperAdmin123!";
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

$check = $conn->query("SELECT * FROM admins WHERE username='$username'");
if($check->num_rows == 0) {
    $sql = "INSERT INTO admins (name, username, password) VALUES ('$name', '$username', '$hashed_password')";
    if($conn->query($sql)) {
        echo "<h2>Data Admin Baru Berhasil Di-inject ke Database!</h2>";
        echo "Gunakan kredensial berikut untuk login:<br><br>";
        echo "Username : <b>$username</b><br>";
        echo "Password : <b>$password</b><br><br>";
        echo "<a href='login.php'>Klik di sini untuk menuju halaman Login</a>";
    } else {
        echo "Gagal: " . $conn->error;
    }
} else {
    echo "<h2>Admin dengan username tersebut sudah ada!</h2>";
    echo "Username : <b>$username</b><br>";
    echo "Password : <b>$password</b><br><br>";
    echo "<a href='login.php'>Klik di sini untuk menuju halaman Login</a>";
}
$conn->close();
?>
