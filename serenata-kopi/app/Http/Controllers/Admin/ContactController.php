<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactInfo;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function edit()
    {
        $kontak = ContactInfo::current();

        return view('admin.kontak.edit', compact('kontak'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'alamat'            => 'required|string',
            'jam_buka'          => 'required|date_format:H:i',
            'jam_tutup'         => 'required|date_format:H:i|after:jam_buka',
            'jam_buka_weekend'  => 'required|date_format:H:i',
            'jam_tutup_weekend' => 'required|date_format:H:i|after:jam_buka_weekend',
            'whatsapp'          => 'required|string|max:20',
            'email'             => 'nullable|email',
            'maps_embed'        => 'nullable|string',
            'maps_link'         => 'nullable|string',
            'qris_image'        => 'nullable|image|max:2048',
        ], [
            'jam_tutup.after'               => 'Jam tutup Senin - Jumat harus setelah jam bukanya.',
            'jam_tutup_weekend.after'       => 'Jam tutup Sabtu - Minggu harus setelah jam bukanya.',
            'jam_buka.date_format'          => 'Format jam buka Senin - Jumat harus JJ:MM, contoh 08:00.',
            'jam_tutup.date_format'         => 'Format jam tutup Senin - Jumat harus JJ:MM, contoh 23:00.',
            'jam_buka_weekend.date_format'  => 'Format jam buka Sabtu - Minggu harus JJ:MM, contoh 08:00.',
            'jam_tutup_weekend.date_format' => 'Format jam tutup Sabtu - Minggu harus JJ:MM, contoh 23:30.',
        ]);

        $kontak = ContactInfo::current();

        if ($request->hasFile('qris_image')) {
            $validated['qris_image'] = $request->file('qris_image')->store('qris', 'public');
        } else {
            unset($validated['qris_image']); // jangan timpa kalau nggak upload baru
        }

        $kontak->update($validated);

        return back()->with('success', 'Info kontak berhasil diperbarui.');
    }
}