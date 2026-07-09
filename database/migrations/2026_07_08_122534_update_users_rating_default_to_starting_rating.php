<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->decimal('rating_value', 8, 2)->default(400.00)->change();
        });

        DB::table('users')->update(['rating_value' => 400.00]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->decimal('rating_value', 8, 2)->default(1000.00)->change();
        });

        DB::table('users')->update(['rating_value' => 1000.00]);
    }
};
