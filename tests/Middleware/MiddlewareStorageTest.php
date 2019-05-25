<?php

namespace Rescue\Tests\Http;

use PHPUnit\Framework\TestCase;
use Rescue\Http\MiddlewareInterface;
use Rescue\Http\RequestHandlerInterface;
use Rescue\Http\Response;
use Rescue\Http\ResponseInterface;
use Rescue\Http\ServerRequestInterface;
use Rescue\Routing\Middleware\MiddlewareStorage;

final class MiddlewareStorageTest extends TestCase
{
    public function testBase(): void
    {
        $middleware1 = $this->getMiddlewareClass();
        $middleware2 = $this->getMiddlewareClass();

        $storage = new MiddlewareStorage();
        $storage->withMiddleware($middleware1);
        $this->assertNotEmpty($storage->getMiddlewares());
        $storage->withMiddleware($middleware2);
        $storage->withMiddleware($middleware1);

        $middlewares = $storage->getMiddlewares();
        $this->assertNotEmpty($middlewares);

        $this->assertTrue($storage->hasMiddleware($middleware2));
        $this->assertEquals($middleware2, array_shift($middlewares));
        $storage->withoutMiddleware($middleware2);
        $this->assertEmpty($storage->getMiddlewares());

        $this->assertTrue(
            $storage->withMiddlewares([$middleware1, $middleware2])
        );

        $this->assertNotEmpty($storage->getMiddlewares());
        $this->assertTrue($storage->hasMiddleware($middleware1));
        $this->assertTrue($storage->hasMiddleware($middleware2));
    }

    private function getMiddlewareClass(): MiddlewareInterface
    {
        return new class () implements MiddlewareInterface
        {
            public function process(
                ServerRequestInterface $request,
                RequestHandlerInterface $handler
            ): ResponseInterface {
                return new Response();
            }
        };
    }

    public function testInvalidMiddlewaresArray(): void
    {
        $storage = new MiddlewareStorage();

        $this->assertFalse($storage->withMiddlewares(['a', 'b']));

    }
}
