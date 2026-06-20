<?php 
require_once 'data.php'; 

// Logika Pencarian sederhana
$search = isset($_GET['search']) ? $_GET['search'] : '';
$filtered_destinasi = $data_destinasi;

if($search) {
    $filtered_destinasi = array_filter($data_destinasi, function($item) use ($search) {
        return stripos($item['nama'], $search) !== false;
    });
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Destinasi - Ngulisik Tour</title>
    <link rel="stylesheet" href="css/destinasi.css">
    <style>
        a.card { text-decoration: none; color: inherit; transition: transform 0.2s; }
        a.card:hover { transform: translateY(-5px); }
    </style>
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
        
        <div class="page-title">
            <a href="index.php" class="back-arrow">&larr;</a>
            <h1>DESTINASI</h1>
        </div>

        <form class="search-container" action="destinasi.php" method="GET">
            <input type="text" name="search" placeholder="Cari Destinasi" value="<?= htmlspecialchars($search) ?>">
            <button type="submit">CARI</button>
        </form>

        <div class="grid-cards">
            <?php if(count($filtered_destinasi) > 0): ?>
                <?php foreach($filtered_destinasi as $item): ?>
                    <a href="detail.php?id=<?= $item['id'] ?>" class="card">
                        <img src="<?= $item['gambar'] ?>" alt="<?= $item['nama'] ?>">
                        <div class="card-body">
                            <h3><?= $item['nama'] ?></h3>
                            <p><?= $item['deskripsi'] ?></p>
                            <div class="card-price"><?= format_rupiah($item['harga']) ?></div>
                        </div>
                    </a>
                <?php endforeach; ?>
            <?php else: ?>
                <p>Destinasi tidak ditemukan.</p>
            <?php endif; ?>
        </div>

    </main>

    </body>
</html>