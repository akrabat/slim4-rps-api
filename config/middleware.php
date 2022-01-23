<?php

declare(strict_types=1);

use App\Handler\ProblemDetailsErrorHandler;
use App\Middleware\BaseUrlMiddleware;
use App\Middleware\OpenApiValidationMiddleware;
use App\Middleware\TimerMiddleware;
use Slim\App;

return static function (App $app) {
    // LIFO stack
    $app->add(OpenApiValidationMiddleware::class);
    $app->addRoutingMiddleware();
    $app->addBodyParsingMiddleware();
    $app->add(BaseUrlMiddleware::class);
    $app->add(TimerMiddleware::class);
    $errorMiddleware = $app->addErrorMiddleware(true, true, true);
    $errorMiddleware->setDefaultErrorHandler(new ProblemDetailsErrorHandler(
        $app->getCallableResolver(),
        $app->getResponseFactory()
    ));
};
