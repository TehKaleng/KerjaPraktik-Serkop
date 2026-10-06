<?php

namespace Tests\Feature;

use App\Models\Menu;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class AdminAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_tamu_diarahkan_ke_login(): void
    {
        $this->get('/admin')->assertRedirect('/login');
    }

    public function test_user_biasa_tidak_bisa_membuka_panel_admin(): void
    {
        $user = User::factory()->create();
        $menu = Menu::create(['nama' => 'Kopi Susu', 'kategori' => 'kopi', 'harga' => 20000]);

        $this->actingAs($user)->get('/admin')->assertForbidden();
        $this->actingAs($user)->delete("/admin/menu/{$menu->id}")->assertForbidden();
        $this->assertDatabaseHas('menus', ['id' => $menu->id]);
    }

    public function test_admin_bisa_membuka_panel_admin(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole(Role::firstOrCreate(['name' => 'admin']));

        $this->actingAs($admin)->get('/admin')->assertOk();
    }
}
