@extends('layouts.app-next')

@section('title', __('admin::admin.dashboard.title'))

@section('sidebar')
    <x-ui.sidebar brandName="Taalimu Admin">
        <div class="space-y-1">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-semibold text-brand-primary bg-brand-50 dark:bg-brand-900/30">
                <i class="fas fa-chart-pie w-4 text-center"></i>
                <span>{{ __('admin::admin.dashboard.title') ?? 'Dashboard' }}</span>
            </a>

            <div class="pt-4 pb-1 px-3 text-[10px] font-bold text-slate-400 uppercase tracking-wider">Management</div>

            <a href="{{ route('admin.tenants.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-50 dark:text-slate-300 dark:hover:bg-slate-800 transition-colors">
                <i class="fas fa-building w-4 text-center"></i>
                <span>Educational Centers</span>
            </a>

            <a href="{{ route('admin.subscriptions.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-50 dark:text-slate-300 dark:hover:bg-slate-800 transition-colors">
                <i class="fas fa-credit-card w-4 text-center"></i>
                <span>Subscriptions</span>
            </a>

            <a href="{{ route('admin.tickets.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-50 dark:text-slate-300 dark:hover:bg-slate-800 transition-colors">
                <i class="fas fa-headset w-4 text-center"></i>
                <span>Support Tickets</span>
            </a>

            <a href="#" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-50 dark:text-slate-300 dark:hover:bg-slate-800 transition-colors">
                <i class="fas fa-heartbeat w-4 text-center"></i>
                <span>System Health</span>
            </a>
        </div>
    </x-ui.sidebar>
@endsection

@section('content')
    <x-ui.page-header
        title="{{ __('admin::admin.dashboard.title') ?? 'Admin Overview' }}"
        subtitle="Real-time ecosystem metrics, tenant subscription statuses, and system activity."
    >
        <x-slot name="actions">
            <x-ui.button variant="primary" icon="fas fa-plus" size="md" href="{{ route('admin.tenants.create') }}">
                Add New Center
            </x-ui.button>
        </x-slot>
    </x-ui.page-header>

    <!-- Top Key Metrics Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
        <x-ui.stats-card
            title="{{ __('admin::admin.dashboard.stats.total_centers') }}"
            value="{{ number_format($totalTenants) }}"
            change="+8%"
            changeType="positive"
            icon="fas fa-building"
            iconColor="text-brand-primary bg-brand-50"
        />

        <x-ui.stats-card
            title="{{ __('admin::admin.dashboard.stats.active_centers') }}"
            value="{{ number_format($activeTenants) }}"
            change="Active"
            changeType="positive"
            icon="fas fa-check-circle"
            iconColor="text-emerald-600 bg-emerald-50"
        />

        <x-ui.stats-card
            title="{{ __('admin::admin.dashboard.stats.expiring_soon') }}"
            value="{{ number_format($expiringSoon) }}"
            change="Action Needed"
            changeType="negative"
            icon="fas fa-clock"
            iconColor="text-amber-600 bg-amber-50"
        />

        <x-ui.stats-card
            title="{{ __('admin::admin.dashboard.stats.total_students') }}"
            value="{{ number_format($totalStudents) }}"
            change="+18%"
            changeType="positive"
            icon="fas fa-user-graduate"
            iconColor="text-sky-600 bg-sky-50"
        />
    </div>

    <!-- Revenue & Support Stats Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
        <x-ui.stats-card
            title="{{ __('admin::admin.dashboard.stats.total_revenue') }}"
            value="{{ number_format($totalRevenue, 2) }} EGP"
            change="+24%"
            changeType="positive"
            icon="fas fa-wallet"
            iconColor="text-emerald-600 bg-emerald-50"
        />

        <x-ui.stats-card
            title="{{ __('admin::admin.dashboard.stats.this_month_revenue') }}"
            value="{{ number_format($thisMonthRevenue, 2) }} EGP"
            change="+12%"
            changeType="positive"
            icon="fas fa-chart-line"
            iconColor="text-brand-primary bg-brand-50"
        />

        <x-ui.stats-card
            title="{{ __('admin::admin.dashboard.stats.open_tickets') }}"
            value="{{ number_format($openTickets) }}"
            change="Open"
            changeType="negative"
            icon="fas fa-headset"
            iconColor="text-amber-600 bg-amber-50"
        />

        <x-ui.stats-card
            title="{{ __('admin::admin.dashboard.stats.total_tickets') }}"
            value="{{ number_format($totalTickets) }}"
            change="Total"
            changeType="neutral"
            icon="fas fa-ticket-alt"
            iconColor="text-slate-600 bg-slate-100"
        />
    </div>

    <!-- Subscription Analytics Grid -->
    <div class="mb-8">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-extrabold text-slate-900 dark:text-slate-100 font-inter">
                {{ __('admin::admin.dashboard.subscription_analytics') ?? 'Subscription Analytics' }}
            </h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
            @foreach($planAnalytics as $plan)
                <x-ui.card>
                    <div class="flex items-center justify-between mb-4">
                        <x-ui.badge variant="brand" size="md">{{ $plan['name'] }}</x-ui.badge>
                        @if($plan['badge'])
                            <x-ui.badge variant="warning" size="sm">{{ $plan['badge'] }}</x-ui.badge>
                        @endif
                    </div>

                    <div class="grid grid-cols-2 gap-4 pt-2 border-t border-brand-border dark:border-slate-800">
                        <div>
                            <p class="text-xs text-slate-400 font-medium">Centers</p>
                            <p class="text-xl font-bold text-slate-900 dark:text-slate-100 mt-1">{{ number_format($plan['centers_count']) }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-slate-400 font-medium">Net Profit</p>
                            <p class="text-xl font-bold text-emerald-600 dark:text-emerald-400 mt-1">{{ number_format($plan['total_profits'], 2) }} <span class="text-xs font-normal">EGP</span></p>
                        </div>
                    </div>
                </x-ui.card>
            @endforeach
        </div>
    </div>

    <!-- Recent Tenants & Tickets Split Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Recent Tenants -->
        <x-ui.card title="{{ __('admin::admin.dashboard.recent_tenants') ?? 'Recent Centers' }}" noPadding="true">
            @if(count($recentTenants) > 0)
                <x-ui.table :headers="['Center Name', 'Domain', 'Joined Date']">
                    @foreach($recentTenants as $tenant)
                        <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/50 transition-colors">
                            <td class="px-6 py-4 font-semibold text-slate-900 dark:text-slate-100">{{ $tenant->name ?? 'N/A' }}</td>
                            <td class="px-6 py-4 text-xs font-mono text-slate-500">{{ $tenant->domain ?? 'N/A' }}</td>
                            <td class="px-6 py-4 text-xs text-slate-400">{{ $tenant->created_at ? $tenant->created_at->diffForHumans() : 'N/A' }}</td>
                        </tr>
                    @endforeach
                </x-ui.table>
            @else
                <x-ui.empty-state
                    title="No Recent Centers"
                    description="No educational centers have registered recently."
                    icon="fas fa-building"
                />
            @endif
        </x-ui.card>

        <!-- Recent Support Tickets -->
        <x-ui.card title="{{ __('admin::admin.dashboard.recent_tickets') ?? 'Recent Support Tickets' }}" noPadding="true">
            @if(count($recentTickets) > 0)
                <x-ui.table :headers="['Subject', 'Status', 'Date']">
                    @foreach($recentTickets as $ticket)
                        <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/50 transition-colors">
                            <td class="px-6 py-4 font-semibold text-slate-900 dark:text-slate-100 truncate max-w-xs">{{ $ticket->subject ?? 'Support Query' }}</td>
                            <td class="px-6 py-4">
                                @if($ticket->status === 'open')
                                    <x-ui.badge variant="danger" size="sm" dot="true">Open</x-ui.badge>
                                @elseif($ticket->status === 'pending')
                                    <x-ui.badge variant="warning" size="sm" dot="true">Pending</x-ui.badge>
                                @else
                                    <x-ui.badge variant="success" size="sm" dot="true">Closed</x-ui.badge>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-xs text-slate-400">{{ $ticket->created_at ? $ticket->created_at->diffForHumans() : 'N/A' }}</td>
                        </tr>
                    @endforeach
                </x-ui.table>
            @else
                <x-ui.empty-state
                    title="No Recent Tickets"
                    description="Support queue is clear. No tickets submitted."
                    icon="fas fa-headset"
                />
            @endif
        </x-ui.card>
    </div>
@endsection
