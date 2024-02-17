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
        Schema::drop('training_session_exercises');
        Schema::create('training_session_exercises', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('training_session_id');
            $table->unsignedBigInteger('exercise_id');
            $table->unique(['training_session_id', 'exercise_id'], 'UK_TSI_EI');
            $table->foreign('training_session_id')->references('id')->on('training_sessions');
            $table->foreign('exercise_id')->references('id')->on('exercises');
            // Add any additional columns if needed
             $table->timestamps(); // Uncomment if you want timestamps on this table
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('training_session_exercises');
    }
};
