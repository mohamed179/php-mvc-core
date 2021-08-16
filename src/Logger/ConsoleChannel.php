<?php

namespace Mohamed179\Core\Logger;

use Monolog\Handler\StreamHandler;
use Monolog\Formatter\LineFormatter;

class ConsoleChannel extends Channel
{
    public function setMonologLoggerHandlers()
    {
        $streamHandler = new StreamHandler('php://stdout');
        $streamHandler->setFormatter(new LineFormatter(
            null, // Format of message in log, default [%datetime%] %channel%.%level_name%: %message% %context% %extra%\n
            null, // Datetime format
            true, // allowInlineLineBreaks option, default false
            false  // ignoreEmptyContextAndExtra option, default false
        ));
        $this->monologLogger->pushHandler($streamHandler);
    }
}