<?php
namespace App\Component;

use App\Services\JokeAPI\Exception\ApiErrorException;
use App\Services\JokeAPI\Exception\InvalidResponseException;
use App\Services\JokeAPI\JokeAPI;
use App\Services\Mail\EmailBuilderInterface;
use App\Services\JokeSaver\JokeSaverInterface;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;

class JokeProcessor implements JokeProcessorInterface
{
    /**
     * @var JokeAPI
     */
    protected $jokeAPI;
    /**
     * @var MailerInterface
     */
    protected $mailer;
    /**
     * @var EmailBuilderInterface
     */
    protected $emailBuilder;
    /**
     * @var JokeSaverInterface
     */
    protected $jokeSaver;

    public function __construct(
        EmailBuilderInterface $emailBuilder,
        JokeAPI $jokeAPI,
        MailerInterface $mailer,
        JokeSaverInterface $jokeSaver
    ) {
        $this->jokeAPI = $jokeAPI;
        $this->mailer = $mailer;
        $this->emailBuilder = $emailBuilder;
        $this->jokeSaver = $jokeSaver;
    }

    /**
     * @param string $category
     * @param string $email
     * @throws TransportExceptionInterface
     * @throws ApiErrorException
     * @throws InvalidResponseException
     */
    public function processJoke(string $category, string $email): void
    {
        $jokeEntity = $this->jokeAPI->getJokeByCategory($category);

        $this->sendJokeToEmail($email, $category, $jokeEntity->getJoke());
        $this->jokeSaver->save($jokeEntity, $email);
    }

    /**
     * @param string $email
     * @param string $category
     * @param string $joke
     * @throws TransportExceptionInterface
     */
    private function sendJokeToEmail(string $email, string $category, string $joke): void
    {
        $emailObject = $this->emailBuilder->build($email, $category, $joke);

        $this->mailer->send($emailObject);
    }
}