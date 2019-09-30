<?php

declare(strict_types=1);

use App\Handler\CreateGameHandler;
use App\Handler\GetGameHandler;
use App\Handler\GameJudgementHandler;
use App\Handler\ListGamesHandler;
use App\Handler\NextMoveHandler;
use App\Handler\RootHandler;
use Slim\App;

return static function (App $app) {
    $app->get('/', RootHandler::class)->setName('root');

    $app->post('/games', CreateGameHandler::class);
    $app->get('/games', ListGamesHandler::class);
    $app->get('/games/{id}', GetGameHandler::class);
    $app->post('/games/{id}/moves', NextMoveHandler::class);
    $app->get('/games/{id}/judgement', GameJudgementHandler::class);
};
