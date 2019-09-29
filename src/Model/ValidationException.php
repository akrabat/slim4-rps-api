<?php

declare(strict_types=1);

namespace App\Model;

use RuntimeException;

final class ValidationException extends RuntimeException
{
    private $messages;

    public static function withMessages(array $messages): ValidationException
    {
        $e = new ValidationException("Validation failure");
        $e->messages = $messages;
        return $e;
    }

    public function getMessages(): array
    {
        return $this->messages;
    }
}
