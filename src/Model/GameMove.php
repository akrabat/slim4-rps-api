<?php

declare(strict_types=1);

namespace App\Model;

use Assert\Assertion;
use Assert\AssertionFailedException;

final class GameMove
{
    public const NOT_PLAYED = 'NOT_PLAYED';
    public const ROCK = 'ROCK';
    public const PAPER = 'PAPER';
    public const SCISSORS = 'SCISSORS';

    public static $validMoves = ['NOT_PLAYED', 'ROCK', 'PAPER', 'SCISSORS'];
    private $move;

    /**
     * @throws AssertionFailedException
     */
    public function __construct(string $move)
    {
        Assertion::choice($move, self::$validMoves);
        $this->move = $move;
    }

    public function toString(): string
    {
        return $this->move;
    }

    public function description(): string
    {
        return strtolower(str_replace('_', ' ', $this->move));
    }
}
