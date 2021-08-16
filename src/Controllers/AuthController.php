<?php

namespace Mohamed179\Core\Controllers;

use Mohamed179\Core\Request;
use Mohamed179\Core\Controllers\Controller;
use Mohamed179\Core\Middlewares\GuestMiddleware;
use Mohamed179\Core\Response;

abstract class AuthController extends Controller
{
    public function __construct()
    {
        $this->registerMiddleware(new GuestMiddleware(['login', 'register']));
    }

    abstract public function login(Request $request, Response $response);

    abstract public function register(Request $request, Response $response);
}
