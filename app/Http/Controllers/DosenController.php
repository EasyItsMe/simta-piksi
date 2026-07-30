<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Dosen;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\DosenImport;

class DosenController extends Controller
{
    public function index()
    {
        if (!auth()->user()->isAdmin()) abort(403);
        $dosens = Dosen::with('user')->get();
        return view('dosen.index', compact('dosens'));
    }

    public function create()
    {
        if (!auth()->user()->isAdmin()) abort(403);
        return view('dosen.create');
    }

    public function store(Request $request)
    {
        if (!auth()->user()->isAdmin()) abort(403);
        
        $request->validate([
            'nidn' => 'required|unique:dosen,nidn',
            'nama_lengkap' => 'required|string|max:255',
            'bidang_keahlian' => 'required|string|max:255'
        ]);

        DB::beginTransaction();
        try {
            $role = Role::where('nama_role', 'Dosen')->first();
            $user = User::create([
                'role_id' => $role->id,
                'name' => $request->nama_lengkap,
                'email' => $request->nidn . '@piksi.ac.id',
                'password' => Hash::make('password')
            ]);

            Dosen::create([
                'user_id' => $user->id,
                'nidn' => $request->nidn,
                'nama_lengkap' => $request->nama_lengkap,
                'bidang_keahlian' => $request->bidang_keahlian
            ]);
            
            DB::commit();
            return redirect()->route('dosen.index')->with('success', 'Data Dosen berhasil ditambahkan. (Default Password: password)');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menambahkan data: ' . $e->getMessage());
        }
    }

    public function edit(Dosen $dosen)
    {
        if (!auth()->user()->isAdmin()) abort(403);
        return view('dosen.edit', compact('dosen'));
    }

    public function update(Request $request, Dosen $dosen)
    {
        if (!auth()->user()->isAdmin()) abort(403);
        
        $request->validate([
            'nidn' => 'required|unique:dosen,nidn,' . $dosen->id,
            'nama_lengkap' => 'required|string|max:255',
            'bidang_keahlian' => 'required|string|max:255'
        ]);

        $dosen->update($request->only('nidn', 'nama_lengkap', 'bidang_keahlian'));
        
        if($dosen->user) {
            $dosen->user->update(['name' => $request->nama_lengkap]);
        }
        
        return redirect()->route('dosen.index')->with('success', 'Data Dosen berhasil diperbarui.');
    }

    public function destroy(Dosen $dosen)
    {
        if (!auth()->user()->isAdmin()) abort(403);
        
        if($dosen->user) {
            $dosen->user->delete();
        }
        $dosen->delete();
        
        return redirect()->route('dosen.index')->with('success', 'Data Dosen berhasil dihapus.');
    }

    public function import(Request $request)
    {
        if (!auth()->user()->isAdmin()) abort(403);

        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:2048'
        ]);

        try {
            Excel::import(new DosenImport, $request->file('file'));
            return redirect()->route('dosen.index')->with('success', 'Data Dosen berhasil di-import.');
        } catch (\Exception $e) {
            return redirect()->route('dosen.index')->with('error', 'Terjadi kesalahan saat import: ' . $e->getMessage());
        }
    }
}