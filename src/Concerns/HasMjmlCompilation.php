<?php

declare(strict_types=1);

namespace Juaniquillo\MjmlBackendComponents\Concerns;

use Juaniquillo\MjmlBackendComponents\Contracts\CompilesMjml;

trait HasMjmlCompilation
{
    /**
     * Compile the current component tree into fully responsive production HTML.
     */
    public function renderHtml(): string
    {
        // 1. Convert the underlying component array tree to a string of raw MJML markup
        // This utilizes your base package's HTML output generation
        $mjmlMarkup = $this->toHtml();

        // 2. Resolve the active compiler bound in Laravel's IoC container
        /** @var CompilesMjml $compiler */
        $compiler = app(CompilesMjml::class);

        // 3. Hand off the string to be executed by Node under the hood
        return $compiler->compile($mjmlMarkup);
    }
}
