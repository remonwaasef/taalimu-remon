<?php

namespace App\Mail;

use App\Models\Student;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

/**
 * Welcome/notification email sent to the guardian of a newly registered student.
 * Supports dynamic template variables from tenant settings.
 */
class WelcomeGuardianMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public string $guardianName;

    public string $studentName;

    public string $processedBody;

    public string $subjectLine;

    public string $senderName;

    /**
     * @param  string  $subjectTemplate  Subject with placeholders
     * @param  string  $bodyTemplate  Body with placeholders
     * @param  array  $variables  Key-value pairs for replacement
     * @param  string  $senderName  Sender display name
     */
    public function __construct(
        string $guardianName,
        string $studentName,
        string $subjectTemplate,
        string $bodyTemplate,
        array $variables,
        string $senderName
    ) {
        $this->guardianName = $guardianName;
        $this->studentName = $studentName;
        $this->senderName = $senderName;

        $this->subjectLine = WelcomeStudentMail::replacePlaceholders($subjectTemplate, $variables);
        $this->processedBody = WelcomeStudentMail::replacePlaceholders($bodyTemplate, $variables);
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject($this->subjectLine)
            ->view('emails.welcome_guardian_mail')
            ->with([
                'guardianName' => $this->guardianName,
                'studentName' => $this->studentName,
                'messageContent' => $this->processedBody,
                'senderName' => $this->senderName,
            ]);
    }
}
