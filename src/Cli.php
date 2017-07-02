<?php

declare(strict_types=1);

namespace Moon\Core;

use Moon\Core\Collection\CliPipelineArrayCollection;
use Moon\Core\Collection\CliPipelineCollectionInterface;
use Moon\Core\Pipeline\CliPipeline;

class Cli
{
    /**
     * @var CliPipeline[] $cliPipelines
     */
    private $cliPipelines = [];

    /**
     * @var string
     */
    private $prefix;

    public function __construct(string $prefix = '')
    {
        $this->prefix = $prefix;
    }

    /**
     * Add a command to be handled by the application
     *
     * @param string $pattern
     * @param array $stages
     *
     * @return void
     */
    public function command(string $pattern, array $stages): void
    {
        $this->cliPipelines[] = new CliPipeline($this->prefix . $pattern, $stages);
    }

    /**
     * Return all pipelines generated by the command
     *
     * @return CliPipelineCollectionInterface
     */
    public function pipelines(): CliPipelineCollectionInterface
    {
        return new CliPipelineArrayCollection($this->cliPipelines);
    }
}