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
        Schema::create('exercises', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->text('instructions')->nullable();
            $table->string('url')->nullable();
            $table->string('youtube_url')->nullable();
            $table->boolean('is_active')->nullable();
            $table->timestamps(); // created_at and updated_at columns
        });

        // Create 'training_session_exercises' table
        Schema::create('training_session_exercises', function (Blueprint $table) {
            $table->unsignedBigInteger('training_session_id');
            $table->unsignedBigInteger('exercise_id');
            $table->primary(['training_session_id', 'exercise_id']);
            $table->foreign('training_session_id')->references('id')->on('training_sessions');
            $table->foreign('exercise_id')->references('id')->on('exercises');
            // Add any additional columns if needed
            // $table->timestamps(); // Uncomment if you want timestamps on this table
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('training_session_exercises');

        // Drop 'exercises' table
        Schema::dropIfExists('exercises');    }
};
