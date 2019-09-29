<?php

declare(strict_types=1);

namespace App\Handler;

use App\Model\Game;
use App\Model\GameId;
use App\Model\GameRepository;
use App\Transformer\GameTransformer;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Log\LoggerInterface;
use RKA\ContentTypeRenderer\HalRenderer;
use Slim\Psr7\Response;

final class CreateGameHandler implements RequestHandlerInterface
{
    private $logger;
    private $renderer;
    private $gameRepository;

    public function __construct(LoggerInterface $logger, GameRepository $gameRepository, HalRenderer $renderer)
    {
        $this->logger = $logger;
        $this->renderer = $renderer;
        $this->gameRepository = $gameRepository;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $data = $request->getParsedBody();
        $this->logger->info("Creating a new game", ['data' => $data]);

        $gameId = GameId::fromUuid();
        $game = Game::newGame($gameId, $data);

        $this->gameRepository->add($game);

        $transformer = new GameTransformer();
        $hal = $transformer->transformItem($game);

        $response = new Response(201);
        $response = $this->renderer->render($request, $response, $hal);
        return $response;
    }
}
