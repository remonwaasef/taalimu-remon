                <!-- Tab: Academic Performance (Elete add) -->
                <div class="tab-pane fade" id="pills-academic">
                    <div class="card border-0 shadow-sm rounded-5 p-4 p-md-5">
                        <h4 class="fw-bold mb-5">{{ __('center::students.profile.academic.title') }}</h4>
                        
                        <!-- Quizzes -->
                        <div class="mb-5">
                            <h6 class="fw-bold text-dark border-start border-4 border-success ps-3 mb-4">{{ __('center::students.profile.academic.quizzes') }}</h6>
                            <div class="row g-3">
                                @forelse($quiz_attempts as $attempt)
                                    <div class="col-md-6">
                                        <div class="quiz-result-card bg-white border rounded-4 p-3 shadow-sm d-flex align-items-center gap-3">
                                            <div class="grade-badge rounded-circle {{ $attempt->score >= 50 ? 'bg-success' : 'bg-danger' }} text-white fw-bold">
                                                {{ $attempt->score }}%
                                            </div>
                                            <div class="flex-grow-1">
                                                <div class="fw-bold text-dark small mb-0">{{ $attempt->quiz->title }}</div>
                                                <small class="text-muted extra-small">{{ $attempt->completed_at->diffForHumans() }}</small>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="col-12 text-center py-4 bg-light rounded-4 text-muted">{{ __('center::students.profile.academic.no_quizzes') }}</div>
                                @endforelse
                            </div>
                        </div>

                        <!-- Assignments -->
                        <div>
                            <h6 class="fw-bold text-dark border-start border-4 border-primary ps-3 mb-4">{{ __('center::students.profile.academic.assignments') }}</h6>
                            <div class="table-responsive" data-mobile-cards>
                                <table class="table align-middle">
                                    <thead>
                                        <tr class="text-muted small">
                                            <th>{{ __('center::students.profile.academic.assignment_title') }}</th>
                                            <th>{{ __('center::students.profile.academic.date') }}</th>
                                            <th>{{ __('center::students.profile.academic.grade') }}</th>
                                            <th>{{ __('center::students.profile.academic.feedback') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($assignments as $submission)
                                            <tr>
                                                <td class="fw-bold small text-dark">{{ $submission->assignment->title }}</td>
                                                <td class="small text-muted">{{ $submission->submitted_at->format('Y/m/d') }}</td>
                                                <td><span class="badge {{ $submission->grade ? 'bg-success' : 'bg-warning' }} bg-opacity-10 text-{{ $submission->grade ? 'success' : 'warning' }} rounded-pill px-3">{{ $submission->grade ?? __('center::students.profile.academic.pending_grade') }}</span></td>
                                                <td class="small opacity-75">{{ $submission->feedback ?? '---' }}</td>
                                            </tr>
                                        @empty
                                            <tr><td colspan="4" class="text-center py-4 text-muted small">{{ __('center::students.profile.academic.no_assignments') }}</td></tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
