<?php

use App\Enums\EnrollmentStatus;
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
        Schema::create('enrollments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_profile_id')
                ->constrained()
                ->cascadeOnDelete();
            $table->foreignId('course_offering_id')
                ->constrained()
                ->cascadeOnDelete();
            $table->enum('status', EnrollmentStatus::values())
                ->default(EnrollmentStatus::REGISTERED);
            $table->timestamps();

            $table->unique(['student_profile_id', 'course_offering_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('enrollments');
    }
};
