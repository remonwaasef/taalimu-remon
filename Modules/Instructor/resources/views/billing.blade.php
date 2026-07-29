@extends('layouts.app-next')

@section('title', __('instructor::billing.title') ?? 'إدارة الحسابات والمدفوعات')

@section('sidebar')
    @include('instructor::partials._sidebar-next', ['active' => 'billing'])
@endsection

@section('content')
    <x-ui.page-header
        title="{{ __('instructor::billing.title') ?? 'إدارة الحسابات والمدفوعات' }}"
        subtitle="{{ __('instructor::billing.subtitle') ?? 'متابعة مستحقات الطلاب والرسوم المحصلة وإرسال تذكيرات سداد عبر الواتساب.' }}"
    >
        <x-slot name="actions">
            <x-ui.badge variant="brand" size="lg">
                {{ $students->count() }} {{ __('instructor::billing.student_count') ?? 'طالب' }}
            </x-ui.badge>
        </x-slot>
    </x-ui.page-header>

    <x-ui.card noPadding="true" class="mb-8">
        @if($students->count() > 0)
            <x-ui.table :headers="[__('instructor::billing.student_name') ?? 'اسم الطالب', __('instructor::billing.total_due') ?? 'إجمالي المستحق', __('instructor::billing.total_paid') ?? 'المسدد', __('instructor::billing.balance') ?? 'الرصيد/المتبقي', __('instructor::billing.actions') ?? 'الإجراءات']">
                @foreach($students as $student)
                    @php
                        $totalDue = $student->enrollments->sum(function($e) { return $e->course->price ?? 0; });
                        $totalPaid = $student->sales->sum('paid_amount');
                        $balance = $totalDue - $totalPaid;
                    @endphp
                    <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="font-bold text-sm text-slate-900 dark:text-slate-100">{{ $student->name }}</div>
                            <span class="text-xs text-slate-400 font-normal">{{ $student->enrollments->pluck('course.title')->filter()->implode(', ') }}</span>
                        </td>

                        <td class="px-6 py-4 text-xs font-mono font-semibold text-slate-800 dark:text-slate-200">
                            {{ number_format($totalDue) }} {{ app('tenant')->settings['currency'] ?? 'EGP' }}
                        </td>

                        <td class="px-6 py-4 text-xs font-mono font-semibold text-emerald-600 dark:text-emerald-400">
                            {{ number_format($totalPaid) }} {{ app('tenant')->settings['currency'] ?? 'EGP' }}
                        </td>

                        <td class="px-6 py-4">
                            @if($balance > 0)
                                <x-ui.badge variant="danger" size="sm" dot="true">{{ number_format($balance) }} {{ app('tenant')->settings['currency'] ?? 'EGP' }} مستحق</x-ui.badge>
                            @else
                                <x-ui.badge variant="success" size="sm" dot="true">{{ __('instructor::billing.paid') ?? 'خالص السداد' }}</x-ui.badge>
                            @endif
                        </td>

                        <td class="px-6 py-4 text-end">
                            <div class="flex items-center justify-end gap-2">
                                @if($balance > 0)
                                    @php
                                        $reminderMsg = __('instructor::billing.reminder_msg', [
                                            'student' => $student->name,
                                            'balance' => $balance,
                                            'instructor' => auth()->user()->name
                                        ]);
                                        $phone = $student->phone;
                                        if (str_starts_with($phone, '0')) $phone = '2' . $phone;
                                        $whatsappUri = "https://api.whatsapp.com/send?phone=" . preg_replace('/[^0-9]/', '', $phone) . "&text=" . urlencode($reminderMsg);
                                    @endphp
                                    <x-ui.button variant="outline" size="sm" icon="fab fa-whatsapp" href="{{ $whatsappUri }}" target="_blank">
                                        إرسال تذكير
                                    </x-ui.button>
                                @else
                                    <span class="text-xs text-emerald-600 font-bold"><i class="fas fa-check-circle me-1"></i> خالص السداد</span>
                                @endif
                            </div>
                        </td>
                    </tr>
                @endforeach
            </x-ui.table>
        @else
            <x-ui.empty-state
                title="لا توجد سجلات مالية حتى الآن"
                description="{{ __('instructor::billing.no_students_registered') ?? 'لم يتم تسجِيل أي مستحقات مالية للطلاب حتى الآن.' }}"
                icon="fas fa-wallet"
            />
        @endif
    </x-ui.card>
@endsection
