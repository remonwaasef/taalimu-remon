<?php

namespace App\Mail;

use App\Models\Student;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CustomStudentMail extends Mailable
{
    use Queueable, SerializesModels;

    public $student;

    public $messageContent;

    public $subjectString;

    public $senderName;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Student $student, $subjectString, $messageContent, $senderName = null)
    {
        $this->student = $student;
        $this->subjectString = str_replace(["\r", "\n"], '', $subjectString);
        $this->messageContent = $messageContent;
        $this->senderName = $senderName ?: config('app.name');
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject($this->subjectString)
            ->view('emails.custom_student_mail')
            ->with([
                'studentName' => $this->student->name,
                'messageContent' => $this->messageContent,
                'senderName' => $this->senderName,
            ]);
    }
}
