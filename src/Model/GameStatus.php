<?php

declare(strict_types=1);

namespace App\Model;

use Assert\Assertion;

final class GameStatus
{
    public const CREATED        = 'CREATED';
    public const PLAYER1_PLAYED = 'PLAYER1_PLAYED';
    public const PLAYER2_PLAYED = 'PLAYER2_PLAYED';
    public const COMPLETE       = 'COMPLETE';

    public static $validStatuses = ['CREATED', 'PLAYER1_PLAYED', 'PLAYER2_PLAYED', 'COMPLETE'];
    private $status;

    public function __construct(string $status)
    {
        Assertion::choice($status, self::$validStatuses);
        $this->status = $status;
    }

    public function toString(): string
    {
        return $this->status;
    }

    public function description(): string
    {
        $descriptions = [
            self::CREATED        => 'Game not started',
            self::PLAYER1_PLAYED => 'Player 1 played, waiting for player 2',
            self::PLAYER2_PLAYED => 'Player 2 played, waiting for player 1',
            self::COMPLETE       => 'Complete',
        ];

        return $descriptions[$this->status];
    }

    public function is(string $status): bool
    {
        Assertion::choice($status, self::$validStatuses);

        return $status === $this->status;
    }
}
