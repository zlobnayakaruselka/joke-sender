<?php
namespace tests\Unit\Services\Mail;

use App\Services\Mail\RandomJokeEmailBuilder;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Twig\Environment;

/**
 * @coversDefaultClass \App\Services\Mail\RandomJokeEmailBuilder
 */
class RandomJokeEmailBuilderTest extends TestCase
{
    /**
     * @var Environment | MockObject
     */
    private $twig;
    /**
     * @var RandomJokeEmailBuilder
     */
    private $emailBuilder;
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
        
        $this->emailBuilder = new RandomJokeEmailBuilder($this->twig, $this->template, $this->emailFrom);
    }

    /**
     * @covers ::build
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

        $email = $this->emailBuilder->build($emailTo, $category, $joke);

        $this->assertEquals($emailTo, $email->getTo()[0]->getAddress());
        $this->assertEquals($this->emailFrom, $email->getFrom()[0]->getAddress());
        $this->assertEquals($html, $email->getHtmlBody());
        $this->assertEquals("Random joke from the '$category' category", $email->getSubject());
    }
}