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
        Schema::table('driving_students', function (Blueprint $table) {
            //$table->foreignId('appointment_test_id')->nullable()->constrained('appointment_tests');
            $table->unsignedBigInteger('appointment_test_id')->nullable();
            $table->foreign('appointment_test_id')
                ->references('id')
                ->on('appointment_tests');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('driving_students', function (Blueprint $table) {
            $table->dropForeign('driving_student_appointment_test_id_foreign');
            $table->dropcolumn('appointment_test_id');
        });
    }
};
