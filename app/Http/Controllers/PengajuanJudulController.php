<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PengajuanJudul;
use App\Models\Dosen;
use App\Models\Pembimbing;
use App\Models\Notifikasi;
use App\Http\Requests\StorePengajuanJudulRequest;
use App\Http\Requests\UpdatePengajuanJudulRequest;
use Illuminate\Support\Facades\Storage;

class PengajuanJudulController extends Controller {
    public function index() {
        $user = auth()->user();
        if ($user->isAdmin()) {
            $pengajuan = PengajuanJudul::with(['mahasiswa.user', 'pembimbings.dosen'])->latest()->paginate(10);
        } elseif ($user->isDosen()) {
            $pengajuan = PengajuanJudul::whereHas('pembimbings', function($q) use ($user) {
                $q->where('dosen_id', $user->dosen->id);
            })->with(['mahasiswa.user', 'pembimbings.dosen'])->latest()->paginate(10);
        } else {
            $pengajuan = PengajuanJudul::where('mahasiswa_id', $user->mahasiswa->id)->with('pembimbings.dosen')->latest()->paginate(10);
        }
        return view('pengajuan.index', compact('pengajuan'));
    }

    public function create() {
        if (!auth()->user()->isMahasiswa()) abort(403);
        return view('pengajuan.create');
    }

    public function store(StorePengajuanJudulRequest $request) {
        $validated = $request->validated();
        
        $validated['mahasiswa_id'] = auth()->user()->mahasiswa->id;
        if ($request->hasFile('file_proposal')) {
            $validated['file_proposal'] = $request->file('file_proposal')->store('proposals', 'public');
        }
        
        PengajuanJudul::create($validated);
        return redirect()->route('pengajuan.index')->with('success', 'Judul berhasil diajukan.');
    }

    public function show(PengajuanJudul $pengajuan) {
        $pengajuan->load(['mahasiswa.user', 'pembimbings.dosen', 'sidang']);
        $dosens = auth()->user()->isAdmin() ? Dosen::all() : collect();
        return view('pengajuan.show', compact('pengajuan', 'dosens'));
    }

    public function edit(PengajuanJudul $pengajuan) {
        if (!auth()->user()->isMahasiswa() || auth()->user()->mahasiswa->id !== $pengajuan->mahasiswa_id) abort(403);
        
        // Hanya bisa diedit jika belum diproses (masih diajukan atau diminta direvisi)
        if (!in_array($pengajuan->status, ['diajukan', 'direvisi'])) {
            return redirect()->route('pengajuan.index')->with('error', 'Pengajuan judul sudah diproses dan tidak dapat diubah.');
        }

        return view('pengajuan.edit', compact('pengajuan'));
    }

    public function update(UpdatePengajuanJudulRequest $request, PengajuanJudul $pengajuan) {
        if (!auth()->user()->isMahasiswa() || auth()->user()->mahasiswa->id !== $pengajuan->mahasiswa_id) abort(403);
        
        if (!in_array($pengajuan->status, ['diajukan', 'direvisi'])) {
            return redirect()->route('pengajuan.index')->with('error', 'Pengajuan judul sudah diproses dan tidak dapat diubah.');
        }

        $validated = $request->validated();
        
        if ($request->hasFile('file_proposal')) {
            if ($pengajuan->file_proposal) {
                Storage::disk('public')->delete($pengajuan->file_proposal);
            }
            $validated['file_proposal'] = $request->file('file_proposal')->store('proposals', 'public');
        }
        
        // Reset status kembali ke diajukan jika sebelumnya direvisi
        $validated['status'] = 'diajukan';

        $pengajuan->update($validated);
        return redirect()->route('pengajuan.index')->with('success', 'Judul berhasil diupdate.');
    }

    public function setPembimbing(Request $request, PengajuanJudul $pengajuan) {
        if (!auth()->user()->isAdmin()) abort(403);
        $request->validate([
            'status' => 'required|in:diajukan,direvisi,diterima,ditolak',
            'judul_terpilih' => 'nullable|in:1,2|required_if:status,diterima',
            'pesan' => 'nullable|string',
            'pembimbing_1' => 'nullable|exists:dosen,id',
            'pembimbing_2' => 'nullable|exists:dosen,id|different:pembimbing_1',
        ]);

        $oldStatus = $pengajuan->status;
        $pengajuan->update([
            'status' => $request->status,
            'judul_terpilih' => $request->judul_terpilih,
            'pesan' => $request->pesan,
        ]);

        if ($request->status == 'diterima') {
            Pembimbing::where('pengajuan_judul_id', $pengajuan->id)->delete();
            if ($request->pembimbing_1) {
                Pembimbing::create(['pengajuan_judul_id' => $pengajuan->id, 'dosen_id' => $request->pembimbing_1, 'tipe_pembimbing' => 'pembimbing_1']);
            }
            if ($request->pembimbing_2) {
                Pembimbing::create(['pengajuan_judul_id' => $pengajuan->id, 'dosen_id' => $request->pembimbing_2, 'tipe_pembimbing' => 'pembimbing_2']);
            }
        }
        
        // Buat Notifikasi jika status berubah
        if ($oldStatus !== $request->status) {
            $pesanNotif = '';
            $statusLabels = [
                'diajukan' => 'Pending',
                'diterima' => 'Disetujui',
                'ditolak' => 'Ditolak',
                'direvisi' => 'Revisi'
            ];
            $label = $statusLabels[$request->status];
            
            $judulAcc = $request->judul_terpilih == 1 ? $pengajuan->judul : $pengajuan->judul_2;

            if ($request->status == 'diterima') {
                $pesanNotif = "Selamat! Pengajuan Judul Anda '{$judulAcc}' telah Disetujui.";
            } elseif ($request->status == 'ditolak') {
                $pesanNotif = "Mohon maaf, Pengajuan Judul Anda telah Ditolak.";
            } elseif ($request->status == 'direvisi') {
                $pesanNotif = "Pengajuan Judul Anda memerlukan Revisi.";
            }

            if ($pesanNotif) {
                Notifikasi::create([
                    'user_id' => $pengajuan->mahasiswa->user_id,
                    'judul' => "Status Pengajuan Judul: {$label}",
                    'pesan' => $pesanNotif,
                ]);
            }
        }
        
        return redirect()->back()->with('success', 'Status, Judul Terpilih, dan Pembimbing berhasil diupdate.');
    }
}