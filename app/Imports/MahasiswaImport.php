<?php

namespace App\Imports;

use App\Models\Mahasiswa;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class MahasiswaImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        // Skip empty rows
        if (!isset($row['nim']) || empty($row['nim'])) {
            return null;
        }

        // Check if NIM already exists
        if (Mahasiswa::where('nim', $row['nim'])->exists()) {
            return null;
        }

        $role = Role::where('nama_role', 'Mahasiswa')->first();
        $email = $row['nim'] . '@piksi.ac.id';
        
        // Find existing User including soft deleted
        $user = User::withTrashed()->where('email', $email)->first();
        
        if ($user) {
            // Restore if deleted and update name
            if ($user->trashed()) {
                $user->restore();
            }
            $user->update(['name' => $row['nama_lengkap']]);
        } else {
            // Create new User
            $user = User::create([
                'role_id' => $role->id,
                'name' => $row['nama_lengkap'],
                'email' => $email,
                'password' => Hash::make('password'),
            ]);
        }

        // Create Mahasiswa
        return new Mahasiswa([
            'user_id' => $user->id,
            'nim' => $row['nim'],
            'nama_lengkap' => $row['nama_lengkap'],
            'program_studi' => $row['program_studi'] ?? 'Sistem Informasi',
            'angkatan' => $row['angkatan'] ?? date('Y'),
        ]);
    }
}
