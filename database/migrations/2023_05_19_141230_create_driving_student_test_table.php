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
        Schema::create('driving_student_test', function (Blueprint $table) {
            $table->id();
            $table->foreignId('driving_student_id')->constrained('driving_students');
            $table->foreignId('test_id')->constrained('tests');
            $table->boolean('is_passed')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('driving_student_test');
    }
};
