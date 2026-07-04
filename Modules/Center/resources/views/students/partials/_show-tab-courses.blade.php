                <!-- Tab: Enrolled Courses -->
                <div class="tab-pane fade" id="pills-courses">
                    <div class="card border-0 shadow-sm rounded-5 p-4 p-md-5">
                        <h4 class="fw-bold mb-5">{{ __('center::students.profile.tabs.courses') }}</h4>
                        <div class="row g-4">
                            @forelse($enrollments as $enrollment)
                                <div class="col-md-6">
                                    <div class="course-elite-card bg-white border rounded-5 p-4 shadow-sm hover-lift h-100">
                                        <div class="d-flex justify-content-between mb-3 align-items-start">
                                            <div class="icon-sq bg-primary bg-opacity-10 text-primary rounded-4">
                                                <i class="fas fa-book-reader"></i>
                                            </div>
                                            <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3">{{ $enrollment->status }}</span>
                                        </div>
                                        <h5 class="fw-bold text-dark mb-1">{{ $enrollment->course->title }}</h5>
                                        <p class="text-muted extra-small mb-4">{{ __('center::students.enrollment_date') }}: {{ $enrollment->enrolled_at->format('Y/m/d') }}</p>
                                        
                                        <div class="mb-2 d-flex justify-content-between small fw-bold">
                                            <span>{{ __('center::students.profile.academic.progress') }}</span>
                                            <span>{{ $enrollment->progress }}%</span>
                                        </div>
                                        <div class="progress rounded-pill bg-light" style="height: 6px;">
                                            <div class="progress-bar rounded-pill" style="width: {{ $enrollment->progress }}%"></div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="col-12 text-center py-5 bg-light rounded-5 text-muted">{{ __('center::students.profile.academic.no_courses') }}</div>
                            @endforelse
                        </div>
                    </div>
                </div>
