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
        Schema::create('worklogs', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->foreignId('fk_user');
            $table->foreign('fk_user')->references('id')->on('users');
            $table->foreignId('fk_proyect')->nullable();
            $table->foreign('fk_proyect')->references('id')->on('proyects');

            $table->text('description');
            $table->dateTime('start');
            $table->dateTime('end');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('worklogs');
    }
};
