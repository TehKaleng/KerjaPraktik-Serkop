<?php

namespace Database\Seeders;

use App\Models\Menu;
use Illuminate\Database\Seeder;

class MenuAsliSeeder extends Seeder
{
    /**
     * Daftar menu asli Serenata Kopi & Space (dari buku menu di kafe).
     * Format: [nama, kategori, harga dalam ribuan rupiah]  ->  27 berarti Rp 27.000
     *
     * Aman dijalankan berulang: menu dicari berdasarkan nama. Kalau sudah ada,
     * hanya kategori dan harganya yang disamakan; foto dan deskripsi tidak diubah.
     * Menu yang kamu tambah sendiri lewat panel admin tidak tersentuh.
     */
    private const DAFTAR = [
        // Kopi - Signature Coffee
        ['The Next Level V1 (Cookies)', 'kopi', 27],
        ['The Next Level V2 (Keju)', 'kopi', 27],
        ['Peanut Butter', 'kopi', 27],
        ['Kopok', 'kopi', 28],
        ['Mareso', 'kopi', 26],
        ['Matcha Presso', 'kopi', 28],
        ['Banoffee', 'kopi', 28],
        // Kopi - Espresso Based
        ['Espresso', 'kopi', 23],
        ['Americano', 'kopi', 25],
        ['Coffee Latte', 'kopi', 26],
        ['Mocha Latte', 'kopi', 29],
        ['Cappucino', 'kopi', 25],
        // Kopi - Manual Brew
        ['V60', 'kopi', 28],
        ['Japanese', 'kopi', 28],
        ['Vietnam Drip', 'kopi', 25],
        ['Tubruk', 'kopi', 20],
        ['Sanger', 'kopi', 25],
        // Non-Kopi - Signature Non Coffee
        ['Rose Pink', 'non-kopi', 26],
        ['Yapok', 'non-kopi', 25],
        ['Chocomaro', 'non-kopi', 28],
        // Non-Kopi - Non Coffee
        ['Avocado', 'non-kopi', 25],
        ['Banana', 'non-kopi', 25],
        ['Cookies & Cream', 'non-kopi', 26],
        ['Dark Chocolate', 'non-kopi', 27],
        ['Mava Latte', 'non-kopi', 27],
        ['Redvelvet', 'non-kopi', 25],
        ['Taro Latte', 'non-kopi', 25],
        ['Vanilla', 'non-kopi', 25],
        // Non-Kopi - Tropical
        ['Yama Booster', 'non-kopi', 25],
        ['Passion Tropical', 'non-kopi', 23],
        ['Celi Mojito', 'non-kopi', 23],
        ['Pinkberry Mint', 'non-kopi', 23],
        ['Yakult Lime', 'non-kopi', 25],
        // Kudapan - Salty Snack
        ['Beef Wrap with Fries', 'kudapan', 30],
        ['Bukan Otak-Otak', 'kudapan', 24],
        ['Cireng', 'kudapan', 23],
        ['Ekado Roll', 'kudapan', 26],
        ['French Fries', 'kudapan', 24],
        ['Mix Platter', 'kudapan', 28],
        ['Tofu Meatball', 'kudapan', 28],
        // Kudapan - Sweet Snack
        ['Choco Banana', 'kudapan', 25],
        ['Chocolate Mantou', 'kudapan', 22],
        // Makanan - Main Course
        ['Ayam Geprek Serenata', 'makanan', 32],
        ['Fire Chicken with Cheese', 'makanan', 32],
        ['Chicken Chop with Blackpepper Sauce', 'makanan', 32],
        ['Spaghetti Bolognese', 'makanan', 32],
        ['Spaghetti Carbonara', 'makanan', 32],
        // Tambahan - Add-ons Topping
        ['Cream Cheese', 'tambahan', 5],
        ['Oreo', 'tambahan', 3],
        ['Regal', 'tambahan', 3],
        ['Extra Shot Espresso', 'tambahan', 5],
        ['Mineral', 'tambahan', 8],
    ];

    public function run(): void
    {
        foreach (self::DAFTAR as [$nama, $kategori, $ribu]) {
            $menu = Menu::firstOrNew(['nama' => $nama]);
            $menu->kategori = $kategori;
            $menu->harga    = $ribu * 1000;
            $menu->save();
        }
    }
}
