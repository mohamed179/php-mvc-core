<?php

namespace Mohamed179\Core\Middlewares;

use Mohamed179\Core\Application;

class GuestMiddleware extends RouteMiddleware
{
    public function execute(): bool
    {
        if (empty($this->actions) || in_array(Application::$app->controller->getAction(), $this->actions)) {
            if (!Application::$app->auth->isGuest()) {
                Application::$app->response->redirect('/');
                return false;
            }
        }
        return true;
    }
}