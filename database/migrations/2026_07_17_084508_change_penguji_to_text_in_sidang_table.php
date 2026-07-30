<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('sidang', function (Blueprint $table) {
            // Because SQLite has issues dropping columns with foreign key constraints,
            // we will just add the new columns and stop using the old ones in the app.
            $table->string('nama_penguji_1')->nullable();
            $table->string('nama_penguji_2')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sidang', function (Blueprint $table) {
            $table->dropColumn('nama_penguji_1');
            $table->dropColumn('nama_penguji_2');
        });
    }
};
