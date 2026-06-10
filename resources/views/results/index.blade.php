@extends('layouts.app')

@section('title', 'Election Results - Gasabo District Elections')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-white rounded-lg shadow-lg p-6">
        <div class="text-center">
            <h1 class="text-3xl font-bold text-gray-800">Election Results</h1>
            <p class="text-gray-500 mt-2">Gasabo District Election {{ date('Y') }}</p>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white rounded-lg shadow-lg p-6 text-center">
            <div class="text-4xl font-bold text-blue-600">{{ $totalVoters }}</div>
            <div class="text-gray-500 mt-1">Registered Voters</div>
        </div>
        <div class="bg-white rounded-lg shadow-lg p-6 text-center">
            <div class="text-4xl font-bold text-green-600">{{ $totalVotes }}</div>
            <div class="text-gray-500 mt-1">Total Votes Cast</div>
        </div>
        <div class="bg-white rounded-lg shadow-lg p-6 text-center">
            <div class="text-4xl font-bold text-purple-600">{{ $turnoutPercentage }}%</div>
            <div class="text-gray-500 mt-1">Voter Turnout</div>
        </div>
    </div>

    <!-- Results by Position -->
    @forelse($resultsByPosition as $position => $candidates)
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h2 class="text-2xl font-bold text-gray-800 mb-4 flex items-center">
                <span>{{ $position }}</span>
                <span class="ml-3 text-sm font-normal text-gray-500 bg-gray-100 px-3 py-1 rounded-full">
                    {{ $positionTotals->get($position, 0) }} total votes
                </span>
            </h2>

            <div class="space-y-4">
                @foreach($candidates as $index => $candidate)
                    @php
                        $totalForPosition = $positionTotals->get($position, 0);
                        $percentage = $totalForPosition > 0 ? round(($candidate->vote_count / $totalForPosition) * 100, 1) : 0;
                        $barWidth = $totalForPosition > 0 ? ($candidate->vote_count / $totalForPosition) * 100 : 0;
                    @endphp

                    <div class="relative p-4 rounded-lg {{ $candidate->is_winner ? 'bg-green-50 border-2 border-green-400' : 'bg-gray-50 border border-gray-200' }}">
                        <div class="flex items-center justify-between mb-2">
                            <div class="flex items-center space-x-3">
                                <span class="text-lg font-bold {{ $candidate->is_winner ? 'text-green-600' : 'text-gray-400' }}">
                                    #{{ $index + 1 }}
                                </span>
                                <div>
                                    <span class="font-semibold text-gray-800">{{ $candidate->name }}</span>
                                    @if($candidate->is_winner)
                                        <span class="ml-2 px-2 py-0.5 bg-green-200 text-green-800 text-xs font-bold rounded-full">
                                            WINNER
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="text-right">
                                <span class="text-xl font-bold {{ $candidate->is_winner ? 'text-green-600' : 'text-gray-700' }}">
                                    {{ $candidate->vote_count }}
                                </span>
                                <span class="text-sm text-gray-500 ml-1">votes</span>
                                <span class="text-sm text-gray-400 ml-2">({{ $percentage }}%)</span>
                            </div>
                        </div>

                        <!-- Progress Bar -->
                        <div class="w-full bg-gray-200 rounded-full h-3 overflow-hidden">
                            <div class="h-full rounded-full transition-all duration-1000 ease-out
                                {{ $candidate->is_winner ? 'bg-green-500' : 'bg-blue-500' }}"
                                style="width: {{ max($barWidth, 2) }}%">
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @empty
        <div class="bg-white rounded-lg shadow-lg p-12 text-center">
            <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
            </svg>
            <p class="text-gray-500 text-lg">No results available yet.</p>
            <p class="text-gray-400 text-sm mt-2">Results will appear once voting begins.</p>
        </div>
    @endforelse

    <!-- Last Updated -->
    <div class="text-center text-sm text-gray-400">
        <p>Last updated: {{ now()->format('F d, Y h:i A') }}</p>
    </div>
</div>
@endsection
