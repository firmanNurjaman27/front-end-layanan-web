<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - Ngulisik Tour</title>
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    <style>
        /* Stat number styling */
        .stat-number { font-size: 40px; font-weight: 800; color: #333; margin-top: 8px; }
        /* Alert styling */
        .alert { padding: 12px 20px; border-radius: 6px; margin-bottom: 20px; font-weight: 600; }
        .alert-success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .alert-error { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        /* Button aksi tabel */
        .btn-hapus { background: #dc3545; color: #fff; border: none; padding: 5px 12px; border-radius: 4px; cursor: pointer; font-weight: bold; font-size: 12px; }
        .btn-hapus:hover { background: #c82333; }
        /* Modal tambah reservasi */
        .modal-overlay { display:none; position:fixed; top:0;left:0;width:100%;height:100%; background:rgba(0,0,0,0.5); z-index:1000; justify-content:center; align-items:center; }
        .modal-overlay.active { display:flex; }
        .modal-box { background:#fff; padding:30px; border-radius:12px; width:90%; max-width:500px; max-height:90vh; overflow-y:auto; }
        .modal-box h3 { color:#8D5C3E; margin-bottom:20px; font-size:20px; }
        .form-group { margin-bottom:15px; }
        .form-group label { display:block; font-weight:600; margin-bottom:5px; font-size:14px; color:#555; }
        .form-group input, .form-group select, .form-group textarea { width:100%; padding:10px 12px; border:1px solid #ddd; border-radius:6px; font-size:14px; outline:none; }
        .form-group input:focus, .form-group select:focus { border-color:#8D5C3E; }
        .btn-submit { background:#8D5C3E; color:#fff; border:none; padding:12px 30px; border-radius:6px; font-weight:bold; cursor:pointer; width:100%; font-size:15px; }
        .btn-submit:hover { background:#71482f; }
        .btn-tambah { background:#4CAF50; color:#fff; border:none; padding:10px 20px; border-radius:6px; font-weight:bold; cursor:pointer; margin-bottom:20px; font-size:14px; }
        .btn-tambah:hover { background:#388E3C; }
        .btn-close { background:transparent; border:none; float:right; font-size:22px; cursor:pointer; color:#999; margin-top:-5px; }
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
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                        <polyline points="16 17 21 12 16 7"></polyline>
                        <line x1="21" y1="12" x2="9" y2="12"></line>
                    </svg>
                    LOGOUT
                </button>
            </form>
        </div>
    </header>

    <div class="layout-container">

        <aside class="sidebar">
            <nav class="sidebar-menu">
                <a href="{{ route('admin.dashboard') }}" class="menu-item active">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                        <rect x="3" y="3" width="7" height="7"></rect>
                        <rect x="14" y="3" width="7" height="7"></rect>
                        <rect x="14" y="14" width="7" height="7"></rect>
                        <rect x="3" y="14" width="7" height="7"></rect>
                    </svg>
                    Dashboard
                </a>
                <a href="{{ route('admin.dashboard') }}" class="menu-item">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                    </svg>
                    Reservasi
                </a>
                <a href="{{ route('admin.destinasi') }}" class="menu-item no-icon">
                    Destinasi
                </a>
            </nav>
        </aside>

        <main class="content-area">

            {{-- Flash Messages --}}
            @if(session('success'))
                <div class="alert alert-success">✓ {{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="alert alert-error">✗ {{ session('error') }}</div>
            @endif

            {{-- DASHBOARD STATS --}}
            <section class="content-section">
                <h2 class="section-title">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                        <rect x="3" y="3" width="7" height="7"></rect>
                        <rect x="14" y="3" width="7" height="7"></rect>
                        <rect x="14" y="14" width="7" height="7"></rect>
                        <rect x="3" y="14" width="7" height="7"></rect>
                    </svg>
                    DASHBOARD
                </h2>

                <div class="stats-grid">
                    <div class="stat-card card-yellow">
                        <div style="text-align:center;">
                            <h3>RESERVASI</h3>
                            <div class="stat-number">{{ $totalReservasi }}</div>
                        </div>
                    </div>
                    <div class="stat-card card-blue">
                        <div style="text-align:center;">
                            <h3>TOTAL WISATAWAN</h3>
                            <div class="stat-number">{{ $totalWisatawan }}</div>
                        </div>
                    </div>
                    <div class="stat-card card-green">
                        <div style="text-align:center;">
                            <h3>TOTAL DESTINASI</h3>
                            <div class="stat-number">{{ $totalDestinasi }}</div>
                        </div>
                    </div>
                </div>
            </section>

            {{-- TABEL RESERVASI --}}
            <section class="content-section">
                <h2 class="section-title">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                    </svg>
                    RESERVASI
                </h2>

                <button class="btn-tambah" onclick="document.getElementById('modalReservasi').classList.add('active')">
                    + Tambah Reservasi
                </button>

                <div class="table-container">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th width="5%">No</th>
                                <th width="15%">Nama</th>
                                <th width="15%">Alamat</th>
                                <th width="15%">No WhatsApp</th>
                                <th width="12%">Tanggal</th>
                                <th width="13%">Destinasi</th>
                                <th width="10%">PJ</th>
                                <th width="8%">Jumlah</th>
                                <th width="7%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($reservasi as $index => $item)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $item->nama }}</td>
                                    <td>{{ $item->alamat }}</td>
                                    <td>{{ $item->no_whatsapp }}</td>
                                    <td>{{ $item->tanggal->format('d/m/Y') }}</td>
                                    <td>{{ $item->destinasi->nama ?? '-' }}</td>
                                    <td>{{ $item->pj }}</td>
                                    <td>{{ $item->jumlah }}</td>
                                    <td>
                                        <form action="{{ route('admin.reservasi.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Hapus reservasi ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-hapus">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="9" style="text-align:center;color:#999;padding:20px;">Belum ada data reservasi</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- FILTER --}}
                <form class="filters-container" method="GET" action="{{ route('admin.dashboard') }}" style="height:auto;flex-direction:row;gap:20px;margin-top:20px;">
                    <div class="filter-group" style="position:relative;">
                        <select class="custom-select" name="pj" onchange="this.form.submit()">
                            <option value="">Pilih PJ</option>
                            @foreach($pjList as $pj)
                                <option value="{{ $pj }}" {{ request('pj') == $pj ? 'selected' : '' }}>{{ $pj }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="filter-group" style="position:relative;">
                        <select class="custom-select" name="destinasi_id" onchange="this.form.submit()">
                            <option value="">Pilih Destinasi</option>
                            @foreach($destinasiList as $dest)
                                <option value="{{ $dest->id }}" {{ request('destinasi_id') == $dest->id ? 'selected' : '' }}>{{ $dest->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    @if(request('pj') || request('destinasi_id'))
                        <a href="{{ route('admin.dashboard') }}" style="align-self:center;color:#8D5C3E;font-weight:600;text-decoration:none;">✕ Reset Filter</a>
                    @endif
                </form>
            </section>

        </main>
    </div>

    {{-- MODAL TAMBAH RESERVASI --}}
    <div class="modal-overlay" id="modalReservasi">
        <div class="modal-box">
            <button class="btn-close" onclick="document.getElementById('modalReservasi').classList.remove('active')">&times;</button>
            <h3>Tambah Reservasi</h3>
            <form action="{{ route('admin.reservasi.store') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label>Nama Wisatawan</label>
                    <input type="text" name="nama" placeholder="Nama lengkap" required>
                </div>
                <div class="form-group">
                    <label>Alamat</label>
                    <textarea name="alamat" rows="2" placeholder="Alamat lengkap" required></textarea>
                </div>
                <div class="form-group">
                    <label>No WhatsApp</label>
                    <input type="text" name="no_whatsapp" placeholder="08xxxxxxxxxx" required>
                </div>
                <div class="form-group">
                    <label>Tanggal Kunjungan</label>
                    <input type="date" name="tanggal" required>
                </div>
                <div class="form-group">
                    <label>Destinasi</label>
                    <select name="destinasi_id" required>
                        <option value="">Pilih Destinasi</option>
                        @foreach($destinasiList as $dest)
                            <option value="{{ $dest->id }}">{{ $dest->nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>Penanggung Jawab (PJ)</label>
                    <input type="text" name="pj" placeholder="Nama PJ" required>
                </div>
                <div class="form-group">
                    <label>Jumlah Orang</label>
                    <input type="number" name="jumlah" min="1" value="1" required>
                </div>
                <button type="submit" class="btn-submit">Simpan Reservasi</button>
            </form>
        </div>
    </div>

</body>
</html>
