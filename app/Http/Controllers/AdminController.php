<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Dosen;

class AdminController extends Controller
{
    public function relasiDosenMahasiswa()
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized action.');
        }

        // Get all dosen and eager load their advising records (bimbingans)
        // and the related thesis (pengajuanJudul) and student (mahasiswa).
        $dosens = Dosen::with(['bimbingans.pengajuanJudul.mahasiswa'])->get();

        return view('admin.relasi', compact('dosens'));
    }

    public function pantauProgress()
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized action.');
        }

        $pengajuans = \App\Models\PengajuanJudul::whereIn('status', ['diajukan', 'diterima', 'direvisi'])
            ->with(['mahasiswa.user', 'pembimbings.dosen', 'progressBimbingans'])
            ->latest()
            ->paginate(15);
            
        return view('admin.progress', compact('pengajuans'));
    }
}
