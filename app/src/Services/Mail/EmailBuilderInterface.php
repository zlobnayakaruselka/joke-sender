<?php

namespace App\Services\Mail;

use Symfony\Component\Mime\Email;

interface EmailBuilderInterface
{
    public function build(string $emailTo, string $subject, string $html): Email;
}