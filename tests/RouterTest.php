<?php

declare(strict_types=1);

namespace Rescue\Tests\Routing;

use PHPUnit\Framework\TestCase;
use Rescue\Routing\Middleware\MiddlewareStorage;
use Rescue\Routing\Middleware\MiddlewareStorageInterface;
use Rescue\Routing\Router;

final class RouterTest extends TestCase
{
    public function testBase(): void
    {
        $middlewareStorage = new MiddlewareStorage();

        $item = new Router('GET', '/', 'test', []);
        $item->withMiddlewareStorage($middlewareStorage);

        $this->assertEquals('GET', $item->getMethod());
        $this->assertEquals('/', $item->getUri());
        $this->assertEquals([], $item->getParams());
        $this->assertEquals('test', $item->getHandlerClass());
        $this->assertInstanceOf(MiddlewareStorageInterface::class, $item->getMiddlewareStorage());
    }
}
