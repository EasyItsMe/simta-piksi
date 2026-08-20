<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PendaftaranTa;

class PendaftaranTaController extends Controller
{
    // UNTUK MAHASISWA
    public function create()
    {
        $mahasiswa = auth()->user()->mahasiswa;
        $pendaftaran = PendaftaranTa::where('mahasiswa_id', $mahasiswa->id)->first();
        return view('pendaftaran.create', compact('pendaftaran'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'file_krs' => 'required|mimes:pdf|max:2048',
            'file_transkrip' => 'required|mimes:pdf|max:2048',
            'file_pembayaran' => 'required|mimes:pdf|max:2048',
        ]);

        $mahasiswa = auth()->user()->mahasiswa;
        $pendaftaran = PendaftaranTa::where('mahasiswa_id', $mahasiswa->id)->first();

        $data = [
            'mahasiswa_id' => $mahasiswa->id,
            'status' => 'menunggu',
            'keterangan' => null
        ];

        if ($request->hasFile('file_krs')) {
            $data['file_krs'] = $request->file('file_krs')->store('pendaftaran', 'public');
        }
        if ($request->hasFile('file_transkrip')) {
            $data['file_transkrip'] = $request->file('file_transkrip')->store('pendaftaran', 'public');
        }
        if ($request->hasFile('file_pembayaran')) {
            $data['file_pembayaran'] = $request->file('file_pembayaran')->store('pendaftaran', 'public');
        }

        if ($pendaftaran) {
            $pendaftaran->update($data);
        } else {
            PendaftaranTa::create($data);
        }

        return redirect()->route('pendaftaran-ta.create')->with('success', 'Dokumen syarat pendaftaran berhasil diunggah dan sedang menunggu verifikasi Admin.');
    }

    // UNTUK ADMIN
    public function index()
    {
        $pendaftarans = PendaftaranTa::with('mahasiswa.user')->latest()->get();
        return view('admin.pendaftaran.index', compact('pendaftarans'));
    }

    public function updateStatus(Request $request, PendaftaranTa $pendaftaran)
    {
        $request->validate([
            'status' => 'required|in:disetujui,ditolak',
            'keterangan' => 'nullable|string'
        ]);

        $pendaftaran->update([
            'status' => $request->status,
            'keterangan' => $request->keterangan
        ]);

        return redirect()->route('admin.pendaftaran-ta.index')->with('success', 'Status pendaftaran berhasil diperbarui.');
    }
}
