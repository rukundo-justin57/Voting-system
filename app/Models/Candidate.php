<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Candidate extends Model
{
    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'candidate_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'position',
        'bio',
    ];

    /**
     * A candidate can receive many votes.
     */
    public function votes(): HasMany
    {
        return $this->hasMany(Vote::class, 'candidate_id', 'candidate_id');
    }

    /**
     * Get the total vote count for this candidate.
     */
    public function voteCount(): int
    {
        return $this->votes()->count();
    }
}
