<?php

namespace Database\Seeders;

use App\Models\Menu;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Menu::insert([
            'menu'  => 'MENU UTAMA',
            'type'  => 'title',
            'icon'  => null,
            'route' => null,
            'uri'  => null,
            'urut' => 1,
            'created_at' => now()
        ]);
        Menu::insert([
            'menu'  => 'Dashboard',
            'type'  => 'menu',
            'icon'  => 'fas fa-tachometer-alt',
            'route' => 'admin.dashboard',
            'uri'   => 'dashboard',
            'urut' => 2,
            'created_at' => now()
        ]);
        Menu::insert([
            'menu'  => 'Admin',
            'type'  => 'menu',
            'icon'  => 'fas fa-user',
            'route' => 'admin.admin.index',
            'uri'   => 'admin',
            'urut' => 3,
            'created_at' => now()
        ]);
        Menu::insert([
            'menu'  => 'Menu',
            'type'  => 'menu',
            'icon'  => 'fas fa-th-list',
            'route' => 'admin.menu.index',
            'uri'   => 'menu',
            'urut' => 4,
            'created_at' => now()
        ]);
    }
}
