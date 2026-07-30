<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('sidang', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pengajuan_judul_id')->constrained('pengajuan_judul')->onDelete('cascade');
            $table->string('naskah_final');
            $table->string('surat_persetujuan');
            $table->foreignId('penguji_id')->nullable()->constrained('dosen')->onDelete('set null');
            $table->dateTime('tanggal_sidang')->nullable();
            $table->string('ruangan')->nullable();
            $table->string('nilai_akhir')->nullable();
            $table->enum('status_lulus', ['menunggu', 'terjadwal', 'selesai', 'revisi', 'lulus'])->default('menunggu');
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('sidang');
    }
};