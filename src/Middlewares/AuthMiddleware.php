<?php

namespace Mohamed179\Core\Middlewares;

use Mohamed179\Core\Application;
use Mohamed179\Core\Exceptions\ForbiddenException;

class AuthMiddleware extends RouteMiddleware
{
    public function execute(): bool
    {
        if (empty($this->actions) || in_array(Application::$app->controller->getAction(), $this->actions)) {
            if (Application::$app->auth->isGuest()) {
                throw new ForbiddenException();
                return false;
            }
        }
        return true;
    }
}