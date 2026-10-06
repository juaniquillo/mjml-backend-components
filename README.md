# MJML Backend Components

Build responsive HTML emails from PHP. Compose MJML layouts in backend code, compile them via a JavaScript pipeline, and render standard production-ready HTML email output.

## Requirements

- PHP ^8.3
- Laravel 12 or 13
- Node.js plus the MJML CLI (`npm install -g mjml`) for the default `node` driver
- `ext-v8js` only if you opt into the `v8js` driver (suggested, not required)

## Installation

Install the package via Composer:

```bash
composer require juaniquillo/mjml-backend-components
```

Publish the configuration file using:

```bash
php artisan vendor:publish --tag=mjml-backend-component-config
```

> [!TIP]
> #### Laravel Boost Skill
> If you use [Laravel Boost](https://github.com/laravel/boost), install the AI skill:
> ```bash
> php artisan boost:add-skill https://github.com/juaniquillo/mjml-backend-components
> ```

## Usage

Assemble components with the fluent builder, inspect the intermediate MJML with `toHtml()`, and compile to responsive HTML with `renderHtml()`:

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

$mjml = $email->toHtml();      // raw MJML markup
$html = $email->renderHtml();  // production HTML email
```

The builder has shorthands for common elements (`document`, `head`, `preview`, `font`, `attributes`, `style`, `section`, `text`). Any other `MjmlComponentEnum` case (e.g. `BODY`, `COLUMN`, `IMAGE`, `BUTTON`) is built through the generic `MjmlComponentBuilder::make()` path with `setContents()`, `setContent()`, `setAttributes()`, and `setAttribute()`.

## Compiler drivers

`config/mjml-backend-component.php` selects the driver via `MJML_COMPILER` (default `node`):

| Driver | Config key | Environment |
| --- | --- | --- |
| `node` | `drivers.node` | `MJML_NODE_BINARY` (default `npx`), runs `mjml -s` as an isolated process |
| `v8js` | `drivers.v8js` | `MJML_JS_PATH` (path to a bundled `mjml.js`), in-memory compilation via `ext-v8js` |
| custom | any key | point `class` at your own `CompilesMjml` implementation |

## Testing

```bash
composer test:unit     # Pest suite
composer analyse       # PHPStan
composer lint:check    # Pint (check mode)
composer rector:check  # Rector (dry run)
composer qa            # all of the above
```

## Contributing

Please read [CONTRIBUTING.md](CONTRIBUTING.md) before submitting a pull request.

## License

MIT. See [LICENSE.md](LICENSE.md).
