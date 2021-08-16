<?php

namespace Mohamed179\Core\Logger;

use Monolog\Formatter\LineFormatter;
use Monolog\Handler\RotatingFileHandler;

class RotatingFileChannel extends FileBasedChannel
{
    public function setMonologLoggerHandlers()
    {
        $logFilePath = Logger::$LOGS_DIR.'/'.$this->logFileName;
        $rotatingFileHandler = new RotatingFileHandler($logFilePath);
        $rotatingFileHandler->setFormatter(new LineFormatter(
            null, // Format of message in log, default [%datetime%] %channel%.%level_name%: %message% %context% %extra%\n
            null, // Datetime format
            true, // allowInlineLineBreaks option, default false
            false  // ignoreEmptyContextAndExtra option, default false
        ));
        $this->monologLogger->pushHandler($rotatingFileHandler);
    }
}