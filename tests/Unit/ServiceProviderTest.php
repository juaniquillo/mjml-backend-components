<?php

declare(strict_types=1);

use Juaniquillo\MjmlBackendComponents\Builders\MjmlComponentBuilder;
use Juaniquillo\MjmlBackendComponents\Compilers\NodeProcessCompiler;
use Juaniquillo\MjmlBackendComponents\Compilers\V8JsCompiler;
use Juaniquillo\MjmlBackendComponents\Contracts\CompilesMjml;

it('binds the node compiler by default', function () {
    expect(config('mjml-backend-component.default'))->toBe('node')
        ->and(config('mjml-backend-component.drivers.node.class'))->toBe(NodeProcessCompiler::class)
        ->and(app(CompilesMjml::class))->toBeInstanceOf(NodeProcessCompiler::class);
});

it('binds the configured driver', function () {
    config()->set('mjml-backend-component.default', 'v8js');

    app()->forgetInstance(CompilesMjml::class);

    expect(app(CompilesMjml::class))->toBeInstanceOf(V8JsCompiler::class);
});

it('rejects unknown drivers', function () {
    config()->set('mjml-backend-component.default', 'missing');

    app()->forgetInstance(CompilesMjml::class);

    expect(fn () => app(CompilesMjml::class))->toThrow(RuntimeException::class, 'not configured correctly');
});

it('renders html through the bound compiler', function () {
    config()->set('mjml-backend-component.drivers.node.binary', PHP_BINARY);
    config()->set('mjml-backend-component.drivers.node.arguments', ['-r', 'echo stream_get_contents(STDIN);']);

    app()->forgetInstance(CompilesMjml::class);

    $html = MjmlComponentBuilder::text('Hi')->renderHtml();

    expect($html)->toBe('<mj-text>Hi</mj-text>');
});
