<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        DB::table('users')->insert([
            [
                'name' => 'admin',
                'email' => 'admin@gmail.com',
                'password' => Hash::make('12345678'),
                'role' => 'Admin',
                'created_at' => date('Y-m-d H:i:s')
            ],
            [
                'name' => 'user',
                'email' => 'user@gmail.com',
                'password' => Hash::make('12345678'),
                'role' => 'Customer',
                'created_at' => date('Y-m-d H:i:s')
            ]
        ]);
        DB::table('mesins')->insert([
            [
                'nama' => 'Mesin 1',
                'type' => 'Cuci',
                'durasi' => '28',
                'Status' => 'Ready',
            ],
            [
                'nama' => 'Mesin 1',
                'type' => 'Pengering',
                'durasi' => '42',
                'Status' => 'Ready',
            ],
            [
                'nama' => 'Mesin 2',
                'type' => 'Cuci',
                'durasi' => '28',
                'Status' => 'Ready',
            ],
            [
                'nama' => 'Mesin 2',
                'type' => 'Pengering',
                'durasi' => '42',
                'Status' => 'Ready',
            ],
            [
                'nama' => 'Mesin 3',
                'type' => 'Cuci',
                'durasi' => '28',
                'Status' => 'Ready',
            ],
            [
                'nama' => 'Mesin 3',
                'type' => 'Pengering',
                'durasi' => '42',
                'Status' => 'Ready',
            ],
            [
                'nama' => 'Mesin 4',
                'type' => 'Cuci',
                'durasi' => '28',
                'Status' => 'Ready',
            ],
            [
                'nama' => 'Mesin 4',
                'type' => 'Pengering',
                'durasi' => '42',
                'Status' => 'Ready',
            ],
            [
                'nama' => 'Mesin 5',
                'type' => 'Cuci',
                'durasi' => '28',
                'Status' => 'Ready',
            ],
            [
                'nama' => 'Mesin 5',
                'type' => 'Pengering',
                'durasi' => '42',
                'Status' => 'Ready',
            ],
             [
                'nama' => 'Mesin 6',
                'type' => 'Cuci',
                'durasi' => '28',
                'Status' => 'Ready',
            ],
            [
                'nama' => 'Mesin 6',
                'type' => 'Pengering',
                'durasi' => '42',
                'Status' => 'Ready',
            ],
        ]);
    }
}
