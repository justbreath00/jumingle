@extends('meeting.layout')
@section('title', 'Join Meeting — MeetNow')

@section('content')
<div class="card">
    <a href="{{ route('dashboard') }}" class="back-link">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 12H5M12 5l-7 7 7 7"/></svg>
        Back
    </a>

    <h2>Join a meeting</h2>
    <p class="sub">Paste the meeting link and enter your nickname to join.</p>

    <div class="form-group">
        <label for="meetLink">Meeting link</label>
        <input type="url" id="meetLink" placeholder="https://yourdomain.com/meeting/room/ABC-DEFG-HIJ" autofocus />
        <p id="linkError" style="font-size:12px;color:#dc2626;margin-top:5px;display:none;">Please enter a valid meeting link.</p>
    </div>

    <div class="form-group">
        <label for="nickname">Your nickname</label>
        <input type="text" id="nickname" placeholder="e.g. Maria Santos" maxlength="32" />
        <p id="nickError" style="font-size:12px;color:#dc2626;margin-top:5px;display:none;">Please enter a nickname.</p>
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
    const basePattern = /\/meeting\/room\/([A-Z0-9\-]+)/;

    function joinMeeting() {
        let valid = true;

        const link = document.getElementById('meetLink').value.trim();
        const nickname = document.getElementById('nickname').value.trim();

        const linkError = document.getElementById('linkError');
        const nickError = document.getElementById('nickError');

        // Validate link
        const match = link.match(basePattern);
        if (!match) {
            linkError.style.display = 'block';
            document.getElementById('meetLink').style.borderColor = '#dc2626';
            valid = false;
        } else {
            linkError.style.display = 'none';
            document.getElementById('meetLink').style.borderColor = '';
        }

        // Validate nickname
        if (!nickname) {
            nickError.style.display = 'block';
            document.getElementById('nickname').style.borderColor = '#dc2626';
            valid = false;
        } else {
            nickError.style.display = 'none';
            document.getElementById('nickname').style.borderColor = '';
        }

        if (!valid) return;

        const roomId = match[1];
        window.location.href = '/meeting/room/' + roomId + '?nickname=' + encodeURIComponent(nickname);
    }

    ['meetLink','nickname'].forEach(id => {
        document.getElementById(id).addEventListener('keydown', e => {
            if (e.key === 'Enter') joinMeeting();
        });
    });
</script>
@endpush