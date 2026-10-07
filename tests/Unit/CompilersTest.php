<?php

declare(strict_types=1);

use Juaniquillo\MjmlBackendComponents\Compilers\NodeProcessCompiler;
use Juaniquillo\MjmlBackendComponents\Compilers\V8JsCompiler;

it('pipes markup through the node process compiler', function () {
    $compiler = new NodeProcessCompiler([
        'binary' => PHP_BINARY,
        'arguments' => ['-r', 'echo stream_get_contents(STDIN);'],
    ]);

    expect($compiler->compile('<mjml></mjml>'))->toBe('<mjml></mjml>');
});

it('throws when the node process fails', function () {
    $compiler = new NodeProcessCompiler([
        'binary' => PHP_BINARY,
        'arguments' => ['-r', 'fwrite(STDERR, "boom"); exit(1);'],
    ]);

    expect(fn () => $compiler->compile('<mjml></mjml>'))
        ->toThrow(RuntimeException::class, 'MJML compilation failed: boom');
});

it('times out slow compilations', function () {
    $compiler = new NodeProcessCompiler([
        'binary' => PHP_BINARY,
        'arguments' => ['-r', 'sleep(5);'],
        'timeout' => 1,
    ]);

    expect(fn () => $compiler->compile('<mjml></mjml>'))
        ->toThrow(RuntimeException::class, 'MJML compilation timed out after 1 seconds');
});

it('requires the v8js extension', function () {
    if (extension_loaded('v8js')) {
        $this->markTestSkipped('v8js extension is installed.');
    }

    $compiler = new V8JsCompiler(['mjml_js_path' => 'irrelevant.js']);

    expect(fn () => $compiler->compile('<mjml></mjml>'))
        ->toThrow(RuntimeException::class, 'The V8Js PHP extension is not installed');
});

it('requires the mjml js bundle', function () {
    if (! extension_loaded('v8js')) {
        $this->markTestSkipped('v8js extension is not installed.');
    }

    $compiler = new V8JsCompiler(['mjml_js_path' => 'missing-bundle.js']);

    expect(fn () => $compiler->compile('<mjml></mjml>'))
        ->toThrow(RuntimeException::class, 'Compiled MJML JavaScript bundle not found');
});
