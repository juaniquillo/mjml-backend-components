<?php

declare(strict_types=1);

namespace Juaniquillo\MjmlBackendComponents\Compilers;

use Juaniquillo\MjmlBackendComponents\Contracts\CompilesMjml;
use RuntimeException;
use Symfony\Component\Process\Process;

class NodeProcessCompiler implements CompilesMjml
{
    protected string $binary;

    /** @var list<string> */
    protected array $arguments;

    /** @param  array<string, mixed>  $config */
    public function __construct(array $config = [])
    {
        $this->binary = $config['binary'] ?? 'npx';
        $this->arguments = $config['arguments'] ?? ['mjml', '-s'];
    }

    public function compile(string $mjmlMarkup): string
    {
        $process = new Process([$this->binary, ...$this->arguments]);
        $process->setInput($mjmlMarkup);
        $process->run();

        if (! $process->isSuccessful()) {
            throw new RuntimeException(
                'MJML compilation failed: '.$process->getErrorOutput(),
            );
        }

        return $process->getOutput();
    }
}
