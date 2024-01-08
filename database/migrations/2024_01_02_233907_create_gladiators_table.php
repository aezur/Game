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

            $table->unsignedBigInteger('ludus_id')->nullable();
            $table
                ->foreign('ludus_id')
                ->references('id')->on('ludi')
                ->cascadeOnDelete();

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
