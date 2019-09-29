<?php

declare(strict_types=1);

namespace App\Model;

use DateTimeImmutable;
use RuntimeException;

final class Game implements Entity
{
    /** @var GameId */
    private $gameId;
    /** @var string */
    private $player1;
    /** @var string */
    private $player2;
    /** @var GameMove */
    private $player1Move;
    /** @var GameMove */
    private $player2Move;
    /** @var GameStatus */
    private $status;

    /** @var DateTimeImmutable */
    private $created;

    private function __construct()
    {
        $this->created = new DateTimeImmutable();
        $this->player1Move = new GameMove(GameMove::NOT_PLAYED);
        $this->player2Move = new GameMove(GameMove::NOT_PLAYED);
        $this->status = new GameStatus(GameStatus::CREATED);
    }

    public static function newGame(GameId $gameId, array $data) : Game
    {
        self::validate($data);

        $game = new Game();
        $game->gameId = $gameId;
        $game->player1 = $data['player1'];
        $game->player2 = $data['player2'];
        return $game;
    }

    public function result()
    {
        if ($this->status !== GameStatus::COMPLETE) {
            throw new RuntimeException('Game not complete');
        }

        $winner = $this->player1;
        $winnerMove = 'rock';
        $loserMove = 'scissors';

        return [
            'result' => $winner . 'won. ' . $winnerMove . ' beats ' . $loserMove,
            'winner' => $winner,
        ];
    }

    public function state(): array
    {
        return [
            'game_id' => $this->gameId->toString(),
            'player1' => $this->player1,
            'player2' => $this->player2,
            'player1_move' => $this->player1Move->toString(),
            'player2_move' => $this->player2Move->toString(),
            'status' => $this->status->toString(),
            'created' => $this->created->format('Y-m-d H:i:s'),
        ];
    }

    public static function fromState(array $state): Game
    {
        $game = new Game();
        $game->gameId = GameId::fromString($state['game_id']);
        $game->player1 = $state['player1'];
        $game->player2 = $state['player2'];
        $game->player1Move = new GameMove($state['player1_move']);
        $game->player2Move = new GameMove($state['player2_move']);
        $game->status = new GameStatus($state['status']);
        $game->created = new DateTimeImmutable($state['created']);

        return $game;
    }

    private static function validate(array $data): void
    {
        $messages = [];
        if (!array_key_exists('player1', $data)) {
            $messages['player1'] = 'player1 is missing';
        }
        if (!array_key_exists('player2', $data)) {
            $messages['player2'] = 'player2 is missing';
        }

        if (!empty($messages)) {
            throw ValidationException::withMessages($messages);
        }
    }
}
