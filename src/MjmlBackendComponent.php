<?php

declare(strict_types=1);

namespace Juaniquillo\MjmlBackendComponents;

use Illuminate\Contracts\Support\Htmlable;
use Juaniquillo\BackendComponents\Components\DefaultAttributeBag;
use Juaniquillo\BackendComponents\Concerns\HasContent;
use Juaniquillo\BackendComponents\Concerns\IsBackendComponent;
use Juaniquillo\BackendComponents\Contracts\AttributeBag;
use Juaniquillo\BackendComponents\Contracts\BackendComponent;
use Juaniquillo\BackendComponents\Contracts\ContentComponent;
use Juaniquillo\MjmlBackendComponents\Concerns\HasMjmlCompilation;
use Juaniquillo\MjmlBackendComponents\Enums\MjmlComponentEnum;

class MjmlBackendComponent implements BackendComponent, ContentComponent, Htmlable
{
    use HasContent;
    use HasMjmlCompilation;
    use IsBackendComponent;

    protected string $elementName;

    public function __construct(MjmlComponentEnum $component)
    {
        $this->elementName = $component->value;
    }

    public function getAttributeBag(): AttributeBag
    {
        return new DefaultAttributeBag(
            attributes: $this->getAttributes(),
            content: $this->processContent(),
        );
    }

    public function toArray(): array
    {
        return [
            'name' => $this->elementName,
            'component' => self::class,
            'attributes' => $this->getAttributes(),
            'contents' => $this->processContent()->toArray(),
        ];
    }

    /**
     * Render the component tree down to its intermediate string of raw MJML markup.
     * This fulfills Laravel's Htmlable contract.
     */
    public function toHtml(): string
    {
        $attributes = $this->renderAttributes();
        $content = $this->renderContents();

        // Special handling for the root element so we output valid <mjml> instead of <mj-mjml>
        if ($this->elementName === 'mjml') {
            return "<mjml{$attributes}>{$content}</mjml>";
        }

        return "<mj-{$this->elementName}{$attributes}>{$content}</mj-{$this->elementName}>";
    }

    /**
     * Format the array from getAttributes() into a standard XML attribute string.
     *
     * Uses ENT_COMPAT so single quotes (e.g. {{ asset('images/hero.jpg') }})
     * pass through literal without being escaped to &#039;.
     */
    protected function renderAttributes(): string
    {
        // Get the array of name => value mappings from IsBackendComponent trait
        $attributes = $this->getAttributes();

        if ($attributes === []) {
            return '';
        }

        $rendered = [];
        foreach ($attributes as $name => $value) {
            $rendered[] = $name.'="'.htmlspecialchars((string) $value, ENT_COMPAT | ENT_SUBSTITUTE, 'UTF-8', true).'"';
        }

        // Return with a leading space for clean spacing inside the tag element
        return ' '.implode(' ', $rendered);
    }

    /**
     * Format the child contents from getContents() into a single continuous string.
     */
    protected function renderContents(): string
    {
        // Get the array of mixed values (strings or nested Components) from HasContent trait
        $contents = $this->getContents();

        if ($contents === []) {
            return '';
        }

        $rendered = '';
        foreach ($contents as $item) {
            // If it's a nested component or Htmlable item, render it; otherwise, cast it to a string
            $rendered .= ($item instanceof Htmlable) ? $item->toHtml() : (string) $item;
        }

        return $rendered;
    }
}
