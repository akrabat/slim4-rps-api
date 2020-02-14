<?php

declare(strict_types=1);

use App\Handler\ProblemDetailsErrorHandler;
use App\Middleware\BaseUrlMiddleware;
use App\Middleware\TimerMiddleware;
use Slim\App;

return static function (App $app) {
    // LIFO stack
    $errorMiddleware = $app->addErrorMiddleware(true, true, true);
    $app->add(TimerMiddleware::class);
    $app->add(BaseUrlMiddleware::class);
    $app->addBodyParsingMiddleware();
    $app->addRoutingMiddleware();

    $errorMiddleware->setDefaultErrorHandler(new ProblemDetailsErrorHandler(
        $app->getCallableResolver(),
        $app->getResponseFactory()
    ));
};
