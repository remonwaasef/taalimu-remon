@extends('center::layouts.hope-master')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">{{ __('Activity Logs') }}</h1>
    </div>

    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>{{ __('User') }}</th>
                            <th>{{ __('Action') }}</th>
                            <th>{{ __('Subject') }}</th>
                            <th>{{ __('Changes') }}</th>
                            <th>{{ __('Date') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($activities as $activity)
                        <tr>
                            <td>
                                @if($activity->causer)
                                    {{ $activity->causer->name }}
                                    <small class="d-block text-muted">({{ $activity->causer->role }})</small>
                                @else
                                    System
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-{{ $activity->event == 'created' ? 'success' : ($activity->event == 'updated' ? 'warning' : 'danger') }}">
                                    {{ ucfirst($activity->event ?: $activity->description) }}
                                </span>
                            </td>
                            <td>
                                {{ class_basename($activity->subject_type) }}
                                @if($activity->subject)
                                    #{{ $activity->subject->id }}
                                @endif
                            </td>
                            <td>
                                @if($activity->event == 'updated')
                                    <small>
                                        @foreach($activity->changes['attributes'] ?? [] as $key => $value)
                                            @if(!in_array($key, ['password', 'remember_token', 'google2fa_secret']))
                                                <strong>{{ $key }}:</strong> 
                                                <span class="text-danger">{{ $activity->changes['old'][$key] ?? 'null' }}</span> 
                                                -> 
                                                <span class="text-success">{{ is_array($value) ? json_encode($value) : $value }}</span><br>
                                            @endif
                                        @endforeach
                                    </small>
                                @elseif($activity->event == 'created')
                                    <small>Created</small>
                                @elseif($activity->event == 'deleted')
                                    <small>Deleted</small>
                                @else
                                    <small>{{ $activity->description }}</small>
                                @endif
                            </td>
                            <td>{{ $activity->created_at->format('Y-m-d H:i:s') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            {{ $activities->links() }}
        </div>
    </div>
</div>
@endsection
