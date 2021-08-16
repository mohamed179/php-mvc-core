<?php

namespace Mohamed179\Core\Exceptions;

class NotFoundException extends Exception
{
    protected int $responseCode = 404;
    protected $message = 'Page Not Found';
}
