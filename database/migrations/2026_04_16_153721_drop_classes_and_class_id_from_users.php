<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Remove the 'classes' table and the 'class_id' column from 'users'.
     * The feature was never used in the application.
     */
    public function up(): void
    {
        // 1. Drop FK and column from users first
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'class_id')) {
                // Drop foreign key if it exists (ignore if not)
                try {
                    $table->dropForeign(['class_id']);
                } catch (\Throwable $e) {
                    // No FK to drop — continue
                }
                $table->dropColumn('class_id');
            }
        });

        // 2. Drop the classes table
        Schema::dropIfExists('classes');
    }

    /**
     * Reverse: re-create the classes table and add class_id back.
     */
    public function down(): void
    {
        Schema::create('classes', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('academic_year')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('class_id')->nullable()->constrained('classes')->nullOnDelete();
        });
    }
};
