                <!-- Tab: Bookings -->
                <div class="tab-pane fade" id="pills-bookings">
                    <div class="card border-0 shadow-sm rounded-5 p-4 p-md-5">
                        <div class="d-flex justify-content-between align-items-center mb-5">
                            <h4 class="fw-bold mb-0">{{ __('center::students.profile.bookings.title') }}</h4>
                            <button type="button" class="btn btn-primary rounded-pill px-4 fw-bold shadow-sm" data-bs-toggle="modal" data-bs-target="#addBookingModal">
                                <i class="fas fa-plus me-2"></i>{{ __('center::students.profile.bookings.add_booking') }}</button>
                        </div>

                        <div class="table-responsive">
                            <table class="table align-middle">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="border-0 rounded-start px-4">{{ __('center::students.profile.bookings.course') }}</th>
                                        <th class="border-0">{{ __('center::students.profile.bookings.time') }}</th>
                                        <th class="border-0">{{ __('center::students.profile.bookings.classroom') }}</th>
                                        <th class="border-0 rounded-end px-4">{{ __('center::students.profile.bookings.status') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($bookings as $booking)
                                        <tr>
                                            <td class="px-4 fw-bold small text-dark">{{ $booking->schedule->course?->title ?? '—' }}</td>
                                            <td>
                                                <div class="small fw-bold">{{ __('center::schedules.' . $booking->schedule->day_of_week) }}</div>
                                                <small class="text-muted extra-small">{{ $booking->schedule->start_time }} - {{ $booking->schedule->end_time }}</small>
                                            </td>
                                            <td class="small text-muted">{{ $booking->schedule->classroom->name }}</td>
                                            <td class="px-4">
                                                <span class="badge bg-{{ $booking->status == 'confirmed' ? 'success' : 'danger' }} bg-opacity-10 text-{{ $booking->status == 'confirmed' ? 'success' : 'danger' }} rounded-pill px-3">
                                                    {{ $booking->status }}
                                                </span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="4" class="text-center py-5 text-muted">{{ __('center::students.profile.bookings.no_bookings') }}</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
