<?php

declare(strict_types=1);

namespace Juaniquillo\MjmlBackendComponents\Contracts;

interface Htmlable
{
    /**
     * Render the component tree down to its intermediate string of raw MJML markup.
     */
    public function toHtml(): string;
}
