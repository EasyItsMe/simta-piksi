<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\User::create([
            'name' => 'Administrator',
            'email' => 'admin@piksi.ac.id',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        \App\Models\User::create([
            'name' => 'Dr. Budi Santoso, M.Kom',
            'email' => 'budi.santoso@piksi.ac.id',
            'identifier_number' => '0412118501',
            'password' => bcrypt('password'),
            'role' => 'dosen',
        ]);

        \App\Models\User::create([
            'name' => 'Siti Aminah, M.T',
            'email' => 'siti.aminah@piksi.ac.id',
            'identifier_number' => '0415088203',
            'password' => bcrypt('password'),
            'role' => 'dosen',
        ]);

        \App\Models\User::create([
            'name' => 'Ahmad Fauzi',
            'email' => 'ahmad.fauzi@piksi.ac.id',
            'identifier_number' => '20230001',
            'password' => bcrypt('password'),
            'role' => 'mahasiswa',
        ]);

        \App\Models\User::create([
            'name' => 'Rina Melati',
            'email' => 'rina.melati@piksi.ac.id',
            'identifier_number' => '20230002',
            'password' => bcrypt('password'),
            'role' => 'mahasiswa',
        ]);
    }
}
