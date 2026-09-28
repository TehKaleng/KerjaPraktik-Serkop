<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\ReservationItem;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function index()
    {
        $menus = Menu::latest()->get()->map(fn ($m) => [
            'id'        => $m->id,
            'nama'      => $m->nama,
            'kategori'  => ucfirst($m->kategori),
            'harga'     => $m->harga,
            'foto'      => $m->foto,
        ]);

        return view('admin.menu.index', compact('menus'));
    }

    public function edit(Menu $menu)
    {
        return view('admin.menu.edit', compact('menu'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama'       => 'required|string|max:255',
            'kategori'   => 'required|in:kopi,non-kopi,kudapan',
            'harga'      => 'required|integer|min:0',
            'deskripsi'  => 'nullable|string',
            'foto'       => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('foto')) {
            $validated['foto'] = $request->file('foto')->store('menu', 'public');
        }

        Menu::create($validated);

        return back()->with('success', 'Menu berhasil ditambahkan.');
    }

    public function update(Request $request, Menu $menu)
    {
        $validated = $request->validate([
            'nama'       => 'required|string|max:255',
            'kategori'   => 'required|in:kopi,non-kopi,kudapan',
            'harga'      => 'required|integer|min:0',
            'deskripsi'  => 'nullable|string',
            'foto'       => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('foto')) {
            $validated['foto'] = $request->file('foto')->store('menu', 'public');
        }

        $menu->update($validated);

        return back()->with('success', 'Menu berhasil diperbarui.');
    }

    public function destroy(Menu $menu)
    {
        // reservation_items.menu_id pakai restrictOnDelete -> tanpa cek ini, hapus menu
        // yang pernah dipesan bakal error 500.
        if (ReservationItem::where('menu_id', $menu->id)->exists()) {
            return back()->with('error', "Menu \"{$menu->nama}\" tidak bisa dihapus karena sudah pernah dipesan di reservasi.");
        }

        $menu->delete();

        return back()->with('success', 'Menu berhasil dihapus.');
    }
}