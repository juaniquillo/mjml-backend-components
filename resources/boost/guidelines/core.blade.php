@php
    // Laravel Boost AI Guidelines for juaniquillo/mjml-backend-components
    // Auto-loaded when the user runs `php artisan boost:install`
@endphp
## MJML Backend Components

This package composes MJML email layouts in PHP and compiles them to responsive production HTML. It is an MJML adapter over `juaniquillo/base-backend-component` (attribute/content traits, nesting, serialization). Framework-agnostic: no Laravel integration, no Blade dependency.

### Building emails

Use `MjmlComponentFactory` with a `MjmlComponentEnum`. Shorthands exist for common elements (`document`, `head`, `preview`, `font`, `attributes`, `style`, `section`, `text`, `all`); every other enum case goes through the generic `make()` path:

```php
use Juaniquillo\MjmlBackendComponents\Factories\MjmlComponentFactory;
use Juaniquillo\MjmlBackendComponents\Enums\MjmlComponentEnum;

$email = MjmlComponentFactory::document([
    MjmlComponentFactory::head([
        MjmlComponentFactory::preview('Your weekly digest'),
    ]),
    MjmlComponentFactory::make(MjmlComponentEnum::BODY)->setContents([
        MjmlComponentFactory::section(
            [MjmlComponentFactory::text('Hello!', ['color' => '#333'])],
            ['background-color' => '#ffffff']
        ),
    ]),
]);

$mjml = $email->toHtml();                                  // raw MJML markup (Htmlable contract)
$html = $email->renderHtml(new NodeProcessCompiler); // compiled production HTML
```

Content and attributes use `setContent()` / `setContents()` and `setAttribute()` / `setAttributes()` from the base package.

### Compiler drivers

Pass a `CompilesMjml` driver to `renderHtml()` (constructed with a plain config array):

- `node`: isolated `npx mjml -s` process (`binary`, `arguments`, `timeout` keys).
- `v8js`: in-memory compilation via `ext-v8js` (`mjml_js_path` key, required); the extension is optional and never required.
- custom: implement `Juaniquillo\MjmlBackendComponents\Contracts\CompilesMjml` and pass it to `renderHtml()` the same way.
