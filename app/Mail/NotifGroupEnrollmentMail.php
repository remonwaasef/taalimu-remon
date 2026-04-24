<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NotifGroupEnrollmentMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public string $processedBody;
    public string $subjectLine;
    public string $senderName;

    public function __construct(string $subjectTemplate, string $bodyTemplate, array $variables, string $senderName)
    {
        $this->senderName = $senderName;
        $this->subjectLine = self::replacePlaceholders($subjectTemplate, $variables);
        $this->processedBody = self::replacePlaceholders($bodyTemplate, $variables);
    }

    public function build()
    {
        return $this->subject($this->subjectLine)
                    ->view('emails.welcome_student_mail')
                    ->with([
                        'studentName' => $this->senderName, // It uses studentName variable inside the view
                        'messageContent' => $this->processedBody,
                        'senderName'     => $this->senderName,
                    ]);
    }

    public static function replacePlaceholders(string $template, array $variables): string
    {
        foreach ($variables as $key => $value) {
            $template = str_replace('{' . $key . '}', (string) $value, $template);
        }
        return $template;
    }
}
