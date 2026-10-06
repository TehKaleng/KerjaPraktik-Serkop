<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\GalleryPhoto;
use App\Models\Testimonial;
use App\Models\ContactInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class HomeController extends Controller
{
    public function index()
    {
        return view('serenatacoffee', [
            'menus'       => $this->menuTerurut(),
            'fotos'       => $this->fotoGaleriAcak(), // 5 pertama tampil di grid, seluruhnya ada di tampilan besar
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

    /**
     * Foto galeri untuk bagian Suasana, urutannya diacak setiap kali halaman dimuat.
     * Lima foto pertama tampil di grid, seluruhnya (maksimal $batas) ada di tampilan besar.
     */
    private function fotoGaleriAcak(int $batas = 30)
    {
        $tegak = [];
        $lebar = [];

        foreach (GalleryPhoto::all() as $foto) {
            if ($this->fotoTegak($foto->foto)) {
                $tegak[] = $foto;
            } else {
                $lebar[] = $foto;
            }
        }

        return collect(self::susunFotoAcak($tegak, $lebar, $batas));
    }

    /**
     * Menyusun urutan: [1 foto tegak untuk kotak besar, 4 foto lebar untuk kotak kecil, sisanya acak].
     * Foto dipisah menurut bentuknya supaya tiap kotak di grid selalu mendapat foto yang cocok,
     * walaupun urutannya acak. Kalau salah satu jenis kurang, kotaknya diisi foto jenis lain.
     */
    private static function susunFotoAcak(array $tegak, array $lebar, int $batas = 30): array
    {
        shuffle($tegak);
        shuffle($lebar);

        $besar = $tegak !== [] ? [array_shift($tegak)] : ($lebar !== [] ? [array_shift($lebar)] : []);

        $kecil = array_splice($lebar, 0, 4);
        if (count($kecil) < 4) {
            $kecil = array_merge($kecil, array_splice($tegak, 0, 4 - count($kecil)));
        }

        $sisa = array_merge($tegak, $lebar);
        shuffle($sisa);

        return array_slice(array_merge($besar, $kecil, $sisa), 0, $batas);
    }

    /** True kalau file foto lebih tinggi daripada lebarnya. File yang tidak terbaca dianggap lebar. */
    private function fotoTegak(string $path): bool
    {
        try {
            $disk = Storage::disk('public');

            if (! $disk->exists($path)) {
                return false;
            }

            $ukuran = @getimagesize($disk->path($path));

            return $ukuran !== false && $ukuran[1] > $ukuran[0];
        } catch (\Throwable $e) {
            return false;
        }
    }
}