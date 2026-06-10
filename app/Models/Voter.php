<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Voter extends Model
{
    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'voter_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'national_id',
    ];

    /**
     * A voter can cast only one vote.
     * This relationship returns a collection but
     * the database constraint ensures at most one.
     */
    public function votes(): HasMany
    {
        return $this->hasMany(Vote::class, 'voter_id', 'voter_id');
    }

    /**
     * Check if this voter has already voted.
     */
    public function hasVoted(): bool
    {
        return $this->votes()->exists();
    }
}
