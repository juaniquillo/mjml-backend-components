# Contributing

Thanks for helping out. Keep changes small and focused, and follow the checklist below before opening a pull request.

## Quality gates

```bash
composer test:unit     # Pest suite must pass
composer analyse       # PHPStan level 7, no errors
composer lint:check    # Pint check mode must pass (run vendor/bin/pint to fix)
composer rector:check  # Rector dry run must be clean
```

Or run everything at once with `composer qa`.

## Conventions

- `declare(strict_types=1)` at the top of every PHP file.
- Pest style tests (`it(...)` / `expect(...)`) under `tests/Unit`, booted through `tests/TestCase.php`.
- New enum cases work through `MjmlComponentBuilder::make()` automatically; only add a shorthand when it earns its place.
- The `v8js` driver stays optional: guard on `extension_loaded('v8js')` / `class_exists('V8Js')` and never add `ext-v8js` to `require` or `require-dev`.
