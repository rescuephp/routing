<?php

namespace Rescue\Routing\Middleware;

use Rescue\Http\MiddlewareInterface;

interface MiddlewareStorageInterface
{
    /**
     * @return MiddlewareInterface[]
     */
    public function getMiddlewares(): array;

    public function hasMiddleware(MiddlewareInterface $middleware): bool;

    /**
     * @param MiddlewareInterface[] $middlewares
     * @return bool
     */
    public function withMiddlewares(array $middlewares): bool;

    public function withMiddleware(MiddlewareInterface $middleware): MiddlewareStorageInterface;

    public function withoutMiddleware(MiddlewareInterface $middleware): MiddlewareStorageInterface;
}
