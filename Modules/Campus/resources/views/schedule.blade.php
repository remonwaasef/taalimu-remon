@extends('campus::layouts.master')

@section('content')
<div class="row align-items-center mb-5">
    <div class="col-md-6">
        <h2 class="fw-bold text-dark mb-2">الجدول الدراسي 📅</h2>
        <p class="text-muted mb-0">مواعيد محاضراتك ودوراتك التدريبية خلال الأسبوع</p>
    </div>
</div>

<div class="row g-4">
    @foreach($days as $dayNum => $dayName)
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="card-header bg-white border-0 py-3 px-4 d-flex align-items-center justify-content-between">
                    <h5 class="fw-bold mb-0 text-primary">
                        <i class="far fa-calendar-alt me-2"></i> {{ $dayName }}
                    </h5>
                    @if(isset($schedules[$dayNum]) && count($schedules[$dayNum]) > 0)
                        <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-3">
                            {{ count($schedules[$dayNum]) }} حصص
                        </span>
                    @else
                        <span class="badge bg-light text-muted rounded-pill px-3">يوم راحة ☕</span>
                    @endif
                </div>
                
                @if(isset($schedules[$dayNum]) && count($schedules[$dayNum]) > 0)
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0 align-middle">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="px-4 py-3 text-muted border-0 small">الوقت</th>
                                        <th class="px-4 py-3 text-muted border-0 small">المادة / الدورة</th>
                                        <th class="px-4 py-3 text-muted border-0 small">المحاضر</th>
                                        <th class="px-4 py-3 text-muted border-0 small">القاعة</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($schedules[$dayNum] as $session)
                                        <tr>
                                            <td class="px-4 py-3" style="width: 20%;">
                                                <div class="d-flex align-items-center gap-2">
                                                    <div class="bg-primary bg-opacity-10 text-primary rounded p-2 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                                        <i class="far fa-clock"></i>
                                                    </div>
                                                    <div>
                                                        <div class="fw-bold fs-5">{{ \Carbon\Carbon::parse($session->start_time)->format('g:i') }}</div>
                                                        <small class="text-muted text-uppercase">{{ \Carbon\Carbon::parse($session->start_time)->format('A') }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-4 py-3">
                                                <div class="fw-bold text-dark">{{ $session->course->title }}</div>
                                                <small class="text-muted">{{ Str::limit($session->course->description, 50) }}</small>
                                            </td>
                                            <td class="px-4 py-3">
                                                <div class="d-flex align-items-center gap-2">
                                                    @if($session->instructor && $session->instructor->user && $session->instructor->user->profile_photo_url)
                                                        <img src="{{ $session->instructor->user->profile_photo_url }}" class="rounded-circle" width="32" height="32">
                                                    @else
                                                        <div class="rounded-circle bg-light d-flex align-items-center justify-content-center text-muted" style="width: 32px; height: 32px;">
                                                            <i class="fas fa-user-tie"></i>
                                                        </div>
                                                    @endif
                                                    <span class="text-dark">{{ $session->instructor->name ?? 'غير محدد' }}</span>
                                                </div>
                                            </td>
                                            <td class="px-4 py-3">
                                                @if($session->classroom)
                                                    <span class="badge bg-info bg-opacity-10 text-info rounded-pill px-3 py-2">
                                                        <i class="fas fa-map-marker-alt me-1"></i> {{ $session->classroom->name }}
                                                    </span>
                                                @else
                                                    <span class="text-muted small">أونلاين / غير محدد</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @else
                    <div class="card-body p-5 text-center">
                        <div class="mb-3 opacity-25">
                            <i class="fas fa-couch fa-3x text-muted"></i>
                        </div>
                        <h6 class="text-muted">لا توجد محاضرات في هذا اليوم</h6>
                    </div>
                @endif
            </div>
        </div>
    @endforeach
</div>

<style>
    .table > :not(caption) > * > * {
        padding: 1rem 1.5rem;
        background-color: transparent;
        border-bottom-width: 1px;
        box-shadow: inset 0 0 0 9999px transparent;
    }
    
    .rounded-ultra { border-radius: 1.5rem; }
</style>
@endsection
