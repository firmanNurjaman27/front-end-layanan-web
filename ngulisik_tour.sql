-- =============================================
-- DATABASE: ngulisik_tour
-- Ngulisik Tour - Wisata Tasikmalaya
-- Import file ini melalui phpMyAdmin XAMPP
-- =============================================

CREATE DATABASE IF NOT EXISTS `ngulisik_tour` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `ngulisik_tour`;

-- =============================================
-- Tabel: destinasi
-- =============================================
CREATE TABLE IF NOT EXISTS `destinasi` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `nama` VARCHAR(255) NOT NULL,
  `deskripsi` TEXT NOT NULL,
  `harga` INT NOT NULL DEFAULT 0,
  `gambar` TEXT NOT NULL,
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================
-- Tabel: reservasi
-- =============================================
CREATE TABLE IF NOT EXISTS `reservasi` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `nama` VARCHAR(255) NOT NULL,
  `alamat` TEXT NOT NULL,
  `no_whatsapp` VARCHAR(20) NOT NULL,
  `tanggal` DATE NOT NULL,
  `destinasi_id` INT UNSIGNED NOT NULL,
  `pj` VARCHAR(100) NOT NULL,
  `jumlah` INT NOT NULL DEFAULT 1,
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (`destinasi_id`) REFERENCES `destinasi`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================
-- Tabel: admins
-- =============================================
CREATE TABLE IF NOT EXISTS `admins` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(255) NOT NULL DEFAULT 'Admin',
  `username` VARCHAR(100) NOT NULL UNIQUE,
  `password` VARCHAR(255) NOT NULL,
  `remember_token` VARCHAR(100) NULL,
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================
-- Data: destinasi (4 wisata Tasikmalaya)
-- =============================================
INSERT INTO `destinasi` (`nama`, `deskripsi`, `harga`, `gambar`) VALUES
(
  'GUNUNG GALUNGGUNG',
  'Gunung Galunggung menghadirkan pesona alam yang memukau dengan kawah eksotis, udara sejuk, dan panorama pegunungan yang menenangkan. Cocok untuk liburan, hunting foto, hingga menikmati sunrise yang indah bersama keluarga maupun sahabat. Suasana alamnya yang asri membuat setiap kunjungan terasa lebih berkesan dan menyegarkan.',
  10000,
  'https://images.unsplash.com/photo-1589307357839-2ce1676251ef?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80'
),
(
  'KAMPUNG NAGA',
  'Kampung Naga adalah destinasi wisata budaya yang menawarkan suasana tradisional khas Sunda yang masih terjaga hingga saat ini. Dikelilingi alam yang asri dan udara sejuk, kampung ini menghadirkan pengalaman unik untuk melihat kehidupan masyarakat adat yang hidup harmonis dengan alam dan tetap mempertahankan budaya leluhur.',
  15000,
  'https://images.unsplash.com/photo-1518509562904-e7ef99cdcc86?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80'
),
(
  'KEBUN TEH TARAJU',
  'Kebun Teh Taraju menawarkan hamparan kebun teh hijau yang menyejukkan mata dengan udara pegunungan yang segar dan suasana alam yang tenang. Tempat ini cocok untuk bersantai, menikmati keindahan alam, berburu foto estetik, hingga melepas penat bersama keluarga maupun sahabat.',
  10000,
  'https://images.unsplash.com/photo-1596706972084-297eb0981e6e?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80'
),
(
  'PANTAI KARANG TAWULAN',
  'Pantai Karang Tawulan menghadirkan keindahan pantai selatan dengan hamparan laut biru, tebing karang yang eksotis, dan pemandangan sunset yang memukau. Suasana alamnya yang masih asri membuat tempat ini cocok untuk bersantai, menikmati angin pantai, berburu foto, hingga melepas penat.',
  20000,
  'https://images.unsplash.com/photo-1505243144133-c82d56c80c2f?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80'
);

-- =============================================
-- Data: admin default
-- Password: admin123 (bcrypt hash)
-- =============================================
INSERT INTO `admins` (`name`, `username`, `password`) VALUES
('Administrator', 'admin', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');

-- =============================================
-- Data: contoh reservasi
-- =============================================
INSERT INTO `reservasi` (`nama`, `alamat`, `no_whatsapp`, `tanggal`, `destinasi_id`, `pj`, `jumlah`) VALUES
('Budi Santoso', 'Jl. Merdeka No.10, Tasikmalaya', '08123456789', '2024-08-15', 1, 'Pak Andi', 4),
('Siti Rahayu', 'Jl. Pahlawan No.5, Garut', '08234567890', '2024-08-20', 2, 'Bu Dewi', 6),
('Ahmad Fauzi', 'Jl. Sudirman No.22, Bandung', '08345678901', '2024-08-25', 4, 'Pak Budi', 3);
