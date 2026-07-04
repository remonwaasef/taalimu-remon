                <!-- Tab: Points Log -->
                <div class="tab-pane fade" id="pills-points">
                    <div class="card border-0 shadow-sm rounded-5 p-4 p-md-5">
                        <div class="d-flex justify-content-between align-items-center mb-5">
                            <h4 class="fw-bold mb-0">{{ __('center::students.profile.points.title') }}</h4>
                            <div class="badge bg-indigo-accent text-white rounded-pill px-4 py-2 fs-6 shadow-sm">
                                {{ __('center::students.points_total', ['points' => $stats['points']]) }}
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table align-middle">
                                <thead>
                                    <tr class="text-muted small">
                                        <th>{{ __('center::students.profile.points.points') }}</th>
                                        <th>{{ __('center::students.profile.points.reason') }}</th>
                                        <th>{{ __('center::students.profile.points.date') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($point_logs as $log)
                                        <tr>
                                            <td>
                                                <span class="badge {{ $log->points > 0 ? 'bg-success' : 'bg-danger' }} rounded-pill px-3">
                                                    {{ $log->points > 0 ? '+' : '' }}{{ $log->points }}
                                                </span>
                                            </td>
                                            <td class="fw-bold small">{{ $log->reason }}</td>
                                            <td class="small text-muted">{{ $log->created_at->format('Y-m-d h:i A') }}</td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="3" class="text-center py-5 text-muted">{{ __('center::students.profile.points.no_logs') }}</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
