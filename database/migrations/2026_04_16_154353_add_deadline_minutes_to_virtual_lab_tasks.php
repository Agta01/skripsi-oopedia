<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('virtual_lab_tasks', function (Blueprint $table) {
            // NULL = tidak ada deadline; nilai positif = durasi maksimal dalam menit
            $table->unsignedSmallInteger('deadline_minutes')->nullable()->after('expected_result_image')
                  ->comment('Durasi maksimal pengerjaan dalam menit. NULL = tidak ada deadline.');
        });
    }

    public function down(): void
    {
        Schema::table('virtual_lab_tasks', function (Blueprint $table) {
            $table->dropColumn('deadline_minutes');
        });
    }
};
