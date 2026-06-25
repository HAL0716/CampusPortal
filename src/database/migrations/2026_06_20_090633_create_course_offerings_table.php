<?php

use App\Enums\DayOfWeek;
use App\Enums\Period;
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
        Schema::create('course_offerings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')
                ->constrained()
                ->cascadeOnDelete();
            $table->foreignId('academic_term_id')
                ->constrained()
                ->cascadeOnDelete();
            $table->foreignId('teacher_id')
                ->nullable()
                ->constrained('teacher_profiles')
                ->nullOnDelete();
            $table->enum('day_of_week', DayOfWeek::values());
            $table->enum('period', Period::values());
            $table->timestamps();
            $table->unique(['course_id', 'academic_term_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('course_offerings');
    }
};
