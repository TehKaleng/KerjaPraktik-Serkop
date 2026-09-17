<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GalleryPhoto;
use Illuminate\Http\Request;

class GalleryController extends Controller
{
    public function index()
    {
        $fotos = GalleryPhoto::latest()->get()->map(fn ($f) => [
            'id'         => $f->id,
            'foto'       => $f->foto,
            'keterangan' => $f->keterangan,
        ]);

        return view('admin.galeri.index', compact('fotos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'foto'       => 'required|image|max:2048',
            'keterangan' => 'nullable|string|max:255',
        ]);

        GalleryPhoto::create([
            'foto'       => $request->file('foto')->store('galeri', 'public'),
            'keterangan' => $request->keterangan,
        ]);

        return back()->with('success', 'Foto berhasil diupload.');
    }

    public function destroy(GalleryPhoto $galeri)
    {
        $galeri->delete();

        return back()->with('success', 'Foto berhasil dihapus.');
    }
}
