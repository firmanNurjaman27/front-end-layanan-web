<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $destinasi->nama }} - Ngulisik Tour</title>
    <link rel="stylesheet" href="{{ asset('css/detail.css') }}">
</head>
<body>

    <header>
        <div class="header-inner">
            <div class="logo"><span>NT</span> NGULISIK<br>TOUR</div>
            <nav>
                <a href="{{ route('home') }}">HOME</a>
                <a href="{{ route('destinasi.index') }}">DESTINASI</a>
                <a href="#" class="btn-whatsapp">WhatsApp</a>
            </nav>
        </div>
    </header>

    <main class="container">

        <div class="back-action">
            <a href="{{ route('destinasi.index') }}" class="btn-kembali">&larr; KEMBALI</a>
        </div>

        <div class="detail-card-wrapper">
            <div class="detail-card">
                <img src="{{ $destinasi->gambar }}" alt="{{ $destinasi->nama }}">
                <div class="detail-body">
                    <h2>{{ $destinasi->nama }}</h2>
                    <p>{{ $destinasi->deskripsi }}</p>
                    <div class="detail-price">{{ $destinasi->harga_format }}</div>
                </div>
            </div>

            <div class="action-center">
                <a href="https://wa.me/628123456789?text=Halo,%20saya%20ingin%20memesan%20tiket%20ke%20{{ urlencode($destinasi->nama) }}" target="_blank">
                    <button class="btn-pesan">Pesan</button>
                </a>
            </div>
        </div>

    </main>

</body>
</html>
