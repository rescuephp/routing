<?php

declare(strict_types=1);

namespace Rescue\Routing\Middleware;

use Psr\Http\Server\MiddlewareInterface;
use function get_class;

class MiddlewareStorage implements MiddlewareStorageInterface
{
    /**
     * @var MiddlewareInterface[]
     */
    private $middlewares;

    /**
     * MiddlewareStorage constructor.
     * @param MiddlewareInterface[] $middlewares
     */
    public function __construct(array $middlewares = [])
    {
        $this->middlewares = $middlewares;
    }

    /**
     * @inheritDoc
     */
    public function getMiddlewares(): array
    {
        return $this->middlewares;
    }

    /**
     * @inheritDoc
     */
    public function withoutMiddleware(MiddlewareInterface $middleware): MiddlewareStorageInterface
    {
        if ($this->hasMiddleware($middleware)) {
            unset($this->middlewares[get_class($middleware)]);
        }

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function hasMiddleware(MiddlewareInterface $middleware): bool
    {
        return isset($this->middlewares[get_class($middleware)]);
    }

    /**
     * @inheritDoc
     */
    public function withMiddlewares(array $middlewares): bool
    {
        foreach ($middlewares as $middleware) {
            if (!$middleware instanceof MiddlewareInterface) {
                return false;
            }

            $this->withMiddleware($middleware);
        }

        return true;
    }

    /**
     * @inheritDoc
     */
    public function withMiddleware(MiddlewareInterface $middleware): MiddlewareStorageInterface
    {
        $this->middlewares[get_class($middleware)] = $middleware;

        return $this;
    }
}
