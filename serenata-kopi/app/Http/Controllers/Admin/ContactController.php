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
            'alamat'     => 'required|string',
            'jam_buka'   => 'required|string|max:10',
            'jam_tutup'  => 'required|string|max:10',
            'whatsapp'   => 'required|string|max:20',
            'email'      => 'nullable|email',
            'maps_embed' => 'nullable|string',
            'maps_link'  => 'nullable|string',
        ]);

        $kontak = ContactInfo::current();
        $kontak->update($validated);

        return back()->with('success', 'Info kontak berhasil diperbarui.');
    }
}
