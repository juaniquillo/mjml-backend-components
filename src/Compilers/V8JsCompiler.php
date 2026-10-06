<?php

declare(strict_types=1);

namespace Juaniquillo\MjmlBackendComponents\Compilers;

use Juaniquillo\MjmlBackendComponents\Contracts\CompilesMjml;
use RuntimeException;
use Throwable;
use V8Js;

class V8JsCompiler implements CompilesMjml
{
    protected string $mjmlSourcePath;

    /** @param  array<string, mixed>  $config */
    public function __construct(array $config = [])
    {
        // Path to a pre-bundled, browser/vanilla-compatible mjml.js library file
        $this->mjmlSourcePath = $config['mjml_js_path'] ?? base_path('vendor/mjml/mjml.js');
    }

    public function compile(string $mjmlMarkup): string
    {
        if (! class_exists('V8Js')) {
            throw new RuntimeException('The V8Js PHP extension is not installed on this server environment.');
        }

        if (! file_exists($this->mjmlSourcePath)) {
            throw new RuntimeException("Compiled MJML JavaScript bundle not found at: {$this->mjmlSourcePath}");
        }

        $v8 = new V8Js;

        // 1. Read the core MJML JS bundle code
        $mjmlLibSource = file_get_contents($this->mjmlSourcePath);

        // 2. Inject the markup string into the JavaScript context safely using json_encode
        $safeMarkup = json_encode($mjmlMarkup);

        // 3. Formulate the JS snippet that executes the engine
        // MJML typically exposes an 'mjml2html()' function in global/browser builds
        $jsCode = <<<JS
            {$mjmlLibSource}
            
            // Execute the library compilation
            var result = mjml2html({$safeMarkup});
            
            // Return the html property out of the result object back to PHP
            result.html;
        JS;

        try {
            // Execute inside V8 memory
            return (string) $v8->executeString($jsCode, 'mjml_compiler.js');
        } catch (Throwable $e) {
            $details = $e->getMessage();

            if (method_exists($e, 'getJsLineNumber') && method_exists($e, 'getJsFileName') && method_exists($e, 'getJsSourceLine')) {
                $details = sprintf(
                    '%s on line %d of %s. Source: [%s]',
                    $e->getMessage(),
                    $e->getJsLineNumber(),
                    $e->getJsFileName(),
                    trim($e->getJsSourceLine()),
                );
            }

            // Construct a beautiful, developer-friendly debugging report
            throw new RuntimeException("MJML V8Js Compilation Failed: {$details}", 0, $e);
        }
    }
}
