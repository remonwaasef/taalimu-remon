@extends('center::layouts.master')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-dark">إدارة القاعات الدراسية</h2>
        <a href="{{ route('center.classrooms.create') }}" class="btn btn-primary rounded-pill px-4 shadow-sm">
            <span class="me-2">+</span> إضافة قاعة جديدة
        </a>
    </div>

    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-4">
            <!-- Classrooms Table -->
            <div class="table-responsive" style="min-height: 350px; overflow-x: auto;">
                <table class="table align-middle">
                    <thead class="bg-light">
                        <tr>
                            <th class="border-0 rounded-start">اسم القاعة</th>
                            <th class="border-0">السعة الاستيعابية</th>
                            <th class="border-0">تاريخ الإضافة</th>
                            <th class="border-0 rounded-end">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($classrooms as $classroom)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="bg-primary bg-opacity-10 text-primary rounded-3 d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                            🏢
                                        </div>
                                        <div class="fw-bold">{{ $classroom->name }}</div>
                                    </div>
                                </td>
                                <td>{{ $classroom->capacity ?? 'غير محدد' }} طالب</td>
                                <td class="text-muted">{{ $classroom->created_at->format('Y-m-d') }}</td>
                                <td>
                                    <div class="{{ ($loop->remaining < 2 && $classrooms->count() > 2) ? 'dropup' : 'dropdown' }}">
                                        <button class="btn btn-sm btn-light rounded-circle" type="button" data-bs-toggle="dropdown">
                                            ⋮
                                        </button>
                                        <ul class="dropdown-menu border-0 shadow">
                                            <li><a class="dropdown-item" href="{{ route('center.classrooms.show', $classroom) }}"><i class="fas fa-eye me-2 text-primary"></i> عرض تفاصيل الجدول</a></li>
                                            <li><a class="dropdown-item" href="{{ route('center.classrooms.edit', $classroom) }}"><i class="fas fa-edit me-2 text-warning"></i> تعديل القاعة</a></li>
                                            <li><hr class="dropdown-divider"></li>
                                            <li>
                                                <form action="{{ route('center.classrooms.destroy', $classroom) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من الحذف؟')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="dropdown-item text-danger">حذف</button>
                                                </form>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-5 text-muted">لا يوجد قاعات مضافة حالياً</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-4">
                {{ $classrooms->links() }}
            </div>
        </div>
    </div>
@endsection
