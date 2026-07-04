<?php

namespace App\Mail;

/**
 * Identical to the generic template mail; kept as a separate class so payment
 * confirmations stay distinguishable in queue/failed-job logs.
 */
class NotifPaymentConfirmedMail extends NotifGroupEnrollmentMail {}
