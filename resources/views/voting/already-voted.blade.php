@extends('layouts.app')

@section('title', 'Already Voted - Gasabo District Elections')

@section('content')
<div class="min-h-[60vh] flex items-center justify-center">
    <div class="bg-white rounded-lg shadow-xl p-8 w-full max-w-lg text-center">
        <div class="mb-6">
            <div class="w-20 h-20 bg-yellow-100 rounded-full flex items-center justify-center mx-auto">
                <svg class="w-10 h-10 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                </svg>
            </div>
        </div>

        <h1 class="text-3xl font-bold text-gray-800 mb-4">Already Voted</h1>
        <p class="text-gray-600 text-lg mb-6">
            You have already cast your vote in this election. The system allows only one vote per voter to ensure election integrity.
        </p>

        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-8">
            <p class="text-blue-700 text-sm">
                If you believe this is an error, please contact the Gasabo District Election Commission at
                <strong>elections@gasabo.gov.rw</strong> for assistance.
            </p>
        </div>

        <div class="space-y-3">
            <a href="{{ route('results.index') }}"
                class="block w-full bg-blue-600 text-white py-2 px-4 rounded-lg hover:bg-blue-700 transition-colors font-medium">
                View Election Results
            </a>
            <form method="POST" action="{{ route('voter.logout') }}">
                @csrf
                <button type="submit"
                    class="block w-full bg-gray-100 text-gray-700 py-2 px-4 rounded-lg hover:bg-gray-200 transition-colors">
                    Logout
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
