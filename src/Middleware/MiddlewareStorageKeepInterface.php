<?php

namespace Rescue\Routing\Middleware;

interface MiddlewareStorageKeepInterface
{
    public function getMiddlewareStorage(): MiddlewareStorageInterface;

    /**
     * @param MiddlewareStorageInterface $middlewareStorage
     * @return static
     */
    public function withMiddlewareStorage(MiddlewareStorageInterface $middlewareStorage);
}
