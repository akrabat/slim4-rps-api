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

    /**
     * @var string[]
     */
    public static array $validMoves = ['NOT_PLAYED', 'ROCK', 'PAPER', 'SCISSORS'];

    /**
     * @var string[]
     */
    public static array $validNextMoves = ['ROCK', 'PAPER', 'SCISSORS'];

    /**
     * @throws ValidationException
     */
    public function __construct(private string $move)
    {
        $this->move = strtoupper($move);
        try {
            Assertion::choice($this->move, self::$validMoves);
        } catch (AssertionFailedException $e) {
            throw new ValidationException("$this->move is invalid", $e->getCode(), $e);
        }
    }

    public function toString(): string
    {
        return $this->move;
    }

    public function description(): string
    {
        return ucwords(strtolower(str_replace('_', ' ', $this->move)));
    }
}
