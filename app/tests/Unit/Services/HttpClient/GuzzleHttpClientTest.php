<?php
namespace tests\Unit\Services\HttpClient;

use App\Services\HttpClient\GuzzleHttpClient;
use GuzzleHttp\Client;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;

/**
 * @coversDefaultClass \App\Services\HttpClient\GuzzleHttpClient
 */
class GuzzleHttpClientTest extends TestCase
{
    /**
     * @var Client | MockObject
     */
    private $clientMock;
    /**
     * @var GuzzleHttpClient
     */
    private $httpClient;

    public function setUp(): void
    {
        parent::setUp();

        $this->clientMock = $this->getMockBuilder(Client::class)->disableOriginalConstructor()
            ->addMethods(['get'])->getMock();
        $this->httpClient = new GuzzleHttpClient($this->clientMock);
    }

    /**
     * @covers ::get
     */
    public function testGet(): void
    {
        $url = 'http://localhost';
        $options = ['q' => '123'];

        $response = $this->getMockBuilder(ResponseInterface::class)->disableOriginalConstructor()->getMock();

        $this->clientMock->expects($this->once())
            ->method('get')
            ->with($url, $options)
            ->willReturn($response);

        $this->assertEquals($response, $this->httpClient->get($url, $options));
    }

    public function testGetWithRuntimeException()
    {
        $this->expectException(\RuntimeException::class);

        $this->clientMock->expects($this->once())->method('get')
            ->willThrowException(new \RuntimeException());

        $this->httpClient->get('url');
    }
}