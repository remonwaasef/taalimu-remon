                <!-- Tab: Attendance (Elite add) -->
                <div class="tab-pane fade" id="pills-attendance">
                    <div class="card border-0 shadow-sm rounded-5 p-4 p-md-5">
                        <div class="d-flex justify-content-between align-items-center mb-5">
                            <h4 class="fw-bold mb-0">{{ __('center::students.profile.attendance.title') }}</h4>
                            <div class="d-flex gap-2">
                                <div class="badge bg-success rounded-pill px-3">{{ __('center::students.present') }}: {{ $attendance_logs->where('status', 'present')->count() }}</div>
                                <div class="badge bg-danger rounded-pill px-3">{{ __('center::students.absent') }}: {{ $attendance_logs->where('status', 'absent')->count() }}</div>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table align-middle">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="border-0 rounded-start px-4">{{ __('center::students.profile.attendance.date') }}</th>
                                        <th class="border-0">{{ __('center::students.session_content') }}</th>
                                        <th class="border-0">{{ __('center::students.profile.attendance.check_in') }}</th>
                                        <th class="border-0 rounded-end px-4">{{ __('center::students.profile.attendance.status') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($attendance_logs as $log)
                                        <tr>
                                            <td class="px-4 fw-bold small">{{ $log->session_date->format('Y-m-d') }}</td>
                                            <td>
                                                <div class="small fw-bold text-dark">{{ $log->course->title }}</div>
                                                <small class="text-muted extra-small">{{ __('center::schedules.' . $log->schedule->day_of_week) }} ({{ $log->schedule->start_time }})</small>
                                            </td>
                                            <td class="small text-muted">{{ $log->check_in_time ? $log->check_in_time->format('h:i A') : '---' }}</td>
                                            <td class="px-4">
                                                <span class="badge bg-{{ $log->status == 'present' ? 'success' : ($log->status == 'absent' ? 'danger' : 'warning') }} bg-opacity-10 text-{{ $log->status == 'present' ? 'success' : ($log->status == 'absent' ? 'danger' : 'warning') }} rounded-pill px-3 font-arabic">
                                                    {{ $log->status == 'present' ? __('center::students.present') : ($log->status == 'absent' ? __('center::students.absent') : __('center::students.late')) }}
                                                </span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="4" class="text-center py-5 text-muted">{{ __('center::students.profile.attendance.no_logs') }}</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
