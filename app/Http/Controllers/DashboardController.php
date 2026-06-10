<?php

namespace App\Http\Controllers;

use App\Models\Candidate;
use App\Models\Vote;
use App\Models\Voter;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Display the admin dashboard with election statistics.
     */
    public function index(): View
    {
        $totalVoters = Voter::count();
        $totalCandidates = Candidate::count();
        $totalVotes = Vote::count();
        $turnoutPercentage = $totalVoters > 0
            ? round(($totalVotes / $totalVoters) * 100, 2)
            : 0;

        // Recent votes (last 10)
        $recentVotes = Vote::with(['voter', 'candidate'])
            ->latest()
            ->take(10)
            ->get();

        // Candidates with vote counts
        $candidateStandings = Candidate::withCount('votes')
            ->orderBy('votes_count', 'desc')
            ->get();

        return view('dashboard.index', compact(
            'totalVoters',
            'totalCandidates',
            'totalVotes',
            'turnoutPercentage',
            'recentVotes',
            'candidateStandings'
        ));
    }
}
