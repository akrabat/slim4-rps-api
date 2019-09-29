<?php

declare(strict_types=1);

use App\Error\Renderer\JsonProblemDetailsErrorRenderer;
use App\Error\Renderer\XmlProblemDetailsErrorRenderer;
use Slim\App;

return static function (App $app) {
    $app->addRoutingMiddleware();
    $app->addBodyParsingMiddleware();
    $errorMiddleware = $app->addErrorMiddleware(true, true, true);

    // Register new error renderers
    $errorHandler = $errorMiddleware->getDefaultErrorHandler();
    $errorHandler->registerErrorRenderer('application/json', JsonProblemDetailsErrorRenderer::class);
    $errorHandler->registerErrorRenderer('application/xml', XmlProblemDetailsErrorRenderer::class);
};
