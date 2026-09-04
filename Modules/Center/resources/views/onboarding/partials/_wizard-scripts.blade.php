    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('onboardingWizard', (initialStatus) => ({
                currentStep: (new URLSearchParams(window.location.search).get('step')) || (initialStatus === 'pending' ? 'welcome' : initialStatus),
                loading: false,
                init() {
                    this.initWizard();
                },
                
                steps: [
                    { id: 'step_1', label: '{{ __('onboarding.steps.step_1') }}' },
                    { id: 'step_2', label: '{{ __('onboarding.steps.step_2') }}' },
                    { id: 'step_3', label: '{{ __('onboarding.steps.step_3') }}' },
                    { id: 'step_4', label: '{{ __('onboarding.steps.step_4') }}' }
                ],
                
                formData: {
                    step_1: { 
                        locale: '{{ app()->getLocale() }}', 
                        currency: '{{ $tenant->settings['currency'] ?? session('suggested_currency', 'EGP') }}',
                        education_system: '{{ $tenant->settings['education_system'] ?? 'egyptian_national' }}'
                    },
                    step_2: { instructors: [{ instructor_name: '', instructor_phone: '', instructor_specialization: '', instructor_email: '', commission_type: 'percentage', commission_rate: '0' }] },
                    step_3: { courses: [{ instructor_index: '0', course_name: '', price: '', sessions_count: '1', schedules: [{day: '0', time: '16:00', time_end: '18:00'}] }] },
                    step_4: { students: [{ student_name: '', student_email: '', student_phone: '', parent_name: '', parent_phone: '', parent_email: '', grade_id: '', enroll_course_indices: [] }] }
                },
                
                syncSchedules(count) {
                    const n = parseInt(count) || 0;
                    if (n < 1) return;
                    const finalCount = Math.min(n, 12);
                    const currentCount = this.formData.step_3.courses[0].schedules.length;
                    if (finalCount > currentCount) {
                        for (let i = 0; i < (finalCount - currentCount); i++) {
                            this.formData.step_3.courses[0].schedules.push({day: '0', time: '16:00', time_end: '18:00'});
                        }
                    } else if (finalCount < currentCount) {
                        this.formData.step_3.courses[0].schedules.splice(finalCount);
                    }
                },

                toggleCourseEnroll(student, idx) {
                    if (!Array.isArray(student.enroll_course_indices)) {
                        student.enroll_course_indices = [];
                    }
                    const pos = student.enroll_course_indices.indexOf(idx);
                    if (pos === -1) {
                        student.enroll_course_indices.push(idx);
                    } else {
                        student.enroll_course_indices.splice(pos, 1);
                    }
                },

                get currentStepIndex() {
                    return this.steps.findIndex(s => s.id === this.currentStep);
                },

                prevStep() {
                    const currentIndex = this.currentStepIndex;
                    if (currentIndex > 0) {
                        this.currentStep = this.steps[currentIndex - 1].id;
                        this.updateUrl();
                    }
                },

                updateUrl() {
                    const url = new URL(window.location.href);
                    url.searchParams.set('step', this.currentStep);
                    window.history.pushState({}, '', url);
                },

                initWizard() {
                    const savedInstructors = @json($existingInstructors ?? []);
                    if (savedInstructors.length > 0) {
                        this.formData.step_2.instructors = savedInstructors;
                    }

                    const savedCourses = @json($existingCourses ?? []);
                    if (savedCourses.length > 0) {
                        this.formData.step_3.courses = savedCourses;
                    }

                    const savedStudents = @json($existingStudents ?? []);
                    if (savedStudents.length > 0) {
                        this.formData.step_4.students = savedStudents;
                    }

                    if (!this.steps.find(s => s.id === this.currentStep)) {
                        this.currentStep = 'step_1';
                    }
                },
                
                async updateLanguage(locale) {
                    if (this.loading) return;
                    this.loading = true;
                    try {
                        const response = await fetch('{{ route('center.onboarding.update-locale') }}', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                            body: JSON.stringify({ locale: locale })
                        });
                        if (response.ok) {
                            window.location.reload();
                        }
                    } catch (error) { console.error(error); } 
                    finally { this.loading = false; }
                },

                async submitStep(stepId, skip = false) {
                    if (this.loading) return;
                    this.loading = true;
                    try {
                        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                        const response = await fetch('{{ route('center.onboarding.submit') }}', {
                            method: 'POST',
                            headers: { 
                                'Content-Type': 'application/json', 
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': csrfToken
                            },
                            body: JSON.stringify({ step: stepId, skip: skip, ...this.formData[stepId] })
                        });
                        const data = await response.json();
                        if (!response.ok) throw new Error(data.message || 'Error');
                        if (data.redirect) {
                            window.location.href = data.redirect;
                        } else if (data.next_step) {
                            this.currentStep = data.next_step;
                            this.updateUrl();
                            window.scrollTo({ top: 0, behavior: 'smooth' });
                        }
                    } catch (error) {
                        let errorMessage = error.message;
                        let errorTitle = '{{ __('center::messages.onboarding_wizard.error_title') }}';
                        
                        if (errorMessage.includes('CSRF token mismatch') || errorMessage.includes('419')) {
                            errorMessage = '{{ app()->getLocale() === 'ar' ? 'انتهت مدة الجلسة بسبب عدم النشاط. يرجى تحديث الصفحة والمحاولة مرة أخرى.' : (app()->getLocale() === 'fr' ? 'La session a expiré pour cause d\'inactivité. Veuillez actualiser la page et réessayer.' : 'Session expired due to inactivity. Please refresh the page and try again.') }}';
                        }
                        
                        Swal.fire({ 
                            icon: 'error', 
                            title: errorTitle, 
                            text: errorMessage, 
                            confirmButtonColor: '#10b981',
                            confirmButtonText: '{{ __('center::messages.onboarding_wizard.ok') }}'
                        });
                    } finally { this.loading = false; }
                }
            }));
        });
    </script>
