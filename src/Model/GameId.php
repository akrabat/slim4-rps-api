<?php

declare(strict_types=1);

namespace App\Model;

use Assert\Assertion;
use Ramsey\Uuid\Uuid;

final class GameId
{
    private $id;

    private function __construct(string $id)
    {
        Assertion::uuid($id);

        $this->id = $id;
    }

    /**
     * @throws \Exception
     */
    public static function fromUuid(): GameId
    {
        return new self(Uuid::uuid4()->toString());
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
