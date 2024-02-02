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
        Schema::table('users', function (Blueprint $table) {
            $table->string('first_name', 64)->nullable(false)->after('name');
            $table->string('last_name', 64)->nullable(false)->after('first_name');
            $table->string('type', 10)->nullable(false)->after('remember_token');
            $table->string('user_role', 10)->nullable(false)->after('type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('first_name');
            $table->dropColumn('last_name');
            $table->dropColumn('type');
            $table->dropColumn('user_role');
        });
    }
};
