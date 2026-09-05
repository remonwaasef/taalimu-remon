                <!-- Tab: Basic Info -->
                <div class="tab-pane fade show active" id="pills-info">
                    <div class="row g-3">
                        <!-- Right Column: Basic Info & Contact -->
                        <div class="col-lg-8">
                            <!-- Basic Information Card -->
                            <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-3 bg-white">
                                <div class="card-header bg-white border-bottom px-4 py-3 d-flex align-items-center justify-content-between">
                                    <h6 class="fw-bold mb-0 text-dark d-flex align-items-center gap-2">
                                        <i class="fas fa-id-card-alt text-primary"></i>
                                        <span>{{ __('center::students.profile.basic_info.title') }}</span>
                                    </h6>
                                    <span class="badge bg-light text-muted border rounded-pill px-2.5 py-1 text-xs">
                                        {{ $student->grade_level_name }}
                                    </span>
                                </div>
                                <div class="card-body p-4">
                                    <div class="row g-3">
                                        <!-- School & Section -->
                                        <div class="col-sm-6">
                                            <div class="p-2.5 rounded-xl bg-light bg-opacity-60 border border-light">
                                                <small class="text-muted d-flex align-items-center gap-1.5 mb-1 text-xs">
                                                    <i class="fas fa-school text-primary"></i>
                                                    <span>{{ __('center::students.profile.basic_info.school_info') }}</span>
                                                </small>
                                                <div class="fw-bold text-dark text-sm">
                                                    {{ $student->school_name ?? __('center::students.profile.basic_info.no_school') }}
                                                    @if($student->section_type)
                                                        <span class="badge bg-white border text-dark ms-1">{{ $student->section_type }}</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Address -->
                                        <div class="col-sm-6">
                                            <div class="p-2.5 rounded-xl bg-light bg-opacity-60 border border-light">
                                                <small class="text-muted d-flex align-items-center gap-1.5 mb-1 text-xs">
                                                    <i class="fas fa-map-marker-alt text-danger"></i>
                                                    <span>{{ __('center::students.profile.basic_info.address') }}</span>
                                                </small>
                                                <div class="fw-bold text-dark text-sm truncate" title="{{ $student->address }}">
                                                    {{ $student->address ?? __('center::students.profile.basic_info.no_address') }}
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Birth Date -->
                                        <div class="col-sm-4">
                                            <div class="p-2.5 rounded-xl bg-light bg-opacity-60 border border-light">
                                                <small class="text-muted d-flex align-items-center gap-1.5 mb-1 text-xs">
                                                    <i class="fas fa-birthday-cake text-success"></i>
                                                    <span>{{ __('center::students.profile.basic_info.birth_date') }}</span>
                                                </small>
                                                <div class="fw-bold text-dark text-sm">
                                                    {{ $student->birth_date ? $student->birth_date->format('Y/m/d') : '---' }}
                                                </div>
                                            </div>
                                        </div>

                                        <!-- National ID -->
                                        <div class="col-sm-4">
                                            <div class="p-2.5 rounded-xl bg-light bg-opacity-60 border border-light">
                                                <small class="text-muted d-flex align-items-center gap-1.5 mb-1 text-xs">
                                                    <i class="fas fa-id-card text-info"></i>
                                                    <span>{{ __('center::students.profile.basic_info.national_id') }}</span>
                                                </small>
                                                <div class="fw-bold text-dark text-sm font-monospace" dir="ltr">
                                                    {{ $student->national_id ?? '---' }}
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Joined At -->
                                        <div class="col-sm-4">
                                            <div class="p-2.5 rounded-xl bg-light bg-opacity-60 border border-light">
                                                <small class="text-muted d-flex align-items-center gap-1.5 mb-1 text-xs">
                                                    <i class="fas fa-calendar-alt text-warning"></i>
                                                    <span>{{ __('center::students.profile.basic_info.joined_at') }}</span>
                                                </small>
                                                <div class="fw-bold text-dark text-sm">
                                                    {{ $student->joined_at ? $student->joined_at->format('Y/m/d') : $student->created_at->format('Y/m/d') }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Contact & Family Card -->
                            <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-3 bg-white">
                                <div class="card-header bg-white border-bottom px-4 py-3">
                                    <h6 class="fw-bold text-dark mb-0 d-flex align-items-center gap-2">
                                        <i class="fas fa-address-book text-primary"></i>
                                        <span>{{ __('center::students.profile.contact_info') }}</span>
                                    </h6>
                                </div>
                                
                                <div class="card-body p-4">
                                    <div class="row g-3">
                                        <!-- Student Phone -->
                                        <div class="col-sm-6">
                                            <div class="p-3 rounded-xl border border-light bg-light bg-opacity-50 d-flex align-items-center justify-content-between h-100">
                                                <div class="d-flex align-items-center gap-2.5">
                                                    <div class="w-8 h-8 rounded-lg bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center shrink-0">
                                                        <i class="fas fa-mobile-alt text-xs"></i>
                                                    </div>
                                                    <div>
                                                        <small class="text-muted d-block text-xs mb-0.5">{{ __('center::students.profile.student_phone') }}</small>
                                                        <span class="fw-bold text-dark text-sm font-monospace" dir="ltr">{{ $student->phone }}</span>
                                                    </div>
                                                </div>
                                                <div class="d-flex align-items-center gap-1.5">
                                                    <a href="tel:{{ $student->phone }}" class="btn btn-sm btn-white border rounded-lg text-primary shadow-xs" title="اتصال"><i class="fas fa-phone-alt text-xs"></i></a>
                                                    @php
                                                        $studentWa = sanitizePhoneForWhatsApp($student->phone);
                                                        $waPayMsg = __('center::students.wa_student_payment_msg', ['name' => $student->name]);
                                                        $waAttMsg = __('center::students.wa_student_attendance_msg', ['name' => $student->name]);
                                                    @endphp
                                                    <div class="dropdown">
                                                        <button class="btn btn-sm btn-white border text-success rounded-lg shadow-xs" type="button" data-bs-toggle="dropdown" aria-expanded="false" title="واتساب">
                                                            <i class="fab fa-whatsapp text-xs"></i>
                                                        </button>
                                                        <ul class="dropdown-menu shadow-sm border-0 rounded-4">
                                                            <li><a class="dropdown-item d-flex align-items-center gap-2 text-xs py-2" href="https://web.whatsapp.com/send?phone={{ $studentWa }}" onclick="openSmartWhatsApp('{{ $studentWa }}', ''); return false;" target="_blank"><i class="fas fa-comment text-muted"></i> {{ __('center::students.wa_general_msg') }}</a></li>
                                                            <li><hr class="dropdown-divider"></li>
                                                            <li><a class="dropdown-item text-danger d-flex align-items-center gap-2 text-xs py-2" href="https://web.whatsapp.com/send?phone={{ $studentWa }}&text={{ urlencode($waPayMsg) }}" onclick="openSmartWhatsApp('{{ $studentWa }}', @json($waPayMsg)); return false;" target="_blank"><i class="fas fa-file-invoice-dollar"></i> {{ __('center::students.wa_payment_reminder') }}</a></li>
                                                            <li><a class="dropdown-item text-warning d-flex align-items-center gap-2 text-xs py-2" href="https://web.whatsapp.com/send?phone={{ $studentWa }}&text={{ urlencode($waAttMsg) }}" onclick="openSmartWhatsApp('{{ $studentWa }}', @json($waAttMsg)); return false;" target="_blank"><i class="fas fa-user-clock"></i> {{ __('center::students.wa_attendance_alert') }}</a></li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Guardian Phone -->
                                        <div class="col-sm-6">
                                            <div class="p-3 rounded-xl border border-light bg-light bg-opacity-50 d-flex align-items-center justify-content-between h-100">
                                                <div class="d-flex align-items-center gap-2.5">
                                                    <div class="w-8 h-8 rounded-lg bg-info bg-opacity-10 text-info d-flex align-items-center justify-content-center shrink-0">
                                                        <i class="fas fa-user-shield text-xs"></i>
                                                    </div>
                                                    <div>
                                                        <small class="text-muted d-block text-xs mb-0.5">{{ __('center::students.guardian_relation', ['relation' => $student->parent_relation ?? __('center::students.profile.basic_info.parent_default')]) }}</small>
                                                        <span class="fw-bold text-dark text-sm font-monospace" dir="ltr">{{ $student->guardian?->phone ?? $student->parent_phone ?? '—' }}</span>
                                                    </div>
                                                </div>
                                                @if($student->guardian?->phone || $student->parent_phone)
                                                    <div class="d-flex align-items-center gap-1.5">
                                                        <a href="tel:{{ $student->guardian?->phone ?? $student->parent_phone }}" class="btn btn-sm btn-white border rounded-lg text-primary shadow-xs" title="اتصال"><i class="fas fa-phone-alt text-xs"></i></a>
                                                        @php
                                                            $guardianWa = sanitizePhoneForWhatsApp($student->guardian?->phone ?? $student->parent_phone);
                                                            $waGPayMsg = __('center::students.wa_guardian_payment_msg', ['name' => $student->name]);
                                                            $waGAttMsg = __('center::students.wa_guardian_attendance_msg', ['name' => $student->name]);
                                                        @endphp
                                                        <div class="dropdown">
                                                            <button class="btn btn-sm btn-white border text-success rounded-lg shadow-xs" type="button" data-bs-toggle="dropdown" aria-expanded="false" title="واتساب">
                                                                <i class="fab fa-whatsapp text-xs"></i>
                                                            </button>
                                                            <ul class="dropdown-menu shadow-sm border-0 rounded-4">
                                                                <li><a class="dropdown-item d-flex align-items-center gap-2 text-xs py-2" href="https://web.whatsapp.com/send?phone={{ $guardianWa }}" onclick="openSmartWhatsApp('{{ $guardianWa }}', ''); return false;" target="_blank"><i class="fas fa-comment text-muted"></i> {{ __('center::students.wa_general_msg') }}</a></li>
                                                                <li><hr class="dropdown-divider"></li>
                                                                <li><a class="dropdown-item text-danger d-flex align-items-center gap-2 text-xs py-2" href="https://web.whatsapp.com/send?phone={{ $guardianWa }}&text={{ urlencode($waGPayMsg) }}" onclick="openSmartWhatsApp('{{ $guardianWa }}', @json($waGPayMsg)); return false;" target="_blank"><i class="fas fa-file-invoice-dollar"></i> {{ __('center::students.wa_payment_reminder') }}</a></li>
                                                                <li><a class="dropdown-item text-warning d-flex align-items-center gap-2 text-xs py-2" href="https://web.whatsapp.com/send?phone={{ $guardianWa }}&text={{ urlencode($waGAttMsg) }}" onclick="openSmartWhatsApp('{{ $guardianWa }}', @json($waGAttMsg)); return false;" target="_blank"><i class="fas fa-user-clock"></i> {{ __('center::students.wa_attendance_alert') }}</a></li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>

                                        <!-- Student Email -->
                                        <div class="col-sm-6">
                                            <div class="p-3 rounded-xl border border-light bg-light bg-opacity-50 d-flex align-items-center justify-content-between h-100">
                                                <div class="d-flex align-items-center gap-2.5 min-w-0">
                                                    <div class="w-8 h-8 rounded-lg bg-danger bg-opacity-10 text-danger d-flex align-items-center justify-content-center shrink-0">
                                                        <i class="fas fa-envelope text-xs"></i>
                                                    </div>
                                                    <div class="min-w-0">
                                                        <small class="text-muted d-block text-xs mb-0.5">{{ __('center::students.profile.student_email') }}</small>
                                                        <span class="fw-bold text-dark text-sm truncate d-block" title="{{ $student->user->email ?? $student->email }}">{{ $student->user->email ?? $student->email ?? '---' }}</span>
                                                    </div>
                                                </div>
                                                @if($student->user?->email || $student->email)
                                                    <a href="mailto:{{ $student->user->email ?? $student->email }}" class="btn btn-sm btn-white border rounded-lg text-primary shadow-xs shrink-0 ms-2" title="إرسال بريد"><i class="fas fa-paper-plane text-xs"></i></a>
                                                @endif
                                            </div>
                                        </div>

                                        <!-- Parent Email -->
                                        <div class="col-sm-6">
                                            <div class="p-3 rounded-xl border border-light bg-light bg-opacity-50 d-flex align-items-center justify-content-between h-100">
                                                <div class="d-flex align-items-center gap-2.5 min-w-0">
                                                    <div class="w-8 h-8 rounded-lg bg-secondary bg-opacity-10 text-secondary d-flex align-items-center justify-content-center shrink-0">
                                                        <i class="fas fa-envelope-open-text text-xs"></i>
                                                    </div>
                                                    <div class="min-w-0">
                                                        <small class="text-muted d-block text-xs mb-0.5">{{ __('center::students.profile.parent_email') }}</small>
                                                        <span class="fw-bold text-dark text-sm truncate d-block" title="{{ $student->guardian?->email ?? $student->parent_email }}">{{ $student->guardian?->email ?? $student->parent_email ?? '---' }}</span>
                                                    </div>
                                                </div>
                                                @if($student->guardian?->email || $student->parent_email)
                                                    <a href="mailto:{{ $student->guardian?->email ?? $student->parent_email }}" class="btn btn-sm btn-white border rounded-lg text-primary shadow-xs shrink-0 ms-2" title="إرسال بريد"><i class="fas fa-paper-plane text-xs"></i></a>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    @if(isset($siblings) && $siblings->count() > 0)
                                        <div class="pt-3 border-top mt-3">
                                            <small class="text-muted fw-bold d-block mb-2 text-xs">{{ __('center::students.profile.siblings') }}</small>
                                            <div class="d-flex flex-wrap gap-2">
                                                @foreach($siblings as $sibling)
                                                    <a href="{{ route('center.students.show', $sibling->id) }}" class="d-inline-flex align-items-center gap-2 px-3 py-1.5 bg-light rounded-xl text-decoration-none border shadow-xs text-xs">
                                                        <i class="fas fa-user-graduate text-primary"></i>
                                                        <span class="fw-bold text-dark">{{ $sibling->name }}</span>
                                                        <span class="text-muted">({{ $sibling->grade->name ?? '-' }})</span>
                                                    </a>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Left Column: Compact QR Code & Account Security -->
                        <div class="col-lg-4">
                            <!-- Digital ID / QR Card -->
                            <div class="card border-0 shadow-sm rounded-4 p-3.5 text-center overflow-hidden position-relative mb-3 bg-white">
                                <div class="d-flex align-items-center justify-content-between pb-2 mb-3 border-bottom">
                                    <h6 class="fw-bold text-dark mb-0 text-xs d-flex align-items-center gap-1.5">
                                        <i class="fas fa-qrcode text-primary"></i>
                                        <span>{{ __('center::students.profile.qr_code') }}</span>
                                    </h6>
                                    <code class="text-primary fw-bold text-xs font-monospace">#{{ $student->code }}</code>
                                </div>
                                
                                <div class="bg-light rounded-3 p-3 mb-3 d-inline-block mx-auto border">
                                    <div id="sidebar-student-qrcode" class="d-flex justify-content-center"></div>
                                </div>
                                
                                <p class="text-[11px] text-muted mb-3 px-1">{{ __('center::students.profile.scan_qr_tip') }}</p>
                                
                                @php
                                    $magicLoginUrl = \Illuminate\Support\Facades\URL::temporarySignedRoute('center.login.magic', now()->addMinutes(15), [
                                        'student' => $student->id, 
                                        'tenant' => app('tenant')->domain
                                    ]);
                                @endphp
                                
                                <div class="d-grid gap-2">
                                    <button onclick="copyToClipboard('{{ $magicLoginUrl }}')" class="btn btn-sm btn-primary rounded-xl fw-bold shadow-xs py-2 text-xs">
                                        <i class="fas fa-magic me-1.5"></i>{{ __('center::students.profile.copy_magic_link') }}
                                    </button>
                                    <button onclick="printIDCard()" class="btn btn-sm btn-outline-secondary rounded-xl fw-bold py-2 text-xs bg-white">
                                        <i class="fas fa-print me-1.5"></i>{{ __('center::students.profile.print_id_card') }}
                                    </button>
                                </div>
                            </div>

                            <!-- Account & Security Card -->
                            <div class="card border-0 shadow-sm rounded-4 p-3 bg-light bg-opacity-60 border border-light">
                                <div class="d-flex align-items-center gap-2 mb-2">
                                    <i class="fas fa-user-lock text-warning text-sm"></i>
                                    <h6 class="fw-bold text-dark mb-0 text-xs">{{ __('center::students.profile.basic_info.security_settings') }}</h6>
                                </div>
                                <p class="text-muted text-[11px] mb-3">{{ __('center::students.profile.basic_info.security_help') }}</p>
                                <form id="resetPasswordForm" action="{{ route('center.students.reset-password', $student->id) }}" method="POST" class="d-grid">
                                    @csrf
                                    <button type="button" id="resetPasswordBtn" class="btn btn-sm btn-outline-warning text-dark rounded-xl py-2 fw-bold shadow-xs text-xs bg-white">
                                        <i class="fas fa-sync-alt me-1.5 text-warning"></i>{{ __('center::students.profile.basic_info.reset_password') }}
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
