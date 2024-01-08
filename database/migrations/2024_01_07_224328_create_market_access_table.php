<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('market_access', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->constrained();
            $table->date('date');
            $table->unique(['user_id', 'date']);

            $table->string('seed');
            $table->string('purchased')->default('');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('market_access');
    }
};
