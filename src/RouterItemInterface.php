<?php

declare(strict_types=1);

namespace Rescue\Routing;

use Rescue\Routing\Middleware\MiddlewareStorageKeepInterface;

interface RouterItemInterface extends MiddlewareStorageKeepInterface
{
    /**
     * Returns HTTP method
     * @return string
     */
    public function getMethod(): string;

    /**
     * Returns uri
     * @return string
     */
    public function getUri(): string;

    /**
     * Returns handler class name
     * @return string
     */
    public function getHandlerClass(): string;

    /**
     * Returns uri regular expression
     * @return string
     */
    public function getRegExUri(): string;

    /**
     * Returns params names array from uri
     * @return array
     */
    public function getParamsNames(): array;
}
