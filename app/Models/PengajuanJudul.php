<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class PengajuanJudul extends Model {
    protected $table = 'pengajuan_judul';
    protected $fillable = ['mahasiswa_id', 'judul', 'deskripsi', 'judul_2', 'deskripsi_2', 'judul_terpilih', 'pesan', 'file_proposal', 'status'];
    public function mahasiswa() { return $this->belongsTo(Mahasiswa::class, 'mahasiswa_id'); }
    public function pembimbings() { return $this->hasMany(Pembimbing::class, 'pengajuan_judul_id'); }
    public function progressBimbingans() { return $this->hasMany(ProgressBimbingan::class, 'pengajuan_judul_id'); }
    public function sidang() { return $this->hasOne(Sidang::class, 'pengajuan_judul_id'); }

    /**
     * Mendapatkan judul yang di-ACC (judul_2 jika judul_terpilih == 2, sisanya judul)
     */
    public function getJudulFinalAttribute()
    {
        return $this->judul_terpilih == 2 ? $this->judul_2 : $this->judul;
    }
    /**
     * Mengecek apakah progress bimbingan sudah 100% (Tahapan 'Final' dan disetujui oleh semua pembimbing).
     */
    public function isProgressLengkap()
    {
        $pembimbings = $this->pembimbings;
        if ($pembimbings->isEmpty()) {
            return false; // Belum ada pembimbing
        }

        foreach ($pembimbings as $pembimbing) {
            $hasFinal = $this->progressBimbingans()
                ->where('dosen_id', $pembimbing->dosen_id)
                ->where('tahapan', 'Final')
                ->where('status', 'disetujui')
                ->exists();

            if (!$hasFinal) {
                return false;
            }
        }

        return true;
    }

    /**
     * Menghitung persentase progress bimbingan (0-100%).
     */
    public function getProgressPercentage()
    {
        $stages = [
            'Proposal' => 15,
            'Bab 1' => 30,
            'Bab 2' => 45,
            'Bab 3' => 60,
            'Bab 4' => 75,
            'Bab 5' => 90,
            'Final' => 100
        ];
        
        $approvedProgress = $this->progressBimbingans()->where('status', 'disetujui')->pluck('tahapan')->toArray();
        $uniqueApproved = array_unique($approvedProgress);
        
        $totalStages = count($stages);
        $approvedCount = count($uniqueApproved);
        
        return min(100, (int)round(($approvedCount / $totalStages) * 100));
    }
}