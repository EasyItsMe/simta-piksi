<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('pengajuan_judul', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mahasiswa_id')->constrained('mahasiswa')->onDelete('cascade');
            $table->string('judul');
            $table->text('deskripsi');
            $table->string('file_proposal')->nullable();
            $table->enum('status', ['diajukan', 'direvisi', 'diterima', 'ditolak'])->default('diajukan');
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('pengajuan_judul');
    }
};