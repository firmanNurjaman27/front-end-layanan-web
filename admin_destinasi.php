<?php
session_start();
if(!isset($_SESSION['admin'])) { 
    header("Location: login.php"); 
    exit; 
}
require_once 'config.php';

// Handle Delete Destinasi
if(isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $res = $conn->query("SELECT gambar FROM destinasi WHERE id = $id");
    if ($res && $res->num_rows > 0) {
        $gambar = $res->fetch_assoc()['gambar'];
        if (!empty($gambar) && file_exists($gambar)) {
            @unlink($gambar);
        }
    }
    $conn->query("DELETE FROM destinasi WHERE id = $id");
    header("Location: admin_destinasi.php");
    exit;
}

// Handle Delete Galeri
if(isset($_GET['delete_galeri'])) {
    $id = (int)$_GET['delete_galeri'];
    $res = $conn->query("SELECT gambar FROM galeri WHERE id = $id");
    if ($res && $res->num_rows > 0) {
        $gambar = $res->fetch_assoc()['gambar'];
        if (!empty($gambar) && file_exists($gambar)) {
            @unlink($gambar);
        }
    }
    $conn->query("DELETE FROM galeri WHERE id = $id");
    header("Location: admin_destinasi.php#galeri");
    exit;
}

$error_msg = "";

// Handle Add/Edit Destinasi
if(isset($_POST['submit_destinasi'])) {
    $nama = $conn->real_escape_string($_POST['nama']);
    $alamat = $conn->real_escape_string($_POST['alamat']);
    $fasilitas = $conn->real_escape_string($_POST['fasilitas']);
    $harga = (int)$_POST['harga'];
    
    $id = isset($_POST['id']) && !empty($_POST['id']) ? (int)$_POST['id'] : null;
    $gambar = "";
    
    if ($id) {
        $res = $conn->query("SELECT gambar FROM destinasi WHERE id = $id");
        if ($res && $res->num_rows > 0) {
            $gambar = $res->fetch_assoc()['gambar'];
        }
    }
    
    // Handle File Upload
    if (isset($_FILES['gambar_file']) && $_FILES['gambar_file']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['gambar_file']['tmp_name'];
        $fileName = $_FILES['gambar_file']['name'];
        $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        if (in_array($fileExtension, $allowedExtensions)) {
            if (!is_dir(UPLOAD_DIR)) {
                mkdir(UPLOAD_DIR, 0755, true);
            }
            if (!empty($gambar) && file_exists($gambar)) {
                @unlink($gambar);
            }
            $newFileName = time() . '_' . md5(uniqid()) . '.' . $fileExtension;
            $dest_path = UPLOAD_DIR . $newFileName;
            
            if (move_uploaded_file($fileTmpPath, $dest_path)) {
                $gambar = UPLOAD_URL . $newFileName;
            } else {
                $error_msg = "Gagal mengunggah gambar destinasi.";
            }
        } else {
            $error_msg = "Format file tidak didukung!";
        }
    } else {
        if (!$id) {
            $error_msg = "Gambar wajib diunggah.";
        }
    }
    
    if (empty($error_msg)) {
        // Also ensure we handle cases where old code used 'deskripsi'. In the new design, 'fasilitas' and 'alamat' take precedence, we'll map fasilitas to deskripsi if we want or just use the new columns.
        // Let's use the new columns + keep deskripsi empty or same as fasilitas.
        if($id) {
            $conn->query("UPDATE destinasi SET nama='$nama', alamat='$alamat', fasilitas='$fasilitas', deskripsi='$fasilitas', harga=$harga, gambar='$gambar' WHERE id=$id");
        } else {
            $conn->query("INSERT INTO destinasi (nama, alamat, fasilitas, deskripsi, harga, gambar) VALUES ('$nama', '$alamat', '$fasilitas', '$fasilitas', $harga, '$gambar')");
        }
        header("Location: admin_destinasi.php");
        exit;
    }
}

// Handle Add Galeri
if(isset($_POST['submit_galeri'])) {
    if (isset($_FILES['galeri_file']) && $_FILES['galeri_file']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['galeri_file']['tmp_name'];
        $fileName = $_FILES['galeri_file']['name'];
        $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        if (in_array($fileExtension, $allowedExtensions)) {
            if (!is_dir(UPLOAD_DIR)) {
                mkdir(UPLOAD_DIR, 0755, true);
            }
            $newFileName = 'galeri_' . time() . '_' . md5(uniqid()) . '.' . $fileExtension;
            $dest_path = UPLOAD_DIR . $newFileName;
            
            if (move_uploaded_file($fileTmpPath, $dest_path)) {
                $gambar = UPLOAD_URL . $newFileName;
                $conn->query("INSERT INTO galeri (gambar) VALUES ('$gambar')");
                header("Location: admin_destinasi.php#galeri");
                exit;
            } else {
                $error_msg = "Gagal mengunggah gambar galeri.";
            }
        } else {
            $error_msg = "Format file tidak didukung!";
        }
    } else {
        $error_msg = "Pilih file gambar untuk galeri.";
    }
}

// Search
$search = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';
$where_sql = $search ? " WHERE nama LIKE '%$search%' " : "";
$destinasi_list = $conn->query("SELECT * FROM destinasi $where_sql ORDER BY id DESC");

$galeri_list = $conn->query("SELECT * FROM galeri ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Destinasi - Ngulisik Tour</title>
    <link rel="stylesheet" href="css/admin.css">
    <link rel="stylesheet" href="css/destinasi-cms.css">
</head>
<body>

    <header class="topbar">
        <div class="logo">
            <span class="nt">NT</span>
            <span class="text">NGULISIK<br>TOUR</span>
        </div>
        <div class="user-action">
            <a href="admin.php" style="color:white; text-decoration:none; margin-right:20px; font-weight:bold;">Dashboard</a>
            <a href="admin.php" style="color:white; text-decoration:none; margin-right:20px; font-weight:bold;">Pesanan</a>
            <a href="admin_destinasi.php" style="color:white; text-decoration:none; margin-right:20px; font-weight:bold;">Destinasi</a>
        </div>
    </header>

    <div class="layout-container">
        <!-- Assuming the design doesn't have the left sidebar based on the header having Dashboard|Pesanan|Destinasi menus right in it?
             Wait, the image header has "Dashboard Pesanan Destinasi" on the right side instead of the user greeting and logout.
             Ah! The top bar in the image actually has "Dashboard Pesanan Destinasi" as links on the right! 
             Okay, I will match the image header for this page, but wait, usually we want consistent sidebar. 
             I will keep the layout consistent but add the required styling for the content. -->

        <main class="content-area" style="padding-top:20px;">
            <div class="destinasi-header">
                <h2 class="section-title">DESTINASI</h2>
            </div>
            
            <div class="toolbar">
                <form method="GET" action="" style="display:flex; gap:10px; flex:1;">
                    <input type="text" name="search" class="search-input" placeholder="Cari..." value="<?= htmlspecialchars($search) ?>">
                </form>
                <a href="#tambah-destinasi" class="btn-brown">TAMBAH DESTINASI</a>
            </div>

            <?php if(!empty($error_msg)): ?>
                <div style="background:#f8d7da; color:#721c24; padding:10px; border-radius:4px; margin-bottom:15px;"><?= $error_msg ?></div>
            <?php endif; ?>

            <div class="table-container">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th width="5%">NO</th>
                            <th width="20%">NAMA WISATA</th>
                            <th width="25%">ALAMAT</th>
                            <th width="20%">FASILITAS</th>
                            <th width="15%">HARGA</th>
                            <th width="15%">AKSI</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if($destinasi_list && $destinasi_list->num_rows > 0): ?>
                            <?php $no = 1; while($row = $destinasi_list->fetch_assoc()): 
                                $data_json = htmlspecialchars(json_encode($row), ENT_QUOTES, 'UTF-8');
                            ?>
                            <tr>
                                <td style="text-align:center;"><?= $no++ ?></td>
                                <td><?= htmlspecialchars($row['nama']) ?></td>
                                <td><?= htmlspecialchars($row['alamat']) ?></td>
                                <td><?= htmlspecialchars($row['fasilitas']) ?></td>
                                <td>Rp <?= number_format($row['harga'], 0, ',', '.') ?></td>
                                <td style="text-align:center;">
                                    <button class="btn-action edit-btn" onclick="openEditModal(<?= $data_json ?>)">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9"></path><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"></path></svg>
                                    </button>
                                    <button class="btn-action delete-btn" onclick="openDeleteModal(<?= $row['id'] ?>)">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                                    </button>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" style="text-align:center; padding: 20px;">Belum ada data destinasi</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- FORM TAMBAH DESTINASI -->
            <div id="tambah-destinasi" class="section-block">
                <h3 class="block-title">TAMBAH DESTINASI</h3>
                <div class="form-box">
                    <form action="" method="POST" enctype="multipart/form-data" class="destinasi-form">
                        <div class="form-left">
                            <div class="form-group-cms">
                                <label>NAMA WISATA</label>
                                <input type="text" name="nama" required>
                            </div>
                            <div class="form-group-cms">
                                <label>ALAMAT</label>
                                <input type="text" name="alamat" required>
                            </div>
                            <div class="form-group-cms">
                                <label>FASILITAS</label>
                                <input type="text" name="fasilitas" required>
                            </div>
                            <div class="form-group-cms">
                                <label>HARGA</label>
                                <input type="number" name="harga" required>
                            </div>
                        </div>
                        <div class="form-right">
                            <label>FOTO</label>
                            <div class="upload-area">
                                <input type="file" name="gambar_file" required accept="image/*">
                            </div>
                        </div>
                        <div class="form-action-bottom">
                            <button type="submit" name="submit_destinasi" class="btn-brown-solid">TAMBAH DESTINASI</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- FORM TAMBAH GALERI -->
            <div id="galeri" class="section-block">
                <h3 class="block-title">TAMBAH GALERI PENGUNJUNG</h3>
                <div class="table-container">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th width="10%">NO</th>
                                <th width="70%">NAMA FILE</th>
                                <th width="20%">AKSI</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if($galeri_list && $galeri_list->num_rows > 0): ?>
                                <?php $no = 1; while($row = $galeri_list->fetch_assoc()): ?>
                                <tr>
                                    <td style="text-align:center;"><?= $no++ ?></td>
                                    <td style="text-align:center;"><img src="<?= htmlspecialchars($row['gambar']) ?>" height="50"></td>
                                    <td style="text-align:center;">
                                        <a href="admin_destinasi.php?delete_galeri=<?= $row['id'] ?>" class="btn-action delete-btn" onclick="return confirm('Hapus gambar ini?');">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                                        </a>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="3" style="text-align:center; padding: 20px;">Belum ada galeri</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <div class="form-box">
                    <form action="" method="POST" enctype="multipart/form-data" class="galeri-form">
                        <div class="upload-area-large">
                            <input type="file" name="galeri_file" required accept="image/*">
                        </div>
                        <div class="form-action-center">
                            <button type="submit" name="submit_galeri" class="btn-brown-solid">SIMPAN GAMBAR</button>
                            <button type="button" class="btn-red-solid" style="margin-left:10px;">BATAL</button>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>

    <!-- MODAL EDIT DESTINASI -->
    <div class="modal-overlay" id="editModal">
        <div class="modal-content" style="max-width:700px;">
            <button class="modal-close" onclick="closeEditModal()">&times;</button>
            <h3 class="block-title" style="margin-bottom:20px; font-size:18px;">EDIT DESTINASI</h3>
            <div class="form-box" style="margin:0; box-shadow:none;">
                <form action="" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="id" id="edit-id">
                    
                    <div style="text-align:center; margin-bottom:20px;">
                        <img id="edit-preview" src="" style="width: 250px; height: 150px; object-fit: cover; background: #e0e0e0; border: 1px solid #ccc;">
                    </div>

                    <div class="form-group-cms">
                        <label>NAMA WISATA</label>
                        <input type="text" name="nama" id="edit-nama" required>
                    </div>
                    <div class="form-group-cms">
                        <label>ALAMAT</label>
                        <input type="text" name="alamat" id="edit-alamat" required>
                    </div>
                    <div class="form-group-cms">
                        <label>FASILITAS</label>
                        <input type="text" name="fasilitas" id="edit-fasilitas" required>
                    </div>
                    <div class="form-group-cms">
                        <label>HARGA</label>
                        <input type="number" name="harga" id="edit-harga" required>
                    </div>
                    <div class="form-group-cms">
                        <label>FOTO (Bisa dikosongkan)</label>
                        <input type="file" name="gambar_file" accept="image/*">
                    </div>
                    
                    <div class="form-action-center" style="margin-top:20px;">
                        <button type="submit" name="submit_destinasi" class="btn-brown-solid">SIMPAN PERUBAHAN</button>
                        <button type="button" class="btn-dark-solid" onclick="closeEditModal()" style="margin-left:10px;">BATAL</button>
                        <button type="button" class="btn-red-solid" onclick="confirmDeleteFromEdit()" style="margin-left:10px;">HAPUS</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- MODAL DELETE CONFIRMATION -->
    <div class="modal-overlay" id="deleteModal" style="z-index: 3000;">
        <div class="modal-content" style="max-width:400px; text-align:center;">
            <h3 style="color:#8D5C3E; margin-bottom:20px;">YAKIN INGIN MENGHAPUS DESTINASI INI?</h3>
            <div style="display:flex; justify-content:center; gap:20px;">
                <a href="#" id="delete-confirm-btn" class="btn-green-solid">YA</a>
                <button type="button" class="btn-red-solid" onclick="closeDeleteModal()">BATAL</button>
            </div>
        </div>
    </div>

    <script>
        let currentEditId = null;

        function openEditModal(data) {
            document.getElementById('edit-id').value = data.id;
            document.getElementById('edit-nama').value = data.nama;
            document.getElementById('edit-alamat').value = data.alamat;
            document.getElementById('edit-fasilitas').value = data.fasilitas;
            document.getElementById('edit-harga').value = data.harga;
            document.getElementById('edit-preview').src = data.gambar;
            currentEditId = data.id;
            
            document.getElementById('editModal').classList.add('active');
        }

        function closeEditModal() {
            document.getElementById('editModal').classList.remove('active');
        }

        function openDeleteModal(id) {
            document.getElementById('delete-confirm-btn').href = 'admin_destinasi.php?delete=' + id;
            document.getElementById('deleteModal').classList.add('active');
        }

        function closeDeleteModal() {
            document.getElementById('deleteModal').classList.remove('active');
        }

        function confirmDeleteFromEdit() {
            if (currentEditId) {
                closeEditModal();
                openDeleteModal(currentEditId);
            }
        }
    </script>
</body>
</html>
