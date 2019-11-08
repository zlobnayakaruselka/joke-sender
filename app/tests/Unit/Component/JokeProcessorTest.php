<?php
namespace tests\Unit\Component;

use App\Component\JokeProcessor;
use App\Entity\JokeEntity;
use App\Services\JokeAPI\ChuckNorrisAPI;
use App\Services\JokeAPI\Exception\ApiErrorException;
use App\Services\JokeAPI\Exception\InvalidResponseException;
use App\Services\JokeAPI\JokeAPI;
use App\Services\JokeSaver\JokeSaverInterface;
use App\Services\Mail\EmailBuilderInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Mailer\Exception\TransportException;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

/**
 * @coversDefaultClass \App\Component\JokeProcessor
 */
class JokeProcessorTest extends TestCase
{
    /**
     * @var EmailBuilderInterface | MockObject
     */
    private $emailBuilder;
    /**
     * @var JokeAPI | MockObject
     */
    private $jokeAPI;
    /**
     * @var MailerInterface | MockObject
     */
    private $mailer;
    /**
     * @var JokeSaverInterface | MockObject
     */
    private $jokeSaver;

    /**
     * @var JokeProcessor
     */
    private $jokeProcessor;

    public function setUp(): void
    {
        parent::setUp();

        $this->emailBuilder = $this->getMockBuilder(EmailBuilderInterface::class)
            ->disableOriginalConstructor()->getMock();
        $this->jokeAPI = $this->getMockBuilder(ChuckNorrisAPI::class)
            ->disableOriginalConstructor()->getMock();
        $this->mailer = $this->getMockBuilder(MailerInterface::class)
            ->disableOriginalConstructor()->getMock();
        $this->jokeSaver = $this->getMockBuilder(JokeSaverInterface::class)
            ->disableOriginalConstructor()->getMock();
        
        $this->jokeProcessor = new JokeProcessor(
            $this->emailBuilder,
            $this->jokeAPI,
            $this->mailer,
            $this->jokeSaver
        );
    }

    /**
     * @covers ::__construct
     * @covers ::processJoke
     * @covers ::sendJokeToEmail
     */
    public function testProcessJoke(): void
    {
        $email = 'ya@gmail.com';
        $category = 'cat1';
        $jokeEntity = $this->getMockBuilder(JokeEntity::class)->disableOriginalConstructor()->getMock();

        $this->jokeAPI->expects($this->once())->method('getJokeByCategory')
            ->with($category)->willReturn($jokeEntity);

        $emailMock = $this->getMockBuilder(Email::class)->disableOriginalConstructor()->getMock();
        $this->emailBuilder->expects($this->once())->method('build')
            ->with($email, $category, '')->willReturn($emailMock);

        $this->mailer->expects($this->once())->method('send')
            ->with($emailMock);
        $this->jokeSaver->expects($this->once())->method('save')
            ->with($jokeEntity, $email);

        $this->jokeProcessor->processJoke($category, $email);
    }

    public function dataProcessJokeWithApiException()
    {
        return [
            [\RuntimeException::class],
            [ApiErrorException::class],
            [InvalidResponseException::class]
        ];
    }

    /**
     * @dataProvider dataProcessJokeWithApiException
     * @covers ::processJoke
     */
    public function testProcessJokeWithApiException(string $exceptionClass): void
    {
        $this->expectException($exceptionClass);

        $this->jokeAPI->expects($this->once())
            ->method('getJokeByCategory')
            ->willThrowException(new $exceptionClass());

        $this->emailBuilder->expects($this->never())
            ->method('build');

        $this->mailer->expects($this->never())
            ->method('send');

        $this->jokeSaver->expects($this->never())
            ->method('save');

        $this->jokeProcessor->processJoke('cat1', 'ya@gmail.com');
    }

    /**
     * @covers ::processJoke
     * @covers ::sendJokeToEmail
     */
    public function testProcessJokeWithEmailException(): void
    {
        $this->expectException(TransportExceptionInterface::class);
        $email = 'ya@gmail.com';
        $category = 'cat1';
        $jokeEntity = $this->getMockBuilder(JokeEntity::class)->disableOriginalConstructor()->getMock();

        $this->jokeAPI->expects($this->once())->method('getJokeByCategory')
            ->with($category)->willReturn($jokeEntity);

        $emailMock = $this->getMockBuilder(Email::class)->disableOriginalConstructor()->getMock();
        $this->emailBuilder->expects($this->once())->method('build')
            ->with($email, $category, '')->willReturn($emailMock);

        $this->mailer->expects($this->once())
            ->method('send')
            ->willThrowException(new TransportException());

        $this->jokeSaver->expects($this->never())
            ->method('save');

        $this->jokeProcessor->processJoke($category, $email);
    }
}