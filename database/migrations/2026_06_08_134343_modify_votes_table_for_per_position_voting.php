<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Modifies the votes table to support per-position voting:
     * 1. Drops the unique constraint on 'voter_id' (which enforced one-vote-total)
     * 2. Adds a 'position' column (denormalized from candidates for constraint purposes)
     * 3. Adds a composite unique constraint on (voter_id, position)
     *    This enforces "one vote per position per voter" at the database level.
     */
    public function up(): void
    {
        Schema::table('votes', function (Blueprint $table) {
            // In MySQL/InnoDB, you must drop the foreign key constraint first
            // before dropping the index it depends on.
            $table->dropForeign(['voter_id']);

            // Drop the old unique constraint that enforced one-vote-total
            $table->dropUnique('votes_voter_id_unique');

            // Add position column (denormalized from candidates for constraint)
            $table->string('position', 100)->after('candidate_id');

            // Enforce one vote per position per voter
            $table->unique(['voter_id', 'position'], 'votes_voter_position_unique');

            // Re-add the foreign key constraint on voter_id
            $table->foreign('voter_id')
                  ->references('voter_id')
                  ->on('voters')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('votes', function (Blueprint $table) {
            // Drop the foreign key before removing indexes
            $table->dropForeign(['voter_id']);

            // Remove the composite unique
            $table->dropUnique('votes_voter_position_unique');

            // Remove the position column
            $table->dropColumn('position');

            // Restore the old unique on voter_id
            $table->unique('voter_id', 'votes_voter_id_unique');

            // Re-add the foreign key constraint on voter_id
            $table->foreign('voter_id')
                  ->references('voter_id')
                  ->on('voters')
                  ->onDelete('cascade');
        });
    }
};
