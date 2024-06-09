<?php

declare(strict_types=1);

namespace AppTest\Handler;

use App\Handler\RootHandler;
use JsonException;
use Monolog\Handler\TestHandler;
use Monolog\Logger;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;

class RootHandlerTest extends TestCase
{
    protected TestHandler $logHandler;
    protected Logger $logger;

    protected function setUp(): void
    {
        $this->logHandler = new TestHandler();
        $this->logger = new Logger('test', [$this->logHandler]);
    }

    /**
     * @throws Exception
     * @throws JsonException
     */
    public function testReturnsLinks(): void
    {
        $request = $this->createMock(ServerRequestInterface::class);

        $rootHandler = new RootHandler($this->logger);
        $response = $rootHandler->handle($request);

        $result = json_decode((string)$response->getBody(), true, 512, JSON_THROW_ON_ERROR);
        $this->assertSame(['links' => ['games' => '/games']], $result);
    }
}
