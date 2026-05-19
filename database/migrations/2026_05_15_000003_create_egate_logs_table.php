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
        Schema::create('egate_data', function (Blueprint $table) {
            $table->id();
            $table->string('student_number')->index();
            $table->unsignedBigInteger('lrn')->nullable()->index();
            $table->string('last_name');
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('sex', 20)->nullable();
            $table->string('department')->nullable();
            $table->string('course')->nullable();
            $table->string('year_level', 50)->nullable();
            $table->string('grade_level', 50)->nullable();
            $table->string('status', 10)->default('IN')->index();
            $table->string('image')->nullable();
            $table->dateTime('logged_at')->index();
            $table->string('gate_name')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->text('remarks')->nullable();
            $table->timestamps();

            $table->index(['student_number', 'logged_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('egate_data');
    }
};
