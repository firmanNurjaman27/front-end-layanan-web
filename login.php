<?php
session_start();
$message = "";
$message_type = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    require_once 'config.php';
    if ($conn) {
        $username = $conn->real_escape_string($_POST['username']);
        $password = $_POST['password'];

        $sql_admin = "SELECT * FROM admins WHERE username = '$username'";
        $result_admin = $conn->query($sql_admin);

        if ($result_admin && $result_admin->num_rows > 0) {
            $row = $result_admin->fetch_assoc();
            if (password_verify($password, $row['password'])) {
                $_SESSION['admin'] = $row['username'];
                $_SESSION['name'] = $row['name'];
                header("Location: admin.php");
                exit;
            } else {
                $message = "Password admin salah!";
                $message_type = "error";
            }
        } else {
            $check_users = $conn->query("SHOW TABLES LIKE 'users'");
            if ($check_users && $check_users->num_rows > 0) {
                $sql_user = "SELECT * FROM users WHERE username = '$username'";
                $result_user = $conn->query($sql_user);
                
                if ($result_user && $result_user->num_rows > 0) {
                    $row = $result_user->fetch_assoc();
                    if (password_verify($password, $row['password'])) {
                        $_SESSION['user'] = $row['username'];
                        $_SESSION['name'] = $row['name'];
                        header("Location: index.php");
                        exit;
                    } else {
                        $message = "Password salah!";
                        $message_type = "error";
                    }
                } else {
                    $message = "Username tidak ditemukan!";
                    $message_type = "error";
                }
            } else {
                $message = "Username tidak ditemukan!";
                $message_type = "error";
            }
        }
        $conn->close();
    } else {
        $message = "Koneksi database gagal!";
        $message_type = "error";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - Ngulisik Tour</title>
    <link rel="stylesheet" href="css/login.css">
    <style>
        .msg {
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
            text-align: center;
            width: 100%;
        }
        .msg.error { background-color: #f8d7da; color: #721c24; }
        .msg.success { background-color: #d4edda; color: #155724; }
    </style>
</head>
<body>

    <div class="login-container">
        <div class="logo">
            <span class="nt">NT</span>
            <span class="text">NGULISIK<br>TOUR</span>
        </div>

        <h1>LOGIN</h1>

        <?php if($message): ?>
            <div class="msg <?= $message_type ?>"><?= $message ?></div>
        <?php endif; ?>

        <form action="" method="POST">
            <div class="input-group">
                <label for="username">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                        <circle cx="12" cy="7" r="4"></circle>
                    </svg>
                    Username
                </label>
                <input type="text" id="username" name="username" placeholder="Masukkan username Anda" required>
            </div>

            <div class="input-group">
                <label for="password">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                        <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                    </svg>
                    Password
                </label>
                <div class="password-wrapper">
                    <input type="password" id="password" name="password" placeholder="Masukkan password" required>
                    <button type="button" class="toggle-password" id="togglePassword">
                        <svg id="eye-icon" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                            <circle cx="12" cy="12" r="3"></circle>
                        </svg>
                    </button>
                </div>
            </div>

            <button type="submit" class="btn-login">
                LOGIN 
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="5" y1="12" x2="19" y2="12"></line>
                    <polyline points="12 5 19 12 12 19"></polyline>
                </svg>
            </button>
            <div style="margin-top: 15px; font-size: 14px; text-align: center;">
                Belum punya akun? <a href="register.php" style="color: #4CAF50; text-decoration: none; font-weight: bold;">Register di sini</a>
            </div>
        </form>
    </div>

    <div class="footer-bottom">
        <?php echo "Copyrigth " . date("Y") . " &copy; NGULISIK TOUR"; ?>
    </div>

    <script>
        const togglePassword = document.getElementById('togglePassword');
        const passwordInput = document.getElementById('password');
        const eyeIcon = document.getElementById('eye-icon');

        togglePassword.addEventListener('click', function () {
            // Toggle type input
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);

            // Toggle icon (Mata Terbuka / Mata Dicoret)
            if (type === 'text') {
                eyeIcon.innerHTML = `
                    <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path>
                    <line x1="1" y1="1" x2="23" y2="23"></line>
                `;
            } else {
                eyeIcon.innerHTML = `
                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                    <circle cx="12" cy="12" r="3"></circle>
                `;
            }
        });
    </script>
</body>
</html>