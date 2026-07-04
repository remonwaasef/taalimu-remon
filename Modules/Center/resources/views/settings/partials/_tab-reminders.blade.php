<div class="tab-pane fade {{ in_array($activeTab, ['reminders', 'email_templates']) ? 'show active' : '' }}" id="reminders" role="tabpanel" aria-labelledby="reminders-tab">

    <!-- Sub Tabs Nav -->
    <ul class="nav nav-pills mb-4 bg-light p-2 rounded-4 d-flex justify-content-center gap-2" id="remindersSubTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active rounded-pill px-4 fw-bold" id="welcome-emails-tab" data-bs-toggle="pill" data-bs-target="#welcome-emails" type="button" role="tab">
                <i class="fas fa-handshake me-2"></i> {{ __('center::settings.reminders.sub_tabs.welcome') }}
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link rounded-pill px-4 fw-bold" id="system-notifs-tab" data-bs-toggle="pill" data-bs-target="#system-notifs" type="button" role="tab">
                <i class="fas fa-bell me-2"></i> {{ __('center::settings.reminders.sub_tabs.system') }}
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link rounded-pill px-4 fw-bold" id="payment-reminders-tab" data-bs-toggle="pill" data-bs-target="#payment-reminders" type="button" role="tab">
                <i class="fas fa-calendar-check me-2"></i> {{ __('center::settings.reminders.sub_tabs.payment') }}
            </button>
        </li>
    </ul>

    <div class="tab-content" id="remindersSubTabsContent">
        {{-- NOTE: the email-templates <form> opens inside the welcome-emails partial and
             closes inside the system-notifs partial (one form saves both sub-tabs).
             Keep these two includes adjacent and in this order. --}}
        @include('center::settings.partials._reminders-welcome-emails')

        @include('center::settings.partials._reminders-system-notifs')

        @include('center::settings.partials._reminders-payment-schedule')
    </div> <!-- Close tab-content -->
</div>
