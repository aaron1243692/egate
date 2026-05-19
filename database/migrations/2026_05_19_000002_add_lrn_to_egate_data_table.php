<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('egate_data', 'lrn')) {
            return;
        }

        Schema::table('egate_data', function (Blueprint $table) {
            $table->unsignedBigInteger('lrn')->nullable()->after('student_number')->index();
        });
    }

    public function down(): void
    {
        if (! Schema::hasColumn('egate_data', 'lrn')) {
            return;
        }

        Schema::table('egate_data', function (Blueprint $table) {
            $table->dropColumn('lrn');
        });
    }
};
