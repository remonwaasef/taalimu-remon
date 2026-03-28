@extends('center::layouts.hope-master')

@section('content')
<div class="container text-center mt-5">
    <div class="card shadow-lg mx-auto border-success" style="max-width: 400px;">
        <div class="card-body py-5">
            <div class="display-1 text-success mb-3">✅</div>
            <h2 class="text-success">Attendance Recorded!</h2>
            <p class="lead">{{ $message }}</p>
            <a href="{{ route('center.dashboard', ['tenant' => request()->route('tenant')]) }}" class="btn btn-primary mt-3">Back to Dashboard</a>
        </div>
    </div>
</div>
@endsection
