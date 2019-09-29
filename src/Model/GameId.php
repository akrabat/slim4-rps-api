<?php

declare(strict_types=1);

namespace App\Model;

use Assert\Assertion;
use Ramsey\Uuid\UuidInterface;

final class GameId
{
    private $id;

    private function __construct(string $id)
    {
        Assertion::uuid($id);

        $this->id = $id;
    }

    public static function fromUuid(UuidInterface $id): GameId
    {
        return new self($id->toString());
    }

    public static function fromString(string $id): GameId
    {
        return new self($id);
    }

    public function toString(): string
    {
        return $this->id;
    }
}
