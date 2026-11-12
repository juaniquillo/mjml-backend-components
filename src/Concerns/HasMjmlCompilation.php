<?php

declare(strict_types=1);

namespace Juaniquillo\MjmlBackendComponents\Concerns;

use Juaniquillo\MjmlBackendComponents\Contracts\CompilesMjml;
use RuntimeException;

trait HasMjmlCompilation
{
    /**
     * Compile the current component tree into fully responsive production HTML.
     */
    public function renderHtml(CompilesMjml $compiler): string
    {
        // 1. Convert the underlying component array tree to a string of raw MJML markup
        $mjmlMarkup = $this->toHtml();

        // Explicit structural guard: compilation must start from the document root,
        // validated against the rendered markup so any user of this trait is covered.
        if (! str_starts_with(ltrim($mjmlMarkup), '<mjml')) {
            throw new RuntimeException(
                'MJML compilation failed: renderHtml() must be called on the root MJML document '.
                '(MjmlComponentEnum::MJML), not on a nested component.',
            );
        }

        // 2. Hand off the string to the given compiler driver
        return $compiler->compile($mjmlMarkup);
    }
}
