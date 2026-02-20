<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ __('onboarding.title') }} — {{ $tenant->name }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Tajawal:wght@300;400;500;700;800;900&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #6366f1;
            --primary-dark: #4f46e5;
            --primary-light: #e0e7ff;
            --success: #10b981;
            --success-light: #d1fae5;
            --bg-main: #f8fafc;
            --card-bg: #ffffff;
            --text-primary: #1e293b;
            --text-secondary: #64748b;
            --text-muted: #94a3b8;
            --border: #e2e8f0;
            --shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
            --shadow-lg: 0 20px 60px -12px rgb(0 0 0 / 0.15);
        }

        * { box-sizing: border-box; }

        body {
            font-family: 'Tajawal', 'Inter', system-ui, sans-serif;
            background: var(--bg-main);
            color: var(--text-primary);
            margin: 0;
            min-height: 100vh;
            overflow-x: hidden;
        }

        /* ===== BACKGROUND DECORATION ===== */
        .bg-decoration {
            position: fixed;
            inset: 0;
            z-index: 0;
            pointer-events: none;
            overflow: hidden;
        }
        .bg-decoration::before {
            content: '';
            position: absolute;
            top: -20%;
            right: -10%;
            width: 600px;
            height: 600px;
            background: radial-gradient(circle, rgba(99, 102, 241, 0.08) 0%, transparent 70%);
            border-radius: 50%;
        }
        .bg-decoration::after {
            content: '';
            position: absolute;
            bottom: -20%;
            left: -10%;
            width: 500px;
            height: 500px;
            background: radial-gradient(circle, rgba(16, 185, 129, 0.06) 0%, transparent 70%);
            border-radius: 50%;
        }

        /* ===== WIZARD CONTAINER ===== */
        .wizard-wrapper {
            position: relative;
            z-index: 1;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* ===== TOP HEADER ===== */
        .wizard-header {
            background: linear-gradient(135deg, var(--primary) 0%, #7c3aed 50%, #a855f7 100%);
            color: white;
            padding: 24px 0 40px;
            position: relative;
            overflow: hidden;
        }
        .wizard-header::after {
            content: '';
            position: absolute;
            bottom: -1px;
            left: 0;
            right: 0;
            height: 30px;
            background: var(--bg-main);
            border-radius: 30px 30px 0 0;
        }
        .wizard-header .brand {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 20px;
        }
        .wizard-header .brand img {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            object-fit: contain;
            background: rgba(255,255,255,0.15);
            padding: 4px;
        }
        .wizard-header .brand h1 {
            font-size: 1.1rem;
            font-weight: 700;
            margin: 0;
            opacity: 0.95;
        }

        /* ===== PROGRESS STEPS ===== */
        .progress-steps {
            display: flex;
            justify-content: center;
            gap: 0;
            position: relative;
            max-width: 700px;
            margin: 0 auto;
        }
        .step-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            position: relative;
            flex: 1;
            cursor: default;
        }
        .step-item:not(:last-child)::after {
            content: '';
            position: absolute;
            top: 18px;
            left: 50%;
            width: 100%;
            height: 3px;
            background: rgba(255,255,255,0.2);
            z-index: 0;
        }
        html[dir="rtl"] .step-item:not(:last-child)::after {
            left: auto;
            right: 50%;
        }
        .step-item.completed:not(:last-child)::after {
            background: rgba(255,255,255,0.7);
        }
        .step-circle {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 0.85rem;
            position: relative;
            z-index: 1;
            transition: all 0.4s cubic-bezier(.4,0,.2,1);
            background: rgba(255,255,255,0.15);
            color: rgba(255,255,255,0.5);
            border: 2px solid rgba(255,255,255,0.2);
        }
        .step-item.active .step-circle {
            background: white;
            color: var(--primary);
            border-color: white;
            box-shadow: 0 0 0 4px rgba(255,255,255,0.3);
            transform: scale(1.15);
        }
        .step-item.completed .step-circle {
            background: rgba(255,255,255,0.85);
            color: var(--success);
            border-color: rgba(255,255,255,0.85);
        }
        .step-label {
            font-size: 0.7rem;
            font-weight: 600;
            margin-top: 8px;
            white-space: nowrap;
            opacity: 0.5;
            transition: opacity 0.3s;
        }
        .step-item.active .step-label,
        .step-item.completed .step-label {
            opacity: 1;
        }

        /* ===== MAIN CONTENT ===== */
        .wizard-body {
            flex: 1;
            display: flex;
            align-items: flex-start;
            justify-content: center;
            padding: 0 16px 40px;
        }
        .wizard-card {
            background: var(--card-bg);
            border-radius: 20px;
            box-shadow: var(--shadow-lg);
            width: 100%;
            max-width: 680px;
            margin-top: -10px;
            overflow: hidden;
        }
        .wizard-card-body {
            padding: 40px;
        }

        /* ===== STEP PANELS ===== */
        .step-panel {
            display: none;
            animation: fadeSlideIn 0.4s ease-out;
        }
        .step-panel.active {
            display: block;
        }
        @keyframes fadeSlideIn {
            from { opacity: 0; transform: translateY(15px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .step-title {
            font-size: 1.5rem;
            font-weight: 800;
            margin-bottom: 6px;
            color: var(--text-primary);
        }
        .step-subtitle {
            font-size: 0.95rem;
            color: var(--text-secondary);
            margin-bottom: 30px;
            line-height: 1.6;
        }

        /* ===== FORM STYLES ===== */
        .form-floating {
            margin-bottom: 16px;
        }
        .form-floating > .form-control,
        .form-floating > .form-select {
            border: 2px solid var(--border);
            border-radius: 14px;
            padding: 16px 16px 8px;
            height: 56px;
            font-size: 0.95rem;
            transition: border-color 0.2s, box-shadow 0.2s;
        }
        .form-floating > .form-control:focus,
        .form-floating > .form-select:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1);
        }
        .form-floating > label {
            padding: 16px;
            font-weight: 500;
            color: var(--text-muted);
        }

        /* ===== TEMPLATE CARDS (Step 2) ===== */
        .template-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
            gap: 14px;
            margin-bottom: 20px;
        }
        .template-card {
            background: var(--bg-main);
            border: 2px solid var(--border);
            border-radius: 16px;
            padding: 20px 16px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
        }
        .template-card:hover {
            border-color: var(--primary);
            transform: translateY(-3px);
            box-shadow: var(--shadow);
        }
        .template-card.selected {
            border-color: var(--primary);
            background: var(--primary-light);
        }
        .template-card.selected::after {
            content: '\f058';
            font-family: 'Font Awesome 6 Free';
            font-weight: 900;
            position: absolute;
            top: 10px;
            left: 10px;
            color: var(--primary);
            font-size: 1.1rem;
        }
        html[dir="rtl"] .template-card.selected::after {
            left: auto;
            right: 10px;
        }
        .template-card .icon {
            font-size: 2.2rem;
            margin-bottom: 10px;
            display: block;
        }
        .template-card .title {
            font-weight: 700;
            font-size: 0.85rem;
            margin-bottom: 4px;
            color: var(--text-primary);
        }
        .template-card .desc {
            font-size: 0.72rem;
            color: var(--text-muted);
        }

        /* ===== BUTTONS ===== */
        .btn-wizard {
            padding: 14px 36px;
            border-radius: 14px;
            font-weight: 700;
            font-size: 1rem;
            border: none;
            transition: all 0.3s ease;
        }
        .btn-wizard-primary {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: white;
            box-shadow: 0 4px 14px rgba(99, 102, 241, 0.35);
        }
        .btn-wizard-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(99, 102, 241, 0.45);
            color: white;
        }
        .btn-wizard-primary:disabled {
            opacity: 0.6;
            transform: none;
            box-shadow: none;
            cursor: not-allowed;
        }
        .btn-wizard-outline {
            background: transparent;
            border: 2px solid var(--border);
            color: var(--text-secondary);
        }
        .btn-wizard-outline:hover {
            border-color: var(--text-secondary);
            background: var(--bg-main);
        }

        .wizard-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 24px 40px;
            border-top: 1px solid var(--border);
            background: #fafbfc;
            border-radius: 0 0 20px 20px;
        }

        /* ===== SUMMARY STEP (Step 6) ===== */
        .summary-stat {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 14px;
            background: var(--bg-main);
            border-radius: 14px;
            margin-bottom: 10px;
        }
        .summary-stat .icon-box {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            flex-shrink: 0;
        }
        .summary-stat h6 {
            margin: 0;
            font-weight: 700;
            font-size: 0.95rem;
        }
        .summary-stat small {
            color: var(--text-muted);
        }

        .tutorial-step {
            display: flex;
            align-items: center;
            gap: 16px;
            padding: 16px 0;
            border-bottom: 1px solid var(--border);
        }
        .tutorial-step:last-child { border-bottom: none; }
        .tutorial-num {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: var(--primary-light);
            color: var(--primary);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            font-size: 1rem;
            flex-shrink: 0;
        }

        /* ===== LOADING SPINNER ===== */
        .btn-loading {
            position: relative;
            pointer-events: none;
        }
        .btn-loading::after {
            content: '';
            position: absolute;
            width: 20px;
            height: 20px;
            border: 3px solid rgba(255,255,255,0.3);
            border-top-color: white;
            border-radius: 50%;
            animation: spin 0.7s linear infinite;
            inset-inline-end: 16px;
            top: 50%;
            transform: translateY(-50%);
        }
        @keyframes spin { to { transform: translateY(-50%) rotate(360deg); } }

        /* ===== ALERT ===== */
        .wizard-alert {
            border-radius: 12px;
            padding: 14px 18px;
            font-size: 0.9rem;
            border: none;
            font-weight: 500;
        }

        /* ===== LOGO UPLOAD ===== */
        .logo-upload-area {
            width: 100px;
            height: 100px;
            border-radius: 18px;
            border: 3px dashed var(--border);
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s;
            overflow: hidden;
            margin: 0 auto 20px;
            background: var(--bg-main);
        }
        .logo-upload-area:hover {
            border-color: var(--primary);
            background: var(--primary-light);
        }
        .logo-upload-area img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .logo-upload-area i {
            font-size: 1.8rem;
            color: var(--text-muted);
        }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 576px) {
            .wizard-card-body { padding: 24px 20px; }
            .wizard-footer { padding: 16px 20px; }
            .step-label { display: none; }
            .template-grid { grid-template-columns: repeat(2, 1fr); gap: 10px; }
            .template-card { padding: 14px 10px; }
            .template-card .icon { font-size: 1.6rem; }
            .btn-wizard { padding: 12px 24px; font-size: 0.9rem; }
        }
    </style>
</head>
<body>
    <div class="bg-decoration"></div>

    <div class="wizard-wrapper">
        <!-- HEADER -->
        <div class="wizard-header">
            <div class="container">
                <div class="brand">
                    @if($tenant->logo)
                        <img src="{{ asset('storage/' . $tenant->logo) }}" alt="">
                    @else
                        <div style="width:40px;height:40px;border-radius:10px;background:rgba(255,255,255,0.15);display:flex;align-items:center;justify-content:center;">
                            <i class="fas fa-graduation-cap" style="font-size:1.2rem;"></i>
                        </div>
                    @endif
                    <h1>{{ $tenant->name ?: __('onboarding.setup_your_center') }}</h1>
                </div>

                <div class="progress-steps">
                    @php
                        $steps = [
                            ['icon' => 'fa-building', 'label' => __('onboarding.steps.profile')],
                            ['icon' => 'fa-book', 'label' => __('onboarding.steps.system')],
                            ['icon' => 'fa-chalkboard-teacher', 'label' => __('onboarding.steps.instructor')],
                            ['icon' => 'fa-book-open', 'label' => __('onboarding.steps.course')],
                            ['icon' => 'fa-user-graduate', 'label' => __('onboarding.steps.student')],
                            ['icon' => 'fa-check-double', 'label' => __('onboarding.steps.done')],
                        ];
                    @endphp
                    @foreach($steps as $i => $s)
                        <div class="step-item {{ $i === 0 ? 'active' : '' }}" data-step="{{ $i + 1 }}">
                            <div class="step-circle">
                                <span class="step-num">{{ $i + 1 }}</span>
                                <i class="fas fa-check d-none"></i>
                            </div>
                            <div class="step-label">{{ $s['label'] }}</div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- WIZARD BODY -->
        <div class="wizard-body">
            <div class="wizard-card">
                <div class="wizard-card-body">
                    <!-- Alert Area -->
                    <div id="wizard-alert" class="wizard-alert alert d-none" role="alert"></div>

                    <!-- ==================== STEP 1: Profile ==================== -->
                    <div class="step-panel active" data-step="1">
                        <h2 class="step-title">🏫 {{ __('onboarding.step1.title') }}</h2>
                        <p class="step-subtitle">{{ __('onboarding.step1.subtitle') }}</p>

                        <div class="logo-upload-area" onclick="document.getElementById('logoInput').click()">
                            <img id="logoPreview" class="d-none" src="" alt="">
                            <i id="logoIcon" class="fas fa-camera"></i>
                        </div>
                        <input type="file" id="logoInput" accept="image/*" class="d-none">

                        <div class="form-floating">
                            <input type="text" class="form-control" id="centerName" placeholder=" " value="{{ $tenant->name }}" required>
                            <label>{{ __('onboarding.step1.name') }} *</label>
                        </div>
                        <div class="form-floating">
                            <input type="tel" class="form-control" id="centerPhone" placeholder=" " value="{{ $tenant->phone }}">
                            <label>{{ __('onboarding.step1.phone') }} *</label>
                        </div>
                        <div class="form-floating">
                            <input type="text" class="form-control" id="centerAddress" placeholder=" " value="{{ $tenant->address }}">
                            <label>{{ __('onboarding.step1.address') }}</label>
                        </div>
                        <div class="form-floating">
                            <textarea class="form-control" id="centerDescription" placeholder=" " style="height:80px">{{ $tenant->description }}</textarea>
                            <label>{{ __('onboarding.step1.description') }}</label>
                        </div>
                    </div>

                    <!-- ==================== STEP 2: Academic System ==================== -->
                    <div class="step-panel" data-step="2">
                        <h2 class="step-title">📚 {{ __('onboarding.step2.title') }}</h2>
                        <p class="step-subtitle">{{ __('onboarding.step2.subtitle') }}</p>

                        <div class="template-grid">
                            <div class="template-card" data-template="egyptian_national">
                                <span class="icon">🇪🇬</span>
                                <div class="title">{{ __('onboarding.step2.egyptian') }}</div>
                                <div class="desc">{{ __('onboarding.step2.egyptian_desc') }}</div>
                            </div>
                            <div class="template-card" data-template="egyptian_azhar">
                                <span class="icon">🕌</span>
                                <div class="title">{{ __('onboarding.step2.azhar') }}</div>
                                <div class="desc">{{ __('onboarding.step2.azhar_desc') }}</div>
                            </div>
                            <div class="template-card" data-template="french_system">
                                <span class="icon">🇫🇷</span>
                                <div class="title">{{ __('onboarding.step2.french') }}</div>
                                <div class="desc">{{ __('onboarding.step2.french_desc') }}</div>
                            </div>
                            <div class="template-card" data-template="european_system">
                                <span class="icon">🇬🇧</span>
                                <div class="title">{{ __('onboarding.step2.european') }}</div>
                                <div class="desc">{{ __('onboarding.step2.european_desc') }}</div>
                            </div>
                            <div class="template-card" data-template="custom">
                                <span class="icon">✏️</span>
                                <div class="title">{{ __('onboarding.step2.custom') }}</div>
                                <div class="desc">{{ __('onboarding.step2.custom_desc') }}</div>
                            </div>
                        </div>
                    </div>

                    <!-- ==================== STEP 3: Instructor ==================== -->
                    <div class="step-panel" data-step="3">
                        <h2 class="step-title">👨‍🏫 {{ __('onboarding.step3.title') }}</h2>
                        <p class="step-subtitle">{{ __('onboarding.step3.subtitle') }}</p>

                        <div class="form-floating">
                            <input type="text" class="form-control" id="instructorName" placeholder=" " required>
                            <label>{{ __('onboarding.step3.name') }} *</label>
                        </div>
                        <div class="form-floating">
                            <input type="email" class="form-control" id="instructorEmail" placeholder=" " required>
                            <label>{{ __('onboarding.step3.email') }} *</label>
                        </div>
                        <div class="form-floating">
                            <input type="tel" class="form-control" id="instructorPhone" placeholder=" " required>
                            <label>{{ __('onboarding.step3.phone') }} *</label>
                        </div>
                        <div class="form-floating">
                            <input type="text" class="form-control" id="instructorSpecialty" placeholder=" ">
                            <label>{{ __('onboarding.step3.specialty') }}</label>
                        </div>
                    </div>

                    <!-- ==================== STEP 4: Course ==================== -->
                    <div class="step-panel" data-step="4">
                        <h2 class="step-title">📖 {{ __('onboarding.step4.title') }}</h2>
                        <p class="step-subtitle">{{ __('onboarding.step4.subtitle') }}</p>

                        <div class="form-floating">
                            <input type="text" class="form-control" id="courseTitle" placeholder=" " required>
                            <label>{{ __('onboarding.step4.course_title') }} *</label>
                        </div>
                        <div class="form-floating">
                            <select class="form-select" id="courseInstructor" required>
                                <option value="">{{ __('onboarding.step4.select_instructor') }}</option>
                            </select>
                            <label>{{ __('onboarding.step4.instructor') }} *</label>
                        </div>
                        <div class="form-floating">
                            <select class="form-select" id="courseGrade" required>
                                <option value="">{{ __('onboarding.step4.select_grade') }}</option>
                            </select>
                            <label>{{ __('onboarding.step4.grade') }} *</label>
                        </div>
                        <div class="form-floating">
                            <input type="number" class="form-control" id="coursePrice" placeholder=" " min="0" step="0.01">
                            <label>{{ __('onboarding.step4.price') }}</label>
                        </div>
                    </div>

                    <!-- ==================== STEP 5: Student ==================== -->
                    <div class="step-panel" data-step="5">
                        <h2 class="step-title">🎓 {{ __('onboarding.step5.title') }}</h2>
                        <p class="step-subtitle">{{ __('onboarding.step5.subtitle') }}</p>

                        <div class="form-floating">
                            <input type="text" class="form-control" id="studentName" placeholder=" " required>
                            <label>{{ __('onboarding.step5.name') }} *</label>
                        </div>
                        <div class="form-floating">
                            <input type="tel" class="form-control" id="studentPhone" placeholder=" " required>
                            <label>{{ __('onboarding.step5.phone') }} *</label>
                        </div>
                        <div class="form-floating">
                            <select class="form-select" id="studentGrade" required>
                                <option value="">{{ __('onboarding.step5.select_grade') }}</option>
                            </select>
                            <label>{{ __('onboarding.step5.grade') }} *</label>
                        </div>
                        <div class="form-floating">
                            <select class="form-select" id="studentCourse">
                                <option value="">{{ __('onboarding.step5.select_course') }}</option>
                            </select>
                            <label>{{ __('onboarding.step5.enroll_course') }}</label>
                        </div>
                    </div>

                    <!-- ==================== STEP 6: Summary ==================== -->
                    <div class="step-panel" data-step="6">
                        <div class="text-center mb-4">
                            <div style="font-size: 4rem; margin-bottom: 10px;">🎉</div>
                            <h2 class="step-title">{{ __('onboarding.step6.title') }}</h2>
                            <p class="step-subtitle mb-0">{{ __('onboarding.step6.subtitle') }}</p>
                        </div>

                        <div id="summaryStats">
                            <!-- Populated by JS -->
                        </div>

                        <hr class="my-4">

                        <h5 class="fw-bold mb-3"><i class="fas fa-lightbulb text-warning me-2"></i>{{ __('onboarding.step6.how_attendance') }}</h5>
                        <div class="tutorial-steps-list">
                            <div class="tutorial-step">
                                <div class="tutorial-num">1</div>
                                <div>
                                    <strong>{{ __('onboarding.step6.tut1_title') }}</strong>
                                    <div class="text-muted small">{{ __('onboarding.step6.tut1_desc') }}</div>
                                </div>
                            </div>
                            <div class="tutorial-step">
                                <div class="tutorial-num">2</div>
                                <div>
                                    <strong>{{ __('onboarding.step6.tut2_title') }}</strong>
                                    <div class="text-muted small">{{ __('onboarding.step6.tut2_desc') }}</div>
                                </div>
                            </div>
                            <div class="tutorial-step">
                                <div class="tutorial-num">3</div>
                                <div>
                                    <strong>{{ __('onboarding.step6.tut3_title') }}</strong>
                                    <div class="text-muted small">{{ __('onboarding.step6.tut3_desc') }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- FOOTER -->
                <div class="wizard-footer">
                    <button id="btnPrev" class="btn btn-wizard btn-wizard-outline d-none" onclick="prevStep()">
                        <i class="fas fa-arrow-{{ app()->getLocale() === 'ar' ? 'right' : 'left' }} me-2"></i>
                        {{ __('onboarding.prev') }}
                    </button>
                    <div></div>
                    <button id="btnNext" class="btn btn-wizard btn-wizard-primary" onclick="nextStep()">
                        {{ __('onboarding.next') }}
                        <i class="fas fa-arrow-{{ app()->getLocale() === 'ar' ? 'left' : 'right' }} ms-2"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // ===== STATE =====
        let currentStep = 1;
        const totalSteps = 6;
        let selectedTemplate = null;
        let createdInstructors = @json($instructors);
        let createdCourses = @json($courses);
        let createdStudents = @json($students);
        let stagesData = @json($stages);
        const saveUrl = "{{ route('center.onboarding.save-step', ['tenant' => $tenant->domain]) }}";
        const completeUrl = "{{ route('center.onboarding.complete', ['tenant' => $tenant->domain]) }}";
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

        // ===== NAVIGATION =====
        function updateUI() {
            // Update step panels
            document.querySelectorAll('.step-panel').forEach(p => p.classList.remove('active'));
            document.querySelector(`.step-panel[data-step="${currentStep}"]`).classList.add('active');

            // Update progress steps
            document.querySelectorAll('.step-item').forEach((item, i) => {
                const stepNum = i + 1;
                item.classList.remove('active', 'completed');
                if (stepNum === currentStep) item.classList.add('active');
                else if (stepNum < currentStep) item.classList.add('completed');

                const numEl = item.querySelector('.step-num');
                const checkEl = item.querySelector('.fa-check');
                if (stepNum < currentStep) {
                    numEl?.classList.add('d-none');
                    checkEl?.classList.remove('d-none');
                } else {
                    numEl?.classList.remove('d-none');
                    checkEl?.classList.add('d-none');
                }
            });

            // Update buttons
            const btnPrev = document.getElementById('btnPrev');
            const btnNext = document.getElementById('btnNext');

            btnPrev.classList.toggle('d-none', currentStep === 1);

            if (currentStep === totalSteps) {
                btnNext.innerHTML = `<i class="fas fa-rocket me-2"></i> {{ __('onboarding.start_now') }}`;
            } else {
                btnNext.innerHTML = `{{ __('onboarding.next') }} <i class="fas fa-arrow-{{ app()->getLocale() === 'ar' ? 'left' : 'right' }} ms-2"></i>`;
            }

            // Populate dynamic selects
            if (currentStep === 4) populateStep4Selects();
            if (currentStep === 5) populateStep5Selects();
            if (currentStep === 6) populateSummary();

            hideAlert();
        }

        function nextStep() {
            if (currentStep === totalSteps) {
                completeOnboarding();
                return;
            }
            saveCurrentStep(() => {
                currentStep++;
                updateUI();
            });
        }

        function prevStep() {
            if (currentStep > 1) {
                currentStep--;
                updateUI();
            }
        }

        // ===== SAVE STEPS =====
        function saveCurrentStep(onSuccess) {
            const btn = document.getElementById('btnNext');
            btn.classList.add('btn-loading');
            btn.disabled = true;

            const formData = new FormData();
            formData.append('step', currentStep);

            switch (currentStep) {
                case 1:
                    formData.append('name', document.getElementById('centerName').value);
                    formData.append('phone', document.getElementById('centerPhone').value);
                    formData.append('address', document.getElementById('centerAddress').value);
                    formData.append('description', document.getElementById('centerDescription').value);
                    const logoFile = document.getElementById('logoInput').files[0];
                    if (logoFile) formData.append('logo', logoFile);
                    break;
                case 2:
                    if (!selectedTemplate) {
                        showAlert('{{ __('onboarding.step2.select_required') }}', 'warning');
                        btn.classList.remove('btn-loading');
                        btn.disabled = false;
                        return;
                    }
                    formData.append('template_key', selectedTemplate);
                    break;
                case 3:
                    const iName = document.getElementById('instructorName').value;
                    const iEmail = document.getElementById('instructorEmail').value;
                    const iPhone = document.getElementById('instructorPhone').value;
                    if (!iName || !iEmail || !iPhone) {
                        showAlert('{{ __('onboarding.required_fields') }}', 'warning');
                        btn.classList.remove('btn-loading');
                        btn.disabled = false;
                        return;
                    }
                    formData.append('instructor_name', iName);
                    formData.append('instructor_email', iEmail);
                    formData.append('instructor_phone', iPhone);
                    formData.append('instructor_specialty', document.getElementById('instructorSpecialty').value);
                    break;
                case 4:
                    const cTitle = document.getElementById('courseTitle').value;
                    const cInstructor = document.getElementById('courseInstructor').value;
                    const cGrade = document.getElementById('courseGrade').value;
                    if (!cTitle || !cInstructor || !cGrade) {
                        showAlert('{{ __('onboarding.required_fields') }}', 'warning');
                        btn.classList.remove('btn-loading');
                        btn.disabled = false;
                        return;
                    }
                    formData.append('course_title', cTitle);
                    formData.append('instructor_id', cInstructor);
                    formData.append('grade_id', cGrade);
                    formData.append('price', document.getElementById('coursePrice').value || 0);
                    break;
                case 5:
                    const sName = document.getElementById('studentName').value;
                    const sPhone = document.getElementById('studentPhone').value;
                    const sGrade = document.getElementById('studentGrade').value;
                    if (!sName || !sPhone || !sGrade) {
                        showAlert('{{ __('onboarding.required_fields') }}', 'warning');
                        btn.classList.remove('btn-loading');
                        btn.disabled = false;
                        return;
                    }
                    formData.append('student_name', sName);
                    formData.append('student_phone', sPhone);
                    formData.append('student_grade_id', sGrade);
                    formData.append('student_course_id', document.getElementById('studentCourse').value);
                    break;
            }

            fetch(saveUrl, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                },
                body: formData,
            })
            .then(r => r.json())
            .then(data => {
                btn.classList.remove('btn-loading');
                btn.disabled = false;
                if (data.success) {
                    // Update local state
                    if (currentStep === 2 && data.stages) stagesData = data.stages;
                    if (currentStep === 3 && data.instructor) createdInstructors.push(data.instructor);
                    if (currentStep === 4 && data.course) createdCourses.push(data.course);
                    if (currentStep === 5 && data.student) createdStudents.push(data.student);
                    onSuccess();
                } else {
                    showAlert(data.message || '{{ __('onboarding.error') }}', 'danger');
                }
            })
            .catch(err => {
                btn.classList.remove('btn-loading');
                btn.disabled = false;
                // Try to parse validation errors
                if (err.response) {
                    err.response.json().then(d => {
                        const msgs = d.errors ? Object.values(d.errors).flat().join('<br>') : d.message;
                        showAlert(msgs, 'danger');
                    });
                } else {
                    showAlert('{{ __('onboarding.error') }}', 'danger');
                }
            });
        }

        function completeOnboarding() {
            const btn = document.getElementById('btnNext');
            btn.classList.add('btn-loading');
            btn.disabled = true;

            fetch(completeUrl, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({}),
            })
            .then(r => r.json())
            .then(data => {
                if (data.success && data.redirect) {
                    window.location.href = data.redirect;
                }
            })
            .catch(() => {
                btn.classList.remove('btn-loading');
                btn.disabled = false;
                showAlert('{{ __('onboarding.error') }}', 'danger');
            });
        }

        // ===== POPULATE DYNAMIC SELECTS =====
        function populateStep4Selects() {
            const instSel = document.getElementById('courseInstructor');
            const gradeSel = document.getElementById('courseGrade');
            instSel.innerHTML = `<option value="">{{ __('onboarding.step4.select_instructor') }}</option>`;
            createdInstructors.forEach(i => {
                instSel.innerHTML += `<option value="${i.id}">${i.name}</option>`;
            });
            populateGradeSelect(gradeSel, '{{ __('onboarding.step4.select_grade') }}');
        }

        function populateStep5Selects() {
            const gradeSel = document.getElementById('studentGrade');
            const courseSel = document.getElementById('studentCourse');
            populateGradeSelect(gradeSel, '{{ __('onboarding.step5.select_grade') }}');
            courseSel.innerHTML = `<option value="">{{ __('onboarding.step5.select_course') }}</option>`;
            createdCourses.forEach(c => {
                courseSel.innerHTML += `<option value="${c.id}">${c.title}</option>`;
            });
        }

        function populateGradeSelect(sel, placeholder) {
            sel.innerHTML = `<option value="">${placeholder}</option>`;
            stagesData.forEach(stage => {
                const grp = document.createElement('optgroup');
                grp.label = stage.name;
                (stage.grades || []).forEach(g => {
                    const opt = document.createElement('option');
                    opt.value = g.id;
                    opt.textContent = g.name;
                    grp.appendChild(opt);
                });
                sel.appendChild(grp);
            });
        }

        // ===== SUMMARY =====
        function populateSummary() {
            const container = document.getElementById('summaryStats');
            container.innerHTML = `
                <div class="summary-stat">
                    <div class="icon-box" style="background:#e0e7ff;color:#6366f1;"><i class="fas fa-chalkboard-teacher"></i></div>
                    <div>
                        <h6>${createdInstructors.length} {{ __('onboarding.step6.instructors') }}</h6>
                        <small>{{ __('onboarding.step6.instructors_added') }}</small>
                    </div>
                </div>
                <div class="summary-stat">
                    <div class="icon-box" style="background:#dcfce7;color:#16a34a;"><i class="fas fa-book-open"></i></div>
                    <div>
                        <h6>${createdCourses.length} {{ __('onboarding.step6.courses') }}</h6>
                        <small>{{ __('onboarding.step6.courses_created') }}</small>
                    </div>
                </div>
                <div class="summary-stat">
                    <div class="icon-box" style="background:#fef3c7;color:#d97706;"><i class="fas fa-user-graduate"></i></div>
                    <div>
                        <h6>${createdStudents.length} {{ __('onboarding.step6.students') }}</h6>
                        <small>{{ __('onboarding.step6.students_added') }}</small>
                    </div>
                </div>
            `;
        }

        // ===== TEMPLATE SELECTION =====
        document.querySelectorAll('.template-card').forEach(card => {
            card.addEventListener('click', function() {
                document.querySelectorAll('.template-card').forEach(c => c.classList.remove('selected'));
                this.classList.add('selected');
                selectedTemplate = this.dataset.template;
            });
        });

        // ===== LOGO PREVIEW =====
        document.getElementById('logoInput').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(ev) {
                    const preview = document.getElementById('logoPreview');
                    preview.src = ev.target.result;
                    preview.classList.remove('d-none');
                    document.getElementById('logoIcon').classList.add('d-none');
                };
                reader.readAsDataURL(file);
            }
        });

        // ===== ALERTS =====
        function showAlert(msg, type = 'danger') {
            const el = document.getElementById('wizard-alert');
            el.className = `wizard-alert alert alert-${type}`;
            el.innerHTML = msg;
            el.classList.remove('d-none');
            el.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
        }

        function hideAlert() {
            document.getElementById('wizard-alert').classList.add('d-none');
        }
    </script>
</body>
</html>
