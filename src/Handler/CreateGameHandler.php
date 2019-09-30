<?php

declare(strict_types=1);

namespace App\Handler;

use App\Exception\HttpValidationException;
use App\Model\Game;
use App\Model\GameId;
use App\Model\GameRepository;
use App\Model\ValidationException;
use App\Transformer\GameTransformer;
use Fig\Http\Message\StatusCodeInterface;
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
        $data = (array)$request->getParsedBody();
        $this->logger->info("Creating a new game", ['data' => $data]);

        $gameId = GameId::fromUuid();
        try {
            $game = Game::newGame($gameId, $data);
        } catch (ValidationException $e) {
            throw HttpValidationException::fromValidationException($request, $e);
        }

        $this->gameRepository->add($game);

        $transformer = new GameTransformer();
        $hal = $transformer->transform($game);

        $response = new Response(StatusCodeInterface::STATUS_CREATED);
        $response = $this->renderer->render($request, $response, $hal);
        return $response;
    }
}
