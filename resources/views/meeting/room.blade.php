<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meeting {{ $roomId }} — Jumingle</title>
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background: #111827;
            color: #fff;
            height: 100vh;
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        /* ── Top bar ── */
        .topbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 20px;
            height: 54px;
            background: #1f2937;
            border-bottom: 1px solid #374151;
            flex-shrink: 0;
        }
        .topbar-left  { display: flex; align-items: center; gap: 12px; }
        .topbar-right { display: flex; align-items: center; gap: 10px; }

        .logo-icon {
            width: 28px; height: 28px; background: #1a73e8;
            border-radius: 6px; display: flex; align-items: center; justify-content: center;
        }
        .logo-icon svg { width: 16px; height: 16px; fill: #fff; }
        .logo-name { font-size: 15px; font-weight: 600; }

        .room-badge {
            background: #374151; border-radius: 6px;
            padding: 3px 10px; font-size: 12px; color: #9ca3af;
            font-family: monospace; letter-spacing: 0.5px;
        }
        .share-btn {
            display: flex; align-items: center; gap: 6px;
            background: #374151; border: none; color: #d1d5db;
            padding: 6px 14px; border-radius: 6px; font-size: 13px;
            cursor: pointer; transition: background 0.15s;
        }
        .share-btn:hover { background: #4b5563; }
        .share-btn.copied { background: #166534; color: #bbf7d0; }
        .my-badge {
            background: #1a73e8; color: #fff;
            padding: 5px 12px; border-radius: 6px;
            font-size: 13px; font-weight: 500;
        }

        /* ── Video grid ── */
        .video-area {
            flex: 1;
            display: grid;
            gap: 8px;
            padding: 12px;
            overflow: hidden;
            min-height: 0;
        }
        .grid-1    { grid-template-columns: 1fr; }
        .grid-2    { grid-template-columns: 1fr 1fr; }
        .grid-3,
        .grid-4    { grid-template-columns: 1fr 1fr; grid-template-rows: 1fr 1fr; }
        .grid-many { grid-template-columns: repeat(3, 1fr); }

        /* ── Tile ── */
        .tile {
            position: relative;
            background: #1f2937;
            border-radius: 12px;
            overflow: hidden;
            border: 2px solid transparent;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 0;
            min-width: 0;
        }
        .tile.speaking { border-color: #1a73e8; }

        .tile video {
            width: 100%;
            height: 100%;
            object-fit: contain;
            background: #0d1117;
            display: block;
        }
        .tile video.mirror { transform: scaleX(-1); }

        /* camera off overlay */
        .cam-off {
            position: absolute; inset: 0;
            background: #1f2937;
            display: flex; flex-direction: column;
            align-items: center; justify-content: center;
            gap: 14px;
        }
        .avatar {
            width: 84px; height: 84px;
            border-radius: 50%;
            background: #1a73e8;
            display: flex; align-items: center; justify-content: center;
            font-size: 32px; font-weight: 700; color: #fff;
            letter-spacing: -1px; flex-shrink: 0;
        }
        .cam-off-name { font-size: 14px; color: #9ca3af; font-weight: 500; }

        /* name label */
        .tile-label {
            position: absolute;
            bottom: 10px; left: 10px;
            background: rgba(0,0,0,0.55);
            padding: 3px 10px;
            border-radius: 6px;
            font-size: 12px; font-weight: 500;
            max-width: calc(100% - 50px);
            white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
            z-index: 2;
        }

        /* muted dot */
        .muted-dot {
            position: absolute;
            bottom: 10px; right: 10px;
            background: #dc2626;
            width: 26px; height: 26px;
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            z-index: 2;
        }
        .muted-dot svg { width: 13px; height: 13px; stroke: #fff; fill: none;
                         stroke-width: 2; stroke-linecap: round; stroke-linejoin: round; }

        /* waiting message inside local tile */
        .waiting-msg {
            position: absolute; bottom: 48px;
            left: 50%; transform: translateX(-50%);
            white-space: nowrap;
            background: rgba(0,0,0,0.5);
            padding: 6px 14px; border-radius: 8px;
            font-size: 13px; color: #9ca3af;
            pointer-events: none; z-index: 3;
        }

        /* ── Controls ── */
        .controls {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 16px;
            padding: 14px 20px;
            background: #1f2937;
            border-top: 1px solid #374151;
            flex-shrink: 0;
        }
        .ctrl-wrap { display: flex; flex-direction: column; align-items: center; gap: 4px; }
        .ctrl {
            width: 48px; height: 48px;
            border-radius: 50%; border: none;
            display: flex; align-items: center; justify-content: center;
            cursor: pointer;
            transition: background 0.15s, transform 0.1s;
        }
        .ctrl:active { transform: scale(0.93); }
        .ctrl svg { width: 20px; height: 20px; stroke: currentColor; fill: none;
                    stroke-width: 2; stroke-linecap: round; stroke-linejoin: round; }
        .ctrl-on    { background: #374151; color: #e5e7eb; }
        .ctrl-on:hover { background: #4b5563; }
        .ctrl-off   { background: #dc2626; color: #fff; }
        .ctrl-off:hover { background: #b91c1c; }
        .ctrl-leave { background: #dc2626; color: #fff; }
        .ctrl-leave:hover { background: #991b1b; }
        .ctrl-label { font-size: 11px; color: #6b7280; }
        .ctrl-label.red { color: #f87171; }

        /* ── Toast ── */
        .toast {
            position: fixed; bottom: 90px; left: 50%; transform: translateX(-50%);
            background: #1f2937; border: 1px solid #374151;
            padding: 10px 20px; border-radius: 8px;
            font-size: 13px; color: #d1d5db;
            opacity: 0; transition: opacity 0.3s; z-index: 200;
            white-space: nowrap; pointer-events: none;
        }
        .toast.show { opacity: 1; }

        /* ── Permission modal ── */
        .modal-bg {
            position: fixed; inset: 0;
            background: rgba(0,0,0,0.75);
            display: flex; align-items: center; justify-content: center; z-index: 300;
        }
        .modal {
            background: #1f2937; border: 1px solid #374151;
            border-radius: 16px; padding: 2rem;
            max-width: 360px; width: 90%; text-align: center;
        }
        .modal h3 { font-size: 17px; margin-bottom: 8px; }
        .modal p  { color: #9ca3af; font-size: 13px; margin-bottom: 1.5rem; line-height: 1.6; }
        .modal-btns { display: flex; gap: 10px; justify-content: center; }
        .mbtn { padding: 9px 20px; border-radius: 8px; font-size: 14px; font-weight: 500; cursor: pointer; border: none; }
        .mbtn-blue { background: #1a73e8; color: #fff; }
        .mbtn-gray { background: #374151; color: #d1d5db; }
    </style>
</head>
<body>

{{-- ── Topbar ── --}}
<div class="topbar">
    <div class="topbar-left">
        <div class="logo-icon">
            <svg viewBox="0 0 24 24"><path d="M15 10l4.553-2.276A1 1 0 0121 8.723v6.554a1 1 0 01-1.447.894L15 14M3 8a2 2 0 012-2h8a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2V8z"/></svg>
        </div>
        <span class="logo-name">Jumingle</span>
        <span class="room-badge">{{ $roomId }}</span>
    </div>
    <div class="topbar-right">
        <button class="share-btn" id="shareLinkBtn" onclick="shareLink()">
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M10 13a5 5 0 007.54.54l3-3a5 5 0 00-7.07-7.07l-1.72 1.71"/>
                <path d="M14 11a5 5 0 00-7.54-.54l-3 3a5 5 0 007.07 7.07l1.71-1.71"/>
            </svg>
            Copy link
        </button>
        <div class="my-badge">{{ $nickname }}</div>
    </div>
</div>

{{-- ── Video grid ── --}}
<div class="video-area grid-1" id="videoArea"></div>

{{-- ── Controls ── --}}
<div class="controls">
    <div class="ctrl-wrap">
        <button class="ctrl ctrl-on" id="micBtn" onclick="toggleMic()">
            <svg id="micSvg" viewBox="0 0 24 24">
                <path d="M12 1a3 3 0 00-3 3v8a3 3 0 006 0V4a3 3 0 00-3-3z"/>
                <path d="M19 10v2a7 7 0 01-14 0v-2"/>
                <line x1="12" y1="19" x2="12" y2="23"/>
                <line x1="8"  y1="23" x2="16" y2="23"/>
            </svg>
        </button>
        <span class="ctrl-label" id="micLabel">Mute</span>
    </div>

    <div class="ctrl-wrap">
        <button class="ctrl ctrl-on" id="camBtn" onclick="toggleCam()">
            <svg id="camSvg" viewBox="0 0 24 24">
                <path d="M15 10l4.553-2.276A1 1 0 0121 8.723v6.554a1 1 0 01-1.447.894L15 14M3 8a2 2 0 012-2h8a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2V8z"/>
            </svg>
        </button>
        <span class="ctrl-label" id="camLabel">Stop video</span>
    </div>

    <div class="ctrl-wrap">
        <button class="ctrl ctrl-leave" onclick="leaveMeeting()">
            <svg viewBox="0 0 24 24">
                <path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4"/>
                <polyline points="16 17 21 12 16 7"/>
                <line x1="21" y1="12" x2="9" y2="12"/>
            </svg>
        </button>
        <span class="ctrl-label red">Leave</span>
    </div>
</div>

{{-- ── Toast ── --}}
<div class="toast" id="toast"></div>

{{-- ── Permission modal ── --}}
<div class="modal-bg" id="permModal">
    <div class="modal">
        <h3>Camera & Microphone</h3>
        <p>MeetNow needs your camera and microphone. Click Allow when your browser asks.</p>
        <div class="modal-btns">
            <button class="mbtn mbtn-blue" onclick="startMedia(false)">Allow & join</button>
            <button class="mbtn mbtn-gray" onclick="startMedia(true)">Audio only</button>
        </div>
    </div>
</div>

<script>
const ROOM_ID  = '{{ $roomId }}';
const NICKNAME = '{{ addslashes($nickname) }}';
const ROOM_URL = '{{ url("/meeting/room/" . $roomId) }}';
const MY_ID    = Math.random().toString(36).slice(2) + Date.now();

let localStream = null;
let micOn = true;
let camOn = true;
const peers = {};  // peerId → { pc, nickname, micOn, camOn }

/* ── Signaling ── */
const ch = new BroadcastChannel('meetnow:' + ROOM_ID);

function send(msg) { ch.postMessage({ ...msg, from: MY_ID }); }

ch.onmessage = async ({ data: m }) => {
    if (m.from === MY_ID) return;
    if (m.to && m.to !== MY_ID) return;

    if (m.type === 'hello') {
        if (!peers[m.from]) peers[m.from] = {};
        peers[m.from].nickname = m.nickname;
        peers[m.from].micOn = true;
        peers[m.from].camOn = true;
        send({ type: 'hello-ack', to: m.from, nickname: NICKNAME });
        await callPeer(m.from);
    }
    else if (m.type === 'hello-ack') {
        if (!peers[m.from]) peers[m.from] = {};
        peers[m.from].nickname = m.nickname;
    }
    else if (m.type === 'offer') {
        if (!peers[m.from]) peers[m.from] = {};
        peers[m.from].nickname = m.nickname || peers[m.from].nickname || 'Guest';
        const pc = getOrCreatePC(m.from);
        await pc.setRemoteDescription(new RTCSessionDescription(m.sdp));
        const answer = await pc.createAnswer();
        await pc.setLocalDescription(answer);
        send({ type: 'answer', to: m.from, sdp: pc.localDescription });
    }
    else if (m.type === 'answer') {
        const pc = peers[m.from]?.pc;
        if (pc && pc.signalingState !== 'stable') {
            await pc.setRemoteDescription(new RTCSessionDescription(m.sdp));
        }
    }
    else if (m.type === 'ice') {
        const pc = peers[m.from]?.pc;
        if (pc) try { await pc.addIceCandidate(new RTCIceCandidate(m.candidate)); } catch {}
    }
    else if (m.type === 'state') {
        if (!peers[m.from]) peers[m.from] = {};
        peers[m.from].micOn = m.micOn;
        peers[m.from].camOn = m.camOn;
        refreshRemoteTile(m.from);
    }
    else if (m.type === 'bye') {
        removePeer(m.from);
    }
};

/* ── Media ── */
async function startMedia(audioOnly) {
    document.getElementById('permModal').style.display = 'none';
    try {
        localStream = await navigator.mediaDevices.getUserMedia({
            audio: true,
            video: audioOnly ? false : { width: { ideal: 1280 }, height: { ideal: 720 } }
        });
        if (audioOnly) camOn = false;
    } catch (e) {
        showToast('Could not access camera/mic.');
        localStream = new MediaStream();
        camOn = false; micOn = false;
    }
    renderLocalTile();
    updateControlUI();
    send({ type: 'hello', nickname: NICKNAME });
}

/* ── Local tile ── */
function renderLocalTile() {
    let tile = document.getElementById('tile-local');
    if (!tile) {
        tile = makeTile('tile-local', NICKNAME + ' (You)', true);
        document.getElementById('videoArea').appendChild(tile);
    }
    const vid = tile.querySelector('video');
    vid.srcObject = localStream;
    vid.muted = true;
    vid.play().catch(() => {});
    syncLocalTile();
    refreshGrid();
}

function syncLocalTile() {
    const tile = document.getElementById('tile-local');
    if (!tile) return;
    // camera overlay
    tile.querySelector('.cam-off').style.display = camOn ? 'none' : 'flex';
    tile.querySelector('video').style.display    = camOn ? 'block' : 'none';
    // muted dot
    syncMutedDot(tile, micOn);
    // waiting message — show only when alone
    const alone = document.querySelectorAll('.tile').length === 1;
    let wm = tile.querySelector('.waiting-msg');
    if (alone && !wm) {
        wm = document.createElement('div');
        wm.className = 'waiting-msg';
        wm.textContent = 'Waiting for others to join…';
        tile.appendChild(wm);
    } else if (!alone && wm) {
        wm.remove();
    }
}

/* ── Remote tile ── */
function getOrCreateRemoteTile(peerId) {
    const tileId = 'tile-' + peerId;
    let tile = document.getElementById(tileId);
    if (!tile) {
        const nick = peers[peerId]?.nickname || 'Guest';
        tile = makeTile(tileId, nick, false);
        document.getElementById('videoArea').appendChild(tile);
        // remove waiting message from local tile once someone joins
        document.querySelector('.waiting-msg')?.remove();
        refreshGrid();
    }
    return tile;
}

function refreshRemoteTile(peerId) {
    const tile = document.getElementById('tile-' + peerId);
    if (!tile) return;
    const p = peers[peerId] || {};
    tile.querySelector('.cam-off').style.display = (p.camOn === false) ? 'flex' : 'none';
    tile.querySelector('video').style.display    = (p.camOn === false) ? 'none' : 'block';
    syncMutedDot(tile, p.micOn !== false);
}

/* ── Tile factory ── */
function makeTile(id, label, isLocal) {
    const cleanName = label.replace(' (You)', '');
    const div = document.createElement('div');
    div.className = 'tile';
    div.id = id;
    div.innerHTML = `
        <video ${isLocal ? 'muted class="mirror"' : ''} autoplay playsinline></video>
        <div class="cam-off" style="display:none;">
            <div class="avatar">${initials(cleanName)}</div>
            <div class="cam-off-name">${cleanName}</div>
        </div>
        <div class="tile-label">${label}</div>
    `;
    return div;
}

function syncMutedDot(tile, isMicOn) {
    let dot = tile.querySelector('.muted-dot');
    if (!isMicOn && !dot) {
        dot = document.createElement('div');
        dot.className = 'muted-dot';
        dot.innerHTML = `<svg viewBox="0 0 24 24">
            <line x1="1" y1="1" x2="23" y2="23"/>
            <path d="M9 9v3a3 3 0 005.12 2.12M15 9.34V4a3 3 0 00-5.94-.6"/>
            <path d="M17 16.95A7 7 0 015 12v-2m14 0v2a7 7 0 01-.11 1.23"/>
            <line x1="12" y1="19" x2="12" y2="23"/><line x1="8" y1="23" x2="16" y2="23"/>
        </svg>`;
        tile.appendChild(dot);
    } else if (isMicOn && dot) {
        dot.remove();
    }
}

function initials(name) {
    return name.trim().split(/\s+/).slice(0, 2).map(w => (w[0] || '').toUpperCase()).join('');
}

/* ── Grid ── */
function refreshGrid() {
    const area  = document.getElementById('videoArea');
    const count = area.querySelectorAll('.tile').length;
    area.className = 'video-area ' + (
        count === 1 ? 'grid-1' :
        count === 2 ? 'grid-2' :
        count <= 4  ? 'grid-3' : 'grid-many'
    );
    syncLocalTile(); // re-check waiting message
}

/* ── WebRTC ── */
const ICE_CFG = { iceServers: [
    { urls: 'stun:stun.l.google.com:19302' },
    { urls: 'stun:stun1.l.google.com:19302' }
]};

function getOrCreatePC(peerId) {
    if (peers[peerId]?.pc) return peers[peerId].pc;
    if (!peers[peerId]) peers[peerId] = { nickname: 'Guest', micOn: true, camOn: true };

    const pc = new RTCPeerConnection(ICE_CFG);
    peers[peerId].pc = pc;

    if (localStream) localStream.getTracks().forEach(t => pc.addTrack(t, localStream));

    pc.onicecandidate = ({ candidate }) => {
        if (candidate) send({ type: 'ice', to: peerId, candidate });
    };

    pc.ontrack = ({ streams }) => {
        const tile = getOrCreateRemoteTile(peerId);
        const vid  = tile.querySelector('video');
        if (vid.srcObject !== streams[0]) {
            vid.srcObject = streams[0];
            vid.play().catch(() => {});
        }
    };

    pc.onconnectionstatechange = () => {
        if (['disconnected', 'failed', 'closed'].includes(pc.connectionState)) {
            removePeer(peerId);
        }
    };

    return pc;
}

async function callPeer(peerId) {
    const pc    = getOrCreatePC(peerId);
    const offer = await pc.createOffer();
    await pc.setLocalDescription(offer);
    send({ type: 'offer', to: peerId, sdp: pc.localDescription, nickname: NICKNAME });
}

function removePeer(peerId) {
    peers[peerId]?.pc?.close();
    delete peers[peerId];
    document.getElementById('tile-' + peerId)?.remove();
    refreshGrid();
}

/* ── Controls ── */
function toggleMic() {
    micOn = !micOn;
    localStream?.getAudioTracks().forEach(t => { t.enabled = micOn; });
    syncLocalTile();
    updateControlUI();
    send({ type: 'state', micOn, camOn });
}

function toggleCam() {
    camOn = !camOn;
    localStream?.getVideoTracks().forEach(t => { t.enabled = camOn; });
    syncLocalTile();
    updateControlUI();
    send({ type: 'state', micOn, camOn });
}

function updateControlUI() {
    document.getElementById('micBtn').className   = 'ctrl ' + (micOn ? 'ctrl-on' : 'ctrl-off');
    document.getElementById('camBtn').className   = 'ctrl ' + (camOn ? 'ctrl-on' : 'ctrl-off');
    document.getElementById('micLabel').textContent = micOn ? 'Mute'       : 'Unmute';
    document.getElementById('camLabel').textContent = camOn ? 'Stop video' : 'Start video';

    document.getElementById('micSvg').innerHTML = micOn
        ? `<path d="M12 1a3 3 0 00-3 3v8a3 3 0 006 0V4a3 3 0 00-3-3z"/>
           <path d="M19 10v2a7 7 0 01-14 0v-2"/>
           <line x1="12" y1="19" x2="12" y2="23"/>
           <line x1="8"  y1="23" x2="16" y2="23"/>`
        : `<line x1="1" y1="1" x2="23" y2="23"/>
           <path d="M9 9v3a3 3 0 005.12 2.12M15 9.34V4a3 3 0 00-5.94-.6"/>
           <path d="M17 16.95A7 7 0 015 12v-2m14 0v2a7 7 0 01-.11 1.23"/>
           <line x1="12" y1="19" x2="12" y2="23"/>
           <line x1="8" y1="23" x2="16" y2="23"/>`;

    document.getElementById('camSvg').innerHTML = camOn
        ? `<path d="M15 10l4.553-2.276A1 1 0 0121 8.723v6.554a1 1 0 01-1.447.894L15 14M3 8a2 2 0 012-2h8a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2V8z"/>`
        : `<path d="M16 16v1a2 2 0 01-2 2H3a2 2 0 01-2-2V7a2 2 0 012-2h2m5.66 0H14a2 2 0 012 2v3.34"/>
           <path d="M23 7l-7 5 7 5V7z"/>
           <line x1="1" y1="1" x2="23" y2="23"/>`;
}

function leaveMeeting() {
    send({ type: 'bye' });
    localStream?.getTracks().forEach(t => t.stop());
    ch.close();
    window.location.href = '{{ route("dashboard") }}';
}

function shareLink() {
    navigator.clipboard.writeText(ROOM_URL).then(() => {
        const btn  = document.getElementById('shareLinkBtn');
        const orig = btn.innerHTML;
        btn.textContent = '✓ Copied!';
        btn.classList.add('copied');
        setTimeout(() => { btn.innerHTML = orig; btn.classList.remove('copied'); }, 2000);
    });
}

function showToast(msg, ms = 3000) {
    const t = document.getElementById('toast');
    t.textContent = msg;
    t.classList.add('show');
    setTimeout(() => t.classList.remove('show'), ms);
}

window.addEventListener('beforeunload', () => send({ type: 'bye' }));
</script>
</body>
</html>