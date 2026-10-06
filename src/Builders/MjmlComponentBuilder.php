<?php

declare(strict_types=1);

namespace Juaniquillo\MjmlBackendComponents\Builders;

use Juaniquillo\MjmlBackendComponents\Enums\MjmlComponentEnum;
use Juaniquillo\MjmlBackendComponents\MjmlBackendComponent;

class MjmlComponentBuilder
{
    /**
     * Create a new fluent instance of an MJML component.
     */
    public static function make(MjmlComponentEnum $component): MjmlBackendComponent
    {
        return new MjmlBackendComponent($component);
    }

    /**
     * Shorthand to build a root document structure.
     *
     * @param  array<array-key, mixed>  $contents
     */
    public static function document(array $contents = []): MjmlBackendComponent
    {
        return static::make(MjmlComponentEnum::MJML)->setContents($contents);
    }

    /**
     * Shorthand to build the head configuration wrapper.
     *
     * @param  array<array-key, mixed>  $contents
     */
    public static function head(array $contents = []): MjmlBackendComponent
    {
        return static::make(MjmlComponentEnum::HEAD)->setContents($contents);
    }

    /**
     * Shorthand to set the inbox notification preview text.
     */
    public static function preview(string $text): MjmlBackendComponent
    {
        return static::make(MjmlComponentEnum::PREVIEW)->setContent($text);
    }

    /**
     * Shorthand to inject custom web fonts from Google Fonts or local assets.
     */
    public static function font(string $name, string $href): MjmlBackendComponent
    {
        return static::make(MjmlComponentEnum::FONT)
            ->setAttributes([
                'name' => $name,
                'href' => $href,
            ]);
    }

    /**
     * Shorthand to build a global attributes block to set default element stylings.
     *
     * @param  array<array-key, mixed>  $contents
     */
    public static function attributes(array $contents = []): MjmlBackendComponent
    {
        return static::make(MjmlComponentEnum::ATTRIBUTES)->setContents($contents);
    }

    /**
     * Shorthand to apply global styles to custom components or native layout blocks.
     * (Setting $inline to true injects styles straight onto elements for Outlook compliance)
     */
    public static function style(string $css, bool $inline = false): MjmlBackendComponent
    {
        $component = static::make(MjmlComponentEnum::STYLE)->setContent($css);

        if ($inline) {
            $component->setAttribute('inline', 'inline');
        }

        return $component;
    }

    /**
     * Shorthand to build a section block layout.
     *
     * @param  array<array-key, mixed>  $contents
     * @param  array<string, mixed>  $attributes
     */
    public static function section(array $contents = [], array $attributes = []): MjmlBackendComponent
    {
        return static::make(MjmlComponentEnum::SECTION)
            ->setAttributes($attributes)
            ->setContents($contents);
    }

    /**
     * Shorthand to build a text component block.
     *
     * @param  array<string, mixed>  $attributes
     */
    public static function text(string $text, array $attributes = []): MjmlBackendComponent
    {
        return static::make(MjmlComponentEnum::TEXT)
            ->setAttributes($attributes)
            ->setContent($text);
    }
}
