<?php

declare(strict_types=1);

namespace Rescue\Tests\Routing;

use PHPUnit\Framework\TestCase;
use Rescue\Routing\Middleware\MiddlewareStorage;
use Rescue\Routing\RouterInterface;
use Rescue\Routing\RouterStorage;

final class RouterStorageTest extends TestCase
{
    public function testAllowedMethod(): void
    {
        $router = new RouterStorage(new MiddlewareStorage(), 'POST', '/test');
        $this->assertNull($router->get('handlerClass', 'handlerClass'));
        $this->assertNull($router->put('handlerClass', 'handlerClass'));
        $this->assertNull($router->delete('handlerClass', 'handlerClass'));
        $this->assertNull($router->patch('handlerClass', 'handlerClass'));

        $this->assertInstanceOf(RouterInterface::class, $router->post('test', 'handlerClass'));
    }

    public function testUriFormatter(): void
    {
        $router = new RouterStorage(new MiddlewareStorage(), 'get', '/test');
        $item = $router->get('test', 'handlerClass');
        $this->assertEquals('/test', $item->getUri());
    }

    public function testGetItems(): void
    {
        $router = new RouterStorage(new MiddlewareStorage(), 'get', '/test/');
        $router->on('get', '/test', 'handlerClass');

        $item = $router->getRouter();

        $this->assertEquals('/test', $item->getUri());
        $this->assertEquals('GET', $item->getMethod());
    }

    public function testRegItem(): void
    {
        $router = new RouterStorage(new MiddlewareStorage(), 'get', '/test/');
        $router->on('GET', '/test', 'handlerClass');

        $item = $router->getRouter();
        $this->assertEquals('/test', $item->getUri());

        $router = new RouterStorage(new MiddlewareStorage(), 'get', '/test/');
        $router->on('GET', 'test', 'handlerClass');

        $item = $router->getRouter();
        $this->assertEquals('/test', $item->getUri());

        $router = new RouterStorage(new MiddlewareStorage(), 'get', '/test');
        $router->on('GET', '/test/', 'handlerClass');

        $item = $router->getRouter();
        $this->assertEquals('/test', $item->getUri());
    }

    public function testMethods(): void
    {
        $router = new RouterStorage(new MiddlewareStorage(), 'post', '/');

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

        $item = $router->getRouter();

        $this->assertInstanceOf(RouterInterface::class, $item);
    }

    public function testGroup(): void
    {
        $router = new RouterStorage(new MiddlewareStorage(), 'DELETE', '/admin/user/12');

        $router->group(static function (RouterStorage $router) {
            $router->get('/', 'handlerClass');
            $router->get('/{id}', 'handlerClass');
            $router->post('/', 'handlerClass');
            $router->delete('/{id}', 'handlerClass');
            $router->put('/{id}', 'handlerClass');
        }, '/admin/user', new MiddlewareStorage());

        $item = $router->getRouter();

        $this->assertEquals('/admin/user/{id}', $item->getUri());
        $this->assertEquals('DELETE', $item->getMethod());
    }

    public function testGroupOther(): void
    {
        $router = new RouterStorage(new MiddlewareStorage(), 'GET', '/admin/user/');

        $router->group(static function (RouterStorage $router) {
            $router->get('/', 'handlerClass');
            $router->get('/{id}', 'handlerClass');
            $router->post('/', 'handlerClass');
            $router->delete('/{id}', 'handlerClass');
            $router->put('/{id}', 'handlerClass');
        }, '/admin/user', new MiddlewareStorage());

        $item = $router->getRouter();

        $this->assertEquals('/admin/user', $item->getUri());
        $this->assertEquals('GET', $item->getMethod());
    }

    public function testPrefixInRouteItem(): void
    {
        $router = new RouterStorage(new MiddlewareStorage(), 'GET', '/prefix/test/');
        $router->withPrefix('/prefix');
        $router->on('get', 'test', 'handlerClass');

        $item = $router->getRouter();

        $this->assertEquals('/prefix/test', $item->getUri());
    }
}
