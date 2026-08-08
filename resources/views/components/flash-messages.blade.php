<div class="flash-messages-container">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm rounded-3 border mb-4" role="alert">
            <div class="d-flex align-items-center">
                <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center me-3 flex-shrink-0" style="width: 30px; height: 30px;">
                    <i class="fas fa-check"></i>
                </div>
                <div class="fw-medium alert-text">{{ session('success') }}</div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show shadow-sm rounded-3 border mb-4" role="alert">
            <div class="d-flex align-items-center">
                <div class="bg-danger text-white rounded-circle d-flex align-items-center justify-content-center me-3 flex-shrink-0" style="width: 30px; height: 30px;">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <div class="fw-medium alert-text">{{ session('error') }}</div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('info'))
        <div class="alert alert-info alert-dismissible fade show shadow-sm rounded-3 border mb-4" role="alert">
            <div class="d-flex align-items-center">
                <div class="bg-info text-white rounded-circle d-flex align-items-center justify-content-center me-3 flex-shrink-0" style="width: 30px; height: 30px;">
                    <i class="fas fa-info"></i>
                </div>
                <div class="fw-medium alert-text">{{ session('info') }}</div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('warning'))
        <div class="alert alert-warning alert-dismissible fade show shadow-sm rounded-3 border mb-4" role="alert">
            <div class="d-flex align-items-center">
                <div class="bg-warning text-dark rounded-circle d-flex align-items-center justify-content-center me-3 flex-shrink-0" style="width: 30px; height: 30px;">
                    <i class="fas fa-exclamation"></i>
                </div>
                <div class="fw-medium alert-text">{{ session('warning') }}</div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show shadow-sm rounded-3 border mb-4" role="alert">
            <div class="d-flex">
                <div class="bg-danger text-white rounded-circle d-flex align-items-center justify-content-center me-3 mt-1 flex-shrink-0" style="width: 30px; height: 30px;">
                    <i class="fas fa-times"></i>
                </div>
                <div>
                    <h6 class="alert-heading fw-bold alert-text mb-1">{{ __('center::messages.validation_error') ?? 'يرجى مراجعة الأخطاء التالية:' }}</h6>
                    <ul class="mb-0 ps-3 alert-text small">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    if (typeof Swal !== 'undefined') {
        const isDark = document.documentElement.classList.contains('dark') || document.body.classList.contains('dark');
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 4000,
            timerProgressBar: true,
            background: isDark ? '#1E293B' : '#ffffff',
            color: isDark ? '#F8FAFC' : '#1E293B',
            didOpen: (toast) => {
                toast.onmouseenter = Swal.stopTimer;
                toast.onmouseleave = Swal.resumeTimer;
            }
        });

        @if(session('success'))
            Toast.fire({ icon: 'success', title: @json(session('success')) });
        @endif

        @if(session('error'))
            Toast.fire({ icon: 'error', title: @json(session('error')) });
        @endif
    }
});
</script>
