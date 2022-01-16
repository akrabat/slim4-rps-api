<?php

declare(strict_types=1);

namespace App\Handler;

use App\Model\GameRepository;
use App\Transformer\GameTransformer;
use Fig\Http\Message\StatusCodeInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Log\LoggerInterface;
use RKA\ContentTypeRenderer\HalRenderer;
use Slim\Psr7\Response;

final class ListGamesHandler implements RequestHandlerInterface
{
    public function __construct(
        private LoggerInterface $logger,
        private GameRepository $gameRepository,
        private HalRenderer $renderer
    ) {
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $params = $request->getQueryParams();
        $this->logger->info("Listing games", ['queryParams' => $params]);

        $games = $this->gameRepository->fetch();

        /** @var string $baseUrl */
        $baseUrl = $request->getAttribute('base_url');
        $transformer = new GameTransformer($baseUrl);
        $hal = $transformer->transformCollection($games);

        $response = new Response(StatusCodeInterface::STATUS_OK);
        $response = $this->renderer->render($request, $response, $hal);
        return $response;
    }
}
