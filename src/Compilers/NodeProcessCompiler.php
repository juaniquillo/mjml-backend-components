<?php

declare(strict_types=1);

namespace Juaniquillo\MjmlBackendComponents\Compilers;

use Juaniquillo\MjmlBackendComponents\Contracts\CompilesMjml;
use RuntimeException;
use Symfony\Component\Process\Exception\ProcessTimedOutException;
use Symfony\Component\Process\Process;

class NodeProcessCompiler implements CompilesMjml
{
    protected string $binary;

    /** @var list<string> */
    protected array $arguments;

    protected ?float $timeout;

    /** @param  array<string, mixed>  $config */
    public function __construct(array $config = [])
    {
        $this->binary = $config['binary'] ?? 'npx';
        $this->arguments = $config['arguments'] ?? ['mjml', '-s'];
        $this->timeout = isset($config['timeout']) ? (float) $config['timeout'] : 60.0;
    }

    public function compile(string $mjmlMarkup): string
    {
        $process = new Process([$this->binary, ...$this->arguments]);
        $process->setInput($mjmlMarkup);
        $process->setTimeout($this->timeout);

        try {
            $process->run();
        } catch (ProcessTimedOutException $e) {
            throw new RuntimeException(
                sprintf('MJML compilation timed out after %g seconds.', $this->timeout ?? 0),
                0,
                $e,
            );
        }

        if (! $process->isSuccessful()) {
            throw new RuntimeException(
                'MJML compilation failed: '.$process->getErrorOutput(),
            );
        }

        return $process->getOutput();
    }
}
