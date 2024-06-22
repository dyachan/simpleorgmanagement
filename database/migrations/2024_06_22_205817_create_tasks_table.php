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
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->foreignId('fk_proyect');
            $table->foreign('fk_proyect')->references('id')->on('proyects');

            $table->text('title');
            $table->text('description')->nullable();

            $table->dateTime('start')->nullable();
            $table->dateTime('end')->nullable();

            $table->enum('difficulty', ['easy', 'medium', 'hard'])->nullable();
            $table->enum('priority', ['low', 'medium', 'high'])->nullable();

            $table->unsignedTinyInteger('order')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
