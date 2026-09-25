<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Meja;
use Illuminate\Http\Request;

class MejaController extends Controller
{
    public function index()
    {
        $mejaByLantai = Meja::orderBy('nomor_meja')->get()->groupBy('lantai');

        return view('admin.meja.index', compact('mejaByLantai'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'lantai'     => 'required|in:1,2,3',
            'nomor_meja' => 'required|string|max:10',
            'kapasitas'  => 'required|integer|min:1',
        ]);

        $kode = 'L' . $validated['lantai'] . '-' . $validated['nomor_meja'];

        if (Meja::where('kode', $kode)->exists()) {
            return back()->withErrors(['nomor_meja' => "Meja dengan kode {$kode} sudah ada."])->withInput();
        }

        Meja::create([
            'lantai'     => $validated['lantai'],
            'nomor_meja' => $validated['nomor_meja'],
            'kapasitas'  => $validated['kapasitas'],
            'kode'       => $kode,
        ]);

        return back()->with('success', "Meja {$kode} berhasil ditambahkan.");
    }

    public function update(Request $request, Meja $meja)
    {
        $validated = $request->validate([
            'kapasitas' => 'required|integer|min:1',
        ]);

        $meja->update(['kapasitas' => $validated['kapasitas']]);

        return back()->with('success', "Kapasitas meja {$meja->kode} berhasil diperbarui.");
    }

    public function destroy(Meja $meja)
    {
        if ($meja->reservations()->whereIn('status', ['pending', 'confirmed'])->exists()) {
            return back()->with('error', "Meja {$meja->kode} tidak bisa dihapus karena masih punya reservasi aktif.");
        }

        $kode = $meja->kode;
        $meja->delete();

        return back()->with('success', "Meja {$kode} berhasil dihapus.");
    }
}
