<?php
session_start();
if(!isset($_SESSION['admin'])) { 
    header("Location: login.php"); 
    exit; 
}

require_once 'config.php';

// Get Stats
$res_total_reservasi = $conn->query("SELECT COUNT(*) as total FROM reservasi");
$total_reservasi = $res_total_reservasi->fetch_assoc()['total'];

$res_total_wisatawan = $conn->query("SELECT SUM(jumlah) as total FROM reservasi");
$total_wisatawan = $res_total_wisatawan->fetch_assoc()['total'];
if(!$total_wisatawan) $total_wisatawan = 0;

// Get Table Data with Filters
$where_clauses = [];
$filter_pj = isset($_GET['pj']) ? $_GET['pj'] : '';
$filter_destinasi = isset($_GET['destinasi_id']) ? (int)$_GET['destinasi_id'] : 0;

if ($filter_pj !== '') {
    $filter_pj_escaped = $conn->real_escape_string($filter_pj);
    $where_clauses[] = "r.pj = '$filter_pj_escaped'";
}
if ($filter_destinasi > 0) {
    $where_clauses[] = "r.destinasi_id = $filter_destinasi";
}

$where_sql = "";
if (count($where_clauses) > 0) {
    $where_sql = " WHERE " . implode(" AND ", $where_clauses);
}

$sql = "SELECT r.*, d.nama AS destinasi_nama 
        FROM reservasi r 
        LEFT JOIN destinasi d ON r.destinasi_id = d.id 
        $where_sql
        ORDER BY r.created_at DESC";
$reservasi_list = $conn->query($sql);

// Fetch filter options dynamically
$pjs_res = $conn->query("SELECT DISTINCT pj FROM reservasi WHERE pj != ''");
$destinasis_res = $conn->query("SELECT id, nama FROM destinasi");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - Ngulisik Tour</title>
    <link rel="stylesheet" href="css/admin.css">
</head>
<body>

    <header class="topbar">
        <div class="logo">
            <span class="nt">NT</span>
            <span class="text">NGULISIK<br>TOUR</span>
        </div>
        <div class="user-action">
            <span class="greeting">Halo, <?= htmlspecialchars($_SESSION['name']) ?></span>
            <a href="logout.php" class="btn-logout">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                    <polyline points="16 17 21 12 16 7"></polyline>
                    <line x1="21" y1="12" x2="9" y2="12"></line>
                </svg>
                LOGOUT
            </a>
        </div>
    </header>

    <div class="layout-container">
        
        <aside class="sidebar">
            <nav class="sidebar-menu">
                <a href="admin.php" class="menu-item active">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                        <rect x="3" y="3" width="7" height="7"></rect>
                        <rect x="14" y="3" width="7" height="7"></rect>
                        <rect x="14" y="14" width="7" height="7"></rect>
                        <rect x="3" y="14" width="7" height="7"></rect>
                    </svg>
                    Dashboard
                </a>
                <a href="#" class="menu-item">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                    </svg>
                    Reservasi
                </a>
                <a href="admin_destinasi.php" class="menu-item no-icon">
                    Kelola Konten
                </a>
                <a href="admin_users.php" class="menu-item no-icon">
                    Kelola Pengguna
                </a>
            </nav>
        </aside>

        <main class="content-area">
            
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
                        <h3>RESERVASI BARU</h3>
                        <div style="font-size: 32px; font-weight: bold; margin-top: 10px;"><?= $total_reservasi ?></div>
                    </div>
                    <div class="stat-card card-blue">
                        <h3>TOTAL WISATAWAN</h3>
                        <div style="font-size: 32px; font-weight: bold; margin-top: 10px;"><?= $total_wisatawan ?></div>
                    </div>
                    <div class="stat-card card-green">
                        <h3>TOTAL RESERVASI</h3>
                        <div style="font-size: 32px; font-weight: bold; margin-top: 10px;"><?= $total_reservasi ?></div>
                    </div>
                </div>
            </section>

            <section class="content-section">
                <h2 class="section-title">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                    </svg>
                    RESERVASI
                </h2>
                
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
                            <?php if($reservasi_list && $reservasi_list->num_rows > 0): ?>
                                <?php $no = 1; while($row = $reservasi_list->fetch_assoc()): 
                                    $data_json = htmlspecialchars(json_encode($row), ENT_QUOTES, 'UTF-8');
                                ?>
                                <tr>
                                    <td style="text-align:center;"><?= $no++ ?></td>
                                    <td><?= htmlspecialchars($row['nama']) ?></td>
                                    <td><?= htmlspecialchars($row['alamat']) ?></td>
                                    <td><?= htmlspecialchars($row['no_whatsapp']) ?></td>
                                    <td style="text-align:center;"><?= date('d/m/Y', strtotime($row['tanggal'])) ?></td>
                                    <td><?= htmlspecialchars($row['destinasi_nama']) ?></td>
                                    <td><?= htmlspecialchars($row['pj']) ?></td>
                                    <td style="text-align:center;"><?= htmlspecialchars($row['jumlah']) ?></td>
                                    <td style="text-align:center;">
                                        <button class="btn-detail" onclick="showDetailModal(<?= $data_json ?>)">Detail</button>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="9" style="text-align:center; padding: 20px;">Belum ada data reservasi</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <form method="GET" action="admin.php" class="filters-container">
                    <div class="filter-group left-filter">
                        <select name="pj" class="custom-select" onchange="this.form.submit()">
                            <option value="">Semua PJ</option>
                            <?php if ($pjs_res && $pjs_res->num_rows > 0): ?>
                                <?php while($pj_row = $pjs_res->fetch_assoc()): ?>
                                    <option value="<?= htmlspecialchars($pj_row['pj']) ?>" <?= $filter_pj === $pj_row['pj'] ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($pj_row['pj']) ?>
                                    </option>
                                <?php endwhile; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                    <div class="filter-group right-filter">
                        <select name="destinasi_id" class="custom-select" onchange="this.form.submit()">
                            <option value="">Semua Destinasi</option>
                            <?php if ($destinasis_res && $destinasis_res->num_rows > 0): ?>
                                <?php while($dest_row = $destinasis_res->fetch_assoc()): ?>
                                    <option value="<?= $dest_row['id'] ?>" <?= $filter_destinasi === (int)$dest_row['id'] ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($dest_row['nama']) ?>
                                    </option>
                                <?php endwhile; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                </form>

            </section>

        </main>
    </div>

    <!-- MODAL DETAIL PESANAN -->
    <div class="modal-overlay" id="detailModal">
        <div class="modal-content">
            <button class="modal-close" onclick="closeDetailModal()">&times;</button>
            <h3 class="modal-title">DETAIL PESANAN</h3>
            <div class="detail-grid">
                <div class="detail-label">Nama Lengkap</div>
                <div class="detail-value" id="md-nama"></div>
                
                <div class="detail-label">Alamat Lengkap</div>
                <div class="detail-value" id="md-alamat"></div>
                
                <div class="detail-label">No WhatsApp</div>
                <div class="detail-value" id="md-wa"></div>
                
                <div class="detail-label">Tanggal Kunjungan</div>
                <div class="detail-value" id="md-tanggal"></div>
                
                <div class="detail-label">Destinasi</div>
                <div class="detail-value" id="md-destinasi"></div>
                
                <div class="detail-label">Penanggung Jawab (PJ)</div>
                <div class="detail-value" id="md-pj"></div>
                
                <div class="detail-label">Jumlah Wisatawan</div>
                <div class="detail-value" id="md-jumlah"></div>
            </div>
            <div style="text-align: center; margin-top: 20px;">
                <button class="btn-detail" style="padding: 10px 30px;" onclick="closeDetailModal()">TUTUP</button>
            </div>
        </div>
    </div>

    <script>
        function showDetailModal(data) {
            document.getElementById('md-nama').textContent = ': ' + data.nama;
            document.getElementById('md-alamat').textContent = ': ' + data.alamat;
            document.getElementById('md-wa').textContent = ': ' + data.no_whatsapp;
            
            // Format tanggal from YYYY-MM-DD to DD/MM/YYYY
            const d = new Date(data.tanggal);
            const dateStr = d.toLocaleDateString('id-ID', {day: '2-digit', month: '2-digit', year: 'numeric'});
            document.getElementById('md-tanggal').textContent = ': ' + dateStr;
            
            document.getElementById('md-destinasi').textContent = ': ' + data.destinasi_nama;
            document.getElementById('md-pj').textContent = ': ' + data.pj;
            document.getElementById('md-jumlah').textContent = ': ' + data.jumlah + ' Orang';
            
            document.getElementById('detailModal').classList.add('active');
        }

        function closeDetailModal() {
            document.getElementById('detailModal').classList.remove('active');
        }
    </script>
</body>
</html>