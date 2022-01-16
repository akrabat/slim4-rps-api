<?php

declare(strict_types=1);

namespace App\Handler;

use App\Model\Exception\NotFoundException;
use App\Model\GameRepository;
use App\Transformer\GameTransformer;
use Fig\Http\Message\StatusCodeInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Log\LoggerInterface;
use RKA\ContentTypeRenderer\HalRenderer;
use Slim\Exception\HttpNotFoundException;
use Slim\Psr7\Response;

final class GameJudgementHandler implements RequestHandlerInterface
{
    public function __construct(
        private LoggerInterface $logger,
        private GameRepository $gameRepository,
        private HalRenderer $renderer
    ) {
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        /** @var string $id */
        $id = $request->getAttribute('id');
        $this->logger->info("Judging game", ['id' => $id]);

        try {
            $game = $this->gameRepository->loadById($id);
        } catch (NotFoundException $e) {
            throw new HttpNotFoundException($request, $e->getMessage(), $e);
        }

        /** @var string $baseUrl */
        $baseUrl = $request->getAttribute('base_url');
        $transformer = new GameTransformer($baseUrl);

        $hal = $transformer->transform($game);

        $response = new Response(StatusCodeInterface::STATUS_OK);
        $response = $this->renderer->render($request, $response, $hal);
        return $response;
    }
}
