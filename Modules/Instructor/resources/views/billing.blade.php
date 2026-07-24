@extends('layouts.app-next')

@section('title', __('instructor::billing.title') ?? 'Financial Billing & Receivables')

@section('sidebar')
    <x-ui.sidebar brandName="Taalimu">
        <div class="space-y-1">
            <a href="{{ route('instructor.dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-50 dark:text-slate-300 dark:hover:bg-slate-800 transition-colors">
                <i class="fas fa-home w-4 text-center"></i>
                <span>{{ __('instructor::sidebar.dashboard') }}</span>
            </a>

            <div class="pt-4 pb-1 px-3 text-[10px] font-bold text-slate-400 uppercase tracking-wider">Teaching</div>

            <a href="{{ route('instructor.students.list') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-50 dark:text-slate-300 dark:hover:bg-slate-800 transition-colors">
                <i class="fas fa-user-graduate w-4 text-center"></i>
                <span>{{ __('instructor::sidebar.students') }}</span>
            </a>

            <a href="{{ route('instructor.groups.list') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-50 dark:text-slate-300 dark:hover:bg-slate-800 transition-colors">
                <i class="fas fa-users w-4 text-center"></i>
                <span>{{ __('instructor::sidebar.groups') }}</span>
            </a>

            <div class="pt-4 pb-1 px-3 text-[10px] font-bold text-slate-400 uppercase tracking-wider">Finance</div>

            <a href="{{ route('instructor.billing') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-semibold text-brand-primary bg-brand-50 dark:bg-brand-900/30">
                <i class="fas fa-wallet w-4 text-center"></i>
                <span>{{ __('instructor::sidebar.billing') }}</span>
            </a>
        </div>
    </x-ui.sidebar>
@endsection

@section('content')
    <x-ui.page-header
        title="{{ __('instructor::billing.title') ?? 'Financial Billing & Receivables' }}"
        subtitle="{{ __('instructor::billing.subtitle') ?? 'Track student tuition balances, collected fees, and send WhatsApp payment reminders.' }}"
    >
        <x-slot name="actions">
            <x-ui.badge variant="brand" size="lg">
                {{ $students->count() }} {{ __('instructor::billing.student_count') }}
            </x-ui.badge>
        </x-slot>
    </x-ui.page-header>

    <x-ui.card noPadding="true" class="mb-8">
        @if($students->count() > 0)
            <x-ui.table :headers="[__('instructor::billing.student_name'), __('instructor::billing.total_due'), __('instructor::billing.total_paid'), __('instructor::billing.balance'), __('instructor::billing.actions')]">
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
                                <x-ui.badge variant="danger" size="sm" dot="true">{{ number_format($balance) }} {{ app('tenant')->settings['currency'] ?? 'EGP' }} Due</x-ui.badge>
                            @else
                                <x-ui.badge variant="success" size="sm" dot="true">{{ __('instructor::billing.paid') }}</x-ui.badge>
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
                                        Send Reminder
                                    </x-ui.button>
                                @else
                                    <span class="text-xs text-emerald-600 font-bold"><i class="fas fa-check-circle me-1"></i> Paid in Full</span>
                                @endif
                            </div>
                        </td>
                    </tr>
                @endforeach
            </x-ui.table>
        @else
            <x-ui.empty-state
                title="No Billing Records Found"
                description="{{ __('instructor::billing.no_students_registered') }}"
                icon="fas fa-wallet"
            />
        @endif
    </x-ui.card>
@endsection
