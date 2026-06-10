@extends('layouts.app')

@section('title', 'Manage Candidates - Gasabo District Elections')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-white rounded-lg shadow-lg p-6">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Manage Candidates</h1>
                <p class="text-gray-500">Create, edit, and remove election candidates</p>
            </div>
            <a href="{{ route('admin.candidates.create') }}"
                class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors font-medium shadow">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Add New Candidate
            </a>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow-lg p-4">
        <form method="GET" action="{{ route('admin.candidates.index') }}" class="flex flex-col sm:flex-row gap-4">
            <div class="flex-1">
                <label for="search" class="sr-only">Search by name</label>
                <input type="text" id="search" name="search" value="{{ request('search') }}"
                    placeholder="Search by name..."
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>
            <div class="w-full sm:w-48">
                <label for="position" class="sr-only">Filter by position</label>
                <select id="position" name="position"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">All Positions</option>
                    @foreach($positions as $pos)
                        <option value="{{ $pos }}" {{ request('position') == $pos ? 'selected' : '' }}>
                            {{ $pos }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="flex gap-2">
                <button type="submit"
                    class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium">
                    Filter
                </button>
                @if(request()->hasAny(['search', 'position']))
                    <a href="{{ route('admin.candidates.index') }}"
                        class="px-4 py-2 bg-gray-100 text-gray-600 rounded-lg hover:bg-gray-200 transition-colors font-medium">
                        Clear
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Candidates Table -->
    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-200">
                        <th class="py-3 px-4 text-sm font-semibold text-gray-600">#</th>
                        <th class="py-3 px-4 text-sm font-semibold text-gray-600">Name</th>
                        <th class="py-3 px-4 text-sm font-semibold text-gray-600">Position</th>
                        <th class="py-3 px-4 text-sm font-semibold text-gray-600">Bio</th>
                        <th class="py-3 px-4 text-sm font-semibold text-gray-600 text-center">Votes</th>
                        <th class="py-3 px-4 text-sm font-semibold text-gray-600 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($candidates as $index => $candidate)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="py-3 px-4 text-gray-400 text-sm">
                                {{ $candidates->firstItem() + $index }}
                            </td>
                            <td class="py-3 px-4">
                                <div class="flex items-center space-x-3">
                                    <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0">
                                        <span class="text-blue-600 font-bold text-sm">
                                            {{ strtoupper(substr($candidate->name, 0, 1)) }}
                                        </span>
                                    </div>
                                    <span class="font-medium text-gray-800">{{ $candidate->name }}</span>
                                </div>
                            </td>
                            <td class="py-3 px-4">
                                <span class="inline-block bg-purple-100 text-purple-800 px-2 py-1 rounded text-xs font-semibold">
                                    {{ $candidate->position }}
                                </span>
                            </td>
                            <td class="py-3 px-4 text-gray-500 text-sm max-w-xs truncate">
                                {{ $candidate->bio ?: '—' }}
                            </td>
                            <td class="py-3 px-4 text-center">
                                <span class="inline-block bg-blue-100 text-blue-800 px-2 py-1 rounded text-sm font-semibold">
                                    {{ $candidate->voteCount() }}
                                </span>
                            </td>
                            <td class="py-3 px-4 text-right">
                                <div class="flex items-center justify-end space-x-2">
                                    <a href="{{ route('admin.candidates.edit', $candidate) }}"
                                        class="inline-flex items-center px-3 py-1.5 bg-indigo-50 text-indigo-600 rounded-lg hover:bg-indigo-100 transition-colors text-sm font-medium">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                        Edit
                                    </a>
                                    <form method="POST" action="{{ route('admin.candidates.destroy', $candidate) }}"
                                        onsubmit="return confirm('Are you sure you want to delete \'{{ $candidate->name }}\'? This action cannot be undone.')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="inline-flex items-center px-3 py-1.5 bg-red-50 text-red-600 rounded-lg hover:bg-red-100 transition-colors text-sm font-medium">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-12 text-center">
                                <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                <p class="text-gray-500 text-lg">No candidates found.</p>
                                <p class="text-gray-400 text-sm mt-1">
                                    @if(request()->hasAny(['search', 'position']))
                                        Try adjusting your search or filter criteria.
                                    @else
                                        Get started by adding your first candidate.
                                    @endif
                                </p>
                                @if(!request()->hasAny(['search', 'position']))
                                    <a href="{{ route('admin.candidates.create') }}"
                                        class="inline-flex items-center mt-4 px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors font-medium">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                        </svg>
                                        Add New Candidate
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($candidates->hasPages())
            <div class="px-4 py-3 border-t border-gray-100 bg-gray-50">
                {{ $candidates->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
