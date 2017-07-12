<?php

declare(strict_types=1);

namespace Moon\Core;

use Moon\Core\Collection\HttpPipelineArrayCollection;
use Moon\Core\Collection\HttpPipelineCollectionInterface;
use Moon\Core\Pipeline\HttpPipeline;

class Router
{
    /**
     * @var HttpPipelineArrayCollection $httpPipelinesCollection
     */
    private $httpPipelinesCollection;

    /**
     * @var string $prefix
     */
    private $prefix;

    /**
     * @var callable|string|HttpPipeline|array $routerStages
     */
    private $routerStages;

    /**
     * Router constructor.
     *
     * @param string $prefix
     * @param callable|string|HttpPipeline|array $routerStages
     */
    public function __construct(string $prefix = '', $routerStages = null)
    {
        $this->prefix = $prefix;
        $this->httpPipelinesCollection = new HttpPipelineArrayCollection();
        $this->routerStages = $routerStages;
    }

    /**
     * Add a 'GET' route to be handled by the application
     *
     * @param string $pattern
     * @param callable|string|HttpPipeline|array $stages $stages
     *
     * @return void
     */
    public function get(string $pattern, $stages): void
    {
        $pipeline = new HttpPipeline('GET', $this->prefix . $pattern, $this->routerStages);
        $pipeline->pipe($stages);
        $this->httpPipelinesCollection->add($pipeline);

    }

    /**
     * Add a 'POST' route to be handled by the application
     *
     * @param string $pattern
     * @param callable|string|HttpPipeline|array $stages $stages
     *
     * @return void
     */
    public function post(string $pattern, $stages): void
    {
        $pipeline = new HttpPipeline('POST', $this->prefix . $pattern, $this->routerStages);
        $pipeline->pipe($stages);
        $this->httpPipelinesCollection->add($pipeline);
    }

    /**
     * Add a 'PUT' route to be handled by the application
     *
     * @param string $pattern
     * @param callable|string|HttpPipeline|array $stages $stages
     *
     * @return void
     */
    public function put(string $pattern, $stages): void
    {
        $pipeline = new HttpPipeline('PUT', $this->prefix . $pattern, $this->routerStages);
        $pipeline->pipe($stages);
        $this->httpPipelinesCollection->add($pipeline);
    }

    /**
     * Add a 'PATCH' route to be handled by the application
     *
     * @param string $pattern
     * @param callable|string|HttpPipeline|array $stages $stages
     *
     * @return void
     */
    public function patch(string $pattern, $stages): void
    {
        $pipeline = new HttpPipeline('PATCH', $this->prefix . $pattern, $this->routerStages);
        $pipeline->pipe($stages);
        $this->httpPipelinesCollection->add($pipeline);
    }

    /**
     * Add a 'DELETE' route to be handled by the application
     *
     * @param string $pattern
     * @param callable|string|HttpPipeline|array $stages $stages
     *
     * @return void
     */
    public function delete(string $pattern, $stages): void
    {
        $pipeline = new HttpPipeline('DELETE', $this->prefix . $pattern, $this->routerStages);
        $pipeline->pipe($stages);
        $this->httpPipelinesCollection->add($pipeline);
    }

    /**
     * Add a 'OPTIONS' route to be handled by the application
     *
     * @param string $pattern
     * @param callable|string|HttpPipeline|array $stages $stages
     *
     * @return void
     */
    public function options(string $pattern, $stages): void
    {
        $pipeline = new HttpPipeline('OPTIONS', $this->prefix . $pattern, $this->routerStages);
        $pipeline->pipe($stages);
        $this->httpPipelinesCollection->add($pipeline);
    }

    /**
     * Add a 'HEAD' route to be handled by the application
     *
     * @param string $pattern
     * @param callable|string|HttpPipeline|array $stages $stages
     *
     * @return void
     */
    public function head(string $pattern, $stages): void
    {
        $pipeline = new HttpPipeline('HEAD', $this->prefix . $pattern, $this->routerStages);
        $pipeline->pipe($stages);
        $this->httpPipelinesCollection->add($pipeline);
    }

    /**
     * Add multiple verb to a pattern and stages
     *
     * @param string $pattern
     * @param array $verbs
     * @param callable|string|HttpPipeline|array $stages $stages
     *
     * @return void
     *
     * @throws \Moon\Core\Exception\InvalidArgumentException
     */
    public function map(string $pattern, array $verbs, $stages): void
    {
        $pipeline = new HttpPipeline($verbs, $this->prefix . $pattern, $this->routerStages);
        $pipeline->pipe($stages);
        $this->httpPipelinesCollection->add($pipeline);
    }

    /**
     * Return all pipelines generated by the router
     *
     * @return HttpPipelineCollectionInterface
     */
    public function pipelines(): HttpPipelineCollectionInterface
    {
        return $this->httpPipelinesCollection;
    }
}