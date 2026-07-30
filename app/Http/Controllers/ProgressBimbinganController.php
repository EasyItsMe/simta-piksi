<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\ProgressBimbingan;
use App\Models\PengajuanJudul;
use App\Models\KomentarProgress;
class ProgressBimbinganController extends Controller {
    public function index() {
        $user = auth()->user();
        if ($user->isAdmin()) {
            $bimbingans = ProgressBimbingan::with(['mahasiswa.user', 'pengajuanJudul', 'komentars'])->latest()->paginate(10);
            return view('bimbingan.index', compact('bimbingans'));
        } elseif ($user->isDosen()) {
            $dosen_id = $user->dosen->id;
            $pengajuans = PengajuanJudul::whereHas('pembimbings', function($q) use ($dosen_id) {
                $q->where('dosen_id', $dosen_id);
            })->with(['mahasiswa.user', 'progressBimbingans' => function($q) use ($dosen_id) {
                $q->where('dosen_id', $dosen_id)->latest();
            }])->get();
            return view('bimbingan.index', compact('pengajuans'));
        } else {
            $bimbingans = ProgressBimbingan::where('mahasiswa_id', $user->mahasiswa->id)->with(['pengajuanJudul', 'komentars'])->latest()->paginate(10);
            return view('bimbingan.index', compact('bimbingans'));
        }
    }

    public function create() {
        if (!auth()->user()->isMahasiswa()) abort(403);
        $pengajuan = PengajuanJudul::where('mahasiswa_id', auth()->user()->mahasiswa->id)->where('status', 'diterima')->with('pembimbings.dosen')->get();
        return view('bimbingan.create', compact('pengajuan'));
    }

    public function store(Request $request) {
        if (!auth()->user()->isMahasiswa()) abort(403);
        $validated = $request->validate([
            'pengajuan_judul_id' => 'required|exists:pengajuan_judul,id',
            'dosen_id' => 'required|exists:dosen,id',
            'tahapan' => 'required|in:Proposal,Bab 1,Bab 2,Bab 3,Bab 4,Bab 5,Final',
            'judul_progress' => 'required|string|max:255',
            'tanggal_bimbingan' => 'required|date',
            'catatan_mahasiswa' => 'required|string',
            'file_progress' => 'nullable|file|mimes:pdf,doc,docx|max:5120'
        ]);

        $tahapanSequence = ['Proposal', 'Bab 1', 'Bab 2', 'Bab 3', 'Bab 4', 'Bab 5', 'Final'];
        $currentIndex = array_search($request->tahapan, $tahapanSequence);
        
        if ($currentIndex > 0) {
            $previousTahapan = $tahapanSequence[$currentIndex - 1];
            
            // Periksa apakah tahapan sebelumnya sudah disetujui oleh dosen yang sama
            $isPreviousApproved = ProgressBimbingan::where('pengajuan_judul_id', $request->pengajuan_judul_id)
                ->where('dosen_id', $request->dosen_id)
                ->where('tahapan', $previousTahapan)
                ->where('status', 'disetujui')
                ->exists();
                
            if (!$isPreviousApproved) {
                return back()->withErrors(['tahapan' => "Anda tidak bisa melompat ke tahapan {$request->tahapan}. Tahapan sebelumnya ({$previousTahapan}) belum di-ACC oleh Dosen ini!"])->withInput();
            }
        }

        $validated['mahasiswa_id'] = auth()->user()->mahasiswa->id;
        $validated['status'] = 'pending';
        
        if ($request->hasFile('file_progress')) {
            $validated['file_progress'] = $request->file('file_progress')->store('bimbingan', 'public');
        }
        
        ProgressBimbingan::create($validated);
        return redirect()->route('bimbingan.index')->with('success', 'Progress bimbingan diajukan.');
    }

    public function show(ProgressBimbingan $bimbingan) {
        $bimbingan->load(['mahasiswa.user', 'pengajuanJudul.pembimbings.dosen', 'komentars.dosen.user']);
        return view('bimbingan.show', compact('bimbingan'));
    }

    public function storeKomentar(Request $request, ProgressBimbingan $bimbingan) {
        if (!auth()->user()->isDosen()) abort(403);
        $request->validate([
            'komentar' => 'required|string',
            'status' => 'required|in:disetujui,perlu_revisi',
            'file_revisi' => 'nullable|file|mimes:pdf,doc,docx,xlsx,xls|max:10240'
        ]);
        
        $dataKomentar = [
            'progress_bimbingan_id' => $bimbingan->id,
            'dosen_id' => auth()->user()->dosen->id,
            'komentar' => $request->komentar
        ];
        
        if ($request->hasFile('file_revisi')) {
            $dataKomentar['file_revisi'] = $request->file('file_revisi')->store('revisi_dosen', 'public');
        }
        
        KomentarProgress::create($dataKomentar);
        
        $oldStatus = $bimbingan->status;
        $bimbingan->update(['status' => $request->status]);
        
        if ($oldStatus !== $request->status) {
            $pesan = '';
            if ($request->status == 'perlu_revisi') {
                $pesan = "Dosen pembimbing telah memberikan catatan revisi pada progress {$bimbingan->tahapan} Anda.";
            } elseif ($request->status == 'disetujui') {
                $pesan = "Progress {$bimbingan->tahapan} Anda telah disetujui oleh Dosen Pembimbing.";
            }
            
            if ($pesan) {
                \App\Models\Notifikasi::create([
                    'user_id' => $bimbingan->mahasiswa->user_id,
                    'judul' => 'Update Progress Bimbingan',
                    'pesan' => $pesan,
                ]);
            }
        }
        
        return redirect()->back()->with('success', 'Komentar dan status berhasil diperbarui.');
    }
}