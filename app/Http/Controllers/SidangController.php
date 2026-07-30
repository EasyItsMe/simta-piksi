<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sidang;
use App\Models\PengajuanJudul;
use App\Models\Dosen;
use App\Models\User;
use App\Models\Notifikasi;
use App\Http\Requests\StoreSidangRequest;
use App\Http\Requests\UpdateSidangRequest;
use Illuminate\Support\Facades\Storage;

class SidangController extends Controller {
    public function index() {
        $user = auth()->user();
        
        if ($user->isAdmin()) {
            $sidangs = Sidang::with(['pengajuanJudul.mahasiswa.user'])->latest()->paginate(10);
        } elseif ($user->isDosen()) {
            // Sebagai penguji atau pembimbing
            $dosenId = $user->dosen->id;
            $sidangs = Sidang::whereHas('pengajuanJudul.pembimbings', function($q) use ($dosenId) {
                    $q->where('dosen_id', $dosenId);
                })->with(['pengajuanJudul.mahasiswa.user'])->latest()->paginate(10);
        } else {
            $sidangs = Sidang::whereHas('pengajuanJudul', function($q) use ($user) {
                $q->where('mahasiswa_id', $user->mahasiswa->id);
            })->with(['pengajuanJudul'])->latest()->paginate(10);
        }
        
        return view('sidang.index', compact('sidangs'));
    }

    public function create() {
        if (!auth()->user()->isMahasiswa()) abort(403);
        
        $allPengajuans = PengajuanJudul::where('mahasiswa_id', auth()->user()->mahasiswa->id)
            ->where('status', 'diterima')
            ->doesntHave('sidang')
            ->get();
            
        // Filter only pengajuan with 100% progress (Final stage approved by all pembimbing)
        $pengajuans = $allPengajuans->filter(function ($pengajuan) {
            return $pengajuan->isProgressLengkap();
        });
            
        return view('sidang.create', compact('pengajuans'));
    }

    public function store(StoreSidangRequest $request) {
        $validated = $request->validated();
        
        // Cek lagi untuk memastikan mahasiswa tidak mem-bypass form
        $pengajuan = PengajuanJudul::find($validated['pengajuan_judul_id']);
        if (!$pengajuan || !$pengajuan->isProgressLengkap()) {
            return back()->withErrors(['pengajuan_judul_id' => 'Progress bimbingan belum 100% disetujui oleh semua Dosen Pembimbing.']);
        }
        
        if ($request->hasFile('naskah_final')) {
            $validated['naskah_final'] = $request->file('naskah_final')->store('sidang/naskah', 'public');
        }
        if ($request->hasFile('surat_persetujuan')) {
            $validated['surat_persetujuan'] = $request->file('surat_persetujuan')->store('sidang/persetujuan', 'public');
        }
        
        $validated['status_lulus'] = 'menunggu';
        
        $sidang = Sidang::create($validated);
        
        // Notify Admin (optional, but good UX)
        Notifikasi::create([
            'user_id' => User::whereHas('role', function($q) { $q->where('nama_role', 'Admin'); })->first()->id,
            'judul' => 'Pengajuan Sidang Baru',
            'pesan' => 'Mahasiswa ' . auth()->user()->mahasiswa->nama_lengkap . ' telah mengajukan sidang.'
        ]);
        
        return redirect()->route('sidang.index')->with('success', 'Pengajuan sidang berhasil dikirim.');
    }

    public function edit(Sidang $sidang) {
        if (!auth()->user()->isAdmin()) abort(403);
        $dosens = Dosen::all();
        return view('sidang.edit', compact('sidang', 'dosens'));
    }

    public function update(UpdateSidangRequest $request, Sidang $sidang) {
        $validated = $request->validated();
        $oldStatus = $sidang->status_lulus;
        
        if (isset($validated['nilai_kerapihan']) && isset($validated['nilai_penguasaan_materi']) && isset($validated['nilai_presentasi'])) {
            $avg = ($validated['nilai_kerapihan'] + $validated['nilai_penguasaan_materi'] + $validated['nilai_presentasi']) / 3;
            $validated['nilai_akhir'] = number_format($avg, 2);
        }
        
        $sidang->update($validated);
        
        if ($oldStatus !== $request->status_lulus) {
            $pesan = '';
            $statusLabels = [
                'terjadwal' => 'Terjadwal',
                'selesai' => 'Selesai',
                'revisi' => 'Revisi',
                'lulus' => 'Lulus'
            ];
            $label = $statusLabels[$request->status_lulus] ?? 'Menunggu';
            
            if ($request->status_lulus == 'terjadwal') {
                $pesan = "Jadwal sidang Anda telah ditentukan: {$sidang->tanggal_sidang} di ruangan {$sidang->ruangan}.";
            } else {
                $pesan = "Status sidang Anda telah diperbarui menjadi: {$label}.";
            }

            Notifikasi::create([
                'user_id' => $sidang->pengajuanJudul->mahasiswa->user_id,
                'judul' => "Update Status Sidang: {$label}",
                'pesan' => $pesan,
            ]);
        }
        
        return redirect()->route('sidang.index')->with('success', 'Jadwal dan status sidang berhasil diperbarui.');
    }
}