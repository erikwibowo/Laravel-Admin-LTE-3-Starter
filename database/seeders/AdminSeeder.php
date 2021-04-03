<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Admin::insert([
            'name' => 'Erik WIbowo',
            'email' => 'admin@admin.com',
            'password' => Hash::make('admin'), // password
            'phone' => '081510815414',
            'address' => 'Jalan Pahlawan 51161 Pekalongan Jawa Tengah',
            'photo' => 'profil.jpg',
            'thumb' => 'profil.jpg',
            'level' => "SUPER ADMIN",
            'status' => 1,
            'login_at' => now(),
            'created_at' => now()
        ]);
    }
}
