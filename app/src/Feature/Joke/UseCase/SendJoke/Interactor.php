<?php
namespace App\Feature\Joke\UseCase\SendJoke;

use App\Application\FileSystem\JokeSaver\JokeSaverInterface;
use App\Application\Form\Model\JokeSenderModel;
use App\Components\Entity\JokeEntity;
use App\Components\JokeApi\JokeApiInterface;
use App\Components\Services\Mail\EmailFactoryInterface;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;

class Interactor
{
    /**
     * @var JokeApiInterface
     */
    protected $jokeApi;
    /**
     * @var MailerInterface
     */
    protected $mailer;
    /**
     * @var EmailFactoryInterface
     */
    protected $emailBuilder;
    /**
     * @var JokeSaverInterface
     */
    protected $jokeSaver;

    public function __construct(
        EmailFactoryInterface $emailBuilder,
        JokeApiInterface $jokeApi,
        MailerInterface $mailer,
        JokeSaverInterface $jokeSaver
    ) {
        $this->jokeApi = $jokeApi;
        $this->mailer = $mailer;
        $this->emailBuilder = $emailBuilder;
        $this->jokeSaver = $jokeSaver;
    }

    public function sendJoke(JokeSenderModel $jokeSenderModel): void
    {
        $jokeEntity = $this->jokeApi->getJokeByCategory($jokeSenderModel->getCategory());

        $this->sendJokeToEmail($jokeSenderModel, $jokeEntity);
        $this->jokeSaver->save($jokeEntity, $jokeSenderModel->getEmail());
    }

    /**
     * @param JokeSenderModel $jokeSenderModel
     * @param JokeEntity $jokeEntity
     * @throws TransportExceptionInterface
     */
    private function sendJokeToEmail(JokeSenderModel $jokeSenderModel, JokeEntity $jokeEntity): void
    {
        $emailObject = $this->emailBuilder->createEmail(
            $jokeSenderModel->getEmail(),
            $jokeSenderModel->getCategory(),
            $jokeEntity->getJoke()
        );

        //ResponseModelCreator$this->mailer->send($emailObject);
    }
}