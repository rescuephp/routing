<?php

declare(strict_types=1);

namespace Rescue\Tests\Http;

use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
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
        return new class () implements MiddlewareInterface {
            public function process(
                ServerRequestInterface $request,
                RequestHandlerInterface $handler
            ): ResponseInterface {
                return new class implements ResponseInterface {
                    public function getProtocolVersion()
                    {
                        // TODO: Implement withProtocolVersion() method.
                    }

                    public function withProtocolVersion($version)
                    {
                        // TODO: Implement withProtocolVersion() method.
                    }

                    public function getHeaders()
                    {
                        // TODO: Implement getHeaders() method.
                    }

                    public function hasHeader($name)
                    {
                        // TODO: Implement hasHeader() method.
                    }

                    public function getHeader($name)
                    {
                        // TODO: Implement getHeader() method.
                    }

                    public function getHeaderLine($name)
                    {
                        // TODO: Implement getHeaderLine() method.
                    }

                    public function withHeader($name, $value)
                    {
                        // TODO: Implement withHeader() method.
                    }

                    public function withAddedHeader($name, $value)
                    {
                        // TODO: Implement withAddedHeader() method.
                    }

                    public function withoutHeader($name)
                    {
                        // TODO: Implement withoutHeader() method.
                    }

                    public function getBody()
                    {
                        // TODO: Implement getBody() method.
                    }

                    public function withBody(StreamInterface $body)
                    {
                        // TODO: Implement withBody() method.
                    }

                    public function getStatusCode()
                    {
                        // TODO: Implement getStatusCode() method.
                    }

                    public function withStatus($code, $reasonPhrase = '')
                    {
                        // TODO: Implement withStatus() method.
                    }

                    public function getReasonPhrase()
                    {
                        // TODO: Implement getReasonPhrase() method.
                    }
                };
            }
        };
    }

    public function testInvalidMiddlewaresArray(): void
    {
        $storage = new MiddlewareStorage();

        $this->assertFalse($storage->withMiddlewares(['a', 'b']));
    }
}
