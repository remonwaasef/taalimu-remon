@extends('center::layouts.app-next')

@section('panel-content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Assignment: {{ $assignment->title }}</h1>
        <div>
            <a href="{{ route('center.assignments.submissions', $assignment) }}" class="btn btn-info text-white me-2"><i class="fas fa-users"></i> View Submissions</a>
            <a href="{{ route('center.curriculum.edit', $assignment->lesson->section->course) }}" class="btn btn-secondary">Back to Curriculum</a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card">
        <div class="card-body">
            <form action="{{ route('center.assignments.update', $assignment) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="mb-3">
                    <label class="form-label">Title</label>
                    <input type="text" name="title" class="form-control" value="{{ $assignment->title }}" required>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Due Date</label>
                        <input type="datetime-local" name="due_date" class="form-control" value="{{ $assignment->due_date ? $assignment->due_date->format('Y-m-d\TH:i') : '' }}">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Max Score</label>
                        <input type="number" name="max_score" class="form-control" value="{{ $assignment->max_score }}" min="1" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Description / Instructions</label>
                    <textarea name="description" class="form-control" rows="5">{{ $assignment->description }}</textarea>
                </div>

                <button type="submit" class="btn btn-primary">Update Assignment</button>
            </form>
        </div>
    </div>
</div>
@endsection
