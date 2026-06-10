@extends('layouts.app')

@section('title', 'Cast Your Vote - Gasabo District Elections')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
        <div class="text-center mb-6">
            <h1 class="text-3xl font-bold text-gray-800">Cast Your Vote</h1>
            <p class="text-gray-500 mt-2">Gasabo District Election {{ date('Y') }}</p>
            <div class="mt-4 inline-block bg-blue-50 text-blue-700 px-4 py-2 rounded-lg">
                <span class="font-medium">Voter:</span> {{ $voter->name }}
            </div>
        </div>

        <form method="POST" action="{{ route('voting.submit') }}" id="votingForm" class="space-y-8">
            @csrf

            @foreach($candidates as $position => $positionCandidates)
                <div class="border border-gray-200 rounded-lg p-6 position-group" data-position="{{ Str::slug($position) }}">
                    <div class="flex items-center justify-between mb-4 pb-2 border-b border-gray-200">
                        <h2 class="text-xl font-semibold text-gray-800">
                            {{ $position }}
                        </h2>
                        <span class="text-sm text-gray-400">Select one candidate</span>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($positionCandidates as $candidate)
                            <label class="relative flex items-center p-4 border-2 rounded-lg cursor-pointer transition-all duration-200
                                hover:border-blue-400 hover:shadow-md
                                has-[:checked]:border-blue-500 has-[:checked]:bg-blue-50"
                                for="candidate_{{ $candidate->candidate_id }}">
                                <input type="radio"
                                    name="candidate_ids[{{ $position }}]"
                                    value="{{ $candidate->candidate_id }}"
                                    id="candidate_{{ $candidate->candidate_id }}"
                                    class="peer hidden candidate-radio"
                                    data-position="{{ Str::slug($position) }}"
                                    required>
                                <div class="flex items-center space-x-3 w-full">
                                    <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0">
                                        <span class="text-blue-600 font-bold text-lg">
                                            {{ strtoupper(substr($candidate->name, 0, 1)) }}
                                        </span>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="font-medium text-gray-800 truncate">{{ $candidate->name }}</p>
                                        @if($candidate->bio)
                                            <p class="text-xs text-gray-400 mt-0.5 leading-tight truncate">{{ Str::limit($candidate->bio, 60) }}</p>
                                        @endif
                                    </div>
                                    <div class="w-5 h-5 border-2 border-gray-300 rounded-full flex items-center justify-center transition-all duration-200 flex-shrink-0"
                                        data-checkmark>
                                        <svg class="w-3 h-3 text-white hidden" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"/>
                                        </svg>
                                    </div>
                                </div>
                            </label>
                        @endforeach
                    </div>
                </div>
            @endforeach

            @if($candidates->isEmpty())
                <div class="text-center py-8">
                    <p class="text-gray-500 text-lg">No candidates are available for voting at this time.</p>
                </div>
            @else
                <!-- Selection Summary -->
                <div id="selectionSummary" class="hidden bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <div class="flex items-center space-x-2 mb-2">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span class="text-sm font-medium text-blue-800">Your Selections</span>
                    </div>
                    <ul id="selectionList" class="text-sm text-blue-700 space-y-1">
                    </ul>
                </div>

                <div class="text-center">
                    <button type="submit"
                        class="bg-green-600 text-white py-3 px-8 rounded-lg hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition-colors font-medium text-lg shadow-lg hover:shadow-xl transform hover:-translate-y-0.5"
                        onclick="return confirm('Are you sure you want to cast your vote? This action cannot be undone.');">
                        <span class="flex items-center justify-center space-x-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span>Cast Your Vote</span>
                        </span>
                    </button>
                    <p class="text-sm text-gray-400 mt-2">Once submitted, your vote cannot be changed.</p>
                </div>
            @endif
        </form>
    </div>
</div>

@push('scripts')
<script>
    // Visual feedback for selected candidate per position group
    document.querySelectorAll('.position-group').forEach(group => {
        const radios = group.querySelectorAll('.candidate-radio');

        radios.forEach(radio => {
            radio.addEventListener('change', function() {
                // Reset all labels in this group
                group.querySelectorAll('label').forEach(label => {
                    label.classList.remove('border-blue-500', 'bg-blue-50');
                    label.classList.add('border-gray-200');
                });
                group.querySelectorAll('[data-checkmark]').forEach(el => {
                    el.classList.remove('bg-blue-500', 'border-blue-500');
                    el.classList.add('border-gray-300');
                    el.querySelector('svg')?.classList.add('hidden');
                });

                // Highlight the selected one
                const label = this.closest('label');
                const checkmark = label.querySelector('[data-checkmark]');
                checkmark.classList.remove('border-gray-300');
                checkmark.classList.add('bg-blue-500', 'border-blue-500');
                checkmark.querySelector('svg')?.classList.remove('hidden');
                label.classList.remove('border-gray-200', 'hover\\:border-blue-400');
                label.classList.add('border-blue-500', 'bg-blue-50');

                updateSelectionSummary();
            });
        });
    });

    // Update the selection summary display
    function updateSelectionSummary() {
        const summary = document.getElementById('selectionSummary');
        const list = document.getElementById('selectionList');
        const selected = document.querySelectorAll('.candidate-radio:checked');

        if (selected.length === 0) {
            summary.classList.add('hidden');
            return;
        }

        summary.classList.remove('hidden');
        list.innerHTML = '';

        selected.forEach(radio => {
            const label = radio.closest('label');
            const name = label.querySelector('.font-medium')?.textContent || 'Unknown';
            const position = radio.getAttribute('data-position') || 'Unknown';
            const li = document.createElement('li');
            li.className = 'flex items-center space-x-2';
            li.innerHTML = `<svg class="w-4 h-4 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg><span><strong>${position.charAt(0).toUpperCase() + position.slice(1).replace(/-/g, ' ')}:</strong> ${name}</span>`;
            list.appendChild(li);
        });
    }
</script>
@endpush
@endsection
