<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pengajuan_judul', function (Blueprint $table) {
            $table->string('judul_2')->nullable()->after('deskripsi');
            $table->text('deskripsi_2')->nullable()->after('judul_2');
            $table->integer('judul_terpilih')->nullable()->after('status');
            $table->text('pesan')->nullable()->after('judul_terpilih');
        });
    }

    public function down(): void
    {
        Schema::table('pengajuan_judul', function (Blueprint $table) {
            $table->dropColumn(['judul_2', 'deskripsi_2', 'judul_terpilih', 'pesan']);
        });
    }
};
