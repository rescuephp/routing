<?php

declare(strict_types=1);

namespace Rescue\Tests\Routing;

use PHPUnit\Framework\TestCase;
use Rescue\Routing\Middleware\MiddlewareStorage;
use Rescue\Routing\RouterItem;
use Rescue\Routing\RouterItemInterface;
use Rescue\Routing\RouterItemStorage;

final class RouterItemStorageTest extends TestCase
{
    public function testAllowedMethod(): void
    {
        $router = new RouterItemStorage(new MiddlewareStorage(), 'POST');
        $this->assertNull($router->get('handlerClass', 'handlerClass'));
        $this->assertNull($router->put('handlerClass', 'handlerClass'));
        $this->assertNull($router->delete('handlerClass', 'handlerClass'));
        $this->assertNull($router->patch('handlerClass', 'handlerClass'));
        $this->assertInstanceOf(RouterItemInterface::class, $router->post('handlerClass', 'b'));
    }

    public function testUriFormatter(): void
    {
        $router = new RouterItemStorage(new MiddlewareStorage(), 'get');
        $item = $router->get('test', 'handlerClass');
        $this->assertEquals('/test', $item->getUri());
    }

    public function testGetItems(): void
    {
        $router = new RouterItemStorage(new MiddlewareStorage(), 'get');
        $router->on('get', '/test', 'handlerClass');

        $items = $router->getItems();

        /** @var RouterItemInterface $item */
        $item = array_shift($items);

        $this->assertEquals('/test', $item->getUri());
        $this->assertEquals('GET', $item->getMethod());
    }

    public function testMethods(): void
    {
        $router = new RouterItemStorage(new MiddlewareStorage(), 'post');

        $router->get('/', 'handlerClass');
        $router->get('/test', 'handlerClass');
        $router->post('/test', 'handlerClass');
        $router->post('/', 'handlerClass');
        $router->delete('/', 'handlerClass');
        $router->delete('/test', 'handlerClass');
        $router->put('/', 'handlerClass');
        $router->put('/test', 'handlerClass');
        $router->patch('/', 'handlerClass');
        $router->patch('/test', 'handlerClass');

        $items = $router->getItems();

        $this->assertCount(2, $items);

        /** @var RouterItemInterface $item */
        $item = array_shift($items);

        $this->assertEquals('/test', $item->getUri());
        $this->assertEquals('POST', $item->getMethod());
    }

    public function testGroup(): void
    {
        $router = new RouterItemStorage(new MiddlewareStorage(), 'DELETE');

        $router->group(static function (RouterItemStorage $router) {
            $router->get('/', 'handlerClass');
            $router->get('/{id}', 'handlerClass');
            $router->post('/', 'handlerClass');
            $router->delete('/{id}', 'handlerClass');
            $router->put('/{id}', 'handlerClass');
        }, '/admin/user', new MiddlewareStorage());

        $items = $router->getItems();

        /** @var RouterItem $item */
        $item = array_shift($items);
        $this->assertEquals('/admin/user/{id}', $item->getUri());
        $this->assertEquals('DELETE', $item->getMethod());
    }

    public function testPrefixInRouteItem(): void
    {
        $router = new RouterItemStorage(new MiddlewareStorage(), 'GET');
        $router->withPrefix('/prefix');
        $router->on('get', 'test', 'handlerClass');

        $items = $router->getItems();
        /** @var RouterItemInterface $item */
        $item = array_shift($items);

        $this->assertEquals('/prefix/test', $item->getUri());
    }
}
