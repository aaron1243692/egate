<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('egate_logs', function (Blueprint $table) {
            $table->foreignId('egate_data_id')
                ->nullable()
                ->after('id')
                ->constrained('egate_data')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('egate_logs', function (Blueprint $table) {
            $table->dropConstrainedForeignId('egate_data_id');
        });
    }
};
