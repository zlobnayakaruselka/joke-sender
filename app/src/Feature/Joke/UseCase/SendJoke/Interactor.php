<?php
namespace App\Feature\Joke\UseCase\SendJoke;

use App\Application\FileSystem\Saver\JokeSaverInterface;
use App\Application\Form\Model\JokeSenderModel;
use App\Feature\Joke\Entity\JokeEntity;
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
    protected $emailFactory;
    /**
     * @var JokeSaverInterface
     */
    protected $jokeSaver;

    public function __construct(
        JokeApiInterface $jokeApi,
        EmailFactoryInterface $emailFactory,
        MailerInterface $mailer,
        JokeSaverInterface $jokeSaver
    ) {
        $this->jokeApi = $jokeApi;
        $this->mailer = $mailer;
        $this->emailFactory = $emailFactory;
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
        $emailObject = $this->emailFactory->createEmail(
            $jokeSenderModel->getEmail(),
            $jokeSenderModel->getCategory(),
            $jokeEntity->getJoke()
        );

        $this->mailer->send($emailObject);
    }
}