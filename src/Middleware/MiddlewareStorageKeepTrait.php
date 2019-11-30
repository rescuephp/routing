<?php

declare(strict_types=1);

namespace Rescue\Routing\Middleware;

trait MiddlewareStorageKeepTrait
{
    private ?MiddlewareStorageInterface $middlewareStorage;

    public function getMiddlewareStorage(): MiddlewareStorageInterface
    {
        return $this->middlewareStorage;
    }

    /**
     * @param MiddlewareStorageInterface $middlewareStorage
     * @return static
     */
    public function withMiddlewareStorage(MiddlewareStorageInterface $middlewareStorage): self
    {
        $this->middlewareStorage = $middlewareStorage;

        return $this;
    }
}
