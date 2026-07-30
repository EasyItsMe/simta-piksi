<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('progress_bimbingan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pengajuan_judul_id')->constrained('pengajuan_judul')->onDelete('cascade');
            $table->foreignId('mahasiswa_id')->constrained('mahasiswa')->onDelete('cascade');
            $table->enum('tahapan', ['Proposal', 'Bab 1', 'Bab 2', 'Bab 3', 'Bab 4', 'Bab 5', 'Final']);
            $table->string('judul_progress');
            $table->date('tanggal_bimbingan');
            $table->text('catatan_mahasiswa');
            $table->string('file_progress')->nullable();
            $table->enum('status', ['pending', 'disetujui', 'perlu_revisi'])->default('pending');
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('progress_bimbingan');
    }
};