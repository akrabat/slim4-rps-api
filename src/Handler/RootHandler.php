<?php

declare(strict_types=1);

namespace App\Handler;

use Fig\Http\Message\StatusCodeInterface;
use JsonException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Log\LoggerInterface;
use Slim\Psr7\Response;

class RootHandler implements RequestHandlerInterface
{
    public function __construct(private LoggerInterface $logger)
    {
    }

    /**
     * @throws JsonException
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $this->logger->info('Root handler dispatched');

        $response = new Response(StatusCodeInterface::STATUS_OK);
        $response = $response->withHeader('Content-Type', 'application/json');
        $response->getBody()->write(
            json_encode(
                [
                'links' => [
                    'games' => '/games'
                    ],
                ],
                JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR
            )
        );
        return $response;
    }
}
