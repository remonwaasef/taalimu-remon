                <!-- Tab: Basic Info -->
                <div class="tab-pane fade show active" id="pills-info">
                    <div class="row g-4">
                        <!-- Left Column: Basic Info & Contact -->
                        <div class="col-lg-8">
                            <!-- Basic Information Card -->
                            <div class="card border-0 shadow-sm rounded-5 overflow-hidden mb-4">
                                <div class="card-header bg-white border-bottom p-4">
                                    <h5 class="fw-bold mb-0 text-dark"><i class="fas fa-id-card-alt text-primary ms-2"></i>{{ __('center::students.profile.basic_info.title') }}</h5>
                                </div>
                                <div class="card-body p-4">
                                    <div class="row g-4">
                                        <!-- School -->
                                        <div class="col-md-6">
                                            <div class="d-flex align-items-center gap-3">
                                                <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex flex-shrink-0 align-items-center justify-content-center" style="width: 48px; height: 48px;">
                                                    <i class="fas fa-school fs-5"></i>
                                                </div>
                                                <div>
                                                    <small class="text-muted mb-1 d-block">{{ __('center::students.profile.basic_info.school_info') }}</small>
                                                    <div class="fw-bold fs-6 text-dark">{{ $student->school_name ?? __('center::students.profile.basic_info.no_school') }} <span class="badge bg-light border text-dark ms-1">{{ $student->section_type ?? __('center::students.profile.basic_info.general_section') }}</span></div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Address -->
                                        <div class="col-md-6">
                                            <div class="d-flex align-items-center gap-3">
                                                <div class="bg-danger bg-opacity-10 text-danger rounded-circle d-flex flex-shrink-0 align-items-center justify-content-center" style="width: 48px; height: 48px;">
                                                    <i class="fas fa-map-marker-alt fs-5"></i>
                                                </div>
                                                <div>
                                                    <small class="text-muted mb-1 d-block">{{ __('center::students.profile.basic_info.address') }}</small>
                                                    <div class="fw-bold fs-6 text-dark">{{ $student->address ?? __('center::students.profile.basic_info.no_address') }}</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12"><hr class="my-0 border-light"></div>
                                        <!-- Birth Date -->
                                        <div class="col-md-4">
                                            <div class="d-flex align-items-center gap-3">
                                                <div class="bg-success bg-opacity-10 text-success rounded-circle d-flex flex-shrink-0 align-items-center justify-content-center" style="width: 48px; height: 48px;">
                                                    <i class="fas fa-birthday-cake fs-5"></i>
                                                </div>
                                                <div>
                                                    <small class="text-muted mb-1 d-block">{{ __('center::students.profile.basic_info.birth_date') }}</small>
                                                    <div class="fw-bold fs-6 text-dark">{{ $student->birth_date ? $student->birth_date->format('Y/m/d') : '---' }}</div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- National ID -->
                                        <div class="col-md-4">
                                            <div class="d-flex align-items-center gap-3">
                                                <div class="bg-indigo bg-opacity-10 text-indigo rounded-circle d-flex flex-shrink-0 align-items-center justify-content-center" style="width: 48px; height: 48px;">
                                                    <i class="fas fa-id-card fs-5"></i>
                                                </div>
                                                <div>
                                                    <small class="text-muted mb-1 d-block">{{ __('center::students.profile.basic_info.national_id') }}</small>
                                                    <div class="fw-bold fs-6 text-dark" dir="ltr">{{ $student->national_id ?? '---' }}</div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Joined At -->
                                        <div class="col-md-4">
                                            <div class="d-flex align-items-center gap-3">
                                                <div class="bg-warning bg-opacity-10 text-warning rounded-circle d-flex flex-shrink-0 align-items-center justify-content-center" style="width: 48px; height: 48px;">
                                                    <i class="fas fa-calendar-alt fs-5"></i>
                                                </div>
                                                <div>
                                                    <small class="text-muted mb-1 d-block">{{ __('center::students.profile.basic_info.joined_at') }}</small>
                                                    <div class="fw-bold fs-6 text-dark">{{ $student->joined_at ? $student->joined_at->format('Y/m/d') : $student->created_at->format('Y/m/d') }}</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Contact & Family Card -->
                            <div class="card border-0 shadow-sm rounded-5 overflow-hidden mb-4">
                                <div class="card-header bg-white border-bottom p-4">
                                    <h5 class="fw-bold text-primary mb-0"><i class="fas fa-users-cog ms-2"></i>{{ __('center::students.profile.contact_info') }}</h5>
                                </div>
                                
                                <div class="card-body p-4">
                                    <div class="row g-3">
                                        <!-- Student Phone -->
                                        <div class="col-sm-6">
                                            <div class="p-3 border rounded-4 d-flex align-items-center justify-content-between bg-light bg-opacity-50 hover-lift transition-all h-100">
                                                <div class="d-flex align-items-center gap-3">
                                                    <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width: 42px; height: 42px;">
                                                        <i class="fas fa-mobile-alt"></i>
                                                    </div>
                                                    <div>
                                                        <small class="text-muted d-block mb-1">{{ __('center::students.profile.student_phone') }}</small>
                                                        <span class="fw-bold fs-6 text-dark" dir="ltr">{{ $student->phone }}</span>
                                                    </div>
                                                </div>
                                                <div class="d-flex gap-2">
                                                    <a href="tel:{{ $student->phone }}" class="btn btn-sm btn-white border rounded-circle text-primary shadow-sm" title="اتصال"><i class="fas fa-phone-alt"></i></a>
                                                    <div class="dropdown">
                                                        <button class="btn btn-sm btn-white border text-success rounded-circle shadow-sm" type="button" data-bs-toggle="dropdown" aria-expanded="false" title="واتساب">
                                                            <i class="fab fa-whatsapp"></i>
                                                        </button>
                                                        <ul class="dropdown-menu shadow-sm border-0 rounded-4">
                                                            <li><a class="dropdown-item d-flex align-items-center gap-2" href="https://wa.me/{{ sanitizePhoneForWhatsApp($student->phone) }}" target="_blank"><i class="fas fa-comment text-muted"></i> {{ __('center::students.wa_general_msg') }}</a></li>
                                                            <li><hr class="dropdown-divider"></li>
                                                            <li><a class="dropdown-item text-danger d-flex align-items-center gap-2" href="https://wa.me/{{ sanitizePhoneForWhatsApp($student->phone) }}?text={{ urlencode(__('center::students.wa_student_payment_msg', ['name' => $student->name])) }}" target="_blank"><i class="fas fa-file-invoice-dollar"></i> {{ __('center::students.wa_payment_reminder') }}</a></li>
                                                            <li><a class="dropdown-item text-warning d-flex align-items-center gap-2" href="https://wa.me/{{ sanitizePhoneForWhatsApp($student->phone) }}?text={{ urlencode(__('center::students.wa_student_attendance_msg', ['name' => $student->name])) }}" target="_blank"><i class="fas fa-user-clock"></i> {{ __('center::students.wa_attendance_alert') }}</a></li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Guardian Phone -->
                                        <div class="col-sm-6">
                                            <div class="p-3 border rounded-4 d-flex align-items-center justify-content-between bg-light bg-opacity-50 hover-lift transition-all h-100">
                                                <div class="d-flex align-items-center gap-3">
                                                    <div class="bg-info bg-opacity-10 text-info rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width: 42px; height: 42px;">
                                                        <i class="fas fa-user-shield"></i>
                                                    </div>
                                                    <div>
                                                        <small class="text-muted d-block mb-1">{{ __('center::students.guardian_relation', ['relation' => $student->parent_relation ?? __('center::students.profile.basic_info.parent_default')]) }}</small>
                                                        <span class="fw-bold fs-6 text-dark" dir="ltr">{{ $student->guardian?->phone ?? $student->parent_phone }}</span>
                                                    </div>
                                                </div>
                                                <div class="d-flex gap-2">
                                                    <a href="tel:{{ $student->guardian?->phone ?? $student->parent_phone }}" class="btn btn-sm btn-white border rounded-circle text-primary shadow-sm" title="اتصال"><i class="fas fa-phone-alt"></i></a>
                                                    <div class="dropdown">
                                                        <button class="btn btn-sm btn-white border text-success rounded-circle shadow-sm" type="button" data-bs-toggle="dropdown" aria-expanded="false" title="واتساب">
                                                            <i class="fab fa-whatsapp"></i>
                                                        </button>
                                                        <ul class="dropdown-menu shadow-sm border-0 rounded-4">
                                                            <li><a class="dropdown-item d-flex align-items-center gap-2" href="https://wa.me/{{ sanitizePhoneForWhatsApp($student->guardian?->phone ?? $student->parent_phone) }}" target="_blank"><i class="fas fa-comment text-muted"></i> {{ __('center::students.wa_general_msg') }}</a></li>
                                                            <li><hr class="dropdown-divider"></li>
                                                            <li><a class="dropdown-item text-danger d-flex align-items-center gap-2" href="https://wa.me/{{ sanitizePhoneForWhatsApp($student->guardian?->phone ?? $student->parent_phone) }}?text={{ urlencode(__('center::students.wa_guardian_payment_msg', ['name' => $student->name])) }}" target="_blank"><i class="fas fa-file-invoice-dollar"></i> {{ __('center::students.wa_payment_reminder') }}</a></li>
                                                            <li><a class="dropdown-item text-warning d-flex align-items-center gap-2" href="https://wa.me/{{ sanitizePhoneForWhatsApp($student->guardian?->phone ?? $student->parent_phone) }}?text={{ urlencode(__('center::students.wa_guardian_attendance_msg', ['name' => $student->name])) }}" target="_blank"><i class="fas fa-user-clock"></i> {{ __('center::students.wa_attendance_alert') }}</a></li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Student Email -->
                                        <div class="col-sm-6">
                                            <div class="p-3 border rounded-4 d-flex align-items-center justify-content-between bg-light bg-opacity-50 hover-lift transition-all h-100">
                                                <div class="d-flex align-items-center gap-3 w-100 overflow-hidden">
                                                    <div class="bg-danger bg-opacity-10 text-danger rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width: 42px; height: 42px;">
                                                        <i class="fas fa-envelope"></i>
                                                    </div>
                                                    <div class="text-truncate w-100 pe-2">
                                                        <small class="text-muted d-block mb-1">{{ __('center::students.profile.student_email') }}</small>
                                                        <span class="fw-bold fs-6 text-dark text-truncate d-block" title="{{ $student->user->email ?? $student->email }}">{{ $student->user->email ?? $student->email ?? '---' }}</span>
                                                    </div>
                                                </div>
                                                @if($student->user?->email || $student->email)
                                                    <div class="flex-shrink-0">
                                                        <a href="mailto:{{ $student->user->email ?? $student->email }}" class="btn btn-sm btn-white border rounded-circle text-primary shadow-sm" title="إرسال بريد"><i class="fas fa-paper-plane"></i></a>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>

                                        <!-- Parent Email -->
                                        <div class="col-sm-6">
                                            <div class="p-3 border rounded-4 d-flex align-items-center justify-content-between bg-light bg-opacity-50 hover-lift transition-all h-100">
                                                <div class="d-flex align-items-center gap-3 w-100 overflow-hidden">
                                                    <div class="bg-secondary bg-opacity-10 text-secondary rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width: 42px; height: 42px;">
                                                        <i class="fas fa-envelope-open-text"></i>
                                                    </div>
                                                    <div class="text-truncate w-100 pe-2">
                                                        <small class="text-muted d-block mb-1">{{ __('center::students.profile.parent_email') }}</small>
                                                        <span class="fw-bold fs-6 text-dark text-truncate d-block" title="{{ $student->guardian?->email ?? $student->parent_email }}">{{ $student->guardian?->email ?? $student->parent_email ?? '---' }}</span>
                                                    </div>
                                                </div>
                                                @if($student->guardian?->email || $student->parent_email)
                                                    <div class="flex-shrink-0">
                                                        <a href="mailto:{{ $student->guardian?->email ?? $student->parent_email }}" class="btn btn-sm btn-white border rounded-circle text-primary shadow-sm" title="إرسال بريد"><i class="fas fa-paper-plane"></i></a>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    @if($siblings->count() > 0)
                                        <div class="pt-4 border-top mt-4">
                                            <h6 class="fw-bold text-dark small mb-3">{{ __('center::students.profile.siblings') }}</h6>
                                            <div class="d-flex flex-wrap gap-2">
                                                @foreach($siblings as $sibling)
                                                    <a href="{{ route('center.students.show', $sibling->id) }}" class="sibling-chip d-flex align-items-center gap-2 p-2 bg-light rounded-4 text-decoration-none hover-lift border shadow-sm" style="min-width: 180px;">
                                                        <div class="bg-white rounded-circle p-2 shadow-sm text-primary">
                                                            <i class="fas fa-user-graduate small"></i>
                                                        </div>
                                                        <div class="flex-grow-1">
                                                            <div class="fw-bold text-dark small mb-0">{{ $sibling->name }}</div>
                                                            <small class="text-muted extra-small">{{ $sibling->grade->name ?? '-' }}</small>
                                                        </div>
                                                    </a>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Right Column: QR Code & Security -->
                        <div class="col-lg-4">
                            <!-- QR Code Card -->
                            <div class="card border-0 shadow-sm rounded-5 p-4 text-center overflow-hidden position-relative mb-4 bg-white">
                                <div class="position-absolute top-0 end-0 p-3 opacity-10">
                                    <i class="fas fa-qrcode fs-1"></i>
                                </div>
                                <h5 class="fw-bold text-dark border-bottom pb-3 mb-4">{{ __('center::students.profile.qr_code') }}</h5>
                                
                                <div class="qr-display-container bg-light rounded-4 p-4 mb-4 position-relative shadow-inner d-inline-block">
                                    <div id="sidebar-student-qrcode" class="d-flex justify-content-center"></div>
                                    <div class="mt-3">
                                        <code class="text-primary fw-bold fs-4">#{{ $student->code }}</code>
                                    </div>
                                </div>
                                
                                <p class="small text-muted mb-4 px-2">{{ __('center::students.profile.scan_qr_tip') }}</p>
                                
                                @php
                                    $magicLoginUrl = \Illuminate\Support\Facades\URL::temporarySignedRoute('center.login.magic', now()->addMinutes(15), [
                                        'student' => $student->id, 
                                        'tenant' => app('tenant')->domain
                                    ]);
                                @endphp
                                
                                <div class="d-grid gap-2">
                                    <button onclick="copyToClipboard('{{ $magicLoginUrl }}')" class="btn btn-primary rounded-pill fw-bold shadow-sm py-2">
                                        <i class="fas fa-magic me-2"></i>{{ __('center::students.profile.copy_magic_link') }}</button>
                                    <button onclick="printIDCard()" class="btn btn-outline-dark rounded-pill fw-bold border-2 py-2">
                                        <i class="fas fa-print me-2"></i>{{ __('center::students.profile.print_id_card') }}</button>
                                </div>
                            </div>

                            <!-- Security Settings Card -->
                            <div class="card border-0 shadow-sm rounded-5 p-4 bg-warning bg-opacity-10 border-dashed-warning">
                                <div class="text-center mb-3">
                                    <div class="bg-warning bg-opacity-25 text-warning rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                                        <i class="fas fa-user-lock fs-3"></i>
                                    </div>
                                    <h5 class="fw-bold text-warning mb-2">{{ __('center::students.profile.basic_info.security_settings') }}</h5>
                                    <p class="text-muted small px-3">{{ __('center::students.profile.basic_info.security_help') }}</p>
                                </div>
                                <form id="resetPasswordForm" action="{{ route('center.students.reset-password', $student->id) }}" method="POST" class="d-grid">
                                    @csrf
                                    <button type="button" id="resetPasswordBtn" class="btn btn-warning rounded-pill py-2 fw-bold shadow-sm">
                                        <i class="fas fa-sync-alt me-2"></i>{{ __('center::students.profile.basic_info.reset_password') }}</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
