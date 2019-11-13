<?php
namespace App\Components\Services\Mail;

use Symfony\Component\Mime\Email;
use Twig\Environment;

class RandomJokeEmailFactory implements EmailFactoryInterface
{
    /**
     * @var Environment
     */
    private $twig;
    /**
     * @var string
     */
    private $template;
    /**
     * @var string
     */
    private $emailFrom;

    public function __construct(Environment $twig, string $template, string $emailFrom)
    {
        $this->twig = $twig;
        $this->template = $template;
        $this->emailFrom = $emailFrom;
    }

    public function createEmail(string $emailTo, string $category, string $joke): Email
    {
        $html = $this->twig->render($this->template, ['joke' => $joke]);

        $email = (new Email())
            ->from($this->emailFrom)
            ->to($emailTo)
            ->subject("Random joke from the '$category' category")
            ->html($html);

        return $email;
    }
}