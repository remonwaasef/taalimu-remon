@if(auth()->check() && app()->bound('tenant') && !app('tenant')->onboarding_completed_at && auth()->user()->role === 'center_admin')
    
    <!-- Shepherd.js -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/shepherd.js@10.0.1/dist/css/shepherd.css"/>
    <script src="https://cdn.jsdelivr.net/npm/shepherd.js@10.0.1/dist/js/shepherd.min.js"></script>

    <style>
        .shepherd-element {
            z-index: 9999 !important;
        }
        .shepherd-theme-custom .shepherd-content {
            border-radius: 12px;
            box-shadow: 0 10px 40px -10px rgba(0,0,0,0.2);
            font-family: 'Tajawal', sans-serif;
        }
        .shepherd-theme-custom .shepherd-text {
            padding: 1.5rem;
            font-size: 0.95rem;
            line-height: 1.6;
        }
        .shepherd-theme-custom .shepherd-header {
            background: #f8fafc;
            border-bottom: 1px solid #e2e8f0;
            padding: 1rem 1.5rem;
            border-top-left-radius: 12px;
            border-top-right-radius: 12px;
        }
        .shepherd-theme-custom .shepherd-title {
            font-size: 1.1rem;
            font-weight: 700;
            color: #333;
        }
        .shepherd-theme-custom .shepherd-footer {
            padding: 0 1.5rem 1.5rem;
        }
        .btn-tour-primary {
            background: #435ebe;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
        }
        .btn-tour-primary:hover {
            background: #324699;
            transform: translateY(-1px);
        }
        .btn-tour-secondary {
            background: transparent;
            color: #64748b;
            border: 1px solid #cbd5e1;
            padding: 10px 20px;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
            margin-right: 10px;
        }
        .btn-tour-secondary:hover {
            background: #f1f5f9;
        }
        [dir="rtl"] .btn-tour-secondary {
            margin-right: 0;
            margin-left: 10px;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const tour = new Shepherd.Tour({
                useModalOverlay: true,
                defaultStepOptions: {
                    classes: 'shepherd-theme-custom',
                    scrollTo: { behavior: 'smooth', block: 'center' },
                    cancelIcon: { enabled: true }
                }
            });

            // Helper to get step from URL or localStorage
            const urlParams = new URLSearchParams(window.location.search);
            let currentTourStep = parseInt(urlParams.get('onboarding_step')) || parseInt(localStorage.getItem('onboarding_step')) || 1;
            
            // Save state
            localStorage.setItem('onboarding_step', currentTourStep);

            const isRtl = document.documentElement.dir === 'rtl';
            const nextLabel = isRtl ? 'التالي' : 'Next';
            const backLabel = isRtl ? 'السابق' : 'Back';
            const finishLabel = isRtl ? 'إنهاء' : 'Finish';
            const startLabel = isRtl ? 'ابدأ الجولة' : 'Start Tour';
            const settingsLabel = isRtl ? 'اذهب للإعدادات' : 'Go to Settings';
            const fillLabel = isRtl ? 'حفظ ومتابعة' : 'Save & Continue';

            // Define Steps logic based on Routes
            const route = "{{ Route::currentRouteName() }}";
            
            // STEP 1: DASHBOARD
            if (route === 'center.dashboard' || route === 'center.dashboard.alt') {
                if (currentTourStep === 1) {
                    tour.addStep({
                        id: 'intro',
                        title: isRtl ? 'مرحباً بك في منصتك التعليمية! 🚀' : 'Welcome to your Platform! 🚀',
                        text: isRtl 
                            ? 'دعنا نأخذك في جولة سريعة لإعداد مركزك. سنقوم بضبط الإعدادات، إضافة المدرسين، وإنشاء أول دورة.'
                            : 'Let\'s take a quick tour to set up your center. We will configure settings, add instructors, and create your first course.',
                        buttons: [
                            {
                                text: settingsLabel,
                                classes: 'btn-tour-primary',
                                action: function() {
                                    window.location.href = "{{ route('center.settings.index', ['tenant' => $tenant->domain]) }}?onboarding_step=2";
                                }
                            }
                        ]
                    });
                    tour.start();
                } else if (currentTourStep > 5) {
                   // Complete!
                   completeOnboarding();
                }
            }

            // STEP 2: SETTINGS (Profile + System)
            if (route === 'center.settings.index' && currentTourStep === 2) {
                // Highlight Settings Form
                tour.addStep({
                    id: 'settings-intro',
                    title: isRtl ? 'إعدادات المركز' : 'Center Settings',
                    text: isRtl 
                        ? 'هنا يمكنك تعديل اسم المركز، الشعار، والنظام التعليمي. قم بملء البيانات ثم اضغط حفظ.'
                        : 'Here you can edit your center name, logo, and academic system. Fill in the data and click save.',
                    attachTo: { element: '#general form', on: 'bottom' },
                    buttons: [
                        {
                            text: nextLabel,
                            classes: 'btn-tour-primary',
                            action: function() {
                                localStorage.setItem('onboarding_step', 3);
                                window.location.href = "{{ route('center.instructors.create', ['tenant' => $tenant->domain]) }}?onboarding_step=3";
                            }
                        }
                    ]
                });

                // Highlight Save Button
                tour.addStep({
                    id: 'settings-save',
                    title: isRtl ? 'حفظ ومتابعة' : 'Save & Continue',
                    text: isRtl
                        ? 'بعد الانتهاء، اضغط حفظ. سننقلك تلقائياً للخطوة التالية.'
                        : 'After finishing, click save. We will automatically take you to the next step.',
                    attachTo: { element: 'button[type="submit"]', on: 'top' },
                    buttons: []
                });

                tour.start();

                // Intercept Form Submit to redirect to next step
                document.querySelector('#general form')?.addEventListener('submit', function(e) {
                     localStorage.setItem('onboarding_step', 3);
                     // Allow form to submit normally
                });

                // Auto-advance if success message is present
                if (document.querySelector('.alert-success')) {
                     localStorage.setItem('onboarding_step', 3);
                     window.location.href = "{{ route('center.instructors.create', ['tenant' => $tenant->domain]) }}?onboarding_step=3";
                }
            }

            // STEP 3: INSTRUCTORS
            // If user is on index page (after save), redirect to next step
            if (route === 'center.instructors.index' && currentTourStep === 3) {
                 localStorage.setItem('onboarding_step', 4);
                 window.location.href = "{{ route('center.courses.create', ['tenant' => $tenant->domain]) }}?onboarding_step=4";
            }
            if (route === 'center.instructors.create' && currentTourStep === 3) {
                tour.addStep({
                    id: 'instructor-intro',
                    title: isRtl ? 'إضافة مدرس' : 'Add Instructor',
                    text: isRtl
                        ? 'الخطوة التالية هي إضافة كادر التدريس. المدرس هو أساس العملية التعليمية.'
                        : 'Next step is adding teaching staff. Instructors are the core of the educational process.',
                    attachTo: { element: 'form', on: 'right' },
                    buttons: [
                        {
                            text: nextLabel,
                            classes: 'btn-tour-primary',
                            action: function() {
                                localStorage.setItem('onboarding_step', 4);
                                window.location.href = "{{ route('center.courses.create', ['tenant' => $tenant->domain]) }}?onboarding_step=4";
                            }
                        }
                    ]
                });
                
                tour.start();

                document.querySelector('form')?.addEventListener('submit', function() {
                    localStorage.setItem('onboarding_step', 4);
                });
            }

            // STEP 4: COURSES
            // If user is on index page (after save), redirect to next step
            if (route === 'center.courses.index' && currentTourStep === 4) {
                 localStorage.setItem('onboarding_step', 5);
                 window.location.href = "{{ route('center.students.create', ['tenant' => $tenant->domain]) }}?onboarding_step=5";
            }
            if (route === 'center.courses.create' && currentTourStep === 4) {
                 tour.addStep({
                    id: 'course-intro',
                    title: isRtl ? 'إنشاء دورة' : 'Create Course',
                    text: isRtl
                        ? 'الآن أنشئ دورتك الأولى. اربطها بالمدرس الذي أضفته للتو.'
                        : 'Now create your first course. Link it to the instructor you just added.',
                    attachTo: { element: 'form', on: 'left' },
                     buttons: [
                        {
                            text: nextLabel,
                            classes: 'btn-tour-primary',
                            action: function() {
                                localStorage.setItem('onboarding_step', 5);
                                window.location.href = "{{ route('center.students.create', ['tenant' => $tenant->domain]) }}?onboarding_step=5";
                            }
                        }
                    ]
                });
                
                tour.start();

                document.querySelector('form')?.addEventListener('submit', function() {
                    localStorage.setItem('onboarding_step', 5);
                });
            }

            // If they land on courses index and step is 5, redirect to students create
            if (route === 'center.courses.index' && currentTourStep === 5) {
                 window.location.href = "{{ route('center.students.create', ['tenant' => $tenant->domain]) }}?onboarding_step=5";
            }

            // STEP 5: CREATE STUDENT
            if (route === 'center.students.create' && currentTourStep === 5) {
                 tour.addStep({
                    id: 'student-intro',
                    title: isRtl ? 'إضافة طالب' : 'Add Student',
                    text: isRtl
                        ? 'أخيراً، أضف طالباً وسجله في الدورة. سيحصل الطالب على QR Code للحضور.'
                        : 'Finally, add a student and enroll them in the course. They will get a QR Code for attendance.',
                    attachTo: { element: 'form', on: 'right' },
                     buttons: [
                        {
                            text: nextLabel,
                            classes: 'btn-tour-primary',
                            action: finishOnboarding
                        }
                    ]
                });
                
                tour.start();

                document.querySelector('form')?.addEventListener('submit', function() {
                    localStorage.setItem('onboarding_step', 6);
                });
            }

            // If they land on students index and step is 6, finish!
            if ((route === 'center.students.index' || route === 'center.students.show') && currentTourStep === 6) {
                 completeOnboarding();
            }

            function completeOnboarding() {
                // Show completion toast/modal
                 tour.addStep({
                    id: 'completed',
                    title: isRtl ? '🎉 تهانينا! أنت جاهز' : '🎉 Congratulations! You are ready',
                    text: isRtl
                        ? 'لقد أكملت الإعداد الأساسي بنجاح. يمكنك الآن استخدام النظام بالكامل.'
                        : 'You have successfully completed the basic setup. You can now use the full system.',
                    buttons: [
                        {
                            text: finishLabel,
                            classes: 'btn-tour-primary',
                            action: function() {
                                // Mark as complete on server
                                fetch("{{ route('center.onboarding.complete', ['tenant' => $tenant->domain]) }}", {
                                    method: 'POST',
                                    headers: {
                                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                        'Accept': 'application/json'
                                    }
                                }).then(() => {
                                    localStorage.removeItem('onboarding_step');
                                    window.location.href = "{{ route('center.dashboard', ['tenant' => $tenant->domain]) }}";
                                });
                            }
                        }
                    ]
                });
                tour.start();
            }

        });
    </script>
@endif
