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
        Schema::create('votes', function (Blueprint $table) {
            $table->id('vote_id');
            $table->unsignedBigInteger('voter_id');
            $table->unsignedBigInteger('candidate_id');
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('voter_id')
                  ->references('voter_id')
                  ->on('voters')
                  ->onDelete('cascade');

            $table->foreign('candidate_id')
                  ->references('candidate_id')
                  ->on('candidates')
                  ->onDelete('cascade');

            // Enforce "One Voter, One Vote" rule at the database level
            $table->unique('voter_id', 'votes_voter_id_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('votes');
    }
};
