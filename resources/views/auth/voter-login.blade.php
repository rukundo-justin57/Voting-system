@extends('layouts.app')

@section('title', 'Voter Login - Gasabo District Elections')

@section('content')
<div class="min-h-[70vh] flex items-center justify-center">
    <div class="bg-white rounded-lg shadow-xl p-8 w-full max-w-md">
        <div class="text-center mb-8">
            <svg class="w-16 h-16 text-green-600 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
            </svg>
            <h1 class="text-2xl font-bold text-gray-800">Voter Login</h1>
            <p class="text-gray-500 mt-2">Cast your vote securely in the Gasabo District Election</p>
        </div>

        <form method="POST" action="{{ route('voter.login.submit') }}" class="space-y-6">
            @csrf

            <!-- Rwanda National ID -->
            <div>
                <label for="national_id" class="block text-sm font-medium text-gray-700 mb-1">Rwanda National ID Numbers</label>
                <input type="text" id="national_id" name="national_id" value="{{ old('national_id') }}"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 @error('national_id') border-red-500 @enderror"
                    placeholder="Enter your Rwanda National ID Numbers" required autofocus>
                @error('national_id')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Submit -->
            <button type="submit"
                class="w-full bg-green-600 text-white py-2 px-4 rounded-lg hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition-colors font-medium">
                Proceed to Vote
            </button>
        </form>

        <div class="mt-6 text-center">
            <a href="{{ route('home') }}" class="text-sm text-blue-600 hover:text-blue-800">
                &larr; Back to Home
            </a>
        </div>
    </div>
</div>
@endsection
