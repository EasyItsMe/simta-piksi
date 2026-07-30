<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('mahasiswa', function (Blueprint $table) {
            $table->string('angkatan')->nullable()->after('program_studi');
        });

        Schema::table('dosen', function (Blueprint $table) {
            $table->string('bidang_keahlian')->nullable()->after('nama_lengkap');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users_tables', function (Blueprint $table) {
            //
        });
    }
};
