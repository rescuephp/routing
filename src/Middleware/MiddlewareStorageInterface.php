<?php

declare(strict_types=1);

namespace Rescue\Routing\Middleware;

use Psr\Http\Server\MiddlewareInterface;

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
