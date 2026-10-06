# AGENTS.md - MJML Backend Components

Build responsive HTML emails from PHP: compose MJML trees via `MjmlComponentBuilder` / `MjmlBackendComponent`, inspect raw MJML with `toHtml()`, compile to production HTML with `renderHtml()`.

- Usage and drivers: `README.md`
- Contributing gates and conventions: `CONTRIBUTING.md`
- Changelog: `CHANGELOG.md`

## Commands

- `composer test:unit` — Pest suite (Orchestra Testbench)
- `composer analyse` — PHPStan level 7 (`src`, `config`)
- `composer lint:check` — Pint check mode (`src`, `config`, `tests`); run `vendor/bin/pint` to fix
- `composer rector:check` — Rector dry run
- `composer qa` — all of the above, in order

## Gotchas

- `toHtml()` returns raw MJML markup; `renderHtml()` compiles it to HTML via the bound `CompilesMjml` driver.
- Tests boot through `tests/TestCase.php`, which registers the base backend-component provider first — keep that order so helpers like `isComponent()` exist.
- Enum cases without a builder shorthand are built via `MjmlComponentBuilder::make()`.
- `ext-v8js` is `suggest`-only: guard with `extension_loaded('v8js')` / `class_exists('V8Js')`, never add it to `require` or `require-dev`.
- Consumer AI resources live in `resources/boost/` (guideline + skill); keep their code snippets accurate.
- Never commit, tag, or push — the user handles all version control; leave changes uncommitted in the working tree.
