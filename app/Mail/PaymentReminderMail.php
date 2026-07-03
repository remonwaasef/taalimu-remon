<?php

namespace App\Mail;

use App\Models\Student;
use App\Models\Tenant;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PaymentReminderMail extends Mailable
{
    use Queueable, SerializesModels;

    public Student $student;

    public Tenant $tenant;

    public float $amount;

    public int $dueDay;

    public string $stage;

    public string $customMessage;

    /**
     * Create a new message instance.
     */
    public function __construct(Student $student, Tenant $tenant, float $amount, int $dueDay, string $stage, string $customMessage = '')
    {
        $this->student = $student;
        $this->tenant = $tenant;
        $this->amount = $amount;
        $this->dueDay = $dueDay;
        $this->stage = $stage;
        $this->customMessage = $customMessage;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $locale = $this->getTenantLocale();

        $subjectMaps = [
            'ar' => [
                'pre_due_7d' => 'تذكير بموعد الدفع - بعد أسبوع',
                'pre_due_3d' => 'تذكير بموعد الدفع - بعد 3 أيام',
                'due_day' => 'اليوم موعد سداد المصروفات',
                'overdue_1d' => 'تنبيه: تأخر سداد المصروفات',
                'overdue_3d' => 'تنبيه عاجل: مصروفات متأخرة',
                'overdue_7d' => 'إشعار أخير: مصروفات متأخرة',
            ],
            'en' => [
                'pre_due_7d' => 'Payment Reminder - Due in One Week',
                'pre_due_3d' => 'Payment Reminder - Due in 3 Days',
                'due_day' => 'Tuition Payment Due Today',
                'overdue_1d' => 'Notice: Overdue Tuition Payment',
                'overdue_3d' => 'Urgent: Overdue Tuition Payment',
                'overdue_7d' => 'Final Notice: Overdue Tuition Payment',
            ],
            'fr' => [
                'pre_due_7d' => 'Rappel de paiement - Échéance dans une semaine',
                'pre_due_3d' => 'Rappel de paiement - Échéance dans 3 jours',
                'due_day' => 'Paiement des frais de scolarité dû aujourd\'hui',
                'overdue_1d' => 'Avis : Paiement de scolarité en retard',
                'overdue_3d' => 'Urgent : Paiement de scolarité en retard',
                'overdue_7d' => 'Dernier avis : Paiement de scolarité en retard',
            ],
        ];

        $defaultSubjects = [
            'ar' => 'تذكير بالمصروفات',
            'en' => 'Tuition Payment Reminder',
            'fr' => 'Rappel de frais de scolarité',
        ];

        $map = $subjectMaps[$locale] ?? $subjectMaps['en'];
        $subject = $map[$this->stage] ?? ($defaultSubjects[$locale] ?? $defaultSubjects['en']);
        $tenantName = $this->tenant->name;

        return new Envelope(
            subject: "{$tenantName} - {$subject}",
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.payment-reminder',
            with: [
                'studentName' => $this->student->name,
                'tenantName' => $this->tenant->name,
                'amount' => number_format($this->amount, 2),
                'dueDay' => $this->dueDay,
                'stage' => $this->stage,
                'customMessage' => $this->parseTemplate($this->customMessage),
                'currentMonth' => now()->translatedFormat('F Y'),
                'currency' => $this->resolveCurrencySymbol(),
            ],
        );
    }

    /**
     * Resolve the currency symbol from tenant settings.
     */
    protected function resolveCurrencySymbol(): string
    {
        if (function_exists('get_currency_symbol')) {
            return get_currency_symbol();
        }

        $currency = $this->tenant->settings['financial']['currency']
            ?? $this->tenant->settings['currency']
            ?? 'EGP';

        $locale = $this->getTenantLocale();
        $symbols = [
            'EGP' => ($locale === 'ar') ? 'ج.م' : 'EGP',
            'SAR' => ($locale === 'ar') ? 'ر.س' : 'SAR',
            'USD' => '$',
            'EUR' => '€',
        ];

        return $symbols[$currency] ?? $currency;
    }

    /**
     * Get tenant locale.
     */
    protected function getTenantLocale(): string
    {
        return $this->tenant->settings['locale'] ?? app()->getLocale();
    }

    /**
     * Replace template variables in custom message.
     */
    protected function parseTemplate(string $template): string
    {
        if (empty($template)) {
            return '';
        }

        return strtr($template, [
            ':student_name' => $this->student->name,
            ':amount' => number_format($this->amount, 2),
            ':due_day' => $this->dueDay,
            ':tenant_name' => $this->tenant->name,
            ':month' => now()->translatedFormat('F'),
            ':currency' => $this->resolveCurrencySymbol(),
        ]);
    }
}
