<?php
namespace tests\Unit\Components\Services\Mail;

use App\Components\Services\Mail\RandomJokeEmailFactory;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Twig\Environment;

/**
 * @coversDefaultClass \App\Components\Services\Mail\RandomJokeEmailFactory
 */
class RandomJokeEmailFactoryTest extends TestCase
{
    /**
     * @var Environment | MockObject
     */
    private $twig;
    /**
     * @var RandomJokeEmailFactory
     */
    private $emailFactory;
    /**
     * @var string
     */
    private $template = 'email_template';
    /**
     * @var string
     */
    private $emailFrom = 'ya@gmail.com';

    public function setUp(): void
    {
        parent::setUp();

        $this->twig = $this->getMockBuilder(Environment::class)
            ->disableOriginalConstructor()->getMock();
        
        $this->emailFactory = new RandomJokeEmailFactory($this->twig, $this->template, $this->emailFrom);
    }

    /**
     * @covers ::createEmail
     * @covers ::__construct
     */
    public function testBuild(): void
    {
        $emailTo = 'user@gmail.com';
        $category = 'cat1';
        $joke = 'Chuck is no joker';

        $html = 'html_page';

        $this->twig->expects($this->once())->method('render')
            ->with($this->template, ['joke' => $joke])
            ->willReturn($html);

        $email = $this->emailFactory->createEmail($emailTo, $category, $joke);

        $this->assertEquals($emailTo, $email->getTo()[0]->getAddress());
        $this->assertEquals($this->emailFrom, $email->getFrom()[0]->getAddress());
        $this->assertEquals($html, $email->getHtmlBody());
        $this->assertEquals("Random joke from the '$category' category", $email->getSubject());
    }
}