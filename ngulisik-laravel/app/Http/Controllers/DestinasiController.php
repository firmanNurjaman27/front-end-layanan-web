<?php

namespace App\Http\Controllers;

use App\Models\Destinasi;
use Illuminate\Http\Request;

class DestinasiController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search', '');

        $destinasi = Destinasi::when($search, function ($query) use ($search) {
            return $query->where('nama', 'like', "%{$search}%");
        })->get();

        return view('destinasi.index', compact('destinasi', 'search'));
    }

    public function show($id)
    {
        $destinasi = Destinasi::findOrFail($id);
        return view('destinasi.show', compact('destinasi'));
    }
}
