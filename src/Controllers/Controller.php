<?php

namespace Mohamed179\Core\Controllers;

use Mohamed179\Core\Middlewares\Middleware;

abstract class Controller
{
    private string $action;
    private array $middlewares = [];

    public function setAction(string $action)
    {
        $this->action = $action;
    }

    public function getAction(): string
    {
        return $this->action;
    }

    public function registerMiddleware(Middleware $middleware)
    {
        $this->middlewares[] = $middleware;
    }

    public function getMiddlewares(): array
    {
        return $this->middlewares;
    }
}
