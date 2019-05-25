<?php

namespace Rescue\Routing\Middleware;

trait MiddlewareStorageKeepTrait
{
    /**
     * @var MiddlewareStorageInterface
     */
    private $middlewareStorage;

    public function getMiddlewareStorage(): MiddlewareStorageInterface
    {
        return $this->middlewareStorage;
    }

    /**
     * @param MiddlewareStorageInterface $middlewareStorage
     * @return static
     */
    public function withMiddlewareStorage(MiddlewareStorageInterface $middlewareStorage)
    {
        $this->middlewareStorage = $middlewareStorage;

        return $this;
    }
}
