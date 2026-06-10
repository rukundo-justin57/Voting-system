<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUpdateCandidateRequest;
use App\Models\Candidate;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CandidateController extends Controller
{
    /**
     * Display a paginated list of all candidates.
     */
    public function index(Request $request): View
    {
        $query = Candidate::query();

        // Filter by position
        if ($request->filled('position')) {
            $query->where('position', $request->position);
        }

        // Search by name
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $candidates = $query->withCount('votes')
            ->orderBy('position')
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        $positions = Candidate::select('position')
            ->distinct()
            ->orderBy('position')
            ->pluck('position');

        return view('admin.candidates.index', compact('candidates', 'positions'));
    }

    /**
     * Show the form for creating a new candidate.
     */
    public function create(): View
    {
        $positions = Candidate::select('position')
            ->distinct()
            ->orderBy('position')
            ->pluck('position');

        return view('admin.candidates.form', [
            'candidate' => new Candidate(),
            'positions' => $positions,
            'formType' => 'create',
        ]);
    }

    /**
     * Store a newly created candidate.
     */
    public function store(StoreUpdateCandidateRequest $request): RedirectResponse
    {
        Candidate::create($request->validated());

        return redirect()->route('admin.candidates.index')
            ->with('success', 'Candidate "' . $request->name . '" has been created successfully.');
    }

    /**
     * Show the form for editing the specified candidate.
     */
    public function edit(Candidate $candidate): View
    {
        $positions = Candidate::select('position')
            ->distinct()
            ->orderBy('position')
            ->pluck('position');

        return view('admin.candidates.form', [
            'candidate' => $candidate,
            'positions' => $positions,
            'formType' => 'edit',
        ]);
    }

    /**
     * Update the specified candidate.
     */
    public function update(StoreUpdateCandidateRequest $request, Candidate $candidate): RedirectResponse
    {
        $candidate->update($request->validated());

        return redirect()->route('admin.candidates.index')
            ->with('success', 'Candidate "' . $candidate->name . '" has been updated successfully.');
    }

    /**
     * Remove the specified candidate.
     *
     * Prevents deletion if the candidate has existing votes.
     */
    public function destroy(Candidate $candidate): RedirectResponse
    {
        // Prevent deletion if candidate has votes
        if ($candidate->votes()->exists()) {
            return back()->with('error', 'Cannot delete "' . $candidate->name . '" because they have received votes. Consider marking them as inactive instead.');
        }

        $name = $candidate->name;
        $candidate->delete();

        return redirect()->route('admin.candidates.index')
            ->with('success', 'Candidate "' . $name . '" has been deleted successfully.');
    }
}
