<?php
namespace tests\Unit\Feature\Joke\UseCase\SendJoke;

use App\Application\FileSystem\Saver\JokeSaverInterface;
use App\Application\Form\Model\JokeSenderModel;
use App\Feature\Joke\Entity\JokeEntity;
use App\Components\JokeApi\JokeApiInterface;
use App\Components\Services\Mail\EmailFactoryInterface;
use App\Feature\Joke\UseCase\SendJoke\Interactor;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Mailer\Exception\TransportException;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

/**
 * @coversDefaultClass \App\Feature\Joke\UseCase\SendJoke\Interactor
 */
class InteractorTest extends TestCase
{
    /**
     * @var JokeApiInterface | MockObject
     */
    private $jokeApi;
    /**
     * @var MailerInterface | MockObject
     */
    private $mailer;
    /**
     * @var EmailFactoryInterface | MockObject
     */
    private $emailFactory;
    /**
     * @var JokeSaverInterface | MockObject
     */
    private $jokeSaver;
    /**
     * @var Interactor
     */
    private $interactor;

    public function setUp(): void
    {
        parent::setUp();

        $this->jokeApi = $this->getMockBuilder(JokeApiInterface::class)->disableOriginalConstructor()->getMock();
        $this->mailer = $this->getMockBuilder(MailerInterface::class)->disableOriginalConstructor()->getMock();
        $this->emailFactory = $this->getMockBuilder(EmailFactoryInterface::class)->disableOriginalConstructor()->getMock();
        $this->jokeSaver = $this->getMockBuilder(JokeSaverInterface::class)->disableOriginalConstructor()->getMock();

        $this->interactor = new Interactor(
            $this->jokeApi,
            $this->emailFactory,
            $this->mailer,
            $this->jokeSaver
        );
    }

    /**
     * @covers ::<public>
     * @covers ::<private>
     */
    public function testSendJoke()
    {
        $category = 'cat1';
        $email = 'ua@ua.ua';
        $joke = 'ffffffff';

        $senderModel = $this->getMockBuilder(JokeSenderModel::class)->disableOriginalConstructor()->getMock();
        $senderModel->expects($this->exactly(2))->method('getCategory')->willReturn($category);
        $senderModel->expects($this->exactly(2))->method('getEmail')->willReturn($email);

        $jokeEntity = $this->getMockBuilder(JokeEntity::class)->disableOriginalConstructor()->getMock();
        $jokeEntity->expects($this->once())->method('getJoke')->willReturn($joke);

        $this->jokeApi->expects($this->once())->method('getJokeByCategory')->with($category)->willReturn($jokeEntity);

        $emailObject = $this->getMockBuilder(Email::class)->disableOriginalConstructor()->getMock();

        $this->emailFactory->expects($this->once())->method('createEmail')
            ->with($email, $category, $joke)->willReturn($emailObject);

        $this->mailer->expects($this->once())->method('send')->with($emailObject);

        $this->jokeSaver->expects($this->once())->method('save')->with($jokeEntity, $email);

        $this->interactor->sendJoke($senderModel);
    }

    /**
     * @covers ::<public>
     * @covers ::<private>
     */
    public function testSendJokeWithException()
    {
        $this->expectException(TransportExceptionInterface::class);

        $category = 'cat1';
        $email = 'ua@ua.ua';
        $joke = 'ffffffff';

        /** @var JokeSenderModel | MockObject $senderModel */
        $senderModel = $this->getMockBuilder(JokeSenderModel::class)->disableOriginalConstructor()->getMock();
        $senderModel->expects($this->exactly(2))->method('getCategory')->willReturn($category);
        $senderModel->expects($this->exactly(1))->method('getEmail')->willReturn($email);

        $jokeEntity = $this->getMockBuilder(JokeEntity::class)->disableOriginalConstructor()->getMock();
        $jokeEntity->expects($this->once())->method('getJoke')->willReturn($joke);

        $this->jokeApi->expects($this->once())->method('getJokeByCategory')->with($category)->willReturn($jokeEntity);

        $emailObject = $this->getMockBuilder(Email::class)->disableOriginalConstructor()->getMock();

        $this->emailFactory->expects($this->once())->method('createEmail')
            ->with($email, $category, $joke)->willReturn($emailObject);

        $this->mailer->expects($this->once())->method('send')->with($emailObject)->willThrowException(new TransportException(''));

        $this->jokeSaver->expects($this->never())->method('save');

        $this->interactor->sendJoke($senderModel);
    }
}