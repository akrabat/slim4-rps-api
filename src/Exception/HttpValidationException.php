<?php

declare(strict_types=1);

namespace App\Exception;

use App\Model\ValidationException;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Exception\HttpBadRequestException;

final class HttpValidationException extends HttpBadRequestException
{
    private $messages;

    public static function fromvalidationexception(
        ServerRequestInterface $request,
        ValidationException $exception
    ): HttpBadRequestException {
        $e = new self($request, $exception->getMessage());
        $e->messages = $exception->getMessages();
        return $e;
    }

    public function getMessages(): array
    {
        return $this->messages;
    }
}
