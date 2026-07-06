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
        Schema::create('beasiswa_rules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('beasiswa_id')->constrained('beasiswas')->onDelete('cascade');
            $table->string('komponen_biaya'); // e.g., 'ukt', 'dpi', 'seragam', 'atribut', 'pkl', 'ta', 'wisuda'
            $table->enum('jenis_potongan', ['persen', 'nominal']);
            $table->unsignedInteger('nilai_potongan'); // 0-100 for persen, or Rp amount for nominal
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('beasiswa_rules');
    }
};
