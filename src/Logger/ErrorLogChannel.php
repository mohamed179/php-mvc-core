<?php

namespace Mohamed179\Core\Logger;

use Monolog\Formatter\LineFormatter;
use Monolog\Handler\ErrorLogHandler;

class ErrorLogChannel extends Channel
{
    public function setMonologLoggerHandlers()
    {
        $errorLogHandler = new ErrorLogHandler();
        $errorLogHandler->setFormatter(new LineFormatter(
            "%channel%.%level_name%: %message% %context% %extra%", // Format of message in log, default [%datetime%] %channel%.%level_name%: %message% %context% %extra%\n
            null, // Datetime format
            true, // allowInlineLineBreaks option, default false
            true  // ignoreEmptyContextAndExtra option, default false
        ));
        $this->monologLogger->pushHandler($errorLogHandler);
    }
}