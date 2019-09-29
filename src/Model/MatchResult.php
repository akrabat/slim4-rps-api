<?php

declare(strict_types=1);

namespace App\Model;

use Assert\Assertion;

final class MatchResult
{
    public const DRAW = 0;
    public const P1_WIN = 1;
    public const P2_WIN = 2;

    private $result;

    public function __construct(int $result)
    {
        Assertion::between($result, 0, 2);
        $this->result = $result;
    }

    public function toInt(): int
    {
        return $this->result;
    }

    public function is(int $result): bool
    {
        Assertion::between($result, 0, 2);

        return $result === $this->result;
    }
}
