    <!-- Add Booking Modal -->
    <div class="modal fade" id="addBookingModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-elite rounded-5">
                <form action="{{ route('center.bookings.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="student_id" value="{{ $student->id }}">
                    <div class="modal-header border-0 p-4 p-md-5 pb-0">
                        <h4 class="modal-title fw-bold">{{ __('center::students.profile.bookings.modal_title') }}</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-4 p-md-5">
                        <div class="mb-4">
                            <label class="form-label fw-bold opacity-75">{{ __('center::students.select_schedule') }}</label>
                            <select name="schedule_id" class="form-select rounded-4 p-3 border-light bg-light" required>
                                <option value="">{{ __('center::students.profile.bookings.choose_schedule') }}</option>
                                @foreach($availableSchedules as $sch)
                                    <option value="{{ $sch->id }}">
                                        {{ $sch->course->title }} | {{ __('center::schedules.' . $sch->day_of_week) }} ({{ $sch->start_time }} - {{ $sch->end_time }})
                                    </option>
                                @endforeach
                            </select>
                            <small class="text-muted d-block mt-2"><i class="fas fa-info-circle me-1"></i>{{ __('center::students.profile.bookings.active_only_hint') }}</small>
                        </div>
                        <div class="mb-0">
                            <label class="form-label fw-bold opacity-75">{{ __('center::students.profile.bookings.notes_label') }}</label>
                            <textarea name="notes" class="form-control rounded-4 p-3 border-light bg-light" rows="3" placeholder="{{ __('center::students.profile.bookings.notes_placeholder') }}"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer border-0 p-4 p-md-5 pt-0">
                        <button type="button" class="btn btn-white border rounded-pill px-4 fw-bold" data-bs-dismiss="modal">{{ __('center::students.profile.bookings.cancel') }}</button>
                        <button type="submit" class="btn btn-primary rounded-pill px-5 fw-bold shadow-sm">{{ __('center::students.profile.bookings.confirm') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Send Email Modal -->
    <div class="modal fade" id="sendEmailModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-elite rounded-5">
                <div class="modal-header border-0 bg-light p-4 p-md-5 rounded-top-5 pb-4">
                    <h5 class="modal-title fw-bold"><i class="fas fa-envelope me-2 text-primary"></i> إرسال بريد إلكتروني للطالب</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('center.students.send-email', $student->id) }}" method="POST">
                    @csrf
                    <div class="modal-body p-4 p-md-5">
                        @if(!$student->email && !($student->user->email ?? null))
                            <div class="alert alert-warning small border-0 shadow-sm rounded-4 text-center">
                                <i class="fas fa-exclamation-triangle me-1"></i> هذا الطالب لا يمتلك بريداً إلكترونياً مسجلاً. قد لا ينجح الإرسال.
                            </div>
                        @endif
                        <div class="mb-4">
                            <label class="form-label fw-bold opacity-75">موضوع الرسالة (Subject) <span class="text-danger">*</span></label>
                            <input type="text" name="subject" class="form-control rounded-4 p-3 border-light bg-light" required placeholder="مثال: تنبيه غياب، تحديث بيانات، أو تحية">
                        </div>
                        <div class="mb-0">
                            <label class="form-label fw-bold opacity-75">نص الرسالة <span class="text-danger">*</span></label>
                            <textarea name="message" class="form-control rounded-4 p-3 border-light bg-light" rows="6" required placeholder="اكتب محتوى رسالتك هنا..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer border-0 p-4 p-md-5 pt-0">
                        <button type="button" class="btn btn-white border rounded-pill px-4 fw-bold" data-bs-dismiss="modal">إلغاء</button>
                        <button type="submit" class="btn btn-primary rounded-pill px-5 fw-bold shadow-sm">
                            <i class="fas fa-paper-plane me-2"></i> إرسال الآن
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
