<?php

declare(strict_types=1);

namespace Juaniquillo\MjmlBackendComponents;

use Juaniquillo\BaseBackendComponent\Components\DefaultAttributeBag;
use Juaniquillo\BaseBackendComponent\Concerns\HasContent;
use Juaniquillo\BaseBackendComponent\Concerns\IsBackendComponent;
use Juaniquillo\BaseBackendComponent\Contracts\AttributeBag;
use Juaniquillo\BaseBackendComponent\Contracts\BackendComponent;
use Juaniquillo\BaseBackendComponent\Contracts\ContentComponent;
use Juaniquillo\BaseBackendComponent\Themes\GenericThemeManager;
use Juaniquillo\MjmlBackendComponents\Concerns\HasMjmlCompilation;
use Juaniquillo\MjmlBackendComponents\Contracts\Htmlable;
use Juaniquillo\MjmlBackendComponents\Enums\MjmlComponentEnum;
use RuntimeException;

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
            // MJML components carry no themes, paths, or settings. The empty
            // slots below satisfy the base BackendComponent contract shape.
            'theme' => [
                'manager' => GenericThemeManager::class,
                'themes' => [],
                'path' => '',
                'realPath' => '',
            ],
            'path' => null,
            'settings' => [],
        ];
    }

    /**
     * Render the component tree down to its intermediate string of raw MJML markup.
     * This fulfills this package's Htmlable contract.
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
            if ($item instanceof Htmlable) {
                $rendered .= $item->toHtml();
            } elseif (is_string($item) || is_int($item)) {
                $rendered .= (string) $item;
            } else {
                throw new RuntimeException(
                    'MJML rendering failed: contents must be strings, integers, or Htmlable instances.',
                );
            }
        }

        return $rendered;
    }
}
