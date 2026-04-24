@extends('center::layouts.hope-master')

@section('content')
    <div class="header-banner-elite mb-5 animate__animated animate__fadeInDown" style="background: linear-gradient(135deg, #059669 0%, #10b981 100%); border-radius: 30px; padding: 40px; position: relative; overflow: hidden; box-shadow: 0 20px 40px rgba(16, 185, 129, 0.15);">
        <div class="position-relative z-index-1">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div>
                    <h2 class="fw-black text-white mb-1 letter-spacing-tight" style="font-size: 2.5rem;">{{ __('center::messages.blade_0719') }}</h2>
                    <p class="text-white opacity-75 mb-0 fs-5">{{ __('center::messages.blade_0720') }}</p>
                </div>
                <a href="{{ route('center.students.index') }}" class="btn btn-glass-white rounded-pill px-4 py-2 fw-bold transition-all hover-lift">
                    <i class="fas fa-users-viewfinder me-2"></i> {{ __('center::messages.blade_0721') }}
                </a>
            </div>
        </div>
        <!-- Decorative elements -->
        <div class="position-absolute top-0 end-0 mt-n5 me-n5 opacity-10">
            <i class="fas fa-user-plus" style="font-size: 20rem; transform: rotate(-15deg);"></i>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-xl-8 col-lg-10">
            <div class="card border-0 shadow-elite rounded-5 overflow-hidden animate__animated animate__fadeInUp animate__delay-1s">
                <div class="card-body p-0">
                    <form action="{{ route('center.students.store') }}" method="POST" enctype="multipart/form-data" id="student-form">
                        @csrf
                        
                        {{-- 1. Student Info Section --}}
                        <div class="p-4 p-md-5">
                            <div class="d-flex align-items-center gap-3 mb-5 section-title-glow">
                                <div class="icon-box-emerald">
                                    <i class="fas fa-user-graduate"></i>
                                </div>
                                <div>
                                    <h4 class="fw-bold mb-0 text-dark">{{ __('center::messages.blade_0722') }}</h4>
                                    <div class="title-underline"></div>
                                </div>
                            </div>
                            
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <div class="elite-floating-group">
                                        <i class="fas fa-signature input-icon"></i>
                                        <input type="text" name="name" value="{{ old('name') }}" class="form-control elite-input" id="nameInput" placeholder=" " required>
                                        <label for="nameInput">{{ __('center::messages.blade_0724') }} <span class="text-danger">*</span></label>
                                        @error('name') <div class="error-msg">{{ $message }}</div> @enderror
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="elite-floating-group">
                                        <i class="fas fa-mobile-screen-button input-icon"></i>
                                        <input type="tel" name="phone" value="{{ old('phone') }}" class="form-control elite-input" id="phoneInput" placeholder=" " required pattern="[0-9]*">
                                        <label for="phoneInput">{{ __('center::messages.blade_0725') }} <span class="text-danger">*</span></label>
                                        @error('phone') <div class="error-msg">{{ $message }}</div> @enderror
                                    </div>
                                </div>
                                
                                <div class="col-md-12">
                                    <div class="elite-floating-group">
                                        <i class="fas fa-at input-icon"></i>
                                        <input type="email" name="email" value="{{ old('email') }}" class="form-control elite-input" id="emailInput" placeholder=" ">
                                        <label for="emailInput">{{ __('center::messages.blade_0726') }} ({{ __('center::messages.blade_0753') }})</label>
                                        @error('email') <div class="error-msg">{{ $message }}</div> @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="section-divider"></div>

                        {{-- 2. Parent Info Section --}}
                        <div class="p-4 p-md-5 bg-soft-emerald">
                            <div class="d-flex align-items-center gap-3 mb-5 section-title-glow">
                                <div class="icon-box-emerald secondary">
                                    <i class="fas fa-shield-heart"></i>
                                </div>
                                <div>
                                    <h4 class="fw-bold mb-0 text-dark">{{ __('center::messages.blade_0737') }}</h4>
                                    <div class="title-underline"></div>
                                </div>
                            </div>
                            
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <div class="elite-floating-group">
                                        <i class="fas fa-user-shield input-icon"></i>
                                        <input type="text" name="parent_name" value="{{ old('parent_name') }}" class="form-control elite-input" id="pNameInput" placeholder=" ">
                                        <label for="pNameInput">{{ __('center::messages.blade_0741') }}</label>
                                        @error('parent_name') <div class="error-msg">{{ $message }}</div> @enderror
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="elite-floating-group has-badge">
                                        <i class="fas fa-phone-volume input-icon"></i>
                                        <input type="tel" name="parent_phone" id="parent_phone" value="{{ old('parent_phone') }}" class="form-control elite-input" placeholder=" " pattern="[0-9]*">
                                        <label for="parent_phone">{{ __('center::messages.blade_0739') }}</label>
                                        <span class="guardian-status-badge d-none" id="guardian-found-badge">
                                            <i class="fas fa-check-circle"></i> {{ __('center::messages.blade_0783') }}
                                        </span>
                                        @error('parent_phone') <div class="error-msg">{{ $message }}</div> @enderror
                                    </div>
                                    <div id="guardian-info-alert" class="guardian-alert animate__animated animate__fadeIn d-none">
                                        <i class="fas fa-info-circle"></i> {{ __('center::messages.blade_0784') }} <span class="fw-bold text-emerald" id="found-guardian-name"></span> {{ __('center::messages.blade_0785') }}
                                    </div>
                                </div>
                                
                                <div class="col-md-12">
                                    <div class="elite-floating-group">
                                        <i class="fas fa-envelope-open-text input-icon"></i>
                                        <input type="email" name="parent_email" value="{{ old('parent_email') }}" class="form-control elite-input" id="parentEmailInput" placeholder=" ">
                                        <label for="parentEmailInput">بريد ولي الأمر (اختياري)</label>
                                        @error('parent_email') <div class="error-msg">{{ $message }}</div> @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="section-divider"></div>

                        {{-- 3. Academic Info Section --}}
                        <div class="p-4 p-md-5">
                            <div class="d-flex align-items-center gap-3 mb-5 section-title-glow">
                                <div class="icon-box-emerald tertiary">
                                    <i class="fas fa-book-open-reader"></i>
                                </div>
                                <div>
                                    <h4 class="fw-bold mb-0 text-dark">{{ __('center::messages.blade_0745') }}</h4>
                                    <div class="title-underline"></div>
                                </div>
                            </div>
                            
                            <div class="row g-4">
                                <div class="col-md-12">
                                    <div class="elite-floating-group">
                                        <i class="fas fa-layer-group input-icon"></i>
                                        <select name="grade_id" class="form-select elite-input" id="gradeSelect" required>
                                            <option value="" selected disabled></option>
                                            @foreach($stages as $stage)
                                                <optgroup label="📂 {{ $stage->name }}">
                                                    @foreach($stage->grades as $grade)
                                                        <option value="{{ $grade->id }}" {{ old('grade_id') == $grade->id ? 'selected' : '' }}>{{ $grade->name }}</option>
                                                    @endforeach
                                                </optgroup>
                                            @endforeach
                                        </select>
                                        <label for="gradeSelect">{{ __('center::messages.blade_0748') }} <span class="text-danger">*</span></label>
                                        @error('grade_id') <div class="error-msg">{{ $message }}</div> @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="p-4 p-md-5 bg-light-gray">
                            <button type="submit" class="btn btn-emerald-elite w-100 rounded-pill py-3 fw-black text-uppercase letter-spacing-1 shadow-lg transition-all">
                                <span>{{ __('center::messages.blade_0750') }}</span>
                                <i class="fas fa-arrow-left ms-2"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <style>
        :root {
            --emerald-primary: #10b981;
            --emerald-dark: #059669;
            --emerald-soft: #f0fdf4;
            --elite-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.08);
            --transition-smooth: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
        }

        .header-banner-elite {
            position: relative;
        }

        .btn-glass-white {
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            color: white;
        }

        .btn-glass-white:hover {
            background: white;
            color: var(--emerald-dark);
            transform: translateY(-3px);
        }

        .shadow-elite {
            box-shadow: var(--elite-shadow);
        }

        .rounded-5 {
            border-radius: 2rem !important;
        }

        .icon-box-emerald {
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, #10b981, #059669);
            color: white;
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            box-shadow: 0 10px 20px rgba(16, 185, 129, 0.2);
        }

        .icon-box-emerald.secondary { background: linear-gradient(135deg, #0ea5e9, #0284c7); box-shadow: 0 10px 20px rgba(14, 165, 233, 0.2); }
        .icon-box-emerald.tertiary { background: linear-gradient(135deg, #8b5cf6, #7c3aed); box-shadow: 0 10px 20px rgba(139, 92, 246, 0.2); }

        .title-underline {
            height: 4px;
            width: 40px;
            background: var(--emerald-primary);
            border-radius: 2px;
            margin-top: 5px;
        }

        .section-divider {
            height: 1px;
            background: linear-gradient(to right, transparent, rgba(0,0,0,0.05), transparent);
        }

        .bg-soft-emerald {
            background-color: var(--emerald-soft);
        }

        .elite-floating-group {
            position: relative;
            margin-bottom: 10px;
        }

        .elite-input {
            height: 65px;
            padding: 25px 20px 10px 55px !important;
            border-radius: 18px !important;
            border: 2px solid transparent !important;
            background-color: #f8fafc !important;
            font-weight: 600;
            transition: var(--transition-smooth);
        }

        [dir="rtl"] .elite-input {
            padding: 25px 55px 10px 20px !important;
        }

        .elite-input:focus {
            background-color: white !important;
            border-color: var(--emerald-primary) !important;
            box-shadow: 0 10px 25px rgba(16, 185, 129, 0.1) !important;
        }

        .elite-floating-group label {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            left: 55px;
            color: #94a3b8;
            pointer-events: none;
            transition: var(--transition-smooth);
            font-weight: 500;
            margin-bottom: 0;
        }

        [dir="rtl"] .elite-floating-group label {
            left: auto;
            right: 55px;
        }

        .input-icon {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            left: 20px;
            color: #94a3b8;
            font-size: 1.2rem;
            transition: var(--transition-smooth);
            z-index: 5;
        }

        [dir="rtl"] .input-icon {
            left: auto;
            right: 20px;
        }

        .elite-input:focus + label,
        .elite-input:not(:placeholder-shown) + label {
            top: 15px;
            transform: translateY(0);
            font-size: 0.75rem;
            color: var(--emerald-primary);
        }

        .elite-input:focus ~ .input-icon {
            color: var(--emerald-primary);
        }

        .error-msg {
            color: #ef4444;
            font-size: 0.8rem;
            font-weight: 600;
            margin-top: 5px;
            margin-inline-start: 15px;
        }

        .guardian-status-badge {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            left: auto;
            right: 15px;
            background: #10b981;
            color: white;
            padding: 4px 12px;
            border-radius: 10px;
            font-size: 0.75rem;
            font-weight: bold;
            z-index: 6;
        }

        [dir="rtl"] .guardian-status-badge {
            right: auto;
            left: 15px;
        }

        .guardian-alert {
            margin-top: 10px;
            background: #dcfce7;
            color: #166534;
            padding: 12px 20px;
            border-radius: 15px;
            font-size: 0.85rem;
            border-inline-start: 4px solid #10b981;
        }

        .btn-emerald-elite {
            background: linear-gradient(135deg, #10b981, #059669);
            color: white;
            border: none;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            font-size: 1.1rem;
        }

        .btn-emerald-elite:hover {
            transform: scale(1.02);
            box-shadow: 0 15px 30px rgba(16, 185, 129, 0.3);
            color: white;
        }

        .bg-light-gray { background-color: #fcfcfc; }
        .text-emerald { color: #10b981; }
        .fw-black { font-weight: 900; }
        .letter-spacing-tight { letter-spacing: -0.05em; }
        .letter-spacing-1 { letter-spacing: 0.1em; }
    </style>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // 1. Numeric check for phones
        const phoneInputs = document.querySelectorAll('input[type="tel"]');
        phoneInputs.forEach(input => {
            input.addEventListener('input', function() {
                this.value = this.value.replace(/[^0-9]/g, '');
            });
        });

        // 2. Guardian Lookup
        const parentPhoneInput = document.getElementById('parent_phone');
        const badge = document.getElementById('guardian-found-badge');
        const alertBox = document.getElementById('guardian-info-alert');
        const nameSpan = document.getElementById('found-guardian-name');
        const parentNameInput = document.getElementById('pNameInput');

        let timeout = null;
        parentPhoneInput.addEventListener('input', function() {
            clearTimeout(timeout);
            const phone = this.value.trim();
            
            if (phone.length >= 8) {
                timeout = setTimeout(() => {
                    fetch(`{{ route('center.guardians.lookup') }}?phone=${phone}`)
                        .then(response => response.json())
                        .then(data => {
                            if (data.found) {
                                badge.classList.remove('d-none');
                                alertBox.classList.remove('d-none');
                                nameSpan.textContent = data.guardian.name;
                                if (!parentNameInput.value) parentNameInput.value = data.guardian.name;
                                
                                // Visual feedback
                                parentPhoneInput.style.borderColor = '#10b981';
                            } else {
                                badge.classList.add('d-none');
                                alertBox.classList.add('d-none');
                                parentPhoneInput.style.borderColor = 'transparent';
                            }
                        });
                }, 500);
            } else {
                badge.classList.add('d-none');
                alertBox.classList.add('d-none');
                parentPhoneInput.style.borderColor = 'transparent';
            }
        });
    });
</script>
@endsection
