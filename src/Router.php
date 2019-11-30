<?php

declare(strict_types=1);

namespace Rescue\Routing;

use Rescue\Routing\Middleware\MiddlewareStorageKeepTrait;

class Router implements RouterInterface
{
    use MiddlewareStorageKeepTrait;

    private string $method;

    private string $uri;

    private string $handlerClass;

    private string $regExUri;

    private array $params;

    public function __construct(
        string $method,
        string $uri,
        string $handlerClass,
        array $params = []
    ) {
        $this->method = $method;
        $this->uri = $uri;
        $this->handlerClass = $handlerClass;
        $this->params = $params;
    }

    /**
     * @inheritDoc
     */
    public function getMethod(): string
    {
        return $this->method;
    }

    /**
     * @inheritDoc
     */
    public function getUri(): string
    {
        return $this->uri;
    }

    /**
     * @inheritDoc
     */
    public function getHandlerClass(): string
    {
        return $this->handlerClass;
    }

    /**
     * @inheritDoc
     */
    public function getParams(): array
    {
        return $this->params;
    }
}
