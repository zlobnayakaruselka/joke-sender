<?php

namespace App\Components\Services\Mail;

use Symfony\Component\Mime\Email;

interface EmailFactoryInterface
{
    public function createEmail(string $emailTo, string $subject, string $html): Email;
}