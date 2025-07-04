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
            // ‼️ 1) adiciona a coluna
            $table->enum('role', ['admin', 'user'])
                  ->default('user')
                  ->after('password');   // opcional: posição da coluna
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // ‼️ 2) remove no rollback
            $table->dropColumn('role');
        });
    }
};
