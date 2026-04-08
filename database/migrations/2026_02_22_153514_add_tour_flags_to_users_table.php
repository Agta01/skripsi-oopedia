<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('has_seen_materials_tour')->default(false)->after('has_seen_virtual_lab_tour');
            $table->boolean('has_seen_questions_tour')->default(false)->after('has_seen_materials_tour');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['has_seen_materials_tour', 'has_seen_questions_tour']);
        });
    }
};
