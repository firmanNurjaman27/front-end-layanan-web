<?php
session_start();
if(!isset($_SESSION['admin'])) { 
    header("Location: login.php"); 
    exit; 
}
require_once 'config.php';

if(isset($_GET['make_admin'])) {
    $id = (int)$_GET['make_admin'];
    
    // Get user data
    $res = $conn->query("SELECT * FROM users WHERE id = $id");
    if($res && $res->num_rows > 0) {
        $user = $res->fetch_assoc();
        $name = $conn->real_escape_string($user['name']);
        $username = $conn->real_escape_string($user['username']);
        $password = $conn->real_escape_string($user['password']);
        
        // Insert to admins table
        $conn->query("INSERT INTO admins (name, username, password) VALUES ('$name', '$username', '$password')");
        
        // Delete from users table
        $conn->query("DELETE FROM users WHERE id = $id");
    }
    header("Location: admin_users.php");
    exit;
}

$users_list = $conn->query("SELECT * FROM users ORDER BY created_at DESC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Pengguna - Ngulisik Tour</title>
    <link rel="stylesheet" href="css/admin.css">
    <style>
        .btn-upgrade { background: #2ecc71; color: white; border: none; padding: 5px 10px; border-radius: 3px; cursor: pointer; text-decoration: none; font-size: 14px;}
        .btn-upgrade:hover { background: #27ae60; }
    </style>
</head>
<body>

    <header class="topbar">
        <div class="logo">
            <span class="nt">NT</span>
            <span class="text">NGULISIK<br>TOUR</span>
        </div>
        <div class="user-action">
            <span class="greeting">Halo, <?= htmlspecialchars($_SESSION['name']) ?></span>
            <a href="logout.php" class="btn-logout">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                    <polyline points="16 17 21 12 16 7"></polyline>
                    <line x1="21" y1="12" x2="9" y2="12"></line>
                </svg>
                LOGOUT
            </a>
        </div>
    </header>

    <div class="layout-container">
        <aside class="sidebar">
            <nav class="sidebar-menu">
                <a href="admin.php" class="menu-item">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                        <rect x="3" y="3" width="7" height="7"></rect>
                        <rect x="14" y="3" width="7" height="7"></rect>
                        <rect x="14" y="14" width="7" height="7"></rect>
                        <rect x="3" y="14" width="7" height="7"></rect>
                    </svg>
                    Dashboard
                </a>
                <a href="admin.php" class="menu-item">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                    </svg>
                    Reservasi
                </a>
                <a href="admin_destinasi.php" class="menu-item no-icon">
                    Kelola Konten
                </a>
                <a href="admin_users.php" class="menu-item active">
                    Kelola Pengguna
                </a>
            </nav>
        </aside>

        <main class="content-area">
            <section class="content-section">
                <h2 class="section-title">KELOLA PENGGUNA (Regular Users)</h2>
                
                <div class="table-container">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th width="10%">ID</th>
                                <th width="30%">Nama</th>
                                <th width="30%">Username</th>
                                <th width="15%">Tgl Daftar</th>
                                <th width="15%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if($users_list && $users_list->num_rows > 0): ?>
                                <?php while($row = $users_list->fetch_assoc()): ?>
                                <tr>
                                    <td style="text-align:center;"><?= $row['id'] ?></td>
                                    <td><?= htmlspecialchars($row['name']) ?></td>
                                    <td><?= htmlspecialchars($row['username']) ?></td>
                                    <td style="text-align:center;"><?= date('d/m/Y', strtotime($row['created_at'])) ?></td>
                                    <td style="text-align:center;">
                                        <a href="admin_users.php?make_admin=<?= $row['id'] ?>" class="btn-upgrade" onclick="return confirm('Jadikan user ini sebagai Admin?');">Jadikan Admin</a>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5" style="text-align:center; padding: 20px;">Belum ada data pengguna regular</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </section>
        </main>
    </div>
</body>
</html>
