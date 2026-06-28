<?php 
require_once 'config.php'; 
// Ambil data destinasi dari database
$result = $conn->query("SELECT * FROM destinasi LIMIT 4");
$data_destinasi = [];
if ($result && $result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $data_destinasi[] = $row;
    }
}

// Ambil data galeri dari database (3 terbaru)
$galeri_result = $conn->query("SELECT * FROM galeri ORDER BY id DESC LIMIT 3");
$data_galeri = [];
if ($galeri_result && $galeri_result->num_rows > 0) {
    while($row = $galeri_result->fetch_assoc()) {
        $data_galeri[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ngulisik Tour - Tasikmalaya</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        /* Tambahan agar saat Card di klik bentuknya tetap rapi sebagai link */
        a.card { text-decoration: none; color: inherit; transition: transform 0.2s; }
        a.card:hover { transform: translateY(-5px); }
    </style>
</head>
<body>

    <header>
        <div class="logo"><span>NT</span> NGULISIK<br>TOUR</div>
        <nav>
            <a href="index.php">HOME</a>
            <a href="destinasi.php">DESTINASI</a>
            <a href="login.php">LOGIN</a>
            <a href="register.php">REGISTER</a>
            <a href="#" class="btn-whatsapp">WhatsApp</a>
        </nav>
    </header>

    <section class="hero">
        <div class="hero-image-wrapper">
            <img src="https://images.unsplash.com/photo-1542384701-c0e46e0eda04?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80" alt="Hero Background Tasikmalaya">
        </div>
        <div class="hero-content">
            <h1>Jelajahi Pesona Tersembunyi<br>Sang Mutiara dari Priangan Timur!</h1>
            <p>Dari ketinggian kawah Galunggung yang magis hingga deburan ombak Karang Tawulan yang puitis. Kabupaten Tasikmalaya adalah undangan terbuka bagi Anda pecinta keindahan.</p>
        </div>
    </section>

    <section class="notice-section">
        <h4>- PEMBERITAHUAN -</h4>
        <p>Chat yang anda kirim akan diterima oleh admin penanggung jawab masing-masing destinasi untuk membantu informasi dan kebutuhan Anda</p>
    </section>

    <section class="destinations">
        <div class="destinations-header">
            <h2>MAU MULAI DARI MANA?</h2>
            <p>Tasikmalaya dalam Genggaman: Dari Puncak Galunggung hingga Deburan Tawulan.<br>Kabupaten Tasikmalaya punya semua yang kamu butuhkan untuk melepas penat.</p>
        </div>

        <div class="cards-grid">
            <?php if(count($data_destinasi) > 0): ?>
                <?php foreach($data_destinasi as $item): ?>
                    <a href="detail.php?id=<?= $item['id'] ?>" class="card">
                        <img src="<?= htmlspecialchars($item['gambar']) ?>" alt="<?= htmlspecialchars($item['nama']) ?>">
                        <div class="card-body">
                            <h3><?= htmlspecialchars($item['nama']) ?></h3>
                            <p><?= substr(htmlspecialchars($item['deskripsi']), 0, 150) ?>...</p>
                            <div class="card-price"><?= format_rupiah($item['harga']) ?></div>
                        </div>
                    </a>
                <?php endforeach; ?>
            <?php else: ?>
                <p style="grid-column: 1/-1; text-align: center; color: #888; padding: 20px;">Belum ada destinasi wisata.</p>
            <?php endif; ?>
        </div>

        <a href="destinasi.php" class="btn-more">Lebih Banyak</a>
    </section>

    <section class="adventure-section">
        <h2>SIAP UNTUK PETUALANGAN SELANJUTNYA?</h2>
        <p>Jelajahi keindahan alam dan budaya, ciptakan pengalaman tak terlupakan.</p>
        <div class="placeholder-grid">
            <?php if(count($data_galeri) > 0): ?>
                <?php foreach($data_galeri as $galeri): ?>
                    <div class="placeholder-box" style="background-image: url('<?= htmlspecialchars($galeri['gambar']) ?>'); background-size: cover; background-position: center;"></div>
                <?php endforeach; ?>
                
                <?php 
                // Jika galeri kurang dari 3, tambahkan box kosong sebagai placeholder agar grid tetap utuh (seperti gambar)
                $kurang = 3 - count($data_galeri);
                for($i=0; $i<$kurang; $i++): ?>
                    <div class="placeholder-box"></div>
                <?php endfor; ?>
            <?php else: ?>
                <div class="placeholder-box"></div>
                <div class="placeholder-box"></div>
                <div class="placeholder-box"></div>
            <?php endif; ?>
        </div>
    </section>

    <footer>
        <div class="footer-content">
            <div class="footer-logo"><span>NT</span> NGULISIK<br>TOUR</div>
            </div>
        <div class="footer-bottom">
            <?= "Copyright " . date("Y") . " &copy; NGULISIK TOUR"; ?>
        </div>
    </footer>

</body>
</html>