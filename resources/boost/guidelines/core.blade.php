@php
    // Laravel Boost AI Guidelines for juaniquillo/mjml-backend-components
    // Auto-loaded when the user runs `php artisan boost:install`
@endphp
## MJML Backend Components

This package composes MJML email layouts in PHP and compiles them to responsive production HTML. It is an MJML adapter over `juaniquillo/laravel-backend-component` (attribute/content traits, nesting, serialization).

### Building emails

Use `MjmlComponentBuilder` with a `MjmlComponentEnum`. Shorthands exist for common elements (`document`, `head`, `preview`, `font`, `attributes`, `style`, `section`, `text`); every other enum case goes through the generic `make()` path:

```php
use Juaniquillo\MjmlBackendComponents\Builders\MjmlComponentBuilder;
use Juaniquillo\MjmlBackendComponents\Enums\MjmlComponentEnum;

$email = MjmlComponentBuilder::document([
    MjmlComponentBuilder::head([
        MjmlComponentBuilder::preview('Your weekly digest'),
    ]),
    MjmlComponentBuilder::make(MjmlComponentEnum::BODY)->setContents([
        MjmlComponentBuilder::section(
            [MjmlComponentBuilder::text('Hello!', ['color' => '#333'])],
            ['background-color' => '#ffffff']
        ),
    ]),
]);

$mjml = $email->toHtml();     // raw MJML markup (implements Htmlable)
$html = $email->renderHtml(); // compiled production HTML
```

Content and attributes use `setContent()` / `setContents()` and `setAttribute()` / `setAttributes()` from the base package.

### Compiler drivers

`config/mjml-backend-component.php` selects the driver via `MJML_COMPILER` (default `node`):

- `node` — isolated `npx mjml -s` process (`MJML_NODE_BINARY`).
- `v8js` — in-memory compilation via `ext-v8js` (`MJML_JS_PATH`); the extension is optional and never required.
- custom — point any driver key's `class` at your own `Juaniquillo\MjmlBackendComponents\Contracts\CompilesMjml` implementation.

Publish the config with `php artisan vendor:publish --tag=mjml-backend-component-config`.
