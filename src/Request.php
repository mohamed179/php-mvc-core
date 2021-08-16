<?php

namespace Mohamed179\Core;

class Request
{
    public function getRequestMethod()
    {
        return strtolower($_SERVER['REQUEST_METHOD']);
    }

    public function isGet(): bool
    {
        return $this->getRequestMethod() == 'get';
    }

    public function isPost(): bool
    {
        return $this->getRequestMethod() == 'post';
    }

    public function getRequestURI()
    {
        return $_SERVER['REQUEST_URI'];
    }

    public function getRequestRoute()
    {
        $uri = $this->getRequestURI();
        return preg_replace('/\?(.*)/', '', $uri);
    }

    public function getRequestHeader(string $name)
    {
        return getallheaders()[$name] ?? null;
    }

    public function getRequestBody()
    {
        $body = [];
        if ($this->isGet()) {
            foreach ($_GET as $key => $value) {
                $body[$key] = htmlspecialchars($value);
            }
        } else if ($this->isPost()) {
            foreach ($_POST as $key => $value) {
                $body[$key] = htmlspecialchars($value);
            }
        }
        return $body;
    }
}
