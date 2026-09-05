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
            showSuccessToast('{{ __('center::students.copy_success') }}');
        });
    }

    function copyAllDetails() {
        @if(session('generated_password'))
            const text = @json($whatsappText ?? '');
            navigator.clipboard.writeText(text).then(function() {
                showSuccessToast('{{ __('center::students.copy_all_success') }}');
            });
        @endif
    }

    function openSmartWhatsApp(phone) {
        const isMobile = /Android|iPhone|iPad|iPod/i.test(navigator.userAgent);
        const msg = @json($whatsappText ?? '');
        const encoded = encodeURIComponent(msg);

        if (isMobile) {
            window.open('https://api.whatsapp.com/send?phone=' + phone + '&text=' + encoded, '_blank');
        } else {
            // Open WhatsApp Web directly on desktop
            window.open('https://web.whatsapp.com/send?phone=' + phone + '&text=' + encoded, '_blank');
        }
    }

    function showSuccessToast(message) {
        const toast = document.createElement('div');
        toast.className = 'position-fixed bottom-0 start-50 translate-middle-x mb-5 bg-dark text-white py-2 px-4 rounded-pill shadow-lg animate__animated animate__fadeInUp';
        toast.style.zIndex = '99999';
        toast.innerHTML = '<i class="fas fa-check-circle text-success me-2"></i> ' + message;
        document.body.appendChild(toast);
        setTimeout(() => {
            toast.classList.remove('animate__fadeInUp');
            toast.classList.add('animate__fadeOutDown');
            setTimeout(() => toast.remove(), 400);
        }, 2500);
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
