# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

### Added

- `MjmlBackendComponent` for composing MJML trees in PHP with raw-markup (`toHtml`) and compiled-HTML (`renderHtml`) output.
- `MjmlComponentEnum` covering document, body, and head elements.
- `MjmlComponentBuilder` with fluent shorthands plus a generic `make()` path for every enum case.
- Compiler drivers behind the `CompilesMjml` contract: `NodeProcessCompiler` (`npx mjml -s` by default) and `V8JsCompiler` (opt-in via `ext-v8js`).
- Publishable `mjml-backend-component` config with per-driver settings.
- Pest test suite covering rendering, builders, drivers, and the service provider.
