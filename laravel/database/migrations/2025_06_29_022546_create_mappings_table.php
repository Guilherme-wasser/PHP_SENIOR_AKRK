<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mappings', function (Blueprint $table) {
            $table->id();

            $table->foreignId('fund_id')->constrained()
                  ->cascadeOnDelete();          // opcional, facilita limpeza

            $table->string('key');
            $table->string('value');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mappings');
    }
};
