<?php

declare(strict_types=1);

use App\Handler\ProblemDetailsErrorHandler;
use App\Middleware\TimerMiddleware;
use Slim\App;

return static function (App $app) {
    $app->add(TimerMiddleware::class);
    $app->addRoutingMiddleware();
    $app->addBodyParsingMiddleware();
    $errorMiddleware = $app->addErrorMiddleware(true, true, true);
    $errorMiddleware->setDefaultErrorHandler(new ProblemDetailsErrorHandler(
        $app->getCallableResolver(),
        $app->getResponseFactory()
    ));
};
