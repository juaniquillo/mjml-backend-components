# MJML Backend Components

Build responsive HTML emails from PHP. Compose MJML layouts in backend code, compile them via a JavaScript pipeline, and render standard production-ready HTML email output.

## Requirements

- PHP ^8.3
- Node.js plus the MJML CLI (`npm install -g mjml`) for the default `node` driver
- `ext-v8js` only if you opt into the `v8js` driver (suggested, not required)

## Installation

Install the package via Composer:

```bash
composer require juaniquillo/mjml-backend-components
```

## Usage

Assemble components with the fluent factory, inspect the intermediate MJML with `toHtml()`, and compile to responsive HTML with `renderHtml()` by passing a compiler driver explicitly:

```php
use Juaniquillo\MjmlBackendComponents\Compilers\NodeProcessCompiler;
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

$mjml = $email->toHtml();                          // raw MJML markup
$html = $email->renderHtml(new NodeProcessCompiler); // production HTML email
```

The factory has shorthands for common elements (`document`, `head`, `preview`, `font`, `attributes`, `style`, `section`, `text`). Any other `MjmlComponentEnum` case (e.g. `BODY`, `COLUMN`, `IMAGE`, `BUTTON`) is built through the generic `MjmlComponentFactory::make()` path with `setContents()`, `setContent()`, `setAttributes()`, and `setAttribute()`.

## Compiler drivers

Pass a `CompilesMjml` driver to `renderHtml()`. The two bundled drivers take plain config arrays:

| Driver | Class | Config keys |
| --- | --- | --- |
| `node` | `NodeProcessCompiler` | `binary` (default `npx`), `arguments` (default `['mjml', '-s']`), `timeout` (default `60.0`) |
| `v8js` | `V8JsCompiler` | `mjml_js_path` (required path to a bundled `mjml.js`), in-memory compilation via `ext-v8js` |

Custom drivers implement `Juaniquillo\MjmlBackendComponents\Contracts\CompilesMjml::compile(string $mjmlMarkup): string` and are passed to `renderHtml()` the same way.

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
