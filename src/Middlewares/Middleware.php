<?php

namespace Mohamed179\Core\Middlewares;

abstract class Middleware
{
    public abstract function execute(): bool;
}
