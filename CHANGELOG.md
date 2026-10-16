# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

### Added

- `MjmlComponentEnum` is now a string-backed enum (values are the MJML tag suffix: `mjml`, `head`, `body`, `section`, `column`, `group`, `text`, `image`, `button`, `divider`, `spacer`, `hero`, `raw`, `attributes`, `all`, `selector`, `class`, `style`, `font`, `title`, `preview`).
- `MjmlComponentEnum::MJML_CLASS` (= `'class'`) — renders `<mj-class>`.
- `MjmlComponentBuilder::all()` shorthand — `MjmlComponentBuilder::all(['font-family' => 'Inter'])` → `<mj-all font-family="Inter">`.
- Single-quote-preserving attribute escaping: `e()` uses `ENT_COMPAT` so `{{ asset('images/hero.jpg') }}` passes through literal without being escaped to `&#039;`.
- `MjmlComponentEnum` cases now support `->value`, `->name`, `from()`, `tryFrom()`, and `json_encode()`.

### Changed

- `MjmlBackendComponent::__construct()` now accepts only `MjmlComponentEnum` (no more string workaround). Uses `$component->value` for the element name.
- Attribute escaping changed from `ENT_QUOTES` to `ENT_COMPAT` — single quotes (`'`) no longer become `&#039;`.
- `renderAttributes()` interpolation bug fixed — `htmlspecialchars()` is now properly called via concatenation instead of inside `{$...}`.

### Deprecated

- Direct use of string component names in `new MjmlBackendComponent('...')` — use the enum cases instead.
