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
            'created_at' => now()
        ]);
        Menu::insert([
            'menu'  => 'Dashboard',
            'type'  => 'menu',
            'icon'  => 'fas fa-tachometer-alt',
            'route' => 'admin.dashboard',
            'uri'   => 'dashboard',
            'created_at' => now()
        ]);
        Menu::insert([
            'menu'  => 'Admin',
            'type'  => 'menu',
            'icon'  => 'fas fa-user',
            'route' => 'admin.admin.index',
            'uri'   => 'admin',
            'created_at' => now()
        ]);
        Menu::insert([
            'menu'  => 'Menu',
            'type'  => 'menu',
            'icon'  => 'fas fa-th-list',
            'route' => 'admin.menu.index',
            'uri'   => 'menu',
            'created_at' => now()
        ]);
    }
}
