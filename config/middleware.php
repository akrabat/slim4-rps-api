<?php

declare(strict_types=1);

use App\Handler\ProblemDetailsErrorHandler;
use App\Middleware\BaseUrlMiddleware;
use App\Middleware\OpenApiValidationMiddleware;
use App\Middleware\TimerMiddleware;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\App;

return static function (App $app) {
    // LIFO stack: top of this list executes first
    // Everything before the routing middleware knows which route will be executed

//    $app->add(OpenApiValidationMiddleware::class);

    $app->addRoutingMiddleware();
    $app->addBodyParsingMiddleware();
    $app->add(BaseUrlMiddleware::class);
    $app->add(TimerMiddleware::class);

    // Render all errors as Problem Details (RFC 9457)
    $errorMiddleware = $app->addErrorMiddleware(true, true, true);
    $errorMiddleware->setDefaultErrorHandler(new ProblemDetailsErrorHandler(
        $app->getCallableResolver(),
        $app->getResponseFactory()
    ));

    // force Accept header to be application/json
    $app->add(new class implements MiddlewareInterface {
        public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
        {
            $request = $request->withHeader('Accept', 'application/json');
            return $handler->handle($request);
        }
    });
};
