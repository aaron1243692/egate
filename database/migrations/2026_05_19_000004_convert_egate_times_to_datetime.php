<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('egate_data', function (Blueprint $table) {
            $table->dateTime('logged_at')->nullable()->change();
        });

        Schema::table('egate_logs', function (Blueprint $table) {
            $table->dateTime('created_at')->nullable()->change();
            $table->dateTime('updated_at')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('egate_data', function (Blueprint $table) {
            $table->timestamp('logged_at')->nullable()->change();
        });

        Schema::table('egate_logs', function (Blueprint $table) {
            $table->timestamp('created_at')->nullable()->change();
            $table->timestamp('updated_at')->nullable()->change();
        });
    }
};
