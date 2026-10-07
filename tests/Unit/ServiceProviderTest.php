<?php

declare(strict_types=1);

use Juaniquillo\MjmlBackendComponents\Builders\MjmlComponentBuilder;
use Juaniquillo\MjmlBackendComponents\Compilers\NodeProcessCompiler;
use Juaniquillo\MjmlBackendComponents\Compilers\V8JsCompiler;
use Juaniquillo\MjmlBackendComponents\Contracts\CompilesMjml;

it('binds the node compiler by default', function () {
    expect(config('mjml-backend-components.default'))->toBe('node')
        ->and(config('mjml-backend-components.drivers.node.class'))->toBe(NodeProcessCompiler::class)
        ->and(app(CompilesMjml::class))->toBeInstanceOf(NodeProcessCompiler::class);
});

it('binds the configured driver', function () {
    config()->set('mjml-backend-components.default', 'v8js');

    app()->forgetInstance(CompilesMjml::class);

    expect(app(CompilesMjml::class))->toBeInstanceOf(V8JsCompiler::class);
});

it('rejects unknown drivers', function () {
    config()->set('mjml-backend-components.default', 'missing');

    app()->forgetInstance(CompilesMjml::class);

    expect(fn () => app(CompilesMjml::class))->toThrow(RuntimeException::class, 'not configured correctly');
});

it('renders html through the bound compiler', function () {
    config()->set('mjml-backend-components.drivers.node.binary', PHP_BINARY);
    config()->set('mjml-backend-components.drivers.node.arguments', ['-r', 'echo stream_get_contents(STDIN);']);

    app()->forgetInstance(CompilesMjml::class);

    $html = MjmlComponentBuilder::document([
        MjmlComponentBuilder::text('Hi'),
    ])->renderHtml();

    expect($html)->toBe('<mjml><mj-text>Hi</mj-text></mjml>');
});

it('rejects renderHtml on nested components', function () {
    expect(fn () => MjmlComponentBuilder::text('Hi')->renderHtml())
        ->toThrow(RuntimeException::class, 'must be called on the root MJML document');
});
