<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Testimonial;
use Illuminate\Http\Request;

class TestimonialController extends Controller
{
    public function index()
    {
        $testimonis = Testimonial::latest()->get()->map(fn ($t) => [
            'id'     => $t->id,
            'nama'   => $t->nama,
            'peran'  => $t->peran,
            'rating' => $t->rating,
            'isi'    => $t->isi,
            'tampil' => $t->tampil,
        ]);

        return view('admin.testimoni.index', compact('testimonis'));
    }

    public function edit(Testimonial $testimoni)
    {
        return view('admin.testimoni.edit', compact('testimoni'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama'   => 'required|string|max:255',
            'peran'  => 'nullable|string|max:255',
            'rating' => 'required|integer|min:1|max:5',
            'status' => 'required|in:0,1',
            'isi'    => 'required|string',
        ]);

        Testimonial::create([
            'nama'   => $validated['nama'],
            'peran'  => $validated['peran'] ?? null,
            'rating' => $validated['rating'],
            'isi'    => $validated['isi'],
            'tampil' => (bool) $validated['status'],
        ]);

        return back()->with('success', 'Testimoni berhasil ditambahkan.');
    }

    public function update(Request $request, Testimonial $testimoni)
    {
        $validated = $request->validate([
            'nama'   => 'required|string|max:255',
            'peran'  => 'nullable|string|max:255',
            'rating' => 'required|integer|min:1|max:5',
            'status' => 'required|in:0,1',
            'isi'    => 'required|string',
        ]);

        $testimoni->update([
            'nama'   => $validated['nama'],
            'peran'  => $validated['peran'] ?? null,
            'rating' => $validated['rating'],
            'isi'    => $validated['isi'],
            'tampil' => (bool) $validated['status'],
        ]);

        return back()->with('success', 'Testimoni berhasil diperbarui.');
    }

    public function destroy(Testimonial $testimoni)
    {
        $testimoni->delete();

        return back()->with('success', 'Testimoni berhasil dihapus.');
    }
}
