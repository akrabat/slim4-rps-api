<?php

declare(strict_types=1);

namespace App\Error\Renderer;

use App\Error\ErrorCodes;
use App\Exception\HttpValidationException;
use Crell\ApiProblem\ApiProblem;
use Slim\Interfaces\ErrorRendererInterface;
use Throwable;

abstract class AbstractProblemDetailsErrorRenderer implements ErrorRendererInterface
{
    protected function createApiProblem(Throwable $exception, bool $displayErrorDetails): ApiProblem
    {
        $problem = new ApiProblem($exception->getMessage(), ErrorCodes::urlForCode($exception->getCode()));

        if ($exception instanceof HttpValidationException) {
            $problem['messages'] = $exception->getMessages();
        }

        if ($displayErrorDetails) {
            $formattedExceptions = [];
            do {
                $formattedExceptions[] = $this->formatExceptionFragment($exception);
            } while ($exception = $exception->getPrevious());
            $problem['exception'] = $formattedExceptions;
        }

        return $problem;
    }

    /**
     * @phpstan-return array<string, int|string>
     */
    protected function formatExceptionFragment(Throwable $exception): array
    {
        return [
            'type' => get_class($exception),
            'code' => $exception->getCode(),
            'message' => $exception->getMessage(),
            'file' => $exception->getFile(),
            'line' => $exception->getLine(),
        ];
    }
}
