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
            'name' => 'Dosen Satu',
            'email' => 'dosen1@piksi.ac.id',
            'identifier_number' => 'NIDN12345',
            'password' => bcrypt('password'),
            'role' => 'dosen',
        ]);

        \App\Models\User::create([
            'name' => 'Dosen Dua',
            'email' => 'dosen2@piksi.ac.id',
            'identifier_number' => 'NIDN67890',
            'password' => bcrypt('password'),
            'role' => 'dosen',
        ]);

        \App\Models\User::create([
            'name' => 'Mahasiswa Satu',
            'email' => 'mahasiswa1@piksi.ac.id',
            'identifier_number' => 'NIM001',
            'password' => bcrypt('password'),
            'role' => 'mahasiswa',
        ]);

        \App\Models\User::create([
            'name' => 'Mahasiswa Dua',
            'email' => 'mahasiswa2@piksi.ac.id',
            'identifier_number' => 'NIM002',
            'password' => bcrypt('password'),
            'role' => 'mahasiswa',
        ]);
    }
}
