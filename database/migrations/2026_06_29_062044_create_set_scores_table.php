<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('set_scores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('game_id')->constrained('games')->onDelete('cascade');
            $table->integer('set_number');
            $table->integer('team1_score');
            $table->integer('team2_score');
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('set_scores');
    }
};