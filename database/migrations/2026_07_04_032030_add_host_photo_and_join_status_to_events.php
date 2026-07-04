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
        Schema::table('events', function (Blueprint $table) {
            $table->string('photo_path')->nullable()->after('max_participants');
        });

        Schema::table('event_players', function (Blueprint $table) {
            $table->string('status')->default('pending')->after('user_id');
            $table->timestamp('responded_at')->nullable()->after('status');
            $table->unique(['event_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('event_players', function (Blueprint $table) {
            $table->dropUnique(['event_id', 'user_id']);
            $table->dropColumn(['status', 'responded_at']);
        });

        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn('photo_path');
        });
    }
};
