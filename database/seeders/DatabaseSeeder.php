<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\User;
use App\Models\Mahasiswa;
use App\Models\Dosen;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $roleAdmin = Role::create(['nama_role' => 'Admin']);
        $roleDosen = Role::create(['nama_role' => 'Dosen']);
        $roleMahasiswa = Role::create(['nama_role' => 'Mahasiswa']);

        $admin = User::create([
            'role_id' => $roleAdmin->id,
            'name' => 'Administrator',
            'email' => 'admin@piksi.ac.id',
            'password' => bcrypt('password')
        ]);

        $userDosen1 = User::create([
            'role_id' => $roleDosen->id,
            'name' => 'Dosen Satu',
            'email' => 'dosen1@piksi.ac.id',
            'password' => bcrypt('password')
        ]);
        Dosen::create(['user_id' => $userDosen1->id, 'nidn' => 'NIDN123', 'nama_lengkap' => 'Dosen Satu, M.Kom']);

        $userDosen2 = User::create([
            'role_id' => $roleDosen->id,
            'name' => 'Dosen Dua',
            'email' => 'dosen2@piksi.ac.id',
            'password' => bcrypt('password')
        ]);
        Dosen::create(['user_id' => $userDosen2->id, 'nidn' => 'NIDN456', 'nama_lengkap' => 'Dosen Dua, M.T']);

        $userMhs = User::create([
            'role_id' => $roleMahasiswa->id,
            'name' => 'Mahasiswa Satu',
            'email' => 'mahasiswa1@piksi.ac.id',
            'password' => bcrypt('password')
        ]);
        Mahasiswa::create(['user_id' => $userMhs->id, 'nim' => 'NIM001', 'nama_lengkap' => 'Mahasiswa Satu', 'program_studi' => 'Teknik Informatika']);
    }
}