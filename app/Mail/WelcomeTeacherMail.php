<?php

namespace App\Mail;

use App\Models\Instructor;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class WelcomeTeacherMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public string $teacherName;

    public string $emailAddress;

    public string $plainPassword;

    public string $centerName;

    public string $loginLink;

    public function __construct(
        Instructor $instructor,
        string $plainPassword,
        string $centerName,
        string $loginLink
    ) {
        $this->teacherName = $instructor->name;
        $this->emailAddress = $instructor->email;
        $this->plainPassword = $plainPassword;
        $this->centerName = $centerName;
        $this->loginLink = $loginLink;
    }

    public function build()
    {
        return $this->subject('مرحباً بك في منصة '.$this->centerName)
            ->view('emails.welcome_teacher_mail')
            ->with([
                'teacherName' => $this->teacherName,
                'emailAddress' => $this->emailAddress,
                'plainPassword' => $this->plainPassword,
                'centerName' => $this->centerName,
                'loginLink' => $this->loginLink,
            ]);
    }
}
