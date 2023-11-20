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
        Schema::create('penalties', function (Blueprint $table) {
            $table->id();
            $table->string('label', 100)->nullable();
            $table->string('on', 100)->nullable();
            $table->string('after_every', 100)->nullable();
            $table->string('method', 100)->nullable();
            $table->string('type', 100)->nullable();
            $table->string('value', 100)->nullable();
            $table->integer('grace_period')->nullable();
            $table->integer('duration')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penalties');
    }
};
