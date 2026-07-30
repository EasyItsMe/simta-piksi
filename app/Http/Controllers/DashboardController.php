<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Mahasiswa;
use App\Models\Dosen;
use App\Models\PengajuanJudul;
use App\Models\ProgressBimbingan;
use App\Models\Sidang;
use App\Models\Notifikasi;
use App\Models\LogAktivitas;
use Carbon\Carbon;

class DashboardController extends Controller {
    public function index() {
        $user = auth()->user();
        $data = [];

        if ($user->isAdmin()) {
            $data['total_mahasiswa'] = Mahasiswa::count();
            $data['total_dosen'] = Dosen::count();
            $data['pengajuan_baru'] = PengajuanJudul::where('status', 'diajukan')->count();
            
            $startOfWeek = Carbon::now()->startOfWeek();
            $endOfWeek = Carbon::now()->endOfWeek();
            $data['sidang_minggu_ini'] = Sidang::whereBetween('tanggal_sidang', [$startOfWeek, $endOfWeek])->count();
            
            $data['stats_ta'] = [
                'diajukan' => PengajuanJudul::where('status', 'diajukan')->count(),
                'diterima' => PengajuanJudul::where('status', 'diterima')->count(),
                'ditolak' => PengajuanJudul::where('status', 'ditolak')->count(),
                'direvisi' => PengajuanJudul::where('status', 'direvisi')->count(),
            ];
            
            $data['stats_kelulusan'] = [
                'lulus' => Sidang::where('status_lulus', 'lulus')->count(),
                'revisi' => Sidang::where('status_lulus', 'revisi')->count(),
                'selesai' => Sidang::where('status_lulus', 'selesai')->count(),
                'menunggu' => Sidang::where('status_lulus', 'menunggu')->count(),
            ];
            
            $data['aktivitas'] = LogAktivitas::with('user')->latest()->take(5)->get();

        } elseif ($user->isDosen()) {
            $dosenId = $user->dosen->id;
            $data['jml_mahasiswa_bimbingan'] = PengajuanJudul::whereHas('pembimbings', function($q) use ($dosenId) {
                $q->where('dosen_id', $dosenId);
            })->count();
            
            $data['progress_mahasiswa'] = ProgressBimbingan::where('dosen_id', $dosenId)
                ->with('mahasiswa', 'pengajuanJudul')->latest()->take(5)->get();
            
            $data['daftar_revisi'] = PengajuanJudul::where('status', 'direvisi')->whereHas('pembimbings', function($q) use ($dosenId) {
                $q->where('dosen_id', $dosenId);
            })->with('mahasiswa')->get();
            
            $data['jadwal_sidang'] = Sidang::whereHas('pengajuanJudul.pembimbings', function($q) use ($dosenId) {
                $q->where('dosen_id', $dosenId);
            })->where('tanggal_sidang', '>=', now())->orderBy('tanggal_sidang', 'asc')->take(5)->get();
            
            $data['aktivitas'] = LogAktivitas::where('user_id', $user->id)->latest()->take(5)->get();

        } elseif ($user->isMahasiswa()) {
            $mahasiswaId = $user->mahasiswa->id;
            $pengajuan = PengajuanJudul::where('mahasiswa_id', $mahasiswaId)->with('pembimbings.dosen')->latest()->first();
            
            $data['pengajuan'] = $pengajuan;
            if ($pengajuan) {
                $data['status_ta'] = $pengajuan->status;
                $data['pembimbings'] = $pengajuan->pembimbings;
                $data['sidang'] = Sidang::where('pengajuan_judul_id', $pengajuan->id)->first();
                $data['progress_list'] = ProgressBimbingan::where('pengajuan_judul_id', $pengajuan->id)->orderBy('tanggal_bimbingan', 'asc')->get();
                $data['jml_progress'] = count($data['progress_list']);
            } else {
                $data['status_ta'] = 'Belum Mengajukan';
                $data['pembimbings'] = [];
                $data['sidang'] = null;
                $data['progress_list'] = [];
                $data['jml_progress'] = 0;
            }
            
            $data['notifikasi'] = Notifikasi::where('user_id', $user->id)->latest()->take(5)->get();
        }

        return view('dashboard', compact('data'));
    }
}