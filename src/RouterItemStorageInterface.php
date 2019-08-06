<?php

declare(strict_types=1);

namespace Rescue\Routing;

use Rescue\Routing\Middleware\MiddlewareStorageInterface;
use Rescue\Routing\Middleware\MiddlewareStorageKeepInterface;

interface RouterItemStorageInterface extends MiddlewareStorageKeepInterface
{
    /**
     * @return RouterItemInterface[]
     */
    public function getItems(): array;

    /**
     * @param string $method
     * @param string $uri
     * @param string $handler
     * @return RouterItem|null
     */
    public function on(string $method, string $uri, string $handler): ?RouterItem;

    /**
     * @param string $uri
     * @param string $handler
     * @return RouterItem|null
     */
    public function get(string $uri, string $handler): ?RouterItem;

    /**
     * @param string $uri
     * @param string $handler
     * @return RouterItem|null
     */
    public function post(string $uri, string $handler): ?RouterItem;

    /**
     * @param string $uri
     * @param string $handler
     * @return RouterItem|null
     */
    public function put(string $uri, string $handler): ?RouterItem;

    /**
     * @param string $uri
     * @param string $handler
     * @return RouterItem|null
     */
    public function patch(string $uri, string $handler): ?RouterItem;

    /**
     * @param string $uri
     * @param string $handler
     * @return RouterItem|null
     */
    public function delete(string $uri, string $handler): ?RouterItem;

    public function withPrefix(string $prefix): RouterItemStorageInterface;

    public function group(
        callable $callback,
        string $prefix = null,
        MiddlewareStorageInterface $middlewareStorage = null
    ): RouterItemStorageInterface;
}
