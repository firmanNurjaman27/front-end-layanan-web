<?php 
require_once 'config.php'; 

// Menangkap ID dari parameter URL, default 1 jika tidak ada
$id = isset($_GET['id']) ? (int)$_GET['id'] : 1;

// Mencari data destinasi berdasarkan ID dari database
$detail = null;
$res = $conn->query("SELECT * FROM destinasi WHERE id = $id");
if($res && $res->num_rows > 0) {
    $detail = $res->fetch_assoc();
}

// Jika data tidak ditemukan, alihkan kembali ke halaman destinasi
if(!$detail) {
    header("Location: destinasi.php");
    exit;
}

$success_message = "";
$error_message = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_pemesan = $conn->real_escape_string($_POST['nama']);
    $alamat = $conn->real_escape_string($_POST['alamat']);
    $no_whatsapp = $conn->real_escape_string($_POST['no_whatsapp']);
    $tanggal = $conn->real_escape_string($_POST['tanggal']);
    $pj = $conn->real_escape_string($_POST['pj']);
    $jumlah = (int)$_POST['jumlah'];
    $destinasi_id = $detail['id'];
    
    $insert_sql = "INSERT INTO reservasi (nama, alamat, no_whatsapp, tanggal, destinasi_id, pj, jumlah) 
                   VALUES ('$nama_pemesan', '$alamat', '$no_whatsapp', '$tanggal', $destinasi_id, '$pj', $jumlah)";
    
    if ($conn->query($insert_sql)) {
        // Detail text untuk WhatsApp
        $wa_text = "Halo Admin Ngulisik Tour, saya baru saja melakukan reservasi melalui website.\n\n"
                 . "Detail Reservasi:\n"
                 . "- Nama Pemesan: $nama_pemesan\n"
                 . "- Destinasi Wisata: " . $detail['nama'] . "\n"
                 . "- Tanggal Keberangkatan: $tanggal\n"
                 . "- Jumlah Wisatawan: $jumlah orang\n"
                 . "- PJ Rombongan: $pj\n\n"
                 . "Mohon segera dikonfirmasi. Terima kasih!";
        $wa_url = "https://wa.me/628123456789?text=" . urlencode($wa_text);
        
        echo "<script>
            alert('Reservasi berhasil disimpan! Klik OK untuk konfirmasi via WhatsApp.');
            window.open('$wa_url', '_blank');
            window.location.href = 'detail.php?id=$id';
        </script>";
        exit;
    } else {
        $error_message = "Gagal memproses reservasi: " . $conn->error;
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($detail['nama']) ?> - Ngulisik Tour</title>
    <link rel="stylesheet" href="css/detail.css">
</head>
<body>

    <header>
        <div class="header-inner">
            <div class="logo"><span>NT</span> NGULISIK<br>TOUR</div>
            <nav>
                <a href="index.php">HOME</a>
                <a href="destinasi.php">DESTINASI</a>
                <a href="#" class="btn-whatsapp">WhatsApp</a>
            </nav>
        </div>
    </header>

    <main class="container">
        
        <div class="back-action">
            <a href="destinasi.php" class="btn-kembali">&larr; KEMBALI</a>
        </div>

        <div class="detail-card-wrapper">
            <div class="detail-card">
                <img src="<?= htmlspecialchars($detail['gambar']) ?>" alt="<?= htmlspecialchars($detail['nama']) ?>">
                <div class="detail-body">
                    <h2><?= htmlspecialchars($detail['nama']) ?></h2>
                    <p><?= htmlspecialchars($detail['deskripsi']) ?></p>
                    <div class="detail-price"><?= format_rupiah($detail['harga']) ?></div>
                </div>
            </div>

            <div class="action-center">
                <button class="btn-pesan" onclick="document.getElementById('bookingModal').classList.add('active')">Pesan</button>
            </div>
        </div>

    </main>

    <!-- Footer sesuai desain -->
    <footer>
        <div class="footer-inner">
            <div class="footer-logo-section">
                <div class="logo"><span>NT</span> NGULISIK<br>TOUR</div>
            </div>
            <div class="footer-contact">
                <div class="contact-row"><span>WhatsApp</span><span class="d-flex">: 0854128xxxxxx</span></div>
                <div class="contact-row"><span>Email</span><span class="d-flex">: ngulisiktour@gmail.com</span></div>
                <div class="contact-row"><span>Alamat</span><span class="d-flex">: Jl. Perjuangan no 15 kec. Singaparna<br>&nbsp;&nbsp;Kab. Tasikmalaya</span></div>
            </div>
            <div class="footer-social">
                <strong>Media Sosial</strong>
                Instagram : ngulisik_tour<br>
                Facebook  : Ngulisik Tour<br>
                Tiktok : Ngulisik_Tour
            </div>
        </div>
        <div class="footer-bottom">
            <?= "Copyrigth " . date("Y") . " &copy; NGULISIK TOUR"; ?>
        </div>
    </footer>

    <!-- MODAL RESERVASI -->
    <div class="modal-overlay" id="bookingModal">
        <div class="modal-content">
            <button class="modal-close" onclick="document.getElementById('bookingModal').classList.remove('active')">&times;</button>
            <h3>Formulir Reservasi Wisata</h3>
            <p class="modal-dest-name"><?= htmlspecialchars($detail['nama']) ?></p>
            <?php if(!empty($error_message)): ?>
                <div style="background:#f8d7da; color:#721c24; padding:10px; border-radius:4px; margin-bottom:15px; text-align:center;"><?= $error_message ?></div>
            <?php endif; ?>
            <form action="" method="POST">
                <div class="form-row">
                    <div class="form-group">
                        <label for="nama">Nama Lengkap</label>
                        <input type="text" id="nama" name="nama" required placeholder="Masukkan nama lengkap">
                    </div>
                    <div class="form-group">
                        <label for="no_whatsapp">Nomor WhatsApp</label>
                        <input type="tel" id="no_whatsapp" name="no_whatsapp" required placeholder="08xxxxxxxxxx">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="tanggal">Tanggal Kunjungan</label>
                        <input type="date" id="tanggal" name="tanggal" required>
                    </div>
                    <div class="form-group">
                        <label for="jumlah">Jumlah Wisatawan</label>
                        <input type="number" id="jumlah" name="jumlah" required min="1" placeholder="Jumlah orang">
                    </div>
                </div>
                <div class="form-group">
                    <label for="pj">Penanggung Jawab (PJ)</label>
                    <input type="text" id="pj" name="pj" required placeholder="Nama penanggung jawab">
                </div>
                <div class="form-group">
                    <label for="alamat">Alamat Lengkap</label>
                    <textarea id="alamat" name="alamat" rows="3" required placeholder="Masukkan alamat lengkap"></textarea>
                </div>
                <button type="submit" class="btn-submit-booking">Pesan Sekarang & Hubungi Admin via WhatsApp</button>
            </form>
        </div>
    </div>

</body>
</html>