@extends('center::layouts.master')

@section('title', __('center::messages.blade_0990'))
@section('page-title', __('center::messages.blade_0991'))

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold">{{ __('center::messages.blade_0978') }}</h5>
                <a href="{{ route('center.users.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-1"></i>{{ __('center::messages.blade_0979') }}</a>
            </div>
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif

                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>{{ __('center::messages.blade_0980') }}</th>
                                <th>{{ __('center::messages.blade_0981') }}</th>
                                <th>{{ __('center::messages.blade_0982') }}</th>
                                <th>{{ __('center::messages.blade_0983') }}</th>
                                <th>{{ __('center::messages.blade_0984') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($users as $user)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-circle me-2 bg-primary text-white d-flex align-items-center justify-content-center rounded-circle" style="width: 40px; height: 40px;">
                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <div class="fw-bold">{{ $user->name }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    @if($user->role == 'center_admin')
                                        <span class="badge bg-primary">{{ __('center::messages.blade_0985') }}</span>
                                    @elseif($user->role == 'secretary')
                                        <span class="badge bg-info text-dark">{{ __('center::messages.blade_0986') }}</span>
                                    @elseif($user->role == 'accountant')
                                        <span class="badge bg-success">{{ __('center::messages.blade_0987') }}</span>
                                    @else
                                        <span class="badge bg-secondary">{{ $user->role }}</span>
                                    @endif
                                </td>
                                <td>{{ $user->created_at->format('Y-m-d') }}</td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('center.users.edit', $user->id) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        @if(auth()->id() !== $user->id)
                                        <form action="{{ route('center.users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('{{ __('center::messages.blade_0989') }}');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center py-4 text-muted">{{ __('center::messages.blade_0988') }}</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $users->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
