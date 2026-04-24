<?php

namespace App\Mail;

use App\Models\Student;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

/**
 * Welcome email sent to a newly registered student.
 * Supports dynamic template variables from tenant settings.
 */
class WelcomeStudentMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public string $studentName;
    public string $processedBody;
    public string $subjectLine;
    public string $senderName;

    /**
     * @param Student $student
     * @param string  $subjectTemplate  Subject with placeholders
     * @param string  $bodyTemplate     Body with placeholders
     * @param array   $variables        Key-value pairs for replacement
     * @param string  $senderName       Sender display name (center/instructor name)
     */
    public function __construct(
        Student $student,
        string $subjectTemplate,
        string $bodyTemplate,
        array $variables,
        string $senderName
    ) {
        $this->studentName = $student->name;
        $this->senderName = $senderName;

        // Replace placeholders in subject and body
        $this->subjectLine = self::replacePlaceholders($subjectTemplate, $variables);
        $this->processedBody = self::replacePlaceholders($bodyTemplate, $variables);
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject($this->subjectLine)
                    ->view('emails.welcome_student_mail')
                    ->with([
                        'studentName'    => $this->studentName,
                        'messageContent' => $this->processedBody,
                        'senderName'     => $this->senderName,
                    ]);
    }

    /**
     * Replace {placeholder} tokens with actual values.
     */
    public static function replacePlaceholders(string $template, array $variables): string
    {
        foreach ($variables as $key => $value) {
            $template = str_replace('{' . $key . '}', $value ?? '', $template);
        }
        return $template;
    }
}
