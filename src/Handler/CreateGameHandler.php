<?php

declare(strict_types=1);

namespace App\Handler;

use App\Model\Game;
use App\Model\GameId;
use App\Transformer\GameTransformer;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Log\LoggerInterface;
use Ramsey\Uuid\Uuid;
use RKA\ContentTypeRenderer\HalRenderer;
use Slim\Psr7\Response;

final class CreateGameHandler implements RequestHandlerInterface
{
    private $logger;
    private $renderer;

    public function __construct(LoggerInterface $logger, HalRenderer $renderer)
    {
        $this->logger = $logger;
        $this->renderer = $renderer;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $data = $request->getParsedBody();
        $this->logger->info("Creating a new game", ['data' => $data]);

        $gameId = GameId::fromUuid(Uuid::uuid4());
        $game = Game::fromData($gameId, $data);


        $transformer = new GameTransformer();
        $hal = $transformer->transformItem($game);

        $response = new Response(201);
        $response = $this->renderer->render($request, $response, $hal);
        return $response;
    }
}
