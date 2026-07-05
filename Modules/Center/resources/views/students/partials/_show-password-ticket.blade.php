        <div class="premium-ticket-container mb-5 animate__animated animate__fadeIn">
            <div class="premium-ticket shadow-lg">
                <div class="row g-0">
                    <!-- Left Side: Student Info -->
                    <div class="col-md-8 p-4 bg-white rounded-start-4 position-relative overflow-hidden">
                        <div class="ticket-decoration"></div>
                        <div class="d-flex align-items-center mb-4">
                            <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px;">
                                <i class="fas fa-key fs-4"></i>
                            </div>
                            <div>
                                <h4 class="fw-bold mb-0 text-dark">{{ __('center::students.profile.password_reset_title') }}</h4>
                                <span class="badge bg-warning bg-opacity-10 text-warning rounded-pill px-3 mt-1">{{ __('center::students.profile.new_credentials') }}</span>
                            </div>
                        </div>

                        <div class="row g-4 mt-2">
                            <div class="col-sm-6">
                                <label class="text-muted small text-uppercase fw-bold d-block mb-1">{{ __('center::students.profile.student_name') }}</label>
                                <span class="fw-bold fs-5">{{ $student->name }}</span>
                            </div>
                            <div class="col-sm-6">
                                <label class="text-muted small text-uppercase fw-bold d-block mb-1">{{ __('center::students.profile.temporary_password') }}</label>
                                <div class="d-flex align-items-center gap-2">
                                    <span class="fs-4 fw-bold text-danger font-monospace">{{ session('generated_password') }}</span>
                                    <button onclick="copyToClipboard('{{ session('generated_password') }}')" class="btn btn-sm btn-light rounded-circle" title="{{ __('center::students.copy') }}">
                                        <i class="fas fa-copy text-primary"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="mt-4 pt-3 border-top d-flex flex-wrap gap-2">
                            <button onclick="copyAllDetails()" class="btn btn-outline-dark rounded-pill px-4">
                                <i class="fas fa-copy me-2"></i>{{ __('center::students.profile.copy_all') }}</button>
                            <a href="{{ $whatsappUrl }}" target="_blank" class="btn btn-success rounded-pill px-4">
                                <i class="fab fa-whatsapp me-2"></i>{{ __('center::students.profile.send_whatsapp') }}</a>
                        </div>
                    </div>

                    <!-- Right Side: QR Code -->
                    <div class="col-md-4 p-4 text-center d-flex flex-column align-items-center justify-content-center bg-light rounded-end-4 border-start border-dashed position-relative">
                        <div class="ticket-stub-decoration top"></div>
                        <div class="ticket-stub-decoration bottom"></div>
                        
                        <div class="qr-container bg-white p-2 rounded-3 shadow-sm mb-3">
                            {{-- Signed magic-login link → QR generated locally, never sent to a third party --}}
                            <div class="pwticket-local-qr d-flex justify-content-center" style="width: 140px; height: 140px;" data-qr="{{ $qrUrl }}"></div>
                        </div>
                        <p class="small text-muted mb-0">{{ __('center::students.magic_login_tip') }}</p>
                        @push('scripts')
                        <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
                        <script>
                            document.addEventListener('DOMContentLoaded', function () {
                                document.querySelectorAll('.pwticket-local-qr').forEach(function (el) {
                                    if (typeof QRCode !== 'undefined' && el.dataset.qr) {
                                        new QRCode(el, { text: el.dataset.qr, width: 130, height: 130, correctLevel: QRCode.CorrectLevel.H });
                                    }
                                });
                            });
                        </script>
                        @endpush
                    </div>
                </div>
            </div>
        </div>
