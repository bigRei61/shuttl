<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('game_players', function (Blueprint $table) {
            $table->id();
            $table->foreignId('game_id')->constrained('games')->onDelete('cascade');
            $table->foreignId('player_id')->constrained('users')->onDelete('cascade');
            $table->tinyInteger('team_side');
            $table->enum('role', ['singles', 'doubles_partner']);
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('game_players');
    }
};