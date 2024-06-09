<?php

declare(strict_types=1);

namespace App\Error\Renderer;

use Throwable;

final class JsonProblemDetailsErrorRenderer extends AbstractProblemDetailsErrorRenderer
{
    public function __invoke(Throwable $exception, bool $displayErrorDetails): string
    {
        return $this->createApiProblem($exception, $displayErrorDetails)->asJson(true);
    }
}
