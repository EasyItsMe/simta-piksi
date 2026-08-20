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
            'name' => 'Dr. Budi Santoso, M.Kom',
            'email' => 'budi.santoso@piksi.ac.id',
            'password' => bcrypt('password')
        ]);
        Dosen::create(['user_id' => $userDosen1->id, 'nidn' => '0412118501', 'nama_lengkap' => 'Dr. Budi Santoso, M.Kom']);

        $userDosen2 = User::create([
            'role_id' => $roleDosen->id,
            'name' => 'Siti Aminah, M.T',
            'email' => 'siti.aminah@piksi.ac.id',
            'password' => bcrypt('password')
        ]);
        Dosen::create(['user_id' => $userDosen2->id, 'nidn' => '0415088203', 'nama_lengkap' => 'Siti Aminah, M.T']);

        $userMhs = User::create([
            'role_id' => $roleMahasiswa->id,
            'name' => 'Ahmad Fauzi',
            'email' => 'ahmad.fauzi@piksi.ac.id',
            'password' => bcrypt('password')
        ]);
        Mahasiswa::create(['user_id' => $userMhs->id, 'nim' => '20230001', 'nama_lengkap' => 'Ahmad Fauzi', 'program_studi' => 'Manajemen Informatika']);
    }
}