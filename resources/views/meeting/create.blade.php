@extends('meeting.layout')
@section('title', 'Create Meeting — Jumingle')

@section('content')
<div class="card">
    <a href="{{ route('dashboard') }}" class="back-link">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 12H5M12 5l-7 7 7 7"/></svg>
        Back
    </a>

    <h2>Create a meeting</h2>
    <p class="sub">Your meeting link is ready. Set a nickname and join.</p>

    {{-- Meeting link --}}
    <div style="margin-bottom:1.5rem;">
        <label style="margin-bottom:8px;">Your meeting link</label>
        <div class="link-box">
            <span class="link-text" id="meetingLink">{{ url('/meeting/room/' . $roomId) }}</span>
            <button class="copy-btn" id="copyBtn" onclick="copyLink()">Copy</button>
        </div>
        <p style="font-size:12px;color:#9ca3af;">Share this link with people you want to meet with.</p>
    </div>

    {{-- Nickname --}}
    <div class="form-group">
        <label for="nickname">Your nickname</label>
        <input type="text" id="nickname" placeholder="e.g. Juan dela Cruz" maxlength="32" autofocus />
    </div>

    <button class="btn btn-primary btn-full" style="height:46px;font-size:15px;" onclick="joinMeeting()">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M15 10l4.553-2.276A1 1 0 0121 8.723v6.554a1 1 0 01-1.447.894L15 14M3 8a2 2 0 012-2h8a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2V8z"/>
        </svg>
        Join meeting
    </button>
</div>
@endsection

@push('scripts')
<script>
    const roomId = '{{ $roomId }}';
    const baseUrl = '{{ url("/meeting/room/" . $roomId) }}';

    function copyLink() {
        const link = document.getElementById('meetingLink').textContent;
        navigator.clipboard.writeText(link).then(() => {
            const btn = document.getElementById('copyBtn');
            btn.textContent = 'Copied!';
            btn.classList.add('copied');
            setTimeout(() => {
                btn.textContent = 'Copy';
                btn.classList.remove('copied');
            }, 2000);
        });
    }

    function joinMeeting() {
        const nickname = document.getElementById('nickname').value.trim();
        if (!nickname) {
            document.getElementById('nickname').focus();
            document.getElementById('nickname').style.borderColor = '#dc2626';
            return;
        }
        window.location.href = baseUrl + '?nickname=' + encodeURIComponent(nickname);
    }

    document.getElementById('nickname').addEventListener('keydown', e => {
        if (e.key === 'Enter') joinMeeting();
        document.getElementById('nickname').style.borderColor = '';
    });
</script>
@endpush