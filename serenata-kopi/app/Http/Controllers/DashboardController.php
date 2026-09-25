<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $reservasis = $user->reservations()
            ->with(['meja', 'items.menu'])
            ->latest()
            ->get();

        $notifikasi = $user->notifications()->latest()->take(10)->get();

        // Tandai semua notifikasi sebagai sudah dibaca begitu halaman ini dibuka
        $user->unreadNotifications->markAsRead();

        return view('dashboard', compact('reservasis', 'notifikasi'));
    }
}
