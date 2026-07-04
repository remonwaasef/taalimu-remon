<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('resetPasswordBtn')?.addEventListener('click', function() {
            Swal.fire({
                title: "{{ __('center::students.profile.reset_password.modal_title') }}",
                text: "{{ __('center::students.profile.reset_password.modal_text') }}",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ffc107',
                cancelButtonColor: '#6c757d',
                confirmButtonText: "{{ __('center::students.profile.reset_password.confirm_btn') }}",
                cancelButtonText: "{{ __('center::students.profile.reset_password.cancel') }}",
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('resetPasswordForm').submit();
                }
            });
        });
    });

    function copyToClipboard(text) {
        navigator.clipboard.writeText(text).then(function() {
            const toast = document.createElement('div');
            toast.className = 'position-fixed bottom-0 start-50 translate-middle-x mb-5 bg-dark text-white p-3 rounded-4 shadow animate__animated animate__fadeInUp';
            toast.style.zIndex = '9999';
            toast.innerHTML = '<i class="fas fa-check-circle text-success me-2"></i> {{ __('center::students.profile.reset_password.copy_success') }}';
            document.body.appendChild(toast);
            setTimeout(() => toast.remove(), 2000);
        });
    }

    function copyAllDetails() {
        @if(session('generated_password'))
            const text = @json($msg ?? '');
            navigator.clipboard.writeText(text).then(function() {
                alert("{{ __('center::students.profile.reset_password.copy_success') }}");
            });
        @endif
    }
</script>

<!-- QR Code Library for ID Card -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<script>
    window.printIDCard = function() {
        window.open("{{ route('center.students.id-card', $student->id) }}", '_blank');
    }

    document.addEventListener('DOMContentLoaded', function() {
        const qrContainer = document.getElementById('student-qrcode');
        const sidebarQrContainer = document.getElementById('sidebar-student-qrcode');

        if (typeof QRCode !== 'undefined') {
            if (qrContainer) {
                qrContainer.innerHTML = '';
                new QRCode(qrContainer, {
                    text: "{{ $student->code }}",
                    width: 60,
                    height: 60,
                    colorDark: "#000000",
                    colorLight: "#ffffff",
                    correctLevel : QRCode.CorrectLevel.H
                });
            }

            if (sidebarQrContainer) {
                sidebarQrContainer.innerHTML = '';
                new QRCode(sidebarQrContainer, {
                    text: "{{ $student->code }}",
                    width: 150,
                    height: 150,
                    colorDark: "#000000",
                    colorLight: "#ffffff",
                    correctLevel : QRCode.CorrectLevel.H
                });
            }
        } else {
            console.error('QRCode library not loaded');
        }
    });
</script>
