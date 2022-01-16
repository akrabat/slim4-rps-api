<?php

declare(strict_types=1);

namespace App\Handler;

use App\Error\Renderer\JsonProblemDetailsErrorRenderer;
use App\Error\Renderer\XmlProblemDetailsErrorRenderer;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use RuntimeException;
use Slim\Exception\HttpMethodNotAllowedException;
use Slim\Handlers\ErrorHandler as SlimErrorHandler;
use Slim\Interfaces\CallableResolverInterface;

final class ProblemDetailsErrorHandler extends SlimErrorHandler
{
    public function __construct(CallableResolverInterface $callableResolver, ResponseFactoryInterface $responseFactory)
    {
        parent::__construct($callableResolver, $responseFactory);

        $this->errorRenderers['application/json'] = JsonProblemDetailsErrorRenderer::class;
        $this->errorRenderers['application/xml'] = XmlProblemDetailsErrorRenderer::class;
    }

    /**
     * @return ResponseInterface
     */
    protected function respond(): ResponseInterface
    {
        $response = $this->responseFactory->createResponse($this->statusCode);
        if ($this->contentType !== null && array_key_exists($this->contentType, $this->errorRenderers)) {
            $contentType = $this->contentType;
            if (stripos($this->contentType, 'json') !== false) {
                $contentType = 'application/problem+json';
            } elseif (stripos($this->contentType, 'xml') !== false) {
                $contentType = 'application/problem+xml';
            }
            $response = $response->withHeader('Content-type', $contentType);
        } else {
            $response = $response->withHeader('Content-type', $this->defaultErrorRendererContentType);
        }

        if ($this->exception instanceof HttpMethodNotAllowedException) {
            $allowedMethods = implode(', ', $this->exception->getAllowedMethods());
            $response = $response->withHeader('Allow', $allowedMethods);
        }

        $renderer = $this->determineRenderer();
        $body = $renderer($this->exception, $this->displayErrorDetails);
        if (!is_string($body)) {
            throw new RuntimeException("Failed to render body");
        }
        $response->getBody()->write($body);

        return $response;
    }
}
