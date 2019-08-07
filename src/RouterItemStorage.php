<?php

declare(strict_types=1);

namespace Rescue\Routing;

use Fig\Http\Message\RequestMethodInterface;
use Rescue\Routing\Middleware\MiddlewareStorageInterface;
use Rescue\Routing\Middleware\MiddlewareStorageKeepTrait;
use function strtoupper;

class RouterItemStorage implements RouterItemStorageInterface
{
    use MiddlewareStorageKeepTrait;

    /**
     * @var RouterItemInterface[]
     */
    private $routes = [];

    /**
     * @var string
     */
    private $prefix;

    /**
     * @var string
     */
    private $requestMethod;

    public function __construct(
        MiddlewareStorageInterface $middlewareStorage,
        string $requestMethod
    ) {
        $this->middlewareStorage = $middlewareStorage;
        $this->requestMethod = strtoupper($requestMethod);
    }

    /**
     * @inheritDoc
     */
    public function group(
        callable $callback,
        string $prefix = null,
        MiddlewareStorageInterface $middlewareStorage = null
    ): RouterItemStorageInterface {
        $storage = clone $this;

        if (!empty($prefix)) {
            $storage->withPrefix($prefix);
        }

        if ($middlewareStorage !== null) {
            $middlewareStorage->withMiddlewares($this->middlewareStorage->getMiddlewares());

            $storage->withMiddlewareStorage($middlewareStorage);
        }

        $callback($storage);

        $this->routes = array_merge($this->routes, $storage->getItems());

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function withPrefix(string $prefix): RouterItemStorageInterface
    {
        $this->prefix .= $this->uriFormatter($prefix);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getItems(): array
    {
        return $this->routes;
    }

    /**
     * @inheritDoc
     */
    public function on(string $method, string $uri, string $handler): ?RouterItem
    {
        $method = strtoupper($method);

        if (!$this->methodAllowed($method)) {
            return null;
        }

        $uri = $this->uriFormatter($uri);

        if (!empty($this->prefix)) {
            $uri = $this->prefix . $uri;
        }

        $item = new RouterItem(
            $method,
            $uri,
            $handler,
            $this->convertUriParamsToRegEx($uri),
            $this->getUriParamsNames($uri)
        );

        $item->withMiddlewareStorage(clone $this->getMiddlewareStorage());

        return $this->routes[$uri] = $item;
    }

    /**
     * @inheritDoc
     */
    public function get(string $uri, string $handler): ?RouterItem
    {
        return $this->on(
            RequestMethodInterface::METHOD_GET,
            $uri,
            $handler
        );
    }

    /**
     * @inheritDoc
     */
    public function post(string $uri, string $handler): ?RouterItem
    {
        return $this->on(
            RequestMethodInterface::METHOD_POST,
            $uri,
            $handler
        );
    }

    /**
     * @inheritDoc
     */
    public function put(string $uri, string $handler): ?RouterItem
    {
        return $this->on(
            RequestMethodInterface::METHOD_PUT,
            $uri,
            $handler
        );
    }

    /**
     * @inheritDoc
     */
    public function patch(string $uri, string $handler): ?RouterItem
    {
        return $this->on(
            RequestMethodInterface::METHOD_PATCH,
            $uri,
            $handler
        );
    }

    /**
     * @inheritDoc
     */
    public function delete(string $uri, string $handler): ?RouterItem
    {
        return $this->on(
            RequestMethodInterface::METHOD_DELETE,
            $uri,
            $handler
        );
    }

    private function methodAllowed(string $method): bool
    {
        return $method === $this->requestMethod;
    }

    private function convertUriParamsToRegEx(string $uri): string
    {
        $uri = preg_replace(['/{(\w+)}/', '/\//'], ['(\w+)', '\/'], $uri);

        return "/^$uri$/";
    }

    /**
     * @param string $uri
     * @return array
     */
    private function getUriParamsNames(string $uri): array
    {
        preg_match_all('/{(\w+)}/', $uri, $matches);

        return $matches[1] ?? [];
    }

    private function uriFormatter(string $uri): string
    {
        $uri = trim($uri, " \t\n\r\0\x0B\/");

        return "/$uri";
    }
}
