@extends('layouts.app-next')

@section('title', __('instructor::settings.title'))

@section('sidebar')
    @include('instructor::partials._sidebar-next', ['active' => 'settings'])
@endsection

@section('content')
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="card-header bg-white border-0 p-4 pb-0">
                    <ul class="nav nav-tabs border-0 bg-light p-1 rounded-pill" id="settingsTabs" role="tablist">
                        <li class="nav-item m-0" role="presentation">
                            <button class="nav-link active rounded-pill fw-bold" id="general-tab" data-bs-toggle="tab" data-bs-target="#general" type="button" role="tab">
                                <i class="fas fa-info-circle me-2"></i> {{ __('instructor::settings.general_data') }}
                            </button>
                        </li>
                        <li class="nav-item m-0" role="presentation">
                            <button class="nav-link rounded-pill fw-bold" id="whatsapp-tab" data-bs-toggle="tab" data-bs-target="#whatsapp" type="button" role="tab">
                                <i class="fab fa-whatsapp me-2"></i> {{ __('instructor::settings.whatsapp_settings') }}
                            </button>
                        </li>
                        <li class="nav-item m-0" role="presentation">
                            <button class="nav-link rounded-pill fw-bold" id="email-tab" data-bs-toggle="tab" data-bs-target="#email" type="button" role="tab">
                                <i class="fas fa-envelope me-2"></i> البريد الإلكتروني
                            </button>
                        </li>
                        <li class="nav-item m-0" role="presentation">
                            <button class="nav-link rounded-pill fw-bold" id="reminders-tab" data-bs-toggle="tab" data-bs-target="#reminders" type="button" role="tab">
                                <i class="fas fa-bell me-2"></i> تذكيرات الدفع
                            </button>
                        </li>
                        <li class="nav-item m-0" role="presentation">
                            <button class="nav-link rounded-pill fw-bold" id="subscription-tab" data-bs-toggle="tab" data-bs-target="#subscription" type="button" role="tab">
                                <i class="fas fa-credit-card me-2"></i> {{ __('instructor::settings.platform_subscription') }}
                            </button>
                        </li>
                    </ul>
                </div>
                <div class="card-body p-4">
                    <div class="tab-content" id="settingsTabsContent">
                        
                        {{-- Tab 1: General Settings --}}
                        @include('instructor::settings_partials._tab-general')
                        @include('instructor::settings_partials._tab-whatsapp')
                        @include('instructor::settings_partials._tab-email')
                        @include('instructor::settings_partials._tab-reminders')
                        @include('instructor::settings_partials._tab-subscription')
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
    .nav-tabs .nav-link {
        color: #64748b;
        background: #f8fafc;
        border-bottom: 2px solid transparent !important;
        transition: all 0.3s;
    }
    .nav-tabs .nav-link:hover {
        background: #f1f5f9;
        color: var(--primary-color);
    }
    .nav-tabs .nav-link.active {
        background: white !important;
        color: var(--primary-color) !important;
        border-bottom: 3px solid var(--primary-color) !important;
    }
    .form-control:focus {
        background: white !important;
        box-shadow: 0 0 0 4px rgba(58, 12, 163, 0.1);
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
        border-color: var(--primary-color, #3A0CA3) !important;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(58, 12, 163, 0.15);
    }
    .preset-card.active-preset {
        border-color: var(--primary-color, #3A0CA3) !important;
        border-width: 2px;
        background: #f0f0ff !important;
    }
    .bg-primary-soft { background-color: rgba(58, 12, 163, 0.05) !important; }
    .border-dashed { border-style: dashed !important; }
    .italic { font-style: italic; }
    .var-btn:hover {
        background-color: var(--primary-color) !important;
        color: white !important;
        border-color: var(--primary-color) !important;
    }
    #preview-body span.text-primary {
        background: rgba(58, 12, 163, 0.1);
        padding: 0 4px;
        border-radius: 4px;
    }
    .accordion-button:not(.collapsed) {
        background-color: #f8f9ff !important;
        color: var(--primary-color) !important;
        box-shadow: none;
    }
    .accordion-button:focus { box-shadow: none; }
    .accordion-item { border-color: #e2e8f0 !important; }
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
        const activeField = document.activeElement;
        let isGuardian = activeField && activeField.id.includes('guardian');
        
        let subject = document.getElementById(isGuardian ? 'guardian_subject' : 'student_subject').value;
        let body = document.getElementById(isGuardian ? 'guardian_body' : 'student_body').value;

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
