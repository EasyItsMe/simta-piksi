<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mahasiswa;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\MahasiswaImport;

class MahasiswaController extends Controller
{
    public function index()
    {
        if (!auth()->user()->isAdmin()) abort(403);
        $mahasiswas = Mahasiswa::with('user')->get();
        return view('mahasiswa.index', compact('mahasiswas'));
    }

    public function create()
    {
        if (!auth()->user()->isAdmin()) abort(403);
        return view('mahasiswa.create');
    }

    public function store(Request $request)
    {
        if (!auth()->user()->isAdmin()) abort(403);
        
        $request->validate([
            'nim' => 'required|unique:mahasiswa,nim',
            'nama_lengkap' => 'required|string|max:255',
            'program_studi' => 'required|string|max:100',
            'angkatan' => 'required|numeric'
        ]);

        DB::beginTransaction();
        try {
            $role = Role::where('nama_role', 'Mahasiswa')->first();
            $user = User::create([
                'role_id' => $role->id,
                'name' => $request->nama_lengkap,
                'email' => $request->nim . '@piksi.ac.id',
                'password' => Hash::make('password') // default password
            ]);

            Mahasiswa::create([
                'user_id' => $user->id,
                'nim' => $request->nim,
                'nama_lengkap' => $request->nama_lengkap,
                'program_studi' => $request->program_studi,
                'angkatan' => $request->angkatan,
            ]);
            
            DB::commit();
            return redirect()->route('mahasiswa.index')->with('success', 'Data Mahasiswa berhasil ditambahkan. (Default Password: password)');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menambahkan data: ' . $e->getMessage());
        }
    }

    public function edit(Mahasiswa $mahasiswa)
    {
        if (!auth()->user()->isAdmin()) abort(403);
        return view('mahasiswa.edit', compact('mahasiswa'));
    }

    public function update(Request $request, Mahasiswa $mahasiswa)
    {
        if (!auth()->user()->isAdmin()) abort(403);
        
        $request->validate([
            'nim' => 'required|unique:mahasiswa,nim,' . $mahasiswa->id,
            'nama_lengkap' => 'required|string|max:255',
            'program_studi' => 'required|string|max:100',
            'angkatan' => 'required|numeric'
        ]);

        $mahasiswa->update($request->only('nim', 'nama_lengkap', 'program_studi', 'angkatan'));
        
        if($mahasiswa->user) {
            $mahasiswa->user->update(['name' => $request->nama_lengkap]);
        }
        
        return redirect()->route('mahasiswa.index')->with('success', 'Data Mahasiswa berhasil diperbarui.');
    }

    public function destroy(Mahasiswa $mahasiswa)
    {
        if (!auth()->user()->isAdmin()) abort(403);
        
        if($mahasiswa->user) {
            $mahasiswa->user->delete(); // This should cascade if DB is set up, but safe to delete user
        }
        $mahasiswa->delete();
        
        return redirect()->route('mahasiswa.index')->with('success', 'Data Mahasiswa berhasil dihapus.');
    }

    public function import(Request $request)
    {
        if (!auth()->user()->isAdmin()) abort(403);

        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:2048'
        ]);

        try {
            Excel::import(new MahasiswaImport, $request->file('file'));
            return redirect()->route('mahasiswa.index')->with('success', 'Data Mahasiswa berhasil di-import.');
        } catch (\Exception $e) {
            return redirect()->route('mahasiswa.index')->with('error', 'Terjadi kesalahan saat import: ' . $e->getMessage());
        }
    }
}