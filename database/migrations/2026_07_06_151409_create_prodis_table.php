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
        Schema::create('prodis', function (Blueprint $table) {
            $table->id();
            $table->string('nama_prodi');
            $table->enum('jenjang', ['S1', 'D4', 'D3'])->default('S1');
            $table->unsignedInteger('ukt')->default(0);      // Uang Kuliah Tunggal (per semester)
            $table->unsignedInteger('dpi')->default(0);      // Dana Pengembangan Institusi (uang gedung)
            $table->unsignedInteger('seragam')->default(0);  // Uang Seragam
            $table->unsignedInteger('atribut')->default(0);  // KTM, Almamater, dll
            $table->unsignedInteger('pkl')->default(0);      // Magang / PKL
            $table->unsignedInteger('ta')->default(0);       // Tugas Akhir / Skripsi
            $table->unsignedInteger('wisuda')->default(0);   // Wisuda
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prodis');
    }
};
