@extends('meeting.layout')
@section('title', 'MeetNow — Dashboard')

@section('content')
<div class="card" style="text-align:center; max-width: 420px;">
    <div style="width:64px;height:64px;background:#eff6ff;border-radius:16px;display:flex;align-items:center;justify-content:center;margin:0 auto 1.5rem;">
        <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#1a73e8" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
            <path d="M15 10l4.553-2.276A1 1 0 0121 8.723v6.554a1 1 0 01-1.447.894L15 14M3 8a2 2 0 012-2h8a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2V8z"/>
        </svg>
    </div>

    <h1>Video Meetings</h1>
    <p class="sub">Start a new meeting or join an existing one. No account required.</p>

    <div style="display:flex;flex-direction:column;gap:12px;">
        <a href="{{ route('meeting.create') }}" class="btn btn-primary btn-full" style="height:48px;font-size:15px;">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="16"/><line x1="8" y1="12" x2="16" y2="12"/>
            </svg>
            Create a meeting
        </a>

        <a href="{{ route('meeting.join') }}" class="btn btn-outline btn-full" style="height:48px;font-size:15px;">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M15 10l4.553-2.276A1 1 0 0121 8.723v6.554a1 1 0 01-1.447.894L15 14M3 8a2 2 0 012-2h8a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2V8z"/>
            </svg>
            Join a meeting
        </a>
    </div>
</div>
@endsection