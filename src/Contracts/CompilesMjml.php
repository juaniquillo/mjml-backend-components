<?php

declare(strict_types=1);

namespace Juaniquillo\MjmlBackendComponents\Contracts;

use RuntimeException;

interface CompilesMjml
{
    /**
     * Compile an MJML string into clean email HTML.
     *
     * @throws RuntimeException
     */
    public function compile(string $mjmlMarkup): string;
}
