@extends('center::layouts.hope-master')

@section('content')
    <div class="row g-4 position-relative scroll-container-elite">
        <!-- Sticky Sidebar Navigation -->
        <div class="col-xl-3 d-none d-xl-block">
            <div class="sticky-top" style="top: 100px; z-index: 10;">
                <div class="elite-nav-card bg-white rounded-5 shadow-elite border p-4">
                    <h6 class="fw-bold mb-4 text-dark opacity-50 small text-uppercase letter-spacing-1">{{ __('center::messages.blade_0710') }}</h6>
                    <div class="nav flex-column gap-3 elite-vertical-nav">
                        <a href="#section-personal" class="nav-link active" data-section="personal">
                            <div class="nav-dot"></div>
                            <div class="nav-content">
                                <span class="nav-label">{{ __('center::messages.blade_0711') }}</span>
                                <small class="text-muted">{{ __('center::messages.blade_0712') }}</small>
                            </div>
                        </a>
                        <a href="#section-parent" class="nav-link" data-section="parent">
                            <div class="nav-dot"></div>
                            <div class="nav-content">
                                <span class="nav-label">{{ __('center::messages.blade_0713') }}</span>
                                <small class="text-muted">{{ __('center::messages.blade_0714') }}</small>
                            </div>
                        </a>
                        <a href="#section-academic" class="nav-link" data-section="academic">
                            <div class="nav-dot"></div>
                            <div class="nav-content">
                                <span class="nav-label">{{ __('center::messages.blade_0715') }}</span>
                                <small class="text-muted">{{ __('center::messages.blade_0716') }}</small>
                            </div>
                        </a>
                    </div>

                    <div class="mt-5 pt-4 border-top">
                        <div class="d-flex align-items-center gap-2 text-success small mb-3">
                            <i class="fas fa-shield-halved"></i>
                            <span class="fw-bold">{{ __('center::messages.blade_0717') }}</span>
                        </div>
                        <button type="button" onclick="document.getElementById('student-form').submit()" class="btn btn-primary w-100 rounded-pill py-3 fw-bold shadow-elite btn-elite-submit">{{ __('center::messages.blade_0718') }}<i class="fas fa-check-double ms-2"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Form Column -->
        <div class="col-xl-9">
            <div class="header-action-bar mb-5 animate__animated animate__fadeIn">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h2 class="fw-bold text-dark mb-1">{{ __('center::messages.blade_0719') }}</h2>
                        <p class="text-muted mb-0">{{ __('center::messages.blade_0720') }}</p>
                    </div>
                    <a href="{{ route('center.students.index') }}" class="btn btn-white border shadow-sm rounded-pill px-4 hover-lift">
                        <i class="fas fa-arrow-right me-2"></i>{{ __('center::messages.blade_0721') }}</a>
                </div>
            </div>

            <form action="{{ route('center.students.store') }}" method="POST" enctype="multipart/form-data" id="student-form" class="needs-validation" novalidate>
                @csrf
                
                <!-- Section 1: Personal -->
                <div id="section-personal" class="elite-form-card bg-white rounded-5 shadow-elite border p-4 p-md-5 mb-5 transition-all">
                    <div class="section-header d-flex align-items-center gap-3 mb-5">
                        <div class="section-icon bg-primary shadow-soft text-white rounded-4">
                            <i class="fas fa-user-astronaut fs-4"></i>
                        </div>
                        <div>
                            <h4 class="fw-bold mb-0">{{ __('center::messages.blade_0722') }}</h4>
                            <p class="text-muted small mb-0">{{ __('center::messages.blade_0723') }}</p>
                        </div>
                    </div>

                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="form-floating elite-input-group">
                                <input type="text" name="name" value="{{ old('name') }}" class="form-control" id="nameInput" placeholder="{{ __('center::messages.blade_0751') }}">
                                <label for="nameInput">{{ __('center::messages.blade_0724') }}<span class="text-danger">*</span></label>
                                <div class="validation-indicator"></div>
                                @error('name') <div class="text-danger extra-small mt-1">{{ $message }}</div> @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating elite-input-group">
                                <input type="tel" name="phone" value="{{ old('phone') }}" class="form-control" id="phoneInput" placeholder="{{ __('center::messages.blade_0752') }}">
                                <label for="phoneInput">{{ __('center::messages.blade_0725') }}<span class="text-danger">*</span></label>
                                <div class="validation-indicator"></div>
                                @error('phone') <div class="text-danger extra-small mt-1">{{ $message }}</div> @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating elite-input-group">
                                <input type="email" name="email" value="{{ old('email') }}" class="form-control" id="emailInput" placeholder="{{ __('center::messages.blade_0753') }}">
                                <label for="emailInput">{{ __('center::messages.blade_0726') }}</label>
                                <div class="validation-indicator"></div>
                                @error('email') <div class="text-danger extra-small mt-1">{{ $message }}</div> @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating elite-input-group">
                                <input type="text" name="code" value="{{ old('code') }}" class="form-control" id="codeInput" placeholder="{{ __('center::messages.blade_0754') }}">
                                <label for="codeInput">{{ __('center::messages.blade_0727') }}</label>
                                <div class="validation-indicator"></div>
                                @error('code') <div class="text-danger extra-small mt-1">{{ $message }}</div> @enderror
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-floating elite-input-group">
                                <input type="text" name="address" value="{{ old('address') }}" class="form-control" id="addressInput" placeholder="{{ __('center::messages.blade_0755') }}">
                                <label for="addressInput">{{ __('center::messages.blade_0728') }}</label>
                                @error('address') <div class="text-danger extra-small mt-1">{{ $message }}</div> @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-floating elite-input-group">
                                <input type="text" name="national_id" value="{{ old('national_id') }}" class="form-control" id="idInput" placeholder="{{ __('center::messages.blade_0756') }}">
                                <label for="idInput">{{ __('center::messages.blade_0729') }}</label>
                                @error('national_id') <div class="text-danger extra-small mt-1">{{ $message }}</div> @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-floating elite-input-group">
                                <input type="date" name="birth_date" value="{{ old('birth_date') }}" class="form-control" id="dateInput">
                                <label for="dateInput">{{ __('center::messages.blade_0730') }}</label>
                                @error('birth_date') <div class="text-danger extra-small mt-1">{{ $message }}</div> @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-floating elite-input-group">
                                <select name="gender" class="form-select" id="genderSelect">
                                    <option value="">{{ __('center::messages.blade_0731') }}</option>
                                    <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>{{ __('center::messages.blade_0732') }}</option>
                                    <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>{{ __('center::messages.blade_0733') }}</option>
                                </select>
                                <label for="genderSelect">{{ __('center::messages.blade_0734') }}</label>
                                @error('gender') <div class="text-danger extra-small mt-1">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <div class="col-12 mt-4">
                            <div class="elite-image-upload rounded-5 p-5 text-center transition-all bg-light border-dashed">
                                <div class="upload-visual mb-3 mx-auto">
                                    <div class="avatar-preview-box rounded-circle shadow-sm mx-auto mb-3" id="imagePreview">
                                        <i class="fas fa-camera-retro text-primary fs-3"></i>
                                    </div>
                                </div>
                                <h6 class="fw-bold mb-1">{{ __('center::messages.blade_0735') }}</h6>
                                <p class="text-muted small">{{ __('center::messages.blade_0736') }}</p>
                                <input type="file" name="profile_photo" id="photoInput" class="fake-input" accept="image/*">
                                @error('profile_photo') <div class="text-danger extra-small mt-1">{{ $message }}</div> @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Section 2: Parent -->
                <div id="section-parent" class="elite-form-card bg-white rounded-5 shadow-elite border p-4 p-md-5 mb-5 transition-all">
                    <div class="section-header d-flex align-items-center gap-3 mb-5">
                        <div class="section-icon bg-info shadow-soft text-white rounded-4">
                            <i class="fas fa-user-shield fs-4"></i>
                        </div>
                        <div>
                            <h4 class="fw-bold mb-0">{{ __('center::messages.blade_0737') }}</h4>
                            <p class="text-muted small mb-0">{{ __('center::messages.blade_0738') }}</p>
                        </div>
                    </div>

                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="form-floating elite-input-group position-relative">
                                <input type="tel" name="parent_phone" id="parent_phone" value="{{ old('parent_phone') }}" class="form-control" placeholder="{{ __('center::messages.blade_0757') }}">
                                <label for="parent_phone">{{ __('center::messages.blade_0739') }}</label>
                                <div class="validation-indicator"></div>
                                @error('parent_phone') <div class="text-danger extra-small mt-1">{{ $message }}</div> @enderror
                                <div id="parent-match-chip" class="match-chip d-none animate__animated animate__bounceIn">
                                    <i class="fas fa-magic me-1"></i>{{ __('center::messages.blade_0740') }}<b id="match-name"></b>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating elite-input-group">
                                <input type="text" name="parent_name" id="pNameInput" value="{{ old('parent_name') }}" class="form-control" placeholder="{{ __('center::messages.blade_0758') }}">
                                <label for="pNameInput">{{ __('center::messages.blade_0741') }}</label>
                                @error('parent_name') <div class="text-danger extra-small mt-1">{{ $message }}</div> @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating elite-input-group">
                                <input type="text" name="parent_relation" value="{{ old('parent_relation') }}" class="form-control" id="relInput" placeholder="{{ __('center::messages.blade_0759') }}">
                                <label for="relInput">{{ __('center::messages.blade_0742') }}</label>
                                @error('parent_relation') <div class="text-danger extra-small mt-1">{{ $message }}</div> @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating elite-input-group">
                                <input type="tel" name="emergency_phone" value="{{ old('emergency_phone') }}" class="form-control" id="ePhoneInput" placeholder="{{ __('center::messages.blade_0760') }}">
                                <label for="ePhoneInput">{{ __('center::messages.blade_0743') }}</label>
                                @error('emergency_phone') <div class="text-danger extra-small mt-1">{{ $message }}</div> @enderror
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-floating elite-input-group">
                                <input type="text" name="parent_job" id="pJobInput" value="{{ old('parent_job') }}" class="form-control" placeholder="{{ __('center::messages.blade_0761') }}">
                                <label for="pJobInput">{{ __('center::messages.blade_0744') }}</label>
                                @error('parent_job') <div class="text-danger extra-small mt-1">{{ $message }}</div> @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Section 3: Academic -->
                <div id="section-academic" class="elite-form-card bg-white rounded-5 shadow-elite border p-4 p-md-5 mb-5 transition-all">
                    <div class="section-header d-flex align-items-center gap-3 mb-5">
                        <div class="section-icon bg-success shadow-soft text-white rounded-4">
                            <i class="fas fa-graduation-cap fs-4"></i>
                        </div>
                        <div>
                            <h4 class="fw-bold mb-0">{{ __('center::messages.blade_0745') }}</h4>
                            <p class="text-muted small mb-0">{{ __('center::messages.blade_0746') }}</p>
                        </div>
                    </div>

                    <div class="row g-4">
                        <div class="col-12">
                            <div class="form-floating elite-input-group">
                                <select name="grade_id" class="form-select" id="gradeSelect">
                                    <option value="">{{ __('center::messages.blade_0747') }}</option>
                                    @foreach($stages as $stage)
                                        <optgroup label="📂 {{ $stage->name }}">
                                            @foreach($stage->grades as $grade)
                                                <option value="{{ $grade->id }}" {{ old('grade_id') == $grade->id ? 'selected' : '' }}>{{ $grade->name }}</option>
                                            @endforeach
                                        </optgroup>
                                    @endforeach
                                </select>
                                <label for="gradeSelect">{{ __('center::messages.blade_0748') }}<span class="text-danger">*</span></label>
                                <div class="validation-indicator"></div>
                                @error('grade_id') <div class="text-danger extra-small mt-1">{{ $message }}</div> @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating elite-input-group">
                                <input type="text" name="school_name" value="{{ old('school_name') }}" class="form-control" id="schoolInput" placeholder="{{ __('center::messages.blade_0762') }}">
                                <label for="schoolInput">{{ __('center::messages.blade_0749') }}</label>
                                @error('school_name') <div class="text-danger extra-small mt-1">{{ $message }}</div> @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating elite-input-group">
                                <input type="text" name="section_type" value="{{ old('section_type') }}" class="form-control" id="secInput" placeholder="{{ __('center::messages.blade_0763') }}">
                                <label for="secInput">{{ __('center::students.section_type_label') }}</label>
                                @error('section_type') <div class="text-danger extra-small mt-1">{{ $message }}</div> @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Unified Sticky Submit Bar for Mobile -->
                <div class="d-xl-none fixed-bottom bg-white border-top p-3 d-flex gap-2 shadow-lg" style="z-index: 1000;">
                    <button type="submit" class="btn btn-primary w-100 rounded-pill py-3 fw-bold">{{ __('center::messages.blade_0750') }}</button>
                    <a href="#section-personal" class="btn btn-outline-secondary rounded-circle p-0 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;"><i class="fas fa-arrow-up"></i></a>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const sections = document.querySelectorAll('.elite-form-card');
        const navLinks = document.querySelectorAll('.elite-vertical-nav .nav-link');
        const photoInput = document.getElementById('photoInput');
        const imagePreview = document.getElementById('imagePreview');

        // Scroll Spy Effect
        window.addEventListener('scroll', () => {
            let current = '';
            sections.forEach(section => {
                const sectionTop = section.offsetTop;
                const sectionHeight = section.clientHeight;
                if (window.pageYOffset >= sectionTop - 150) {
                    current = section.getAttribute('id');
                }
            });

            navLinks.forEach(link => {
                link.classList.remove('active');
                if (link.getAttribute('href') === `#${current}`) {
                    link.classList.add('active');
                }
            });
        });

        // Smooth Scroll for Sidebar
        navLinks.forEach(link => {
            link.addEventListener('click', (e) => {
                e.preventDefault();
                const targetId = link.getAttribute('href');
                const targetEl = document.querySelector(targetId);
                window.scrollTo({
                    top: targetEl.offsetTop - 120,
                    behavior: 'smooth'
                });
            });
        });

        // Image Preview Magic
        photoInput?.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    imagePreview.innerHTML = `<img src="${e.target.result}" class="w-100 h-100 object-fit-cover rounded-circle border">`;
                    imagePreview.classList.add('glow-success');
                }
                reader.readAsDataURL(file);
            }
        });

        // Smart Interaction & Validation
        const allInputs = document.querySelectorAll('.form-control, .form-select');
        allInputs.forEach(input => {
            input.addEventListener('input', function() {
                const group = this.closest('.elite-input-group');
                if (!group) return;

                if (this.value.trim() !== '') {
                    if (this.checkValidity()) {
                        group.classList.add('is-elite-valid');
                        group.classList.remove('is-elite-invalid');
                    } else {
                        group.classList.add('is-elite-invalid');
                        group.classList.remove('is-elite-valid');
                    }
                } else {
                    group.classList.remove('is-elite-valid', 'is-elite-invalid');
                }
            });
        });

        // Guardian Magic Lookup
        const parentPhone = document.getElementById('parent_phone');
        const matchChip = document.getElementById('parent-match-chip');
        const matchName = document.getElementById('match-name');
        let whisper;

        parentPhone?.addEventListener('input', function() {
            clearTimeout(whisper);
            const val = this.value.trim();
            if (val.length >= 10) {
                whisper = setTimeout(() => {
                    fetch(`{{ route('center.guardians.lookup') }}?phone=${val}`)
                        .then(r => r.json())
                        .then(data => {
                            if (data.found) {
                                matchChip.classList.remove('d-none');
                                matchName.textContent = data.guardian.name;
                                document.getElementById('pNameInput').value = data.guardian.name;
                                document.getElementById('pJobInput').value = data.guardian.job || '';
                                document.getElementById('addressInput').value = data.guardian.address || '';
                                // Visual feedback
                                document.getElementById('pNameInput').dispatchEvent(new Event('input'));
                            } else {
                                matchChip.classList.add('d-none');
                            }
                        });
                }, 400);
            } else {
                matchChip.classList.add('d-none');
            }
        });
    });
</script>
@endsection

@section('styles')
<style>
    :root {
        --elite-primary: #3A0CA3;
        --elite-accent: #4361EE;
        --elite-soft-bg: #F8FAFC;
        --elite-radius: 2.5rem;
        --elite-shadow: 0 20px 50px -15px rgba(58, 12, 163, 0.15);
    }

    body { background-color: #f1f5f9; }

    .shadow-elite { box-shadow: var(--elite-shadow) !important; }
    .rounded-5 { border-radius: var(--elite-radius) !important; }
    .transition-all { transition: 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275); }

    /* Vertical Navigation Sidebar */
    .elite-nav-card { border: 1px solid rgba(0,0,0,0.05); }

    .elite-vertical-nav .nav-link {
        display: flex;
        align-items: center;
        gap: 15px;
        padding: 12px 15px;
        border-radius: 1.2rem;
        color: #64748b;
        transition: 0.3s;
        border: 1px solid transparent;
    }

    .nav-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: #cbd5e1;
        transition: 0.3s;
    }

    .elite-vertical-nav .nav-link.active {
        background: rgba(58, 12, 163, 0.05);
        color: var(--elite-primary);
        border-color: rgba(58, 12, 163, 0.1);
    }

    .elite-vertical-nav .nav-link.active .nav-dot {
        background: var(--elite-primary);
        transform: scale(1.5);
        box-shadow: 0 0 10px rgba(58, 12, 163, 0.3);
    }

    .nav-label { font-weight: 800; font-size: 0.95rem; }
    .nav-content small { display: block; font-size: 0.72rem; }

    /* Modern Card Layout */
    .elite-form-card {
        border: 1px solid rgba(0,0,0,0.02);
    }

    .section-icon {
        width: 56px;
        height: 56px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    /* Floating Inputs Elite */
    .elite-input-group { position: relative; }

    .form-control, .form-select {
        border: 2px solid #f1f5f9 !important;
        background: #f8fafc !important;
        border-radius: 1.4rem !important;
        padding-top: 1.8rem !important;
        padding-bottom: 0.8rem !important;
        font-weight: 700;
        color: #1e293b;
        transition: 0.3s;
    }

    .form-control:focus, .form-select:focus {
        background: white !important;
        border-color: var(--elite-accent) !important;
        box-shadow: 0 10px 25px -5px rgba(67, 97, 238, 0.1) !important;
    }

    .validation-indicator {
        position: absolute;
        bottom: 0;
        right: 0;
        height: 4px;
        width: 0%;
        background: #10b981;
        transition: 0.5s ease;
        border-radius: 0 0 1.4rem 1.4rem;
    }

    .is-elite-valid .validation-indicator { width: 100%; }
    .is-elite-valid .form-control { border-color: rgba(16, 185, 129, 0.3) !important; }

    /* Image Upload Elite */
    .elite-image-upload {
        position: relative;
        cursor: pointer;
        border: 2px dashed #cbd5e1;
    }

    .elite-image-upload:hover {
        background: white;
        border-color: var(--elite-primary);
        transform: translateY(-5px);
    }

    .avatar-preview-box {
        width: 100px;
        height: 100px;
        background: white;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: 0.4s;
    }

    .fake-input { position: absolute; inset: 0; opacity: 0; cursor: pointer; }

    /* Guardian Match Chip */
    .match-chip {
        position: absolute;
        top: -12px;
        left: 20px;
        background: #10b981;
        color: white;
        padding: 4px 12px;
        border-radius: 50px;
        font-size: 0.7rem;
        font-weight: 800;
        z-index: 5;
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
    }

    /* Buttons */
    .btn-elite-submit {
        background: linear-gradient(135deg, var(--elite-primary), var(--elite-accent));
        border: none;
        color: white;
        box-shadow: 0 10px 20px -5px rgba(58, 12, 163, 0.4);
    }

    .btn-elite-submit:hover {
        transform: translateY(-3px);
        box-shadow: 0 15px 30px -5px rgba(58, 12, 163, 0.5);
        color: white;
    }

    .hover-lift:hover { transform: translateY(-3px); }
    .letter-spacing-1 { letter-spacing: 1px; }

    [dir="rtl"] .form-floating > label { right: 0; left: auto; padding-right: 1.5rem; }
    [dir="rtl"] .match-chip { right: 20px; left: auto; }
</style>
@endsection
```
