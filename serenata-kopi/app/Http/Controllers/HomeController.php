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
            'menus'       => Menu::latest()->get(),
            'fotos'       => GalleryPhoto::latest()->take(4)->get(),
            'testimonis'  => Testimonial::where('tampil', true)->latest()->take(6)->get(),
            'kontak'      => ContactInfo::current(),
        ]);
    }
}
