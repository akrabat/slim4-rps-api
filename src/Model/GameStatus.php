<?php

declare(strict_types=1);

namespace App\Model;

use Assert\Assertion;

final class GameStatus
{
    public const CREATED     = 'CREATED';
    public const IN_PROGRESS = 'IN_PROGRESS';
    public const COMPLETE    = 'COMPLETE';

    public static $validStatuses = ['CREATED', 'IN_PROGRESS', 'COMPLETE'];
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
            self::CREATED     => 'Game not started',
            self::IN_PROGRESS => 'Game in progress',
            self::COMPLETE    => 'Game complete',
        ];

        return $descriptions[$this->status];
    }

    public function is(string $status): bool
    {
        Assertion::choice($status, self::$validStatuses);

        return $status === $this->status;
    }
}
