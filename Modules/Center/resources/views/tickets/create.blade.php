@extends('center::layouts.hope-master')

@section('title', 'Create Ticket')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Create New Ticket</h1>
    </div>

    <div class="card shadow mb-4">
        <div class="card-body">
            <form action="{{ route('center.tickets.store') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label>Subject</label>
                    <input type="text" name="subject" class="form-control" required>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label>Category</label>
                        <select name="category" class="form-control">
                            <option value="technical">Technical Issue</option>
                            <option value="billing">Billing & Subscription</option>
                            <option value="feature">Feature Request</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                    <div class="form-group col-md-6">
                        <label>Priority</label>
                        <select name="priority" class="form-control">
                            <option value="low">Low</option>
                            <option value="medium" selected>Medium</option>
                            <option value="high">High</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label>Message</label>
                    <textarea name="message" class="form-control" rows="5" required></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Submit Ticket</button>
            </form>
        </div>
    </div>
</div>
@endsection
