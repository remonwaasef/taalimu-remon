@extends('center::layouts.hope-master')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="mb-0">{{ $assignment->title }}</h3>
                    @if($assignment->due_date)
                        <span class="badge bg-{{ $assignment->due_date->isPast() ? 'danger' : 'info' }}">
                            Due: {{ $assignment->due_date->format('M d, Y H:i') }}
                        </span>
                    @endif
                </div>
                <div class="card-body">
                    <div class="mb-4">
                        <h5>Instructions:</h5>
                        <p class="text-muted">{!! nl2br(e($assignment->description)) !!}</p>
                        <p><strong>Max Score:</strong> {{ $assignment->max_score }}</p>
                    </div>

                    <hr>

                    @if($submission)
                        <div class="alert alert-success">
                            <h5 class="alert-heading"><i class="fas fa-check-circle"></i> Submitted</h5>
                            <p class="mb-0">Submitted on: {{ $submission->submitted_at->format('M d, Y H:i') }}</p>
                            <p class="mb-0">File: <a href="{{ Storage::url($submission->file_path) }}" target="_blank">Download Submission</a></p>
                        </div>

                        @if($submission->grade !== null)
                            <div class="card bg-light border-success mb-3">
                                <div class="card-body">
                                    <h5 class="card-title text-success">Grade: {{ $submission->grade }} / {{ $assignment->max_score }}</h5>
                                    @if($submission->feedback)
                                        <p class="card-text"><strong>Feedback:</strong> {{ $submission->feedback }}</p>
                                    @endif
                                </div>
                            </div>
                        @else
                            <div class="alert alert-info">
                                Pending Grading...
                            </div>
                        @endif
                    @endif

                    @if(!$submission || ($submission && $submission->grade === null && (!$assignment->due_date || !$assignment->due_date->isPast())))
                        <h5 class="mb-3">{{ $submission ? 'Resubmit Assignment' : 'Submit Assignment' }}</h5>
                        <form action="{{ route('center.assignments.submit', $assignment) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label">Upload File (PDF, Doc, Image, Zip)</label>
                                <input type="file" name="file" class="form-control" required>
                            </div>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-upload me-1"></i> {{ $submission ? 'Update Submission' : 'Submit' }}
                            </button>
                        </form>
                    @elseif($assignment->due_date && $assignment->due_date->isPast())
                        <div class="alert alert-warning">
                            Submission closed. The due date has passed.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
