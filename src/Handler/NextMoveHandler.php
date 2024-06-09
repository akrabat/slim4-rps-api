<?php

declare(strict_types=1);

namespace App\Handler;

use App\Error\ErrorCodes;
use App\Middleware\BaseUrlMiddleware;
use App\Model\Exception\NotFoundException;
use App\Model\GameRepository;
use App\Model\ValidationException;
use App\Transformer\GameTransformer;
use Assert\AssertionFailedException;
use Crell\ApiProblem\ApiProblem;
use Doctrine\DBAL\Exception;
use Fig\Http\Message\StatusCodeInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Log\LoggerInterface;
use RKA\ContentTypeRenderer\ApiProblemRenderer;
use RKA\ContentTypeRenderer\HalRenderer;
use Slim\Exception\HttpNotFoundException;
use Slim\Psr7\Response;

final class NextMoveHandler implements RequestHandlerInterface
{
    public function __construct(
        private LoggerInterface $logger,
        private GameRepository $gameRepository,
        private HalRenderer $renderer
    ) {
    }

    /**
     * @throws AssertionFailedException
     * @throws Exception
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        /** @var string $id */
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
            $problem = new ApiProblem($e->getMessage(), ErrorCodes::urlForCode(400));
            $problem['messages'] = $e->getMessages();

            $renderer = new ApiProblemRenderer(true);
            return $renderer->render($request, new Response(StatusCodeInterface::STATUS_BAD_REQUEST), $problem);
        }

        $this->gameRepository->update($game);

        $transformer = new GameTransformer(BaseUrlMiddleware::getBaseUrl($request));
        $hal = $transformer->transform($game);

        $response = new Response(StatusCodeInterface::STATUS_OK);
        $response = $this->renderer->render($request, $response, $hal);
        return $response;
    }
}
