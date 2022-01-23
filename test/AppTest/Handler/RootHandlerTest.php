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

    public function testReturnsLinks()
    {
        $request = $this->createMock(ServerRequestInterface::class);

        $rootHandler = new RootHandler($this->logger);
        $response = $rootHandler->handle($request);

        $result = json_decode((string)$response->getBody(), true);
        $this->assertSame(['links' => ['games' => '/games']], $result);
    }
}
