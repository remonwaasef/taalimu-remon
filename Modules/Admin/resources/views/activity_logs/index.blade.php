@extends('admin::layouts.master')

@section('title', __('admin.activity_log.title'))

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">{{ __('admin.activity_log.title') }}</h1>
    </div>

    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>{{ __('admin.activity_log.user') }}</th>
                            <th>{{ __('admin.activity_log.action') }}</th>
                            <th>{{ __('admin.activity_log.subject') }}</th>
                            <th>{{ __('admin.activity_log.changes') }}</th>
                            <th>{{ __('admin.activity_log.date') }}</th>
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
                                    {{ __('admin.activity_log.system') }}
                                @endif
                            </td>
                            <td>
                                <span class="badge badge-{{ $activity->event == 'created' ? 'success' : ($activity->event == 'updated' ? 'warning' : 'danger') }}">
                                    {{ __('admin.activity_log.events.' . $activity->event) ?? ucfirst($activity->event) }}
                                </span>
                            </td>
                            <td>
                                {{ __('center::dashboard.models.' . class_basename($activity->subject_type)) ?? class_basename($activity->subject_type) }}
                                @if($activity->subject)
                                    #{{ $activity->subject->id }}
                                @endif
                            </td>
                            <td>
                                @if($activity->event == 'updated')
                                    <small>
                                        @foreach($activity->changes['attributes'] ?? [] as $key => $value)
                                            <strong>{{ $key }}:</strong> 
                                            @php
                                                $oldValue = $activity->changes['old'][$key] ?? 'null';
                                            @endphp
                                            <span class="text-danger">
                                                {{ is_array($oldValue) ? json_encode($oldValue, JSON_UNESCAPED_UNICODE) : $oldValue }}
                                            </span> 
                                            -> 
                                            <span class="text-success">
                                                {{ is_array($value) ? json_encode($value, JSON_UNESCAPED_UNICODE) : $value }}
                                            </span><br>
                                        @endforeach
                                    </small>
                                @elseif($activity->event == 'created')
                                    <small>{{ __('admin.activity_log.events.created') }}</small>
                                @else
                                    <small>{{ __('admin.activity_log.events.deleted') }}</small>
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
