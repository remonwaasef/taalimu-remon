<?php

require __DIR__.'/../vendor/autoload.php';

use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mime\Email;

try {
    $transport = Transport::fromDsn('smtp://a9273e001%40smtp-brevo.com:xsmtpsib-74c0e8eb7df8f8df32fe7c424395b281443f4c9eb0f4f40cd8ff7fcf42f4b822-JIHbg17X9X742KKe@smtp-relay.brevo.com:587');
    $mailer = new Mailer($transport);

    $email = (new Email())
        ->from('reemmoo20022@gmail.com')
        ->to('reemmoo20022@gmail.com')
        ->subject('Testing Brevo Sender address')
        ->text('If you receive this, sending from generic Gmail works.');

    $mailer->send($email);
    echo "Message sent successfully!\n";
} catch (\Exception $e) {
    echo "Error sending message: " . $e->getMessage() . "\n";
}
