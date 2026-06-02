<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class TestEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:email';

    protected $description = 'Test email configuration';

    public function handle()
    {
        try {
            \Illuminate\Support\Facades\Mail::raw('This is a test email from Taalimu.', function ($message) {
                $message->to('test@example.com')
                        ->subject('Test Email');
            });
            $this->info('Test email sent successfully!');
        } catch (\Exception $e) {
            $this->error('Failed to send email: ' . $e->getMessage());
        }
    }
}
