<?php

namespace App\Http\Controllers;

use App\Models\Candidate;
use App\Models\Vote;
use App\Models\Voter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ResultController extends Controller
{
    /**
     * Display the election results dashboard.
     *
     * Uses optimized aggregate queries for real-time vote counting.
     * Groups results by position and calculates standings, totals, and winners.
     */
    public function index(): View
    {
        // Get total votes cast
        $totalVotes = Vote::count();

        // Get total registered voters
        $totalVoters = Voter::count();

        // Calculate voter turnout percentage
        $turnoutPercentage = $totalVoters > 0
            ? round(($totalVotes / $totalVoters) * 100, 2)
            : 0;

        // Get vote count per candidate with candidate details using optimized join query
        $candidateResults = DB::table('candidates')
            ->leftJoin('votes', 'candidates.candidate_id', '=', 'votes.candidate_id')
            ->select(
                'candidates.candidate_id',
                'candidates.name',
                'candidates.position',
                DB::raw('COUNT(votes.vote_id) as vote_count')
            )
            ->groupBy('candidates.candidate_id', 'candidates.name', 'candidates.position')
            ->orderBy('candidates.position')
            ->orderBy('vote_count', 'desc')
            ->get();

        // Group results by position and determine winners
        $resultsByPosition = $candidateResults->groupBy('position')->map(function ($candidates) {
            $maxVotes = $candidates->max('vote_count');

            return $candidates->map(function ($candidate) use ($maxVotes) {
                $candidate->is_winner = $candidate->vote_count > 0 && $candidate->vote_count === $maxVotes;
                return $candidate;
            });
        });

        // Get total votes per position
        $positionTotals = DB::table('votes')
            ->join('candidates', 'votes.candidate_id', '=', 'candidates.candidate_id')
            ->select('candidates.position', DB::raw('COUNT(*) as total'))
            ->groupBy('candidates.position')
            ->pluck('total', 'position');

        return view('results.index', compact(
            'totalVotes',
            'totalVoters',
            'turnoutPercentage',
            'resultsByPosition',
            'positionTotals'
        ));
    }

    /**
     * API endpoint - returns election results as JSON.
     * Protected by Sanctum authentication middleware.
     */
    public function apiResults(Request $request)
    {
        // Aggregate votes per candidate using Eloquent
        $results = Candidate::withCount('votes')
            ->orderBy('position')
            ->orderBy('votes_count', 'desc')
            ->get()
            ->groupBy('position')
            ->map(function ($candidates, $position) {
                $totalVotesForPosition = $candidates->sum('votes_count');
                $maxVotes = $candidates->max('votes_count');

                return [
                    'position' => $position,
                    'total_votes' => $totalVotesForPosition,
                    'candidates' => $candidates->map(function ($candidate) use ($maxVotes, $totalVotesForPosition) {
                        return [
                            'candidate_id' => $candidate->candidate_id,
                            'name' => $candidate->name,
                            'vote_count' => $candidate->votes_count,
                            'percentage' => $totalVotesForPosition > 0
                                ? round(($candidate->votes_count / $totalVotesForPosition) * 100, 2)
                                : 0,
                            'is_winner' => $candidate->votes_count > 0 && $candidate->votes_count === $maxVotes,
                        ];
                    })->values()->toArray(),
                ];
            })->values();

        $totalVotes = Vote::count();
        $totalVoters = Voter::count();

        return response()->json([
            'success' => true,
            'data' => [
                'election' => 'Gasabo District Election',
                'summary' => [
                    'total_registered_voters' => $totalVoters,
                    'total_votes_cast' => $totalVotes,
                    'voter_turnout_percentage' => $totalVoters > 0
                        ? round(($totalVotes / $totalVoters) * 100, 2)
                        : 0,
                ],
                'results' => $results,
                'generated_at' => now()->toIso8601String(),
            ],
        ]);
    }
}
