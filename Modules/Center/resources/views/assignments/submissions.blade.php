@extends('center::layouts.app-next')

@section('panel-content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Submissions: {{ $assignment->title }}</h1>
        <a href="{{ route('center.assignments.edit', $assignment) }}" class="btn btn-secondary">Back to Assignment</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card">
        <div class="card-body">
            <div class="table-responsive" data-mobile-cards>
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Student</th>
                            <th>Submitted At</th>
                            <th>File</th>
                            <th>Grade</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($submissions as $submission)
                            <tr>
                                <td>{{ $submission->user->name }} <br> <small class="text-muted">{{ $submission->user->email }}</small></td>
                                <td>
                                    {{ $submission->submitted_at->format('M d, Y H:i') }}
                                    @if($assignment->due_date && $submission->submitted_at->gt($assignment->due_date))
                                        <span class="badge bg-danger">Late</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('center.submissions.download', $submission) }}" class="btn btn-sm btn-outline-primary"><i class="fas fa-download"></i> Download</a>
                                </td>
                                <td>
                                    @if($submission->grade !== null)
                                        <span class="badge bg-success">{{ $submission->grade }} / {{ $assignment->max_score }}</span>
                                    @else
                                        <span class="badge bg-warning text-dark">Pending</span>
                                    @endif
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#gradeModal-{{ $submission->id }}">
                                        <i class="fas fa-check-square"></i> Grade
                                    </button>

                                    <!-- Grade Modal -->
                                    <div class="modal fade" id="gradeModal-{{ $submission->id }}" tabindex="-1">
                                        <div class="modal-dialog">
                                            <form action="{{ route('center.submissions.grade', $submission) }}" method="POST">
                                                @csrf
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Grade Submission: {{ $submission->user->name }}</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="mb-3">
                                                            <label class="form-label">Grade (Max: {{ $assignment->max_score }})</label>
                                                            <input type="number" name="grade" class="form-control" value="{{ $submission->grade }}" min="0" max="{{ $assignment->max_score }}" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label">Feedback</label>
                                                            <textarea name="feedback" class="form-control" rows="3">{{ $submission->feedback }}</textarea>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                        <button type="submit" class="btn btn-primary">Save Grade</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted">No submissions yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
