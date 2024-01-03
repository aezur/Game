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
        Schema::create('gladiators', function (Blueprint $table) {
            $table->id();

            $table
                ->foreignId('ludus')
                ->nullable()
                ->references('id')
                ->on('ludi')
                ->onDelete('cascade');

            $table->string('name');

            $table->integer('strength');
            $table->integer('defense');

            $table->integer('accuracy');
            $table->integer('evasion');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gladiators');
    }
};
