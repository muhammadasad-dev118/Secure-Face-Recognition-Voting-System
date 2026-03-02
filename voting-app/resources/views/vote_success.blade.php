@extends('layouts.app')

@section('title', 'Vote Recorded')

@section('content')
<div class="max-w-2xl mx-auto text-center py-20">
    <div class="bg-gray-800 p-12 rounded-3xl shadow-2xl border border-green-500/30">
        <div class="w-24 h-24 bg-green-500/20 text-green-500 rounded-full flex items-center justify-center mx-auto mb-8">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
            </svg>
        </div>
        
        <h2 class="text-4xl font-extrabold mb-4 text-white">
            @if(isset($already_voted))
                Already Voted
            @else
                Vote Cast Successfully!
            @endif
        </h2>
        
        <p class="text-gray-400 text-lg mb-10 leading-relaxed">
            @if(isset($already_voted))
                Our records show you have already participated in this election. To ensure integrity, each user is limited to one vote.
            @else
                Thank you for participating! Your identity was securely verified using biometrics, and your choice has been recorded in our permanent ledger.
            @endif
        </p>

        <a href="/" class="inline-block bg-blue-600 hover:bg-blue-700 text-white px-10 py-4 rounded-xl font-bold text-lg transition-all shadow-xl">
            Return Home
        </a>
    </div>
</div>
@endsection
