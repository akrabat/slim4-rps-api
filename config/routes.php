<?php

declare(strict_types=1);

use App\Handler\RootHandler;
use Slim\App;

return static function (App $app) {
    $app->get('/[{name}]', RootHandler::class)->setName('root');
};
