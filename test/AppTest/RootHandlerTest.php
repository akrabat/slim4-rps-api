<?php

declare(strict_types=1);

namespace AppTest\Handler;

use App\Handler\RootHandler;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Log\LoggerInterface;

class RootHandlerTest extends TestCase
{
    /** @var LoggerInterface|MockObject */
    protected $logger;

    protected function setUp(): void
    {
        $this->logger = $this->createMock(LoggerInterface::class);
        $this->logger
            ->expects($this->once())
            ->method('info')
            ->with('Root handler dispatched');
    }

    public function testReturnsHelloWorldWhenNoNameProvided()
    {
        $request = $this->createMock(ServerRequestInterface::class);

        $rootHandler = new RootHandler($this->logger);
        $response = $rootHandler->handle($request);

        $this->assertInstanceOf(ResponseInterface::class, $response);
        $this->assertSame(json_encode(['msg' => 'Hello world']), (string)$response->getBody());
    }

    public function testReturnsCorrectMessageWhenNameProvided()
    {
        $name = 'Foo';
        $request = $this->createMock(ServerRequestInterface::class);
        $request
            ->expects($this->once())
            ->method('getQueryParams')
            ->willReturn(['name' => $name]);

        $rootHandler = new RootHandler($this->logger);
        $response = $rootHandler->handle($request);

        $this->assertInstanceOf(ResponseInterface::class, $response);
        $this->assertSame(json_encode(['msg' => "Hello $name"]), (string)$response->getBody());
    }
}
