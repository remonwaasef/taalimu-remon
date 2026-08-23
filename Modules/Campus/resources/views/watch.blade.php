@extends('campus::layouts.master')

@section('content')
<div class="container-fluid px-3 px-md-4 py-4">
    {{-- Header --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-3 gap-2">
        <div>
            <a href="{{ route('campus.classes.index') }}" class="btn btn-light btn-sm rounded-pill px-3">
                <i class="fas fa-arrow-right me-1"></i> {{ __('campus::classes.back') }}
            </a>
            <h5 class="fw-bold d-inline-block ms-2 mb-0" style="color: var(--primary-color);">{{ $recording->onlineClass->title }}</h5>
        </div>
        <span class="badge bg-success rounded-pill px-3">
            <i class="fas fa-shield-alt me-1"></i> {{ __('campus::classes.protected') }}
        </span>
    </div>

    {{-- Watermark Layer (rendered server-side, repositioned by JS) --}}
    <div id="watermark-layer" class="pointer-events-none" style="position: fixed; inset: 0; z-index: 1050; overflow: hidden;"></div>

    {{-- Video Player --}}
    <div class="card border-0 shadow-sm rounded-4" style="background:#000; aspect-ratio: 16/9; max-width: 100%;">
        <div class="card-body p-0 d-flex align-items-center justify-content-center" style="min-height: 400px;">
            <video id="player" controls playsinline
                   style="width: 100%; height: 100%; background: #000;"
                   poster="{{ asset('images/video-poster.png') }}">
                <track kind="captions" srclang="ar" label="العربية" default>
            </video>
        </div>
    </div>

    {{-- Progress & Info Bar --}}
    <div class="card border-0 shadow-sm rounded-4 mt-3">
        <div class="card-body">
            <div class="row g-3 align-items-center">
                <div class="col-md-8">
                    <div class="d-flex align-items-center gap-3">
                        <div class="fw-bold" style="color: var(--primary-color);">{{ $recording->onlineClass->title }}</div>
                        <span class="badge bg-info text-white">{{ number_format(($recording->duration_seconds ?? 0) / 60, 1) }} {{ __('campus::classes.minutes') }}</span>
                        @if($progress)
                            <span class="badge bg-{{ $progress->completion_percentage >= 95 ? 'success' : 'primary' }}">
                                {{ __('campus::classes.progress') }}: {{ $progress->completion_percentage }}%
                            </span>
                        @endif
                    </div>
                </div>
                <div class="col-md-4 text-md-end">
                    @if($progress && $progress->completed_at)
                        <span class="badge bg-success rounded-pill px-3">
                            <i class="fas fa-check-circle me-1"></i> {{ __('campus::classes.completed') }}
                        </span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Course Context --}}
    <div class="card border-0 shadow-sm rounded-4 mt-3">
        <div class="card-body">
            <h6 class="fw-bold mb-3">{{ __('campus::classes.related_course') }}</h6>
            <a href="{{ route('campus.courses.show', $recording->onlineClass->course) }}" class="d-flex align-items-center gap-3 text-decoration-none text-dark">
                <div class="bg-primary bg-opacity-10 text-primary rounded-circle p-3 d-flex align-items-center justify-content-center" style="width: 56px; height: 56px;">
                    <i class="fas fa-book fs-4"></i>
                </div>
                <div>
                    <div class="fw-semibold">{{ $recording->onlineClass->course->title }}</div>
                    <div class="text-muted small">{{ $recording->onlineClass->instructor->name ?? '' }}</div>
                </div>
            </a>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// =============================================================================
// Secure Video Player with Dynamic Watermark & Progress Tracking
// =============================================================================

(function() {
    'use strict';

    const recordingUuid = '{{ $recording->uuid }}';
    const tokenUrl = '{{ route("campus.recordings.token", $recording) }}';
    const progressUrl = '{{ route("campus.recordings.progress", $recording) }}';
    const duration = {{ $recording->duration_seconds ?? 0 }};
    const resumeAt = {{ $progress ? $progress->last_position_seconds : 0 }};

    const player = document.getElementById('player');
    const watermarkLayer = document.getElementById('watermark-layer');
    let currentToken = null;
    let tokenExpiry = 0;
    let progressTimer = null;
    let watermarkTimer = null;
    let isSeeking = false;

    // ---- Watermark Data (from server-side user context) ----
    const studentName = '{{ auth()->user()->name }}';
    const studentCode = '{{ auth()->user()->student?->code ?? auth()->id() }}';
    const maskedCode = studentCode.length > 4 ? '****' + studentCode.slice(-4) : studentCode;

    // ---- Token Management ----
    async function fetchToken() {
        try {
            const res = await fetch(tokenUrl, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                },
            });
            if (!res.ok) {
                const err = await res.json();
                throw new Error(err.error || 'Token request failed');
            }
            const data = await res.json();
            currentToken = data.token;
            tokenExpiry = Date.now() + (data.ttl * 1000) - 30000; // refresh 30s early
            return data.token;
        } catch (e) {
            console.error('Token fetch failed:', e);
            showPlayerError('{{ __('campus::classes.token_error') }}');
            throw e;
        }
    }

    function getStreamUrl(token) {
        return '{{ route("video.stream", ["token" => ":token"]) }}'.replace(':token', token);
    }

    async function loadVideo() {
        const token = await fetchToken();
        const url = getStreamUrl(token);
        player.src = url;
        player.load();
        
        if (resumeAt > 0) {
            player.addEventListener('loadedmetadata', function onMeta() {
                player.currentTime = Math.min(resumeAt, player.duration - 1);
                player.removeEventListener('loadedmetadata', onMeta);
            }, { once: true });
        }
    }

    // ---- Watermark System ----
    const watermarkPositions = [
        { top: '5%', right: '5%' },
        { bottom: '5%', left: '5%' },
        { top: '15%', left: '50%', transform: 'translateX(-50%)' },
        { bottom: '15%', right: '50%', transform: 'translateX(50%)' },
        { top: '50%', left: '5%', transform: 'translateY(-50%)' },
        { top: '50%', right: '5%', transform: 'translateY(-50%)' },
    ];
    let watermarkIndex = 0;

    function createWatermarkEl() {
        const now = new Date();
        const timeStr = now.toLocaleString('ar-EG', { hour: '2-digit', minute: '2-digit', second: '2-digit', day: '2-digit', month: '2-digit', year: 'numeric' });
        
        const el = document.createElement('div');
        el.className = 'watermark-item';
        el.style.cssText = `
            position: absolute;
            pointer-events: none;
            user-select: none;
            font-family: 'Cairo', sans-serif;
            font-size: 0.85rem;
            font-weight: 600;
            color: rgba(255,255,255,0.35);
            text-shadow: 0 0 8px rgba(0,0,0,0.8), 0 0 16px rgba(0,0,0,0.6);
            white-space: nowrap;
            line-height: 1.4;
            animation: watermark-fade 0.5s ease-in-out;
        `;
        el.innerHTML = `
            <div>${studentName}</div>
            <div>ID: ${maskedCode}</div>
            <div>Taalimu</div>
            <div>${timeStr}</div>
        `;
        return el;
    }

    function placeWatermark() {
        watermarkLayer.innerHTML = '';
        const el = createWatermarkEl();
        const pos = watermarkPositions[watermarkIndex % watermarkPositions.length];
        Object.assign(el.style, pos);
        if (pos.transform) el.style.transform = pos.transform;
        watermarkLayer.appendChild(el);
        watermarkIndex = (watermarkIndex + 1) % watermarkPositions.length;
    }

    function startWatermarkRotation() {
        placeWatermark();
        watermarkTimer = setInterval(placeWatermark, 8000); // move every 8s
    }

    function stopWatermarkRotation() {
        if (watermarkTimer) clearInterval(watermarkTimer);
    }

    // ---- Progress Tracking (throttled) ----
    let lastSavedPosition = -1;
    const PROGRESS_THROTTLE_MS = 15000; // 15s

    async function sendProgress() {
        if (isSeeking) return;
        const pos = Math.floor(player.currentTime);
        if (pos === lastSavedPosition) return;
        if (pos - lastSavedPosition < 5) return; // minimal delta

        lastSavedPosition = pos;
        
        try {
            await fetch(progressUrl, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    position_seconds: pos,
                    watched_delta: Math.min(15, duration), // server throttles anyway
                }),
            });
        } catch (e) {
            console.warn('Progress save failed:', e);
        }
    }

    // ---- Token Refresh Loop ----
    async function refreshTokenLoop() {
        while (true) {
            await new Promise(r => setTimeout(r, 60000)); // check every minute
            if (Date.now() >= tokenExpiry) {
                console.log('Refreshing playback token...');
                try {
                    const token = await fetchToken();
                    // Seamless reload: swap src without interrupting playback if possible
                    const wasPlaying = !player.paused;
                    const currentTime = player.currentTime;
                    player.src = getStreamUrl(token);
                    player.load();
                    if (wasPlaying) {
                        player.addEventListener('loadedmetadata', function onMeta() {
                            player.currentTime = currentTime;
                            player.play().catch(() => {});
                            player.removeEventListener('loadedmetadata', onMeta);
                        }, { once: true });
                    }
                } catch (e) {
                    // token refresh failed - video will stop, user can reload
                }
            }
        }
    }

    // ---- Player Events ----
    player.addEventListener('timeupdate', () => {
        sendProgress();
    });

    player.addEventListener('seeking', () => { isSeeking = true; });
    player.addEventListener('seeked', () => { isSeeking = false; sendProgress(); });

    player.addEventListener('pause', stopWatermarkRotation);
    player.addEventListener('play', startWatermarkRotation);

    player.addEventListener('error', (e) => {
        console.error('Video error:', e);
        if (player.error?.code === MediaError.MEDIA_ERR_SRC_NOT_SUPPORTED) {
            // Try token refresh once
            fetchToken().then(token => {
                player.src = getStreamUrl(token);
                player.load();
            }).catch(() => showPlayerError('{{ __('campus::classes.playback_error') }}'));
        }
    });

    // ---- Init ----
    function init() {
        loadVideo().then(() => {
            startWatermarkRotation();
            refreshTokenLoop();
        });
    }

    function showPlayerError(msg) {
        const container = player.parentElement;
        container.innerHTML = `
            <div class="d-flex align-items-center justify-content-center h-100 text-white">
                <div class="text-center">
                    <i class="fas fa-exclamation-triangle fa-3x text-warning mb-3"></i>
                    <h5>${msg}</h5>
                    <button class="btn btn-outline-light rounded-pill px-4 mt-3" onclick="location.reload()">
                        <i class="fas fa-redo me-2"></i> {{ __('campus::classes.retry') }}
                    </button>
                </div>
            </div>
        `;
    }

    // Prevent right-click save (basic deterrent)
    player.addEventListener('contextmenu', e => e.preventDefault());

    // Keyboard shortcuts
    document.addEventListener('keydown', (e) => {
        if (e.target === player) return;
        if (e.code === 'Space') { e.preventDefault(); player.paused ? player.play() : player.pause(); }
        if (e.code === 'ArrowLeft') { player.currentTime = Math.max(0, player.currentTime - 10); }
        if (e.code === 'ArrowRight') { player.currentTime = Math.min(duration, player.currentTime + 10); }
    });

    // Start
    document.addEventListener('DOMContentLoaded', init);

    // Cleanup on unload
    window.addEventListener('beforeunload', () => {
        stopWatermarkRotation();
        sendProgress(); // final push
    });

})();
</script>

<style>
@keyframes watermark-fade {
    from { opacity: 0; transform: translateY(-10px) scale(0.95); }
    to { opacity: 1; transform: translateY(0) scale(1); }
}

.watermark-item {
    /* Additional visual hardening */
    filter: drop-shadow(0 0 4px rgba(0,0,0,0.7));
}

video::-webkit-media-controls-download-button {
    display: none !important;
}
video::-webkit-media-controls-enclosure {
    overflow: hidden;
}
video::-webkit-media-controls-panel {
    width: calc(100% + 30px); /* hide download in overflow */
}
</style>
@endpush