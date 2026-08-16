<div class="flash-messages-container">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm rounded-3 border mb-4 alert-enter" role="alert">
            <div class="d-flex align-items-center">
                <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center me-3 flex-shrink-0" style="width: 30px; height: 30px;">
                    <i class="fas fa-check check-pop"></i>
                </div>
                <div class="fw-medium alert-text">{{ session('success') }}</div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show shadow-sm rounded-3 border mb-4 alert-enter" role="alert">
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
        <div class="alert alert-info alert-dismissible fade show shadow-sm rounded-3 border mb-4 alert-enter" role="alert">
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
        <div class="alert alert-warning alert-dismissible fade show shadow-sm rounded-3 border mb-4 alert-enter" role="alert">
            <div class="d-flex align-items-center">
                <div class="bg-warning text-dark rounded-circle d-flex align-items-center justify-content-center me-3 flex-shrink-0" style="width: 30px; height: 30px;">
                    <i class="fas fa-exclamation"></i>
                </div>
                <div class="fw-medium alert-text">{{ session('warning') }}</div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if (isset($errors) && $errors->any())
        <div class="alert alert-danger alert-dismissible fade show shadow-sm rounded-3 border mb-4 alert-enter" role="alert">
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

{{-- Toast notifications now use global TaalimuToast from taalimu-global.js --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    if (typeof TaalimuToast !== 'undefined') {
        @if(session('success'))
            TaalimuToast.success(@json(session('success')));
        @endif
        @if(session('error'))
            TaalimuToast.error(@json(session('error')));
        @endif
    }
});
</script>
