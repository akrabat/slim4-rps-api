<?php

declare(strict_types=1);

namespace App\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class BaseUrlMiddleware implements MiddlewareInterface
{
    public function process(
        ServerRequestInterface $request,
        RequestHandlerInterface $handler
    ): ResponseInterface {

        $uri = $request->getUri();
        $scheme = $uri->getScheme();
        $authority = $uri->getAuthority();

        $rootUrl = ($scheme !== '' ? $scheme . ':' : '')
            . ($authority !== '' ? '//' . $authority : '');

        $request = $request->withAttribute('base_url', $rootUrl);
        return $handler->handle($request);
    }

    public static function getBaseUrl(ServerRequestInterface $request): string
    {
        $baseUrl = $request->getAttribute('base_url');
        assert(is_string($baseUrl));
        return $baseUrl;
    }
}
