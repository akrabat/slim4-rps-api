<?php

declare(strict_types=1);

namespace App\Error\Renderer;

use Throwable;

final class XmlProblemDetailsErrorRenderer extends AbstractProblemDetailsErrorRenderer
{
    public function __invoke(Throwable $exception, bool $displayErrorDetails): string
    {
        $problem = $this->createApiProblem($exception, $displayErrorDetails);
        return $problem->asJson(true);
    }
}
