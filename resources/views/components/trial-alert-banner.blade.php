@php
    $tenant = app()->bound('tenant') ? app('tenant') : null;
    $showBanner = false;
    $isExpired = false;
    $isExpiringSoon = false;
    $daysRemaining = 0;

    if ($tenant && auth()->check() && (auth()->user()->role === 'center_admin' || auth()->user()->role === 'admin')) {
        $activeSub = $tenant->activeSubscription;
        $latestSub = $activeSub ?: $tenant->subscriptions()->latest()->first();

        if ($latestSub && $latestSub->ends_at) {
            if ($latestSub->ends_at <= now()) {
                // Subscription / trial has expired
                $showBanner = true;
                $isExpired = true;
            } elseif ($latestSub->status === 'trialing' || $latestSub->stripe_status === 'trialing') {
                $hoursRemaining = now()->diffInRealHours($latestSub->ends_at, false);
                $daysRemaining = max(1, (int) ceil($hoursRemaining / 24));
                if ($daysRemaining <= 3) {
                    $showBanner = true;
                    $isExpiringSoon = true;
                }
            }
        } elseif (! $activeSub) {
            $showBanner = true;
            $isExpired = true;
        }
    }
@endphp

@if($showBanner)
    <div class="mb-5 rounded-2xl overflow-hidden shadow-sm transition-all duration-300 {{ $isExpired ? 'bg-gradient-to-r from-red-500/10 via-red-500/5 to-transparent border border-red-500/30 text-red-900 dark:text-red-200' : 'bg-gradient-to-r from-amber-500/10 via-amber-500/5 to-transparent border border-amber-500/30 text-amber-900 dark:text-amber-200' }}" role="alert">
        <div class="px-4 py-3.5 sm:px-6 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl flex items-center justify-center shrink-0 {{ $isExpired ? 'bg-red-500 text-white shadow-sm shadow-red-500/30' : 'bg-amber-500 text-white shadow-sm shadow-amber-500/30' }}">
                    <i class="fas {{ $isExpired ? 'fa-exclamation-triangle' : 'fa-hourglass-half' }} text-sm"></i>
                </div>
                <div>
                    <h4 class="text-xs sm:text-sm font-bold m-0 leading-snug">
                        @if($isExpired)
                            {{ __('center::subscription.trial_expired_title') }}
                        @else
                            {{ __('center::subscription.trial_expiring_banner', ['days' => $daysRemaining]) }}
                        @endif
                    </h4>
                    <p class="text-[11px] sm:text-xs opacity-80 m-0 mt-0.5">
                        @if($isExpired)
                            {{ __('center::subscription.trial_expired_banner') }}
                        @else
                            {{ __('center::subscription.trial_expired_message', ['package' => $latestSub?->type_label ?? '']) }}
                        @endif
                    </p>
                </div>
            </div>
            <div class="flex items-center gap-2 self-end sm:self-auto shrink-0">
                <a href="{{ route('center.subscription.index', ['tenant' => $tenant->domain]) }}" 
                   class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-xs font-bold text-white transition-all transform hover:-translate-y-0.5 shadow-sm {{ $isExpired ? 'bg-red-600 hover:bg-red-700 shadow-red-500/20' : 'bg-[#2E8B83] hover:bg-[#246f69] shadow-[#2E8B83]/20' }}">
                    <span>{{ $isExpired ? __('center::subscription.pay_and_activate_now') : __('center::subscription.pay_now') }}</span>
                    <i class="fas fa-arrow-left rtl:rotate-0 ltr:rotate-180 text-[10px]"></i>
                </a>
            </div>
        </div>
    </div>
@endif
