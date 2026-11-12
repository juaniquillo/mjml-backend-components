# AGENTS.md - MJML Backend Components

Build responsive HTML emails from PHP: compose MJML trees via `MjmlComponentFactory` / `MjmlBackendComponent`, inspect raw MJML with `toHtml()`, compile to production HTML with `renderHtml($compiler)`.

- Usage and drivers: `README.md`
- Contributing gates and conventions: `CONTRIBUTING.md`
- Changelog: `CHANGELOG.md`

## Commands

- `composer test:unit`: Pest suite
- `composer analyse`: PHPStan level 7 (`src`)
- `composer lint:check`: Pint check mode (`src`, `tests`); run `vendor/bin/pint` to fix
- `composer rector:check`: Rector dry run
- `composer qa`: all of the above, in order (`qa:ci` is the CI variant running `pest --ci`)

## Gotchas

- `toHtml()` returns raw MJML markup; `renderHtml()` compiles it to HTML via the explicitly passed `CompilesMjml` driver (e.g. `new NodeProcessCompiler`).
- Base state (attributes, contents, serialization) comes from `juaniquillo/base-backend-component` traits and contracts (it never renders; all rendering lives in this package).
- Enum cases without a factory shorthand are built via `MjmlComponentFactory::make()`.
- `ext-v8js` is `suggest`-only: guard with `extension_loaded('v8js')` / `class_exists('V8Js')`, never add it to `require` or `require-dev`.
- Consumer AI resources live in `resources/boost/` (guideline + skill); keep their code snippets accurate.
- Never commit, tag, or push (the user handles all version control; leave changes uncommitted in the working tree).
