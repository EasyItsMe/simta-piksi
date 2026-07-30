<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('pembimbing', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pengajuan_judul_id')->constrained('pengajuan_judul')->onDelete('cascade');
            $table->foreignId('dosen_id')->constrained('dosen')->onDelete('cascade');
            $table->enum('tipe_pembimbing', ['pembimbing_1', 'pembimbing_2']);
            $table->timestamps();
            $table->unique(['pengajuan_judul_id', 'dosen_id']);
        });
    }
    public function down(): void {
        Schema::dropIfExists('pembimbing');
    }
};