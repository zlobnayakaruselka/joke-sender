<?php
namespace App\Services\Mail;

use Symfony\Component\Mime\Email;
use Twig\Environment;

class RandomJokeEmailBuilder implements EmailBuilderInterface
{
    private const TEMPLATE = 'mail/random_joke_with_category.html.twig';
    /**
     * @var Environment
     */
    private $twig;

    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
    }

    public function build(string $emailTo, string $category, string $joke): Email
    {
        $html = $this->twig->render(self::TEMPLATE, ['joke' => $joke]);

        $email = (new Email())
            ->from('ya@gmail.com') // TODO в переменную
            ->to($emailTo)
            ->subject("Random joke from the '$category' category")
            ->html($html);

        return $email;
    }
}