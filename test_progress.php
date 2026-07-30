<?php
require __DIR__ . "/vendor/autoload.php";
$app = require_once __DIR__ . "/bootstrap/app.php";
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$pb = \App\Models\ProgressBimbingan::create([
    "mahasiswa_id" => 1,
    "pengajuan_judul_id" => 1,
    "dosen_id" => 1,
    "tahapan" => "Bab 1",
    "tanggal_bimbingan" => "2023-01-01",
    "judul_progress" => "Test",
    "catatan_mahasiswa" => "Test",
    "status" => "disetujui"
]);

$list = \App\Models\ProgressBimbingan::where("id", $pb->id)->get();
$approved = collect($list)->where("status", "disetujui")->pluck("tahapan")->unique()->count();
echo "Approved count: " . $approved . "\n";
$pb->delete();

