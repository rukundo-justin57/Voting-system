@extends('layouts.app')

@section('title', 'Vote Cast Successfully - Gasabo District Elections')

@section('content')
<div class="min-h-[60vh] flex items-center justify-center">
    <div class="bg-white rounded-lg shadow-xl p-8 w-full max-w-lg text-center">
        <div class="mb-6">
            <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto">
                <svg class="w-10 h-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </div>

        <h1 class="text-3xl font-bold text-gray-800 mb-4">Thank You for Voting!</h1>
        <p class="text-gray-600 text-lg mb-6">
            Your vote has been cast successfully and recorded securely in the system.
        </p>

        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-8">
            <p class="text-blue-700 text-sm">
                Your participation strengthens democracy in Gasabo District.
                Every vote matters!
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
