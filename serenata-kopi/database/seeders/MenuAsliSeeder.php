<?php

namespace Database\Seeders;

use App\Models\Menu;
use Illuminate\Database\Seeder;

class MenuAsliSeeder extends Seeder
{

    private const DAFTAR = [
        // Kopi - Signature Coffee
        ['The Next Level V1 (Cookies)', 'kopi', 27, 'Kopi signature Serenata dengan cita rasa cookies.'],
        ['The Next Level V2 (Keju)', 'kopi', 27, 'Kopi signature Serenata dengan cita rasa keju.'],
        ['Peanut Butter', 'kopi', 27, 'Kopi signature dengan cita rasa selai kacang (peanut butter).'],
        ['Kopok', 'kopi', 28, 'Kopi dan alpukat dalam satu gelas.'],
        ['Mareso', 'kopi', 26, 'Perpaduan markisa dan espresso.'],
        ['Matcha Presso', 'kopi', 28, 'Perpaduan espresso dan matcha dalam satu gelas.'],
        ['Banoffee', 'kopi', 28, 'Kopi bercita rasa banoffee: perpaduan pisang dan karamel toffee.'],
        // Kopi - Espresso Based
        ['Espresso', 'kopi', 23, 'Kopi pekat murni dari mesin espresso, tanpa tambahan.'],
        ['Americano', 'kopi', 25, 'Espresso yang dicampur air, rasa kopinya ringan dan bersih.'],
        ['Coffee Latte', 'kopi', 26, 'Espresso dengan susu yang lembut.'],
        ['Mocha Latte', 'kopi', 27, 'Espresso, susu, dan cokelat dalam satu gelas.'],
        ['Cappucino', 'kopi', 26, 'Espresso dengan susu dan buih susu yang tebal.'],
        // Kopi - Manual Brew
        ['V60', 'kopi', 28, 'Kopi seduh manual dengan teknik V60, rasanya bersih dan aromanya terasa.'],
        ['Japanese', 'kopi', 28, 'Kopi seduh manual bergaya Jepang.'],
        ['Vietnam Drip', 'kopi', 25, 'Kopi tetes ala Vietnam dengan saringan khas, rasanya pekat.'],
        ['Tubruk', 'kopi', 20, 'Kopi tubruk khas Indonesia: bubuk kopi diseduh langsung dengan air panas.'],
        ['Sanger', 'kopi', 25, 'Kopi susu khas Aceh.'],
        // Non-Kopi - Signature Non Coffee
        ['Rose Pink', 'non-kopi', 26, 'Minuman segar Yakult dan susu dengan sirup raspberry.'],
        ['Yapok', 'non-kopi', 25, 'Perpaduan Yakult dan alpukat.'],
        ['Chocomaro', 'non-kopi', 28, 'Minuman cokelat dan matcha dengan topping Oreo.'],
        // Non-Kopi - Non Coffee
        ['Avocado', 'non-kopi', 25, 'Minuman alpukat yang creamy dan segar.'],
        ['Banana', 'non-kopi', 25, 'Minuman pisang yang creamy.'],
        ['Cookies & Cream', 'non-kopi', 26, 'Minuman susu bercita rasa cookies and cream, manis dan creamy.'],
        ['Dark Chocolate', 'non-kopi', 27, 'Minuman cokelat pekat dengan rasa cokelat yang kuat.'],
        ['Mava Latte', 'non-kopi', 27, 'Perpaduan matcha dan vanila.'],
        ['Redvelvet', 'non-kopi', 25, 'Minuman red velvet yang manis dan creamy.'],
        ['Taro Latte', 'non-kopi', 25, 'Minuman susu rasa talas (taro) yang manis dan lembut.'],
        ['Vanilla', 'non-kopi', 25, 'Minuman susu rasa vanila yang lembut.'],
        // Non-Kopi - Tropical
        ['Yama Booster', 'non-kopi', 25, 'Minuman segar Yakult dengan markisa.'],
        ['Passion Tropical', 'non-kopi', 23, 'Minuman segar rasa markisa (passion fruit) bergaya tropis.'],
        ['Celi Mojito', 'non-kopi', 23, 'Minuman segar leci dan Sprite.'],
        ['Pinkberry Mint', 'non-kopi', 23, 'Minuman segar perpaduan berry dan mint.'],
        ['Yakult Lime', 'non-kopi', 25, 'Minuman segar Yakult dengan perasan jeruk nipis (lime).'],
        // Kudapan - Salty Snack
        ['Beef Wrap with Fries', 'kudapan', 30, 'Wrap isi daging sapi, disajikan dengan kentang goreng dan saus.'],
        ['Bukan Otak-Otak', 'kudapan', 24, 'Kudapan bakso ikan.'],
        ['Cireng', 'kudapan', 23, 'Aci goreng khas Sunda, renyah di luar dan kenyal di dalam.'],
        ['Ekado Roll', 'kudapan', 26, 'Siomay ala Jepang yang dibalut kulit tahu, lalu digoreng.'],
        ['French Fries', 'kudapan', 24, 'Kentang goreng yang renyah.'],
        ['Mix Platter', 'kudapan', 28, 'Hidangan campur berisi kentang, sosis, dan bakso.'],
        ['Tofu Meatball', 'kudapan', 28, 'Tahu bakso goreng.'],
        // Kudapan - Sweet Snack
        ['Choco Banana', 'kudapan', 25, 'Pisang dengan cokelat leleh dalam balutan renyah, ditaburi gula halus.'],
        ['Chocolate Mantou', 'kudapan', 22, 'Mantou goreng bertabur gula halus, disajikan dengan saus cocolan.'],
        // Makanan - Main Course
        ['Ayam Geprek Serenata', 'makanan', 32, 'Ayam goreng geprek dengan sambal, disajikan bersama nasi dan timun.'],
        ['Fire Chicken with Cheese', 'makanan', 32, 'Ayam berbumbu pedas dengan saus keju, disajikan bersama nasi dan timun.'],
        ['Chicken Chop with Blackpepper Sauce', 'makanan', 32, 'Chicken chop dengan saus lada hitam, disajikan bersama nasi dan timun.'],
        ['Spaghetti Bolognese', 'makanan', 32, 'Spageti dengan saus bolognese yang gurih.'],
        ['Spaghetti Carbonara', 'makanan', 32, 'Spageti ala carbonara yang gurih.'],
        // Tambahan - Add-ons Topping
        ['Cream Cheese', 'tambahan', 5, 'Tambahan topping cream cheese untuk minumanmu.'],
        ['Oreo', 'tambahan', 3, 'Tambahan topping Oreo untuk minumanmu.'],
        ['Regal', 'tambahan', 3, 'Tambahan topping biskuit Regal untuk minumanmu.'],
        ['Extra Shot Espresso', 'tambahan', 5, 'Tambahan satu shot espresso untuk kopi yang lebih kuat.'],
        ['Mineral', 'tambahan', 8, 'Air mineral.'],
    ];

    public function run(): void
    {
        foreach (self::DAFTAR as [$nama, $kategori, $ribu, $deskripsi]) {
            $menu = Menu::firstOrNew(['nama' => $nama]);
            $menu->kategori = $kategori;
            $menu->harga    = $ribu * 1000;

            if ($deskripsi !== null && blank($menu->deskripsi)) {
                $menu->deskripsi = $deskripsi;
            }

            $menu->save();
        }
    }
}