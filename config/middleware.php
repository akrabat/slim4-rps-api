<?php

declare(strict_types=1);

use App\Handler\ProblemDetailsErrorHandler;
use Slim\App;

return static function (App $app) {
    $app->addRoutingMiddleware();
    $app->addBodyParsingMiddleware();
    $errorMiddleware = $app->addErrorMiddleware(true, true, true);
    $errorMiddleware->setDefaultErrorHandler(new ProblemDetailsErrorHandler(
        $app->getCallableResolver(),
        $app->getResponseFactory()
    ));
};
