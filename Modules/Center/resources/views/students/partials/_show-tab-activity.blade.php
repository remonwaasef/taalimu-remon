                <!-- Tab: Activity Log -->
                <div class="tab-pane fade" id="pills-activity">
                    <div class="card border-0 shadow-sm rounded-5 p-4 p-md-5">
                        <h4 class="fw-bold mb-5">{{ __('center::students.profile.activity.title') }}</h4>
                        <div class="activities-timeline">
                            @forelse($recent_activity as $activity)
                                <div class="timeline-item d-flex gap-4 mb-4">
                                    <div class="timeline-icon bg-light text-primary rounded-circle shadow-sm d-flex align-items-center justify-content-center flex-shrink-0" style="width: 48px; height: 48px;">
                                        <i class="fas fa-history small"></i>
                                    </div>
                                    <div class="timeline-content flex-grow-1 border-bottom pb-4">
                                        <div class="d-flex justify-content-between mb-1">
                                            <h6 class="fw-bold text-dark mb-0">{{ $activity->description }}</h6>
                                            <small class="text-muted extra-small">{{ $activity->created_at->diffForHumans() }}</small>
                                        </div>
                                        <div class="text-muted small">{{ __('center::students.profile.activity.by_user') }} <span class="fw-bold">{{ $activity->causer->name ?? __('center::students.profile.activity.system') }}</span>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-5 text-muted">{{ __('center::students.profile.activity.no_activity') }}</div>
                            @endforelse
                        </div>
                    </div>
                </div>
