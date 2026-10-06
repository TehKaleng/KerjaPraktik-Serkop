<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\GalleryPhoto;
use App\Models\Testimonial;
use App\Models\ContactInfo;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        return view('serenatacoffee', [
            'menus'       => $this->menuTerurut(),
            'fotos'       => GalleryPhoto::latest()->take(30)->get(), // grid menampilkan 5 pertama; sisanya di tampilan besar
            'testimonis'  => Testimonial::where('tampil', true)->latest()->take(6)->get(),
            'kontak'      => ContactInfo::current(),
        ]);
    }

    /**
     * Menu diurutkan per kategori (kopi, non-kopi, kudapan, makanan, tambahan),
     * lalu sesuai urutan input di dalam kategori. Urutan tab di halaman depan ikut urutan ini.
     */
    private function menuTerurut()
    {
        $urutan = ['kopi', 'non-kopi', 'kudapan', 'makanan', 'tambahan'];

        return Menu::orderBy('id')->get()
            ->sortBy(fn ($menu) => ($i = array_search($menu->kategori, $urutan)) === false ? 99 : $i)
            ->values();
    }
}