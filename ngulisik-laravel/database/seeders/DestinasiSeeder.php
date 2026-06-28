<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Destinasi;

class DestinasiSeeder extends Seeder
{
    public function run(): void
    {
        $destinasi = [
            [
                'nama'      => 'GUNUNG GALUNGGUNG',
                'deskripsi' => 'Gunung Galunggung menghadirkan pesona alam yang memukau dengan kawah eksotis, udara sejuk, dan panorama pegunungan yang menenangkan. Cocok untuk liburan, hunting foto, hingga menikmati sunrise yang indah bersama keluarga maupun sahabat. Suasana alamnya yang asri membuat setiap kunjungan terasa lebih berkesan dan menyegarkan.',
                'harga'     => 10000,
                'gambar'    => 'https://images.unsplash.com/photo-1589307357839-2ce1676251ef?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80',
            ],
            [
                'nama'      => 'KAMPUNG NAGA',
                'deskripsi' => 'Kampung Naga adalah destinasi wisata budaya yang menawarkan suasana tradisional khas Sunda yang masih terjaga hingga saat ini. Dikelilingi alam yang asri dan udara sejuk, kampung ini menghadirkan pengalaman unik untuk melihat kehidupan masyarakat adat yang hidup harmonis dengan alam dan tetap mempertahankan budaya leluhur.',
                'harga'     => 15000,
                'gambar'    => 'https://images.unsplash.com/photo-1518509562904-e7ef99cdcc86?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80',
            ],
            [
                'nama'      => 'KEBUN TEH TARAJU',
                'deskripsi' => 'Kebun Teh Taraju menawarkan hamparan kebun teh hijau yang menyejukkan mata dengan udara pegunungan yang segar dan suasana alam yang tenang. Tempat ini cocok untuk bersantai, menikmati keindahan alam, berburu foto estetik, hingga melepas penat bersama keluarga maupun sahabat.',
                'harga'     => 10000,
                'gambar'    => 'https://images.unsplash.com/photo-1596706972084-297eb0981e6e?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80',
            ],
            [
                'nama'      => 'PANTAI KARANG TAWULAN',
                'deskripsi' => 'Pantai Karang Tawulan menghadirkan keindahan pantai selatan dengan hamparan laut biru, tebing karang yang eksotis, dan pemandangan sunset yang memukau. Suasana alamnya yang masih asri membuat tempat ini cocok untuk bersantai, menikmati angin pantai, berburu foto, hingga melepas penat.',
                'harga'     => 20000,
                'gambar'    => 'https://images.unsplash.com/photo-1505243144133-c82d56c80c2f?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80',
            ],
        ];

        foreach ($destinasi as $item) {
            Destinasi::firstOrCreate(['nama' => $item['nama']], $item);
        }
    }
}
