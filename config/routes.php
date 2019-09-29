<?php

declare(strict_types=1);

use App\Handler\CreateGameHandler;
use App\Handler\ListGamesHandler;
use App\Handler\RootHandler;
use Slim\App;

return static function (App $app) {
    $app->get('/', RootHandler::class)->setName('root');
    $app->post('/games', CreateGameHandler::class)->setName('game-create');
    $app->get('/games', ListGamesHandler::class)->setName('game-list');
};
