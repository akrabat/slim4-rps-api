<?php

declare(strict_types=1);

namespace App\Model;

final class Game implements Entity
{
    /** @var GameId */
    private $gameId;
    private $player1;
    private $player2;

    private function __construct(GameId $gameId, $player1, $player2)
    {
        $this->gameId = $gameId;
        $this->player1 = $player1;
        $this->player2 = $player2;
    }

    public static function fromData(GameId $gameId, array $data) : Game
    {
        self::validate($data);
        return new Game($gameId, $data['player1'], $data['player2']);
    }

    public function state(): array
    {
        return [
            'game_id' => $this->gameId->toString(),
            'player1' => $this->player1,
            'player2' => $this->player2,
        ];
    }

    public static function fromState(array $state): Game
    {
        $object = new self(
            GameId::fromString($state['game_id']),
            $state['player1'],
            $state['player2'],
        );

        return $object;
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
