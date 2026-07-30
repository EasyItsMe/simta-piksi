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
        Schema::table('sidang', function (Blueprint $table) {
            $table->foreignId('penguji_2_id')->nullable()->after('penguji_id')->constrained('dosen')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sidang', function (Blueprint $table) {
            $table->dropForeign(['penguji_2_id']);
            $table->dropColumn('penguji_2_id');
        });
    }
};
