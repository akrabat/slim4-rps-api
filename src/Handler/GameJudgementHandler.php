<?php

declare(strict_types=1);

namespace App\Handler;

use App\Model\Exception\NotFoundException;
use App\Model\GameRepository;
use App\Transformer\GameTransformer;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Log\LoggerInterface;
use RKA\ContentTypeRenderer\HalRenderer;
use Slim\Exception\HttpNotFoundException;
use Slim\Psr7\Response;

final class GameJudgementHandler implements RequestHandlerInterface
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
        $id = $request->getAttribute('id');
        $this->logger->info("Judging game", ['id' => $id]);

        try {
            $game = $this->gameRepository->loadById($id);
        } catch (NotFoundException $e) {
            throw new HttpNotFoundException($request, $e->getMessage(), $e);
        }
        $transformer = new GameTransformer();

        $hal = $transformer->transform($game);

        $response = new Response(201);
        $response = $this->renderer->render($request, $response, $hal);
        return $response;
    }
}
