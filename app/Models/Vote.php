<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Vote extends Model
{
    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'vote_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'voter_id',
        'candidate_id',
        'position',
    ];

    /**
     * A vote belongs to a specific voter.
     */
    public function voter(): BelongsTo
    {
        return $this->belongsTo(Voter::class, 'voter_id', 'voter_id');
    }

    /**
     * A vote belongs to a specific candidate.
     */
    public function candidate(): BelongsTo
    {
        return $this->belongsTo(Candidate::class, 'candidate_id', 'candidate_id');
    }
}
