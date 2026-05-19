<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('egate_data', function (Blueprint $table) {
            $table->string('lrn')->nullable()->after('student_number')->index();
        });
    }

    public function down(): void
    {
        Schema::table('egate_data', function (Blueprint $table) {
            $table->dropColumn('lrn');
        });
    }
};
