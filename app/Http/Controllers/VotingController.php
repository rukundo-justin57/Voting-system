<?php

namespace App\Http\Controllers;

use App\Http\Requests\CastVoteRequest;
use App\Models\Candidate;
use App\Models\Vote;
use App\Models\Voter;
use Illuminate\Database\QueryException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class VotingController extends Controller
{
    /**
     * Display the voting page with list of candidates grouped by position.
     */
    public function index(Request $request): View|RedirectResponse
    {
        $voterId = $request->session()->get('voter_id');

        if (!$voterId) {
            return redirect()->route('voter.login')
                ->with('error', 'Please log in first to access the voting page.');
        }

        $voter = Voter::find($voterId);

        if (!$voter) {
            return redirect()->route('voter.login')
                ->with('error', 'Voter record not found.');
        }

        // Check if voter has already voted (any position)
        if ($voter->hasVoted()) {
            return redirect()->route('voting.already-voted')
                ->with('info', 'You have already cast your vote. Thank you for participating!');
        }

        // Get all candidates grouped by position
        $candidates = Candidate::all()->groupBy('position');

        return view('voting.index', compact('candidates', 'voter'));
    }

    /**
     * Process the vote submission - supports voting for one candidate per position.
     *
     * Enforces "One Vote Per Position Per Voter" using:
     * 1. Application-level checks (before insert)
     * 2. Database unique constraint on (voter_id, position)
     *
     * All votes are inserted in a transaction for atomicity.
     */
    public function castVote(CastVoteRequest $request): RedirectResponse
    {
        $voterId = $request->session()->get('voter_id');

        if (!$voterId) {
            return redirect()->route('voter.login')
                ->with('error', 'Session expired. Please log in again.');
        }

        $voter = Voter::find($voterId);

        if (!$voter) {
            return redirect()->route('voter.login')
                ->with('error', 'Voter record not found.');
        }

        // Application-level check: prevent duplicate voting
        if ($voter->hasVoted()) {
            return redirect()->route('voting.already-voted')
                ->with('warning', 'Duplicate vote detected. Each voter is allowed only one vote per position.');
        }

        $candidateIds = $request->input('candidate_ids', []);

        if (empty($candidateIds)) {
            return back()->with('error', 'Please select at least one candidate to vote for.');
        }

        // Fetch selected candidates with their positions
        $selectedCandidates = Candidate::whereIn('candidate_id', $candidateIds)->get();

        if ($selectedCandidates->isEmpty()) {
            return back()->with('error', 'No valid candidates selected.');
        }

        // Validate: ensure no two selected candidates share the same position
        $positions = $selectedCandidates->pluck('position')->toArray();
        if (count($positions) !== count(array_unique($positions))) {
            return back()->with('error', 'You can only vote for one candidate per position.');
        }

        try {
            DB::beginTransaction();

            foreach ($selectedCandidates as $candidate) {
                Vote::create([
                    'voter_id' => $voterId,
                    'candidate_id' => $candidate->candidate_id,
                    'position' => $candidate->position,
                ]);
            }

            DB::commit();

            return redirect()->route('voting.success')
                ->with('success', 'Your votes have been cast successfully! Thank you for participating in the election.');

        } catch (QueryException $e) {
            DB::rollBack();

            // Handle database constraint violation (unique on voter_id + position)
            if ($e->getCode() === '23000' || str_contains($e->getMessage(), 'Duplicate entry') || str_contains($e->getMessage(), 'UNIQUE constraint')) {
                return redirect()->route('voting.already-voted')
                    ->with('error', 'A vote has already been recorded for your account in one or more positions.');
            }

            throw $e;
        }
    }

    /**
     * Show success page after voting.
     */
    public function success(Request $request): View|RedirectResponse
    {
        if (!$request->session()->has('voter_id')) {
            return redirect()->route('voter.login');
        }

        return view('voting.success');
    }

    /**
     * Show page when voter has already voted.
     */
    public function alreadyVoted(Request $request): View|RedirectResponse
    {
        return view('voting.already-voted');
    }
}
