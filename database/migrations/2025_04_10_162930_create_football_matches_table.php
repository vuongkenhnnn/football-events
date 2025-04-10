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
        Schema::create('football_matches', function (Blueprint $table) {
            $table->id();
            $table->string('country_name', 100)->index();
            $table->string('league_name', 100)->index();
            $table->string('home_team', 100)->index();
            $table->string('away_team', 100)->index();
            $table->integer('home_score')->default(0);
            $table->integer('away_score')->default(0);
            $table->string('status', 50);
            $table->timestamps();

            $table->index(['country_name', 'league_name', 'home_team', 'away_team'], 'match_lookup_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('football_matches');
    }
};
