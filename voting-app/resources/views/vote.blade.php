@extends('layouts.app')

@section('title', 'Official Ballot')

@section('content')
<div class="max-w-5xl mx-auto animate-fade-in-up">
    <div class="glass p-10 md:p-16 rounded-[4rem] shadow-2x-strong relative overflow-hidden">
        
        <div class="text-center mb-16 relative">
            <div class="inline-block px-4 py-1.5 rounded-full glass-dark border border-brand-500/20 text-brand-400 text-[10px] font-black uppercase tracking-widest mb-4">
                Verified Cryptographic Ballot
            </div>
            <h2 class="text-5xl font-black italic tracking-tighter">Cast Your <span class="bg-clip-text text-transparent bg-gradient-to-r from-brand-400 to-purple-500">Choice.</span></h2>
            <p class="text-slate-500 font-medium mt-4">Select one candidate to secure your vote in the system ledger.</p>
        </div>

        <div class="grid gap-6">
            @foreach($candidates as $candidate)
            <div class="group relative bg-slate-900/40 hover:bg-slate-900 transition-all duration-500 p-8 rounded-[2.5rem] border border-white/5 flex flex-col md:flex-row justify-between items-center gap-6 hover:border-brand-500/30">
                <div class="flex items-center gap-6">
                    <div class="w-16 h-16 rounded-full bg-brand-600/10 flex items-center justify-center font-black text-2xl text-brand-500 border border-brand-500/10 group-hover:bg-brand-600 group-hover:text-white transition-all duration-500">
                        {{ substr($candidate->name, 0, 1) }}
                    </div>
                    <div>
                        <h3 class="text-2xl font-black text-white group-hover:tracking-tight transition-all">{{ $candidate->name }}</h3>
                        <p class="text-slate-500 font-mono text-[10px] uppercase tracking-widest mt-1">Certified Candidate ID: #00{{ $candidate->id }}</p>
                    </div>
                </div>
                
                <button 
                    onclick="castVote({{ $candidate->id }}, '{{ $candidate->name }}')"
                    class="btn-primary w-full md:w-auto px-12 py-5 group-hover:shadow-[0_0_30px_oklch(0.55_0.21_254)] transition-all"
                >
                    Confirm Vote
                </button>
            </div>
            @endforeach
        </div>

        <div id="vote-status" class="mt-16 text-center hidden">
            <div class="relative w-16 h-16 mx-auto mb-6">
                <div class="absolute inset-0 border-4 border-brand-500/20 rounded-full"></div>
                <div class="absolute inset-0 border-4 border-brand-500 border-t-transparent rounded-full animate-spin"></div>
            </div>
            <p class="text-brand-400 font-black italic uppercase tracking-widest animate-pulse">Securing Transaction...</p>
        </div>
    </div>
</div>

<script>
    function castVote(candidateId, name) {
        if (!confirm(`Confirm vote for ${name}? This entry is immutable and cannot be revoked.`)) {
            return;
        }

        document.getElementById('vote-status').classList.remove('hidden');
        const buttons = document.querySelectorAll('button');
        buttons.forEach(b => b.disabled = true);
        
        fetch('/vote', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ candidate_id: candidateId })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.location.href = '/vote/success';
            } else {
                alert('Vault Error: ' + data.message);
                document.getElementById('vote-status').classList.add('hidden');
                buttons.forEach(b => b.disabled = false);
            }
        });
    }
</script>
@endsection
