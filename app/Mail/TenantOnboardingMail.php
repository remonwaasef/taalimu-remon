<?php

namespace App\Mail;

use App\Models\Tenant;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TenantOnboardingMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public Tenant $tenant;

    public User $user;

    public int $step;

    public int $trialDaysLeft;

    public function __construct(Tenant $tenant, User $user, int $step)
    {
        $this->tenant = $tenant;
        $this->user = $user;
        $this->step = $step;

        $subscription = $tenant->activeSubscription();
        $this->trialDaysLeft = $subscription && $subscription->trial_ends_at
            ? max(0, now()->diffInDays($subscription->trial_ends_at, false))
            : 14;
    }

    public function envelope(): Envelope
    {
        $subjects = [
            1 => "{$this->user->name}, votre centre est prêt sur Taalimu ! Vérifiez votre email",
            2 => "{$this->user->name}, 80% des centres ajoutent leurs élèves dès le J1 📊",
            3 => 'Arrêtez de courir après les paiements ! Automatisez avec Taalimu',
            4 => 'Fini les appels sans fin ! Activez votre portail parents',
            5 => 'Comment Centre Y gère 500 élèves sans stress avec Taalimu',
        ];

        return new Envelope(
            subject: $subjects[$this->step] ?? 'Bienvenue sur Taalimu',
        );
    }

    public function content(): Content
    {
        $views = [
            1 => 'emails.onboarding.email_1_welcome',
            2 => 'emails.onboarding.email_2_dashboard',
            3 => 'emails.onboarding.email_3_billing',
            4 => 'emails.onboarding.email_4_whatsapp',
            5 => 'emails.onboarding.email_5_upgrade',
        ];

        return new Content(
            view: $views[$this->step] ?? 'emails.onboarding.email_1_welcome',
        );
    }
}
