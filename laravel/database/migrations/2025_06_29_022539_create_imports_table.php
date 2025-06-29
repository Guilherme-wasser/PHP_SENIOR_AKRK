<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('imports', function (Blueprint $table) {
            $table->id();

            // relações
            $table->foreignId('user_id')->constrained();
            $table->foreignId('fund_id')->constrained();

            // dados do processamento
            $table->string('sequence', 4);
            $table->string('original_file');
            $table->string('cnab_file')->nullable();

            $table->enum('status', ['pending', 'processing', 'done', 'error'])
                  ->default('pending');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('imports');
    }
};
