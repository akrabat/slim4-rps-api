<?php

declare(strict_types=1);

namespace App\Handler;

use App\Model\Exception\NotFoundException;
use App\Model\GameMove;
use App\Model\GameRepository;
use App\Model\ValidationException;
use App\Transformer\GameTransformer;
use Crell\ApiProblem\ApiProblem;
use Fig\Http\Message\StatusCodeInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Log\LoggerInterface;
use RKA\ContentTypeRenderer\ApiProblemRenderer;
use RKA\ContentTypeRenderer\HalRenderer;
use Slim\Exception\HttpBadRequestException;
use Slim\Exception\HttpNotFoundException;
use Slim\Psr7\Response;

final class NextMoveHandler implements RequestHandlerInterface
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
        $data = (array)$request->getParsedBody();
        $this->logger->info("Next move", ['id' => $id, 'data' => $data]);

        try {
            $game = $this->gameRepository->loadById($id);
        } catch (NotFoundException $e) {
            throw new HttpNotFoundException($request, $e->getMessage(), $e);
        }

        try {
            $game->makeMove($data);
        } catch (ValidationException $e) {
            $problem = new ApiProblem($e->getMessage(), 'https://tools.ietf.org/html/rfc7231#section-6.5.1');
            $problem['messages'] = $e->getMessages();

            $renderer = new ApiProblemRenderer(true);
            return $renderer->render($request, new Response(StatusCodeInterface::STATUS_BAD_REQUEST), $problem);
        }

        $this->gameRepository->update($game);

        $transformer = new GameTransformer();
        $hal = $transformer->transform($game);

        $response = new Response(StatusCodeInterface::STATUS_OK);
        $response = $this->renderer->render($request, $response, $hal);
        return $response;
    }
}
