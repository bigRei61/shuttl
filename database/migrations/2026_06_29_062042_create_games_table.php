<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('games', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained('events')->onDelete('cascade');
            $table->enum('format', ['singles', 'doubles', 'mixed_doubles']);
            $table->enum('competitive_type', ['competitive', 'unranked']);
            $table->tinyInteger('winning_side')->nullable();
            $table->integer('team1_sets_won')->default(0);
            $table->integer('team2_sets_won')->default(0);
            $table->timestamp('scheduled_at')->nullable();
            $table->timestamp('played_at')->nullable();
            $table->enum('status', ['scheduled', 'ongoing', 'completed'])->default('scheduled');
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('games');
    }
};