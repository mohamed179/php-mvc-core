<?php

namespace Mohamed179\Core;

class Response
{
    public function setResponseCode(int $code)
    {
        http_response_code($code);
    }

    public function setResponseHeader(string $name, string $value, bool $replace = true, int $code = 0)
    {
        header("$name: $value", $replace, $code);
    }

    public function redirect(string $url)
    {
        header('Location: ' . $url);
    }
}
