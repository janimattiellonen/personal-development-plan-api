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
        Schema::create('personal_plans', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->boolean('is_active')->nullable();
            $table->dateTime('starts_at');
            $table->dateTime('ends_at')->nullable();
            $table->string('goals')->nullable();
            $table->unsignedBigInteger('student_id');
            $table->unsignedBigInteger('instructor_id');
            $table->foreign('student_id', 'personal_plans_student_id_foreign')
                ->references('id')
                ->on('users');
            $table->foreign('instructor_id', 'personal_plans_instructor_id_foreign')
                ->references('id')
                ->on('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('personal_plans');
    }
};
