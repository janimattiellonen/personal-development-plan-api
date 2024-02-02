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
        Schema::create('profiles', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('age')->nullable();
            $table->unsignedBigInteger('user_id')->nullable(false);
            $table->foreign('user_id', 'profiles_user_id_foreign')
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
        Schema::table('profiles', function (Blueprint $table) {
            $table->dropConstrainedForeignId('user_id'); // drops constraint, index key AND column
        });

        Schema::dropIfExists('profiles');
    }
};

