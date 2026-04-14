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
        Schema::table('virtual_lab_tasks', function (Blueprint $table) {
            $table->text('expected_output')->nullable()->after('test_cases');
        });

        Schema::table('tbut_sessions', function (Blueprint $table) {
            $table->boolean('is_success')->default(false)->after('is_completed');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('virtual_lab_tasks', function (Blueprint $table) {
            $table->dropColumn('expected_output');
        });

        Schema::table('tbut_sessions', function (Blueprint $table) {
            $table->dropColumn('is_success');
        });
    }
};
