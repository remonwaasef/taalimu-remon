@if(request()->routeIs('center.attendance.*'))
<!-- Shepherd.js -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/shepherd.js@10.0.1/dist/css/shepherd.css"/>
<script src="https://cdn.jsdelivr.net/npm/shepherd.js@10.0.1/dist/js/shepherd.min.js"></script>

<style>
    .shepherd-element {
        background: #fff;
        border-radius: 15px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        max-width: 400px;
        border: none;
    }
    .shepherd-header { background: #f8f9fa; padding: 15px; border-radius: 15px 15px 0 0; }
    .shepherd-title { color: var(--primary-color); fw-bold; }
    .shepherd-text { padding: 20px; font-size: 0.95rem; line-height: 1.6; }
    .shepherd-footer { padding: 15px; }
    .shepherd-button { 
        border-radius: 20px; 
        padding: 8px 20px; 
        font-weight: bold; 
        transition: all 0.2s;
    }
    .shepherd-button-primary { background: var(--primary-color); color: white; }
    .shepherd-button-secondary { background: #eee; color: #333; }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        if (localStorage.getItem('onboarding_tour_active') === 'true') {
            startAttendanceTour();
        }
    });

    function startAttendanceTour() {
        const tour = new Shepherd.Tour({
            useModalOverlay: true,
            defaultStepOptions: {
                cancelIcon: { enabled: true },
                classes: 'shadow-md bg-purple-dark',
                scrollTo: { behavior: 'smooth', block: 'center' }
            }
        });

        @if(request()->routeIs('center.attendance.index'))
        tour.addStep({
            id: 'welcome',
            title: 'مرحباً في نظام التحضير! 👋',
            text: 'هنا يمكنك متابعة حصص اليوم وتسجيل حضور الطلاب بسهولة.',
            buttons: [{ text: 'التالي', action: tour.next, classes: 'shepherd-button-primary' }]
        });

        tour.addStep({
            id: 'today-sessions',
            title: 'حصص اليوم',
            text: 'ستظهر هنا جميع الحصص المجدولة لهذا اليوم بناءً على الجدول الدراسي.',
            attachTo: { element: '.card-header', on: 'bottom' },
            buttons: [{ text: 'التالي', action: tour.next, classes: 'shepherd-button-primary' }]
        });

        tour.addStep({
            id: 'record-btn',
            title: 'تسجيل الحضور',
            text: 'عندما تبدأ الحصة، اضغط على زر "تسجيل الحضور" لفتح قائمة الطلاب.',
            attachTo: { element: '.btn-outline-primary', on: 'right' },
            buttons: [{ 
                text: 'فهمت!', 
                action: () => {
                    localStorage.setItem('onboarding_tour_active', 'false'); // Mark as done for this page
                    tour.complete();
                }, 
                classes: 'shepherd-button-primary' 
            }]
        });
        @endif

        @if(request()->routeIs('center.attendance.show'))
        tour.addStep({
            id: 'student-list',
            title: 'قائمة الطلاب',
            text: 'هنا يظهر جميع الطلاب المسجلين في هذه الدورة. يمكنك تغيير حالتهم (حاضر، غائب، متأخر) بضغطة واحدة.',
            attachTo: { element: '.table', on: 'bottom' },
            buttons: [{ text: 'التالي', action: tour.next, classes: 'shepherd-button-primary' }]
        });

        tour.addStep({
            id: 'qr-attendance',
            title: 'تحضير بالـ QR',
            text: 'بدلاً من التحضير اليدوي، يمكنك عرض رمز QR ليقوم الطلاب بمسحه وتسجيل حضورهم ذاتياً.',
            attachTo: { element: '.fa-qrcode', on: 'left' },
            buttons: [{ 
                text: 'إنهاء الجولة', 
                action: () => {
                    localStorage.removeItem('onboarding_tour_active'); 
                    tour.complete();
                    Toast.fire({ icon: 'success', title: 'مبروك! أصبحت الآن جاهزاً لاستخدام النظام بالكامل.' });
                }, 
                classes: 'shepherd-button-primary' 
            }]
        });
        @endif

        tour.start();
    }
</script>
@endif
