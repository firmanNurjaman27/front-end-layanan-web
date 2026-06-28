<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Destinasi - Ngulisik Tour</title>
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    <style>
        .btn-hapus { background: #dc3545; color: #fff; border: none; padding: 5px 12px; border-radius: 4px; cursor: pointer; font-weight: bold; font-size: 12px; }
        .btn-hapus:hover { background: #c82333; }
        .alert { padding: 12px 20px; border-radius: 6px; margin-bottom: 20px; font-weight: 600; }
        .alert-success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .modal-overlay { display:none; position:fixed; top:0;left:0;width:100%;height:100%; background:rgba(0,0,0,0.5); z-index:1000; justify-content:center; align-items:center; }
        .modal-overlay.active { display:flex; }
        .modal-box { background:#fff; padding:30px; border-radius:12px; width:90%; max-width:500px; }
        .modal-box h3 { color:#8D5C3E; margin-bottom:20px; }
        .form-group { margin-bottom:15px; }
        .form-group label { display:block; font-weight:600; margin-bottom:5px; font-size:14px; color:#555; }
        .form-group input, .form-group textarea { width:100%; padding:10px 12px; border:1px solid #ddd; border-radius:6px; font-size:14px; outline:none; }
        .btn-submit { background:#8D5C3E; color:#fff; border:none; padding:12px 30px; border-radius:6px; font-weight:bold; cursor:pointer; width:100%; }
        .btn-tambah { background:#4CAF50; color:#fff; border:none; padding:10px 20px; border-radius:6px; font-weight:bold; cursor:pointer; margin-bottom:20px; font-size:14px; }
        .btn-close { background:transparent; border:none; float:right; font-size:22px; cursor:pointer; color:#999; }
        .dest-img { width:60px; height:45px; object-fit:cover; border-radius:4px; }
    </style>
</head>
<body>

    <header class="topbar">
        <div class="logo">
            <span class="nt">NT</span>
            <span class="text">NGULISIK<br>TOUR</span>
        </div>
        <div class="user-action">
            <span class="greeting">Halo, {{ Auth::guard('admin')->user()->name }}</span>
            <form action="{{ route('logout') }}" method="POST" style="display:inline;">
                @csrf
                <button type="submit" class="btn-logout">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg>
                    LOGOUT
                </button>
            </form>
        </div>
    </header>

    <div class="layout-container">

        <aside class="sidebar">
            <nav class="sidebar-menu">
                <a href="{{ route('admin.dashboard') }}" class="menu-item">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><rect x="3" y="3" width="7" height="7"></rect><rect x="14" y="3" width="7" height="7"></rect><rect x="14" y="14" width="7" height="7"></rect><rect x="3" y="14" width="7" height="7"></rect></svg>
                    Dashboard
                </a>
                <a href="{{ route('admin.dashboard') }}" class="menu-item">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                    Reservasi
                </a>
                <a href="{{ route('admin.destinasi') }}" class="menu-item active no-icon">
                    Destinasi
                </a>
            </nav>
        </aside>

        <main class="content-area">

            @if(session('success'))
                <div class="alert alert-success">✓ {{ session('success') }}</div>
            @endif

            <section class="content-section">
                <h2 class="section-title">MANAJEMEN DESTINASI</h2>

                <button class="btn-tambah" onclick="document.getElementById('modalDestinasi').classList.add('active')">
                    + Tambah Destinasi
                </button>

                <div class="table-container">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th width="5%">No</th>
                                <th width="10%">Gambar</th>
                                <th width="25%">Nama</th>
                                <th width="35%">Deskripsi</th>
                                <th width="15%">Harga</th>
                                <th width="10%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($destinasi as $index => $item)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td><img class="dest-img" src="{{ $item->gambar }}" alt="{{ $item->nama }}"></td>
                                    <td>{{ $item->nama }}</td>
                                    <td style="text-align:left;font-size:12px;">{{ Str::limit($item->deskripsi, 100) }}</td>
                                    <td>{{ $item->harga_format }}</td>
                                    <td>
                                        <form action="{{ route('admin.destinasi.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Hapus destinasi ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-hapus">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="6" style="text-align:center;color:#999;padding:20px;">Belum ada destinasi</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </section>

        </main>
    </div>

    {{-- MODAL TAMBAH DESTINASI --}}
    <div class="modal-overlay" id="modalDestinasi">
        <div class="modal-box">
            <button class="btn-close" onclick="document.getElementById('modalDestinasi').classList.remove('active')">&times;</button>
            <h3>Tambah Destinasi</h3>
            <form action="{{ route('admin.destinasi.store') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label>Nama Destinasi</label>
                    <input type="text" name="nama" placeholder="Nama destinasi (UPPERCASE)" required>
                </div>
                <div class="form-group">
                    <label>Deskripsi</label>
                    <textarea name="deskripsi" rows="4" placeholder="Deskripsi destinasi..." required></textarea>
                </div>
                <div class="form-group">
                    <label>Harga Tiket (Rp)</label>
                    <input type="number" name="harga" placeholder="Contoh: 10000" min="0" required>
                </div>
                <div class="form-group">
                    <label>URL Gambar</label>
                    <input type="url" name="gambar" placeholder="https://..." required>
                </div>
                <button type="submit" class="btn-submit">Simpan Destinasi</button>
            </form>
        </div>
    </div>

</body>
</html>
