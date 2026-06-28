<?php

namespace App\Http\Controllers;

use App\Models\Destinasi;
use App\Models\Reservasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function dashboard()
    {
        $totalReservasi  = Reservasi::count();
        $totalWisatawan  = Reservasi::sum('jumlah');
        $totalDestinasi  = Destinasi::count();

        // Data reservasi + filter
        $pjList      = Reservasi::distinct()->pluck('pj');
        $destinasiList = Destinasi::all();

        $reservasi = Reservasi::with('destinasi')
            ->when(request('pj'), fn($q) => $q->where('pj', request('pj')))
            ->when(request('destinasi_id'), fn($q) => $q->where('destinasi_id', request('destinasi_id')))
            ->latest()
            ->get();

        return view('admin.dashboard', compact(
            'totalReservasi', 'totalWisatawan', 'totalDestinasi',
            'reservasi', 'pjList', 'destinasiList'
        ));
    }

    // --- CRUD Reservasi ---
    public function storeReservasi(Request $request)
    {
        $data = $request->validate([
            'nama'         => 'required|string|max:255',
            'alamat'       => 'required|string',
            'no_whatsapp'  => 'required|string|max:20',
            'tanggal'      => 'required|date',
            'destinasi_id' => 'required|exists:destinasi,id',
            'pj'           => 'required|string|max:100',
            'jumlah'       => 'required|integer|min:1',
        ]);

        Reservasi::create($data);
        return redirect()->route('admin.dashboard')->with('success', 'Reservasi berhasil ditambahkan!');
    }

    public function destroyReservasi($id)
    {
        Reservasi::findOrFail($id)->delete();
        return redirect()->route('admin.dashboard')->with('success', 'Reservasi berhasil dihapus!');
    }

    // --- CRUD Destinasi ---
    public function destinasiIndex()
    {
        $destinasi = Destinasi::latest()->get();
        return view('admin.destinasi', compact('destinasi'));
    }

    public function storeDestinasi(Request $request)
    {
        $data = $request->validate([
            'nama'     => 'required|string|max:255',
            'deskripsi'=> 'required|string',
            'harga'    => 'required|integer|min:0',
            'gambar'   => 'required|url',
        ]);

        Destinasi::create($data);
        return redirect()->route('admin.destinasi')->with('success', 'Destinasi berhasil ditambahkan!');
    }

    public function destroyDestinasi($id)
    {
        Destinasi::findOrFail($id)->delete();
        return redirect()->route('admin.destinasi')->with('success', 'Destinasi berhasil dihapus!');
    }
}
