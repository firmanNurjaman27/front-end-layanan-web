<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Destinasi - Ngulisik Tour</title>
    <link rel="stylesheet" href="{{ asset('css/destinasi.css') }}">
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
                <a href="{{ route('home') }}">HOME</a>
                <a href="{{ route('destinasi.index') }}">DESTINASI</a>
                <a href="#" class="btn-whatsapp">WhatsApp</a>
            </nav>
        </div>
    </header>

    <main class="container">

        <div class="page-title">
            <a href="{{ route('home') }}" class="back-arrow">&larr;</a>
            <h1>DESTINASI</h1>
        </div>

        <form class="search-container" action="{{ route('destinasi.index') }}" method="GET">
            <input type="text" name="search" placeholder="Cari Destinasi" value="{{ $search }}">
            <button type="submit">CARI</button>
        </form>

        <div class="grid-cards">
            @forelse($destinasi as $item)
                <a href="{{ route('destinasi.show', $item->id) }}" class="card">
                    <img src="{{ $item->gambar }}" alt="{{ $item->nama }}">
                    <div class="card-body">
                        <h3>{{ $item->nama }}</h3>
                        <p>{{ $item->deskripsi }}</p>
                        <div class="card-price">{{ $item->harga_format }}</div>
                    </div>
                </a>
            @empty
                <p style="grid-column: 1/-1; text-align:center; color:#999;">Destinasi tidak ditemukan.</p>
            @endforelse
        </div>

    </main>

</body>
</html>
