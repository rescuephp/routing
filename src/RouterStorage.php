<?php

declare(strict_types=1);

namespace Rescue\Routing;

use Fig\Http\Message\RequestMethodInterface;
use Rescue\Routing\Middleware\MiddlewareStorageInterface;
use Rescue\Routing\Middleware\MiddlewareStorageKeepTrait;
use function strtoupper;

class RouterStorage implements RouterStorageInterface
{
    use MiddlewareStorageKeepTrait;

    private ?RouterInterface $router;

    private string $prefix = '';

    private string $requestMethod;

    private string $requestUri;

    public function __construct(
        MiddlewareStorageInterface $middlewareStorage,
        string $requestMethod,
        string $uri
    ) {
        $this->middlewareStorage = $middlewareStorage;
        $this->requestMethod = strtoupper($requestMethod);
        $this->requestUri = $this->uriFormatter($uri);
    }

    /**
     * @inheritDoc
     */
    public function group(
        callable $callback,
        string $prefix = null,
        MiddlewareStorageInterface $middlewareStorage = null
    ): RouterStorageInterface {
        $storage = clone $this;

        if (!empty($prefix)) {
            $storage->withPrefix($prefix);
        }

        if ($middlewareStorage !== null) {
            $middlewareStorage->withMiddlewares($this->middlewareStorage->getMiddlewares());

            $storage->withMiddlewareStorage($middlewareStorage);
        }

        $callback($storage);

        if ($storage->getRouter() instanceof RouterInterface) {
            $this->router = $storage->getRouter();
        }

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function withPrefix(string $prefix): RouterStorageInterface
    {
        $this->prefix .= $this->uriFormatter($prefix);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getRouter(): ?RouterInterface
    {
        return $this->router;
    }

    /**
     * @inheritDoc
     */
    public function on(string $method, string $uri, string $handler): ?RouterInterface
    {
        $method = strtoupper($method);

        if (!$this->methodAllowed($method)) {
            return null;
        }

        $uri = $this->uriFormatter($this->prefix . $this->uriFormatter($uri));

        if ($uri === $this->requestUri) {
            $router = new Router(
                $method,
                $uri,
                $handler
            );

            $router->withMiddlewareStorage(clone $this->getMiddlewareStorage());

            return $this->router = $router;
        }

        $regEx = $this->convertUriParamsToRegEx($uri);

        if (preg_match($regEx, $this->requestUri, $matches) === 1) {
            $router = new Router(
                $method,
                $uri,
                $handler,
                $this->parseUriParams($this->getUriParamsNames($uri), $matches)
            );

            $router->withMiddlewareStorage(clone $this->getMiddlewareStorage());

            return $this->router = $router;
        }

        return null;
    }

    /**
     * @inheritDoc
     */
    public function get(string $uri, string $handler): ?RouterInterface
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
    public function post(string $uri, string $handler): ?RouterInterface
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
    public function put(string $uri, string $handler): ?RouterInterface
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
    public function patch(string $uri, string $handler): ?RouterInterface
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
    public function delete(string $uri, string $handler): ?RouterInterface
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

    private function getUriParamsNames(string $uri): array
    {
        preg_match_all('/{(\w+)}/', $uri, $matches);

        return $matches[1] ?? [];
    }

    private function uriFormatter(string $uri): string
    {
        $uri = trim($uri, " \t\n\r\0\x0B\/");

        if (empty($uri)) {
            return '/';
        }

        return "/$uri";
    }

    private function parseUriParams(array $paramsNames, array $matches): array
    {
        array_shift($matches);

        $requestParams = [];

        foreach ($paramsNames as $key => $name) {
            $requestParams[$name] = $matches[$key] ?? null;
        }

        return $requestParams;
    }
}
