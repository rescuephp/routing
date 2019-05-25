<?php

namespace Rescue\Tests\Routing;

use PHPUnit\Framework\TestCase;
use Rescue\Routing\Middleware\MiddlewareStorage;
use Rescue\Routing\Middleware\MiddlewareStorageInterface;
use Rescue\Routing\RouterItem;

final class RouterItemTest extends TestCase
{
    public function testBase(): void
    {
        $middlewareStorage = new MiddlewareStorage();

        $item = new RouterItem('GET', '/', 'test', '/\/$/');
        $item->withMiddlewareStorage($middlewareStorage);

        $this->assertEquals('GET', $item->getMethod());
        $this->assertEquals('/', $item->getUri());
        $this->assertEquals([], $item->getParamsNames());
        $this->assertEquals('/\/$/', $item->getRegExUri());
        $this->assertEquals('test', $item->getHandlerClass());
        $this->assertInstanceOf(MiddlewareStorageInterface::class, $item->getMiddlewareStorage());
    }
}
