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
        $subjectMap = [
            'pre_due_7d' => 'تذكير بموعد الدفع - بعد أسبوع',
            'pre_due_3d' => 'تذكير بموعد الدفع - بعد 3 أيام',
            'due_day'    => 'اليوم موعد سداد المصروفات',
            'overdue_1d' => 'تنبيه: تأخر سداد المصروفات',
            'overdue_3d' => 'تنبيه عاجل: مصروفات متأخرة',
            'overdue_7d' => 'إشعار أخير: مصروفات متأخرة',
        ];

        $subject = $subjectMap[$this->stage] ?? 'تذكير بالمصروفات';
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
                'currency' => $this->tenant->settings['currency'] ?? 'ج.م',
            ],
        );
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
            ':currency' => $this->tenant->settings['currency'] ?? 'ج.م',
        ]);
    }
}
