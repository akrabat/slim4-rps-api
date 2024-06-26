<?php

declare(strict_types=1);

namespace App\Transformer;

use App\Model\Game;
use App\Model\GameMove;
use App\Model\GameStatus;
use App\Model\MatchResult;
use Nocarrier\Hal;
use Nocarrier\HalLink;

final class GameTransformer
{
    public function __construct(protected string $rootUrl = '')
    {
        $this->rootUrl = rtrim($rootUrl, '/');
    }

    /**
     * Create a payload for a single Game
     */
    public function transformItem(Game $game): Hal
    {
        $self = $this->rootUrl . '/games/' . $game->getGameId()->toString();
        $data = [
            'player1' => $game->getPlayer1(),
            'player2' => $game->getPlayer2(),
            'status' => $game->getStatus()->description(),
            'created' => $game->getCreated()->format('Y-m-d H:i:s'),
        ];
        if ($game->getStatus()->is(GameStatus::COMPLETE)) {
            $data = array_merge($data, $this->getResultData($game));
        }
        $links = $this->getLinksForGame($game);

        $resource = new Hal($self, $data);
        foreach ($links as $name => $link) {
            $resource->addHalLink($name, $link);
        }
        return $resource;
    }

    /**
     * Create a payload for collection of Games
     *
     * @param Game[] $games
     * @return Hal
     */
    public function transformCollection(array $games): Hal
    {
        $hal = new Hal($this->rootUrl . '/games');

        $count = 0;
        foreach ($games as $game) {
            $count++;
            $hal->addResource('game', $this->transformItem($game));
        }

        $hal->setData(['count' => $count]);

        return $hal;
    }

    /**
     * Create payloads for actions related to playing the game
     *
     * These payloads do not need the actual game data, just the link to the next action
     */
    public function transform(Game $game): Hal
    {
        $data = $this->getResultData($game);
        $links = $this->getLinksForGame($game);

        $resource = new Hal(null, $data);
        foreach ($links as $name => $link) {
            $resource->addHalLink($name, $link);
        }
        return $resource;
    }

    /**
     * @return array<string, HalLink>
     */
    private function getLinksForGame(Game $game): array
    {
        $links = [];
        $gameId = $game->getGameId()->toString();
        switch ($game->getStatus()->toString()) {
            case GameStatus::CREATED:
                $links['makeNextMove'] = new HalLink(
                    $this->rootUrl . '/games/' . $gameId . '/moves',
                    ['description' => "Make a player's move"]
                );
                break;

            case GameStatus::IN_PROGRESS:
                $player = '1';
                if ($game->getPlayer2Move()->toString() == GameMove::NOT_PLAYED) {
                    $player = '2';
                }
                $links['makeNextMove'] = new HalLink(
                    $this->rootUrl . '/games/' . $gameId . '/moves',
                    ['description' => "Make player $player's move"]
                );
                break;

            case GameStatus::COMPLETE:
                $links['newGame'] = new HalLink(
                    $this->rootUrl . '/games/',
                    ['description' => 'Start a new game']
                );
        }

        return $links;
    }

    /**
     * @return array<string, string>
     */
    private function getResultData(Game $game): array
    {
        if (! $game->getStatus()->is(GameStatus::COMPLETE)) {
            return [];
        }

        $p1Name = $game->getPlayer1();
        $p2Name = $game->getPlayer2();
        $p1Move = $game->getPlayer1Move()->description();
        $p2Move = $game->getPlayer2Move()->description();
        $matchResult = $game->result();
        switch ($matchResult->toInt()) {
            case MatchResult::DRAW:
                return [
                    'result' => 'Draw. Both players chose ' . $p1Move,
                ];

            case MatchResult::P1_WIN:
                return [
                    'result' => $p1Name . ' wins. ' . $p1Move . ' beats ' . $p2Move . '.',
                    'winner' => $p1Name,
                ];

            case MatchResult::P2_WIN:
                return [
                    'result' => $p2Name . ' wins. ' . $p2Move . ' beats ' . $p1Move . '.',
                    'winner' => $p2Name,
                ];
        }
        throw new \RuntimeException("Unknown MatchResult");
    }
}
