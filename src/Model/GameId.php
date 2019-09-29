<?php

declare(strict_types=1);

namespace App\Model;

use Assert\Assertion;
use Assert\AssertionFailedException;
use Ramsey\Uuid\Uuid;

final class GameId
{
    private $id;

    /**
     * @throws AssertionFailedException
     */
    private function __construct(string $id)
    {
        Assertion::uuid($id);

        $this->id = $id;
    }

    /**
     * @throws AssertionFailedException
     */
    public static function fromUuid(): GameId
    {
        return new self(Uuid::uuid4()->toString());
    }

    /**
     * @throws AssertionFailedException
     */
    public static function fromString(string $id): GameId
    {
        return new self($id);
    }

    public function toString(): string
    {
        return $this->id;
    }
}
