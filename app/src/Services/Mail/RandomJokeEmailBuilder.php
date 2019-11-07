<?php
namespace App\Services\Mail;

use Symfony\Component\Mime\Email;
use Twig\Environment;

class RandomJokeEmailBuilder implements EmailBuilderInterface
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

    public function build(string $emailTo, string $category, string $joke): Email
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