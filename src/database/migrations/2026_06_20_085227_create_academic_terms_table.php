<?php

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
        Schema::create('academic_terms', function (Blueprint $table) {
            $table->id();
            $table->integer('academic_year');
            $table->enum('term', Term::values());
            $table->date('registration_start');
            $table->date('registration_end');
            $table->date('lecture_start');
            $table->date('lecture_end');
            $table->timestamps();
            $table->unique(['academic_year', 'term']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('academic_terms');
    }
};
