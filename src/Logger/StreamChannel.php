<?php

namespace Mohamed179\Core\Logger;

use Monolog\Formatter\LineFormatter;
use Monolog\Handler\StreamHandler;

class StreamChannel extends FileBasedChannel
{
    public function setMonologLoggerHandlers()
    {
        $logFilePath = Logger::$LOGS_DIR.'/'.$this->logFileName;
        $streamHandler = new StreamHandler($logFilePath);
        $streamHandler->setFormatter(new LineFormatter(
            null, // Format of message in log, default [%datetime%] %channel%.%level_name%: %message% %context% %extra%\n
            null, // Datetime format
            true, // allowInlineLineBreaks option, default false
            false  // ignoreEmptyContextAndExtra option, default false
        ));
        $this->monologLogger->pushHandler($streamHandler);
    }
}