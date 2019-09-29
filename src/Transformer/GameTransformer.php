<?php

declare(strict_types=1);

namespace App\Transformer;

use App\Model\Entity;
use App\Model\Game;
use App\Model\GameStatus;
use Nocarrier\Hal;
use Nocarrier\HalLink;

final class GameTransformer implements Transformer
{
    public function transformItem(Entity $game): Hal
    {
        /** @var Game $game */
        // $self = '/games/' . $game->getGameId()->toString();
        $self = null;
        $data = [
            'player1' => $game->getPlayer1(),
            'player2' => $game->getPlayer2(),
            'status' => $game->getStatus()->description(),
            'created' => $game->getCreated()->format('Y-m-d H:i:s'),
        ];
        $links = $this->getLinksForGame($game);

        $resource = new Hal($self, $data);
        foreach ($links as $name => $link) {
            $resource->addHalLink($name, $link);
        }
        return $resource;
    }

    /**
     * @param Entity[] $objects
     * @return Hal
     */
    public function transformCollection(array $objects): Hal
    {
        $hal = new Hal('/games');

        $count = 0;
        foreach ($objects as $object) {
            $count++;
            $hal->addResource('game', $this->transformItem($object));
        }

        $hal->setData(['count' => $count]);

        return $hal;
    }


    public function transform(Game $object): Hal
    {
        /** @var Game $object */
        $state = $object->state();
        $gameId = $state['game_id'];

        $self = '/games/' . $gameId;
        $links = [];
        $data = [];

        switch($state['status']) {
            case GameStatus::CREATED:
                $links['makeNextMove'] = new HalLink('/games/' . $gameId .'/moves', ['description' => "Make a player's move"]);
                break;

            case GameStatus::PLAYER1_PLAYED:
                $player = '2';
                $links['makeNextMove'] = new HalLink('/games/' . $gameId .'/moves', ['description' => "Make player $player's move"]);
                break;

            case GameStatus::PLAYER2_PLAYED:
                $player = '1';
                $links['makeNextMove'] = new HalLink('/games/' . $gameId .'/moves', ['description' => "Make player $player's move"]);
                break;

            case GameStatus::COMPLETE;
                $state = $object->result();
                $links['newGame'] = new HalLink('/games/', ['description' => 'Start a new game']);
        }

        $resource = new Hal($self, $data);
        foreach ($links as $name => $link) {
            $resource->addHalLink($name, $link);
        }
        return $resource;
    }

    private function getLinksForGame(Game $game)
    {
        $links = [];
        $gameId = $game->getGameId()->toString();
        switch ($game->getStatus()->toString()) {
            case GameStatus::CREATED:
                $links['makeNextMove'] = new HalLink('/games/' . $gameId . '/moves',
                    ['description' => "Make a player's move"]);
                break;

            case GameStatus::PLAYER1_PLAYED:
                $player = '2';
                $links['makeNextMove'] = new HalLink('/games/' . $gameId . '/moves',
                    ['description' => "Make player $player's move"]);
                break;

            case GameStatus::PLAYER2_PLAYED:
                $player = '1';
                $links['makeNextMove'] = new HalLink('/games/' . $gameId . '/moves',
                    ['description' => "Make player $player's move"]);
                break;

            case GameStatus::COMPLETE;
                $state = $game->result();
                $links['newGame'] = new HalLink('/games/', ['description' => 'Start a new game']);
        }

        return $links;
    }
}
