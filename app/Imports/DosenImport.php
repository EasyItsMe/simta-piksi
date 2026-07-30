<?php

namespace App\Imports;

use App\Models\Dosen;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class DosenImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        // Skip empty rows
        if (!isset($row['nidn']) || empty($row['nidn'])) {
            return null;
        }

        // Check if NIDN already exists
        if (Dosen::where('nidn', $row['nidn'])->exists()) {
            return null;
        }

        $role = Role::where('nama_role', 'Dosen')->first();
        $email = $row['nidn'] . '@piksi.ac.id';
        
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

        // Create Dosen
        return new Dosen([
            'user_id' => $user->id,
            'nidn' => $row['nidn'],
            'nama_lengkap' => $row['nama_lengkap'],
            'bidang_keahlian' => $row['bidang_keahlian'] ?? '-',
        ]);
    }
}
