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
        Schema::create('tbut_sessions', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('task_id')->constrained('virtual_lab_tasks')->onDelete('cascade');

            // TBUT Dimension: Efisiensi (Efficiency)
            $table->timestamp('started_at')->nullable();     // Saat task pertama dibuka
            $table->timestamp('submitted_at')->nullable();   // Saat klik "Submit & Selesai"
            $table->unsignedInteger('duration_seconds')->default(0);  // Total waktu pengerjaan (detik)
            $table->unsignedInteger('run_count')->default(0);         // Jumlah kali klik "Run Code"

            // TBUT Dimension: Efektivitas (Effectiveness)
            $table->boolean('is_completed')->default(false); // Apakah task berhasil diselesaikan
            $table->longText('final_code')->nullable();      // Kode terakhir yang disimpan mahasiswa

            $table->timestamps();

            // Unique constraint: satu sesi per user per task
            $table->unique(['user_id', 'task_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbut_sessions');
    }
};
