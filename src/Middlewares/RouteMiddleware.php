<?php

namespace Mohamed179\Core\Middlewares;

abstract class RouteMiddleware extends Middleware
{
    protected array $actions;

    public function __construct(array $actions = [])
    {
        $this->actions = $actions;
    }
}