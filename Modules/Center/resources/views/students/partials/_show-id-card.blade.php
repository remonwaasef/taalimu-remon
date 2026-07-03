    <!-- ID Card Print Layout (positioned off-screen until print) -->
    <div class="id-card-print">
        <div class="id-card-container">
            <!-- Front of Card -->
            <div class="id-card">
                <!-- Header / Logo Area -->
                <div class="id-header">
                    <div class="logo-area">
                        @if($tenant->logo)
                            <img src="{{ asset('storage/' . $tenant->logo) }}" alt="Logo">
                        @else
                            <i class="fas fa-graduation-cap fa-2x text-white"></i>
                        @endif
                    </div>
                    <div class="center-name">
                        <h1>{{ $tenant->name ?? __('center::students.profile.id_card.center_name_fallback') }}</h1>
                        <span>{{ __('center::students.profile.id_card.title') }}</span>
                    </div>
                </div>

                <!-- Main Content -->
                <div class="id-body">
                    <div class="student-photo-wrapper">
                        @if($student->profile_photo)
                            <img src="{{ asset('storage/' . $student->profile_photo) }}" class="student-photo">
                        @else
                             <div class="student-photo-placeholder">{{ substr($student->name, 0, 1) }}</div>
                        @endif
                        <div class="status-indicator"></div>
                    </div>

                    <h2 class="student-name">{{ $student->name }}</h2>
                    <div class="student-meta">
                        <span class="grade-badge">{{ $student->grade_level_name ?? '---' }}</span>
                    </div>

                    <div class="info-grid">
                        <div class="info-item">
                            <label>{{ __('center::students.profile.id_card.student_code') }}</label>
                            <strong>{{ $student->code }}</strong>
                        </div>
                        <div class="info-item">
                            <label>{{ __('center::students.profile.id_card.academic_year') }}</label>
                            <strong>{{ date('Y') }} - {{ date('Y')+1 }}</strong>
                        </div>
                    </div>

                    <div class="qr-area">
                        <div id="student-qrcode"></div>
                        <span class="code-text">{{ $student->code }}</span>
                    </div>
                </div>

                <!-- Footer -->
                <div class="id-footer">
                    <p>{{ __('center::students.profile.id_card.ownership_tip') }}</p>
                </div>
            </div>
        </div>
    </div>
