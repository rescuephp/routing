<?php

namespace Rescue\Routing;

use Rescue\Routing\Middleware\MiddlewareStorageKeepTrait;

class RouterItem implements RouterItemInterface
{
    use MiddlewareStorageKeepTrait;

    /**
     * @var string
     */
    private $method;

    /**
     * @var string
     */
    private $uri;

    /**
     * @var string
     */
    private $handlerClass;

    /**
     * @var string
     */
    private $regExUri;

    /**
     * @var array
     */
    private $paramsNames;


    public function __construct(
        string $method,
        string $uri,
        string $handlerClass,
        string $regExUri,
        array $paramsNames = []
    ) {
        $this->method = $method;
        $this->uri = $uri;
        $this->handlerClass = $handlerClass;
        $this->regExUri = $regExUri;
        $this->paramsNames = $paramsNames;
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function getUri(): string
    {
        return $this->uri;
    }

    public function getHandlerClass(): string
    {
        return $this->handlerClass;
    }

    public function getRegExUri(): string
    {
        return $this->regExUri;
    }

    public function getParamsNames(): array
    {
        return $this->paramsNames;
    }
}
