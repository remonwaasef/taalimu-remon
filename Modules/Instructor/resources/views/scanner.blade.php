@extends('instructor::components.layouts.hope-master')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8 text-center">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="fw-bold mb-0">{{ __('instructor::scanner.smart_attendance', ['title' => $course->title]) }}</h5>
                    <p class="text-muted small mb-0">{{ __('instructor::scanner.guide_camera') }}</p>
                </div>
                
                <div class="card-body p-0 position-relative">
                    <div id="reader" style="width: 100%; min-height: 300px; background: #000;"></div>
                    
                    <!-- Scanner Overlay -->
                    <div id="scanner-status" class="position-absolute top-50 start-50 translate-middle w-100 p-4 d-none" style="z-index: 10;">
                        <div class="alert alert-success shadow-lg rounded-pill animate__animated animate__pulse">
                            <i class="fas fa-check-circle me-2"></i> <span id="status-text">{{ __('instructor::scanner.attendance_marked') }}</span>
                        </div>
                    </div>
                </div>

                <div class="card-footer bg-white border-0 py-4">
                    @if($activeSchedule = $schedule)
                        <div class="alert alert-info small mb-0 rounded-pill">
                            <i class="fas fa-clock me-2"></i> {{ __('instructor::scanner.today_session') }} 
                            <strong>{{ \Carbon\Carbon::parse($activeSchedule->start_time)->format('h:i A') }}</strong>
                        </div>
                        <input type="hidden" id="schedule_id" value="{{ $activeSchedule->id }}">
                    @else
                        <div class="alert alert-warning small mb-0 rounded-pill">
                            <i class="fas fa-exclamation-triangle me-2"></i> {{ __('instructor::scanner.no_session_today') }}
                        </div>
                    @endif
                    
                    <button id="toggle-camera" class="btn btn-primary rounded-pill px-4 mt-3" style="background: var(--primary-color);">
                        <i class="fas fa-camera me-2"></i> {{ __('instructor::scanner.start_camera') }}
                    </button>
                    <a href="{{ route('instructor.dashboard') }}" class="btn btn-link text-muted mt-3 d-block">{{ __('instructor::dashboard.back_to_dashboard') }}</a>
                </div>
            </div>

            <!-- Recent Scans -->
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-transparent border-0 pt-4 px-4 text-start">
                    <h6 class="fw-bold mb-0">{{ __('instructor::scanner.recent_scans') }}</h6>
                </div>
                <div class="card-body p-0">
                    <div id="recent-scans" class="list-group list-group-flush">
                        <div class="text-center py-4 text-muted small">{{ __('instructor::scanner.no_recent_scans') }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Sound Effects -->
<audio id="beep-success" src="https://assets.mixkit.co/active_storage/sfx/2869/2869-preview.mp3"></audio>
<audio id="beep-fail" src="https://assets.mixkit.co/active_storage/sfx/2873/2873-preview.mp3"></audio>

@endsection

@push('scripts')
<script src="https://unpkg.com/html5-qrcode"></script>
<script>
    let html5QrScanner = null;
    let isProcessing = false;
    const courseId = "{{ $course->id }}";
    const scheduleId = document.getElementById('schedule_id')?.value;

    function onScanSuccess(decodedText, decodedResult) {
        if (isProcessing) return;
        
        isProcessing = true;
        processScan(decodedText);
    }

    function processScan(qrIdentifier) {
        if (!scheduleId) {
            showStatus("{{ __('instructor::scanner.no_active_session') }}", "danger");
            isProcessing = false;
            return;
        }

        fetch(`{{ url('instructor/scan') }}/${courseId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                qr_identifier: qrIdentifier,
                schedule_id: scheduleId
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('beep-success').play();
                const studentName = data.student_name;
                const statusMsg = data.already_marked ? "{{ __('instructor::scanner.already_marked') }}" : "{{ __('instructor::scanner.marked_success') }}";
                
                showStatus(`${studentName} - ${statusMsg}`, data.already_marked ? "warning" : "success");
                addRecentScan(studentName, data.remaining_sessions, data.already_marked, data.whatsapp_url);
            } else {
                document.getElementById('beep-fail').play();
                showStatus(data.message, "danger");
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showStatus("{{ __('instructor::scanner.server_error') }}", "danger");
        })
        .finally(() => {
            setTimeout(() => {
                isProcessing = false;
            }, 2000); // Wait 2 seconds before next scan
        });
    }

    function showStatus(message, type) {
        const statusDiv = document.getElementById('scanner-status');
        const statusAlert = statusDiv.querySelector('.alert');
        const statusText = document.getElementById('status-text');

        statusAlert.className = `alert alert-${type} shadow-lg rounded-pill animate__animated animate__pulse`;
        statusText.innerText = message;
        statusDiv.classList.remove('d-none');

        setTimeout(() => {
            statusDiv.classList.add('d-none');
        }, 2000);
    }

    function addRecentScan(name, balance, already, whatsappUrl) {
        const list = document.getElementById('recent-scans');
        const emptyMsg = list.querySelector('.text-center');
        if (emptyMsg) emptyMsg.remove();

        const item = document.createElement('div');
        item.className = "list-group-item bg-transparent border-0 px-4 py-3 border-bottom border-light animate__animated animate__fadeInDown";
        item.innerHTML = `
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="mb-0 fw-bold">${name}</h6>
                    <small class="text-muted">{{ __('instructor::scanner.remaining_sessions', ['balance' => '${balance ?? "--"}']) }}</small>
                </div>
                <div class="d-flex gap-2">
                    <a href="${whatsappUrl}" target="_blank" class="btn btn-sm btn-success rounded-pill px-3">
                        <i class="fab fa-whatsapp"></i> {{ __('instructor::scanner.notify') }}
                    </a>
                    <span class="badge ${already ? 'bg-warning' : 'bg-success'} rounded-pill d-flex align-items-center">
                        ${already ? '{{ __('instructor::scanner.already_marked') }}' : (already === false ? '{{ __('instructor::scanner.active') }}' : '--')}
                    </span>
                </div>
            </div>
        `;
        list.prepend(item);
    }

    document.getElementById('toggle-camera').addEventListener('click', function() {
        if (html5QrScanner) {
            html5QrScanner.clear();
            html5QrScanner = null;
            this.innerHTML = '<i class="fas fa-camera me-2"></i> {{ __('instructor::scanner.start_camera') }}';
        } else {
            html5QrScanner = new Html5QrcodeScanner("reader", { 
                fps: 10, 
                qrbox: {width: 250, height: 250},
                showTorchButtonIfSupported: true
            });
            html5QrScanner.render(onScanSuccess);
            this.innerHTML = '<i class="fas fa-stop me-2"></i> {{ __('instructor::scanner.stop_camera') }}';
        }
    });

    // Auto-start if requested or possible
    window.addEventListener('load', () => {
        // You could auto-start here
    });
</script>
@endpush
