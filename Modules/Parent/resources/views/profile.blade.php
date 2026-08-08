@extends('layouts.app-next')

@section('title', 'البيانات الشخصية')

@section('sidebar')
    @include('parent::partials.sidebar', ['active' => 'profile'])
@endsection

@section('content')
    <x-ui.page-header title="البيانات الشخصية 👤" subtitle="معلوماتك الشخصية وعلاقتك بأبنائك." />

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <x-ui.card title="بيانات ولي الأمر" subtitle="بيانات الحساب الأساسية">
            <div class="space-y-4">
                <div class="flex items-center gap-3 pb-4 border-b border-brand-border/50 dark:border-slate-800/50">
                    <div class="w-14 h-14 rounded-2xl bg-brand-50 dark:bg-brand-900/30 text-brand-primary flex items-center justify-center text-xl">
                        <i class="fas fa-user-shield"></i>
                    </div>
                    <div>
                        <div class="font-bold text-sm text-slate-900 dark:text-slate-100">{{ $guardian->name }}</div>
                        <div class="text-[11px] text-slate-400">ولي أمر</div>
                    </div>
                </div>

                <div class="space-y-3 text-xs">
                    <div class="flex items-center justify-between">
                        <span class="text-slate-400 font-semibold"><i class="fas fa-phone me-2"></i>الهاتف</span>
                        <span class="font-semibold text-slate-700 dark:text-slate-200" dir="ltr">{{ $guardian->phone ?? '—' }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-slate-400 font-semibold"><i class="fas fa-envelope me-2"></i>البريد</span>
                        <span class="font-semibold text-slate-700 dark:text-slate-200" dir="ltr">{{ auth()->user()->email ?? $guardian->email ?? '—' }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-slate-400 font-semibold"><i class="fas fa-briefcase me-2"></i>الوظيفة</span>
                        <span class="font-semibold text-slate-700 dark:text-slate-200">{{ $guardian->job ?? '—' }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-slate-400 font-semibold"><i class="fas fa-map-marker-alt me-2"></i>العنوان</span>
                        <span class="font-semibold text-slate-700 dark:text-slate-200">{{ $guardian->address ?? '—' }}</span>
                    </div>
                </div>
            </div>
        </x-ui.card>

        <x-ui.card title="الأبناء المرتبطون" subtitle="علاقة القرابة بكل طالب" class="lg:col-span-2">
            @if($children->isEmpty())
                <x-ui.empty-state title="لا يوجد أبناء مرتبطون" description="تواصل مع إدارة المركز لربط أبنائك بحسابك." icon="fas fa-users-slash" />
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-xs text-right">
                        <thead>
                            <tr class="text-[11px] text-slate-400 border-b border-brand-border dark:border-slate-800">
                                <th class="py-3 px-3 font-semibold">الطالب</th>
                                <th class="py-3 px-3 font-semibold">الصف</th>
                                <th class="py-3 px-3 font-semibold">صلة القرابة</th>
                                <th class="py-3 px-3 font-semibold">الهاتف</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($children as $student)
                                <tr class="border-b border-brand-border/50 dark:border-slate-800/50 last:border-0">
                                    <td class="py-3 px-3 font-semibold text-slate-700 dark:text-slate-200 flex items-center gap-2">
                                        <div class="w-8 h-8 rounded-lg bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-slate-500">
                                            <i class="fas fa-user-graduate text-[10px]"></i>
                                        </div>
                                        {{ $student->name }}
                                    </td>
                                    <td class="py-3 px-3 text-slate-500 dark:text-slate-400">{{ $student->grade?->name ?? '—' }}</td>
                                    <td class="py-3 px-3">
                                        @php $relation = $student->pivot?->relation; @endphp
                                        <x-ui.badge variant="secondary" size="sm">{{ $relation ?? 'ولي أمر' }}</x-ui.badge>
                                    </td>
                                    <td class="py-3 px-3 text-slate-500 dark:text-slate-400" dir="ltr">{{ $student->phone ?? ($student->parent_phone ?? '—') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </x-ui.card>
    </div>
@endsection