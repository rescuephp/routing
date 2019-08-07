<?php

declare(strict_types=1);

namespace Rescue\Routing;

use Rescue\Routing\Middleware\MiddlewareStorageInterface;
use Rescue\Routing\Middleware\MiddlewareStorageKeepInterface;

interface RouterStorageInterface extends MiddlewareStorageKeepInterface
{
    public function getRouter(): ?RouterInterface;

    public function on(string $method, string $uri, string $handler): ?RouterInterface;

    public function get(string $uri, string $handler): ?RouterInterface;

    public function post(string $uri, string $handler): ?RouterInterface;

    public function put(string $uri, string $handler): ?RouterInterface;

    public function patch(string $uri, string $handler): ?RouterInterface;

    public function delete(string $uri, string $handler): ?RouterInterface;

    public function withPrefix(string $prefix): RouterStorageInterface;

    public function group(
        callable $callback,
        string $prefix = null,
        MiddlewareStorageInterface $middlewareStorage = null
    ): RouterStorageInterface;
}
