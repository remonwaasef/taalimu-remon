@extends('layouts.app-next')

@section('title', __('instructor::settings.title'))

@section('sidebar')
    @include('instructor::partials._sidebar-next', ['active' => 'settings'])
@endsection

@php
    $tenant = app()->bound('tenant') ? app('tenant') : null;
    $hasLogo = !empty($tenant?->logo);
    $hasPhone = !empty($tenant?->phone);
    $hasWhatsApp = !empty($tenant?->settings['whatsapp_api_key'] ?? null);
    $hasAddress = !empty($tenant?->address);
@endphp

@section('content')
    <div x-data="{
        activeTab: (window.location.hash || '#general').replace('#', ''),
        switchTab(tab) {
            this.activeTab = tab;
            window.location.hash = tab;
        }
    }" x-init="
        window.addEventListener('hashchange', () => {
            activeTab = (window.location.hash || '#general').replace('#', '');
        });
        // Auto-activate tab from URL hash on load
        if (window.location.hash) {
            activeTab = window.location.hash.replace('#', '');
            // Also click the corresponding Bootstrap tab if it exists
            $nextTick(() => {
                const tabBtn = document.getElementById(activeTab + '-tab');
                if (tabBtn) tabBtn.click();
            });
        }
    ">
        <div class="row mb-4">
            <div class="col-12">
                {{-- Settings Header --}}
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3 mb-6">
                    <div>
                        <h1 class="text-xl font-extrabold text-slate-800 dark:text-slate-100 font-arabic">
                            <i class="fas fa-cog text-brand-primary me-2"></i>{{ __('instructor::settings.title') }}
                        </h1>
                        <p class="text-sm text-slate-400 mt-1 font-arabic">{{ __('instructor::settings.subtitle') }}</p>
                    </div>
                </div>

                <div class="row g-4">
                    {{-- LEFT: Vertical Navigation Tabs --}}
                    <div class="col-lg-3">
                        <div class="card border-0 shadow-sm rounded-3 overflow-hidden sticky-top" style="top: 90px;">
                            <div class="card-body p-2">
                                <nav class="d-flex flex-column gap-1">
                                    {{-- General --}}
                                    <button @click="switchTab('general')" type="button"
                                            :class="activeTab === 'general' ? 'bg-brand-50 text-brand-primary border-brand-primary dark:bg-brand-900/30' : 'text-slate-600 hover:bg-slate-50 dark:text-slate-300 dark:hover:bg-slate-800'"
                                            class="d-flex align-items-center gap-3 px-3 py-3 rounded-3 border-0 text-start transition-all w-100 cursor-pointer"
                                            style="border-inline-start: 3px solid transparent;"
                                            :style="activeTab === 'general' ? 'border-inline-start-color: var(--primary-color)' : ''">
                                        <div class="w-9 h-9 rounded-2 d-flex align-items-center justify-content-center flex-shrink-0"
                                             :class="activeTab === 'general' ? 'bg-brand-primary text-white' : 'bg-slate-100 text-slate-400 dark:bg-slate-700'">
                                            <i class="fas fa-sliders-h"></i>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <div class="text-xs fw-bold">{{ __('instructor::sidebar.settings_general') }}</div>
                                            <div class="text-xs text-slate-400 text-truncate">{{ __('instructor::sidebar.settings_general_desc') }}</div>
                                        </div>
                                        @if($hasLogo && $hasPhone)
                                            <span class="badge bg-success-subtle text-success rounded-pill px-2 py-1" style="font-size: 9px;">{{ __('instructor::sidebar.status_configured') }}</span>
                                        @else
                                            <span class="badge bg-warning-subtle text-warning rounded-pill px-2 py-1" style="font-size: 9px;">{{ __('instructor::sidebar.status_needs_setup') }}</span>
                                        @endif
                                    </button>

                                    {{-- WhatsApp --}}
                                    <button @click="switchTab('whatsapp')" type="button"
                                            :class="activeTab === 'whatsapp' ? 'bg-brand-50 text-brand-primary border-brand-primary dark:bg-brand-900/30' : 'text-slate-600 hover:bg-slate-50 dark:text-slate-300 dark:hover:bg-slate-800'"
                                            class="d-flex align-items-center gap-3 px-3 py-3 rounded-3 border-0 text-start transition-all w-100 cursor-pointer"
                                            style="border-inline-start: 3px solid transparent;"
                                            :style="activeTab === 'whatsapp' ? 'border-inline-start-color: var(--primary-color)' : ''">
                                        <div class="w-9 h-9 rounded-2 d-flex align-items-center justify-content-center flex-shrink-0"
                                             :class="activeTab === 'whatsapp' ? 'bg-emerald-500 text-white' : 'bg-slate-100 text-slate-400 dark:bg-slate-700'">
                                            <i class="fab fa-whatsapp"></i>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <div class="text-xs fw-bold">{{ __('instructor::sidebar.settings_whatsapp') }}</div>
                                            <div class="text-xs text-slate-400 text-truncate">{{ __('instructor::sidebar.settings_whatsapp_desc') }}</div>
                                        </div>
                                        @if($hasWhatsApp)
                                            <span class="badge bg-success-subtle text-success rounded-pill px-2 py-1" style="font-size: 9px;">{{ __('instructor::sidebar.status_active') }}</span>
                                        @else
                                            <span class="badge bg-secondary-subtle text-secondary rounded-pill px-2 py-1" style="font-size: 9px;">{{ __('instructor::sidebar.status_inactive') }}</span>
                                        @endif
                                    </button>

                                    {{-- Email Templates --}}
                                    <button @click="switchTab('email')" type="button"
                                            :class="activeTab === 'email' ? 'bg-brand-50 text-brand-primary border-brand-primary dark:bg-brand-900/30' : 'text-slate-600 hover:bg-slate-50 dark:text-slate-300 dark:hover:bg-slate-800'"
                                            class="d-flex align-items-center gap-3 px-3 py-3 rounded-3 border-0 text-start transition-all w-100 cursor-pointer"
                                            style="border-inline-start: 3px solid transparent;"
                                            :style="activeTab === 'email' ? 'border-inline-start-color: var(--primary-color)' : ''">
                                        <div class="w-9 h-9 rounded-2 d-flex align-items-center justify-content-center flex-shrink-0"
                                             :class="activeTab === 'email' ? 'bg-blue-500 text-white' : 'bg-slate-100 text-slate-400 dark:bg-slate-700'">
                                            <i class="fas fa-envelope"></i>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <div class="text-xs fw-bold">{{ __('instructor::sidebar.settings_email') }}</div>
                                            <div class="text-xs text-slate-400 text-truncate">{{ __('instructor::sidebar.settings_email_desc') }}</div>
                                        </div>
                                    </button>

                                    {{-- Payment Reminders --}}
                                    <button @click="switchTab('reminders')" type="button"
                                            :class="activeTab === 'reminders' ? 'bg-brand-50 text-brand-primary border-brand-primary dark:bg-brand-900/30' : 'text-slate-600 hover:bg-slate-50 dark:text-slate-300 dark:hover:bg-slate-800'"
                                            class="d-flex align-items-center gap-3 px-3 py-3 rounded-3 border-0 text-start transition-all w-100 cursor-pointer"
                                            style="border-inline-start: 3px solid transparent;"
                                            :style="activeTab === 'reminders' ? 'border-inline-start-color: var(--primary-color)' : ''">
                                        <div class="w-9 h-9 rounded-2 d-flex align-items-center justify-content-center flex-shrink-0"
                                             :class="activeTab === 'reminders' ? 'bg-amber-500 text-white' : 'bg-slate-100 text-slate-400 dark:bg-slate-700'">
                                            <i class="fas fa-bell"></i>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <div class="text-xs fw-bold">{{ __('instructor::sidebar.settings_reminders') }}</div>
                                            <div class="text-xs text-slate-400 text-truncate">{{ __('instructor::sidebar.settings_reminders_desc') }}</div>
                                        </div>
                                    </button>

                                    {{-- Subscription --}}
                                    <button @click="switchTab('subscription')" type="button"
                                            :class="activeTab === 'subscription' ? 'bg-brand-50 text-brand-primary border-brand-primary dark:bg-brand-900/30' : 'text-slate-600 hover:bg-slate-50 dark:text-slate-300 dark:hover:bg-slate-800'"
                                            class="d-flex align-items-center gap-3 px-3 py-3 rounded-3 border-0 text-start transition-all w-100 cursor-pointer"
                                            style="border-inline-start: 3px solid transparent;"
                                            :style="activeTab === 'subscription' ? 'border-inline-start-color: var(--primary-color)' : ''">
                                        <div class="w-9 h-9 rounded-2 d-flex align-items-center justify-content-center flex-shrink-0"
                                             :class="activeTab === 'subscription' ? 'bg-purple-500 text-white' : 'bg-slate-100 text-slate-400 dark:bg-slate-700'">
                                            <i class="fas fa-credit-card"></i>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <div class="text-xs fw-bold">{{ __('instructor::sidebar.settings_subscription') }}</div>
                                            <div class="text-xs text-slate-400 text-truncate">{{ __('instructor::sidebar.settings_subscription_desc') }}</div>
                                        </div>
                                    </button>
                                </nav>
                            </div>
                        </div>
                    </div>

                    {{-- RIGHT: Tab Content --}}
                    <div class="col-lg-9">
                        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                            <div class="card-body p-4">
                                {{-- General Tab --}}
                                <div x-show="activeTab === 'general'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0">
                                    @include('instructor::settings_partials._tab-general')
                                </div>

                                {{-- WhatsApp Tab --}}
                                <div x-show="activeTab === 'whatsapp'" x-cloak x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0">
                                    @include('instructor::settings_partials._tab-whatsapp')
                                </div>

                                {{-- Email Tab --}}
                                <div x-show="activeTab === 'email'" x-cloak x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0">
                                    @include('instructor::settings_partials._tab-email')
                                </div>

                                {{-- Reminders Tab --}}
                                <div x-show="activeTab === 'reminders'" x-cloak x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0">
                                    @include('instructor::settings_partials._tab-reminders')
                                </div>

                                {{-- Subscription Tab --}}
                                <div x-show="activeTab === 'subscription'" x-cloak x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0">
                                    @include('instructor::settings_partials._tab-subscription')
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<style>
    .animate-fade-in {
        animation: fadeIn 0.3s ease-in-out;
    }
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(5px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .form-control:focus {
        background: white !important;
        box-shadow: 0 0 0 4px rgba(46, 139, 131, 0.1);
    }
    .custom-switch .form-check-input {
        width: 3rem;
        height: 1.5rem;
    }
    .custom-switch .form-check-input:checked {
        background-color: #22c55e;
        border-color: #22c55e;
    }
    .x-small { font-size: 0.75rem; }
    .preset-card:hover {
        border-color: var(--primary-color, #2E8B83) !important;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(46, 139, 131, 0.15);
    }
    .preset-card.active-preset {
        border-color: var(--primary-color, #2E8B83) !important;
        border-width: 2px;
        background: #f0fdf4 !important;
    }
    .bg-primary-soft { background-color: rgba(46, 139, 131, 0.05) !important; }
    .border-dashed { border-style: dashed !important; }
    .italic { font-style: italic; }
    .var-btn:hover {
        background-color: var(--primary-color) !important;
        color: white !important;
        border-color: var(--primary-color) !important;
    }
    #preview-body span.text-primary {
        background: rgba(46, 139, 131, 0.1);
        padding: 0 4px;
        border-radius: 4px;
    }
    .accordion-button:not(.collapsed) {
        background-color: #f0fdf4 !important;
        color: var(--primary-color) !important;
        box-shadow: none;
    }
    .accordion-button:focus { box-shadow: none; }
    .accordion-item { border-color: #e2e8f0 !important; }

    /* Vertical tabs responsive: stack on mobile */
    @media (max-width: 991.98px) {
        .col-lg-3 .sticky-top { position: relative !important; top: auto !important; }
        .col-lg-3 nav { flex-direction: row !important; overflow-x: auto; gap: 0.25rem !important; padding-bottom: 0.5rem; }
        .col-lg-3 nav button { min-width: max-content; padding: 0.5rem 0.75rem !important; }
        .col-lg-3 nav button .flex-1 .text-truncate { display: none; }
    }
</style>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const previewSubject = document.getElementById('preview-subject');
    const previewBody = document.getElementById('preview-body');
    const templateInputs = document.querySelectorAll('.template-input');
    const varBtns = document.querySelectorAll('.var-btn');
    const presetCards = document.querySelectorAll('.preset-card');
    
    const sampleData = {
        '{اسم_الطالب}': 'أحمد محمد علي',
        '{اسم_المركز}': 'أكاديمية التعليم',
        '{رابط_الدخول}': 'https://taalimu.com/login',
        '{كلمة_المرور}': '123456',
        '{رقم_الهاتف}': '01012345678',
        '{اسم_ولي_الأمر}': 'أستاذ محمد علي',
        '{المرحلة}': 'الصف الأول الثانوي'
    };

    function updatePreview() {
        if (!previewSubject || !previewBody) return;
        const activeField = document.activeElement;
        let isGuardian = activeField && activeField.id.includes('guardian');
        
        let subject = document.getElementById(isGuardian ? 'guardian_subject' : 'student_subject')?.value || '';
        let body = document.getElementById(isGuardian ? 'guardian_body' : 'student_body')?.value || '';

        Object.keys(sampleData).forEach(key => {
            const regex = new RegExp(key.replace(/[.*+?^${}()|[\]\\]/g, '\\$&'), 'g');
            subject = subject.replace(regex, `<span class="text-primary">${sampleData[key]}</span>`);
            body = body.replace(regex, `<span class="text-primary">${sampleData[key]}</span>`);
        });

        previewSubject.innerHTML = subject || '<span class="text-muted italic">بدون عنوان...</span>';
        previewBody.innerHTML = body || '<span class="text-muted italic">اكتب نص الرسالة لتظهر المعاينة هنا...</span>';
    }

    templateInputs.forEach(input => {
        input.addEventListener('input', updatePreview);
        input.addEventListener('focus', updatePreview);
    });

    varBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const targetId = this.dataset.target;
            const variable = this.dataset.var;
            const textarea = document.getElementById(targetId);
            if (!textarea) return;
            
            const start = textarea.selectionStart;
            const end = textarea.selectionEnd;
            const text = textarea.value;
            
            textarea.value = text.substring(0, start) + variable + text.substring(end);
            textarea.focus();
            textarea.selectionStart = textarea.selectionEnd = start + variable.length;
            
            updatePreview();
        });
    });

    const presets = @json(config('email_templates.presets', []));
    
    presetCards.forEach(card => {
        card.addEventListener('click', function() {
            const presetKey = this.dataset.preset;
            const preset = presets[presetKey];
            
            if (preset) {
                document.getElementById('student_subject').value = preset.student_subject;
                document.getElementById('student_body').value = preset.student_body;
                document.getElementById('guardian_subject').value = preset.guardian_subject;
                document.getElementById('guardian_body').value = preset.guardian_body;
                
                presetCards.forEach(c => c.classList.remove('active-preset'));
                this.classList.add('active-preset');
                
                updatePreview();
            }
        });
    });

    updatePreview();
});
</script>
@endpush
@endsection
