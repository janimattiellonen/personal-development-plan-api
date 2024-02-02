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
        Schema::create('training_sessions', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->boolean('is_active')->nullable();
            $table->dateTime('starts_at');
            $table->dateTime('ends_at')->nullable();
            $table->unsignedBigInteger('personal_plan_id')->nullable(false);
            $table->foreign('personal_plan_id', 'training_sessions_personal_plan_id_foreign')
                ->references('id')
                ->on('personal_plans');


            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('training_sessions', function (Blueprint $table) {
            $table->dropConstrainedForeignId('personal_plan_id'); // drops constraint, index key AND column
        });
        Schema::dropIfExists('training_sessions');
    }
};


/**
 *
- TrainingSession
- id
- started_at
- finished_at
- is_active
- created_at
- updated_at

 */
