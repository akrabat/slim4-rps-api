<?php

namespace App\Middleware;

use League\OpenAPIValidation\PSR7\Exception\ValidationFailed;
use League\OpenAPIValidation\PSR7\ResponseValidator;
use League\OpenAPIValidation\PSR7\ServerRequestValidator;
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
        } catch (ValidationFailed $e) {
            throw new HttpBadRequestException($request, $e->getMessage(), $e);
        }

        // process
        $response = $handler->handle($request);

        // validate response
        try {
            $this->responseValidator->validate($match, $response);
        } catch (ValidationFailed $e) {
            throw new HttpInternalServerErrorException($request, $e->getMessage(), $e);
        }

        return $response;
    }
}
