<?php

declare(strict_types=1);

namespace App\Handler;

use Fig\Http\Message\StatusCodeInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Log\LoggerInterface;
use Slim\Psr7\Response;

class RootHandler implements RequestHandlerInterface
{
    private $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $this->logger->info('Root handler dispatched');

        $name = $request->getQueryParams()['name'] ?? 'world';

        $response = new Response(StatusCodeInterface::STATUS_OK);
        $response = $response->withHeader('Content-Type', 'application/json');
        $response->getBody()->write(json_encode(['msg' => "Hello $name"]));
        return $response;
    }
}
