<?php 
require_once 'data.php'; 

// Menangkap ID dari parameter URL, default 1 jika tidak ada
$id = isset($_GET['id']) ? (int)$_GET['id'] : 1;

// Mencari data destinasi berdasarkan ID (Simulasi SELECT * FROM table WHERE id = $id)
$detail = null;
foreach($data_destinasi as $item) {
    if($item['id'] == $id) {
        $detail = $item;
        break;
    }
}

// Jika data tidak ditemukan, alihkan kembali ke halaman destinasi
if(!$detail) {
    header("Location: destinasi.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $detail['nama'] ?> - Ngulisik Tour</title>
    <link rel="stylesheet" href="css/destinasi1.css">
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
                <img src="<?= $detail['gambar'] ?>" alt="<?= $detail['nama'] ?>">
                <div class="detail-body">
                    <h2><?= $detail['nama'] ?></h2>
                    <p><?= $detail['deskripsi'] ?></p>
                    <div class="detail-price"><?= format_rupiah($detail['harga']) ?></div>
                </div>
            </div>

            <div class="action-center">
                <a href="https://wa.me/628123456789?text=Halo,%20saya%20ingin%20memesan%20tiket%20ke%20<?= urlencode($detail['nama']) ?>" target="_blank">
                    <button class="btn-pesan">Pesan</button>
                </a>
            </div>
        </div>

    </main>

    </body>
</html>