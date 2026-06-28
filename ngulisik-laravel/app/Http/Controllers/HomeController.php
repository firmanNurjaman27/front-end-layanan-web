<?php

namespace App\Http\Controllers;

use App\Models\Destinasi;

class HomeController extends Controller
{
    public function index()
    {
        // Ambil semua destinasi dari database (tampil 4 di homepage)
        $destinasi = Destinasi::latest()->take(4)->get();
        return view('home', compact('destinasi'));
    }
}
