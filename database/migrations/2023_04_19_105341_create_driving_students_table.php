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
        Schema::create('driving_students', function (Blueprint $table) {
            $table->id();
            $table->string('full_name');
            $table->string('mobile_number');
            $table->string('student_address');
            $table->string('email');
            $table->string('password');
            $table->boolean('is_passed')->default(0);
            $table->integer('status')->default(0);
            $table->foreignId('driving_school_id')->nullable()->constrained('driving_schools');
            $table->boolean('is_registed_with_school')->default(0);
            $table->string('nationality_number');
            $table->string('born_date');
            $table->boolean('gender');
            //$table->foreignId('appointment_test_id')->nullable()->constrained('appointment_tests');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('driving_students');
    }
};
