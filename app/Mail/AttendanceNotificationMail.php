<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AttendanceNotificationMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $subject;

    public $body;

    public $variables;

    public $centerName;

    public $studentName;

    /**
     * Create a new message instance.
     */
    public function __construct($subject, $body, $variables, $centerName, $studentName)
    {
        $this->subject = str_replace(["\r", "\n"], '', $subject);
        $this->body = $body;
        $this->variables = $variables;
        $this->centerName = $centerName;
        $this->studentName = $studentName;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        $processedBody = $this->replacePlaceholders($this->body, $this->variables);

        return $this->subject($this->replacePlaceholders($this->subject, $this->variables))
            ->view('emails.welcome_student_mail')
            ->with([
                'studentName' => $this->studentName,
                'messageContent' => $processedBody,
                'senderName' => $this->centerName,
            ]);
    }

    protected function replacePlaceholders($template, $variables)
    {
        foreach ($variables as $key => $value) {
            $template = str_replace('{'.$key.'}', (string) $value, $template);
        }

        return $template;
    }
}
