<?php

namespace App\Middleware;

use League\OpenAPIValidation\PSR7\Exception\ValidationFailed;
use League\OpenAPIValidation\PSR7\ResponseValidator;
use League\OpenAPIValidation\PSR7\ServerRequestValidator;
use League\OpenAPIValidation\Schema\Exception\TooManyValidSchemas;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Exception\HttpBadRequestException;
use Slim\Exception\HttpInternalServerErrorException;

class OpenApiValidationMiddleware implements MiddlewareInterface
{
    public function __construct(
        private ServerRequestValidator $requestValidator,
        private ResponseValidator $responseValidator
    ) {
    }

    /**
     * @throws HttpBadRequestException
     * @throws HttpInternalServerErrorException
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        // validate request
        try {
            $match = $this->requestValidator->validate($request);
        } catch (\Throwable $e) {
            throw new HttpBadRequestException($request, $e->getMessage(), $e);
        }

        // process
        $response = $handler->handle($request);

        // validate response
        try {
            $this->responseValidator->validate($match, $response);
        } catch (ValidationFailed $e) {
            $previous = $e->getPrevious();
            if ($previous && $previous instanceof TooManyValidSchemas) {
                // Response validation allows for missing properties, so NextMoveBody & JudgementBody look the same
                // which causes a TooManyValidSchemas even though one has a `result` property and one doesn't
                return $response;
            }
            throw new HttpInternalServerErrorException($request, $e->getMessage(), $e);
        }

        return $response;
    }
}
