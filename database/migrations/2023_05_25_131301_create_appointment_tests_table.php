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
        Schema::create('appointment_tests', function (Blueprint $table) {
            $table->id();
            $table->string('time');
            $table->string('date');
            $table->foreignId('driving_school_id')->constrained('driving_schools');
            $table->foreignId('driving_student_id')->nullable()->constrained('driving_students');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointment_tests');
    }
};
