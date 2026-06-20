<?php require_once 'data.php'; ?>
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
            <?php foreach($data_destinasi as $item): ?>
                <a href="detail.php?id=<?= $item['id'] ?>" class="card">
                    <img src="<?= $item['gambar'] ?>" alt="<?= $item['nama'] ?>">
                    <div class="card-body">
                        <h3><?= $item['nama'] ?></h3>
                        <p><?= substr($item['deskripsi'], 0, 150) ?>...</p>
                        <div class="card-price"><?= format_rupiah($item['harga']) ?></div>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>

        <a href="destinasi.php" class="btn-more">Lebih Banyak</a>
    </section>

    <section class="adventure-section">
        <h2>SIAP UNTUK PETUALANGAN SELANJUTNYA?</h2>
        <p>Jelajahi keindahan alam dan budaya, ciptakan pengalaman tak terlupakan.</p>
        <div class="placeholder-grid">
            <div class="placeholder-box"></div>
            <div class="placeholder-box"></div>
            <div class="placeholder-box"></div>
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