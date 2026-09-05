        <!-- Horizontal Tabs Navigation -->
        <div class="col-12 mb-3">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white">
                <div class="card-body p-2">
                    <ul class="nav nav-pills d-flex flex-wrap align-items-center justify-content-start gap-1.5 p-0 mb-0" id="profileTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active rounded-xl px-3.5 py-2 text-xs fw-bold text-nowrap d-inline-flex align-items-center gap-2 transition-all" data-bs-toggle="pill" data-bs-target="#pills-info" type="button" role="tab">
                                <i class="fas fa-id-card-alt text-sm"></i>
                                <span>{{ __('center::students.profile.tabs.basic_info') }}</span>
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link rounded-xl px-3.5 py-2 text-xs fw-bold text-nowrap d-inline-flex align-items-center gap-2 transition-all" data-bs-toggle="pill" data-bs-target="#pills-courses" type="button" role="tab">
                                <i class="fas fa-book-open text-sm"></i>
                                <span>{{ __('center::students.profile.tabs.courses') }}</span>
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link rounded-xl px-3.5 py-2 text-xs fw-bold text-nowrap d-inline-flex align-items-center gap-2 transition-all" data-bs-toggle="pill" data-bs-target="#pills-attendance" type="button" role="tab">
                                <i class="fas fa-calendar-check text-sm"></i>
                                <span>{{ __('center::students.profile.tabs.attendance') }}</span>
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link rounded-xl px-3.5 py-2 text-xs fw-bold text-nowrap d-inline-flex align-items-center gap-2 transition-all" data-bs-toggle="pill" data-bs-target="#pills-academic" type="button" role="tab">
                                <i class="fas fa-award text-sm"></i>
                                <span>{{ __('center::students.profile.tabs.academic') }}</span>
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link rounded-xl px-3.5 py-2 text-xs fw-bold text-nowrap d-inline-flex align-items-center gap-2 transition-all" data-bs-toggle="pill" data-bs-target="#pills-sales" type="button" role="tab">
                                <i class="fas fa-receipt text-sm"></i>
                                <span>{{ __('center::students.profile.tabs.financial') }}</span>
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link rounded-xl px-3.5 py-2 text-xs fw-bold text-nowrap d-inline-flex align-items-center gap-2 transition-all" data-bs-toggle="pill" data-bs-target="#pills-points" type="button" role="tab">
                                <i class="fas fa-star text-sm"></i>
                                <span>{{ __('center::students.profile.tabs.points') }}</span>
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link rounded-xl px-3.5 py-2 text-xs fw-bold text-nowrap d-inline-flex align-items-center gap-2 transition-all" data-bs-toggle="pill" data-bs-target="#pills-activity" type="button" role="tab">
                                <i class="fas fa-history text-sm"></i>
                                <span>{{ __('center::students.profile.tabs.activity') }}</span>
                            </button>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
