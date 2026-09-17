<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\GalleryPhoto;
use App\Models\Testimonial;
use App\Models\Reservation;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        return view('admin.dashboard', [
            'totalMenu'         => Menu::count(),
            'totalGaleri'       => GalleryPhoto::count(),
            'totalTestimoni'    => Testimonial::where('tampil', true)->count(),
            'reservasiHariIni'  => Reservation::whereDate('tanggal', today())->count(),
            'reservasiTerbaru'  => Reservation::latest()->take(5)->get()->map(fn ($r) => [
                'nama'    => $r->nama,
                'wa'      => $r->whatsapp,
                'tanggal' => $r->tanggal->format('d M Y'),
                'jam'     => substr($r->jam, 0, 5),
                'orang'   => $r->jumlah_orang,
                'status'  => $r->status,
            ]),
        ]);
    }
}
