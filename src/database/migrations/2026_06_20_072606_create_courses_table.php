<?php

use App\Enums\DayOfWeek;
use App\Enums\Period;
use App\Enums\Term;
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
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('department_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();
            $table->string('name');
            $table->text('description')->nullable();
            $table->integer('credits');
            $table->enum('term', Term::values());
            $table->foreignId('default_teacher_id')
                ->nullable()
                ->constrained('teacher_profiles')
                ->nullOnDelete();
            $table->enum('default_day_of_week', DayOfWeek::values());
            $table->enum('default_period', Period::values());
            $table->timestamps();
            $table->unique(['department_id', 'name']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};
