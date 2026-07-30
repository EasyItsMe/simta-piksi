<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('komentar_progress', function (Blueprint $table) {
            $table->id();
            $table->foreignId('progress_bimbingan_id')->constrained('progress_bimbingan')->onDelete('cascade');
            $table->foreignId('dosen_id')->constrained('dosen')->onDelete('cascade');
            $table->text('komentar');
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('komentar_progress');
    }
};