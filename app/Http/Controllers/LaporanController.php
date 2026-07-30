<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mahasiswa;
use App\Models\Dosen;
use App\Models\Sidang;
use App\Models\ProgressBimbingan;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\LaporanExport;

class LaporanController extends Controller
{
    public function index()
    {
        if (!auth()->user()->isAdmin()) abort(403);
        
        $program_studi = Mahasiswa::select('program_studi')->distinct()->pluck('program_studi');
        // Extract distinct years database-agnostically
        $tahun_sidang = Sidang::whereNotNull('tanggal_sidang')
            ->get()
            ->map(function ($item) {
                return \Carbon\Carbon::parse($item->tanggal_sidang)->year;
            })
            ->unique()
            ->sort()
            ->values();
            
        return view('laporan.index', compact('program_studi', 'tahun_sidang'));
    }

    public function export(Request $request)
    {
        if (!auth()->user()->isAdmin()) abort(403);
        
        $jenis = $request->jenis_laporan; // mahasiswa, dosen, sidang, progress, kelulusan
        $format = $request->format; // pdf, excel
        
        $data = $this->queryData($request);
        $view = 'laporan.pdf.' . $jenis;
        
        if ($format === 'pdf') {
            $pdf = Pdf::loadView($view, compact('data', 'request'));
            return $pdf->download('Laporan_' . ucfirst($jenis) . '_' . date('Ymd') . '.pdf');
        } elseif ($format === 'excel') {
            return Excel::download(new LaporanExport($view, compact('data', 'request')), 'Laporan_' . ucfirst($jenis) . '_' . date('Ymd') . '.xlsx');
        }
        
        return back()->with('error', 'Format tidak valid.');
    }
    
    private function queryData(Request $request)
    {
        $jenis = $request->jenis_laporan;
        
        if ($jenis === 'mahasiswa') {
            $query = Mahasiswa::with('user', 'pengajuanJudul');
            if ($request->program_studi) {
                $query->where('program_studi', $request->program_studi);
            }
            if ($request->tahun) {
                $query->whereYear('created_at', $request->tahun);
            }
            if ($request->semester) {
                $months = $request->semester === 'ganjil' ? [8, 9, 10, 11, 12, 1] : [2, 3, 4, 5, 6, 7];
                $query->where(function($q) use ($months) {
                    foreach ($months as $m) {
                        $q->orWhereMonth('created_at', $m);
                    }
                });
            }
            return $query->get();
        }
        
        if ($jenis === 'dosen') {
            return Dosen::withCount('pembimbing')->get();
        }
        
        if ($jenis === 'sidang' || $jenis === 'kelulusan') {
            $query = Sidang::with(['pengajuanJudul.mahasiswa', 'penguji']);
            
            if ($request->status) {
                $query->where('status_lulus', $request->status);
            }
            if ($request->tahun) {
                $query->whereYear('tanggal_sidang', $request->tahun);
            }
            if ($request->program_studi) {
                $query->whereHas('pengajuanJudul.mahasiswa', function($q) use ($request) {
                    $q->where('program_studi', $request->program_studi);
                });
            }
            return $query->get();
        }
        
        if ($jenis === 'progress') {
            $query = ProgressBimbingan::with(['mahasiswa', 'pengajuanJudul']);
            if ($request->status) {
                $query->where('status', $request->status);
            }
            if ($request->tahun) {
                $query->whereYear('tanggal_bimbingan', $request->tahun);
            }
            return $query->get();
        }
        
        return collect([]);
    }
}