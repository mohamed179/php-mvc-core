<?php

namespace Mohamed179\Core\Logger;

use Monolog\Handler\StreamHandler;
use Monolog\Formatter\LineFormatter;
use Monolog\Handler\ErrorLogHandler;
use Monolog\Handler\RotatingFileHandler;

class StackedChannelBuilder
{
    private array $handlers = [];

    public function getHandlers(): array
    {
        return $this->handlers;
    }

    public function addStreamHandler(string $logFileName)
    {
        $logFilePath = Logger::$LOGS_DIR . '/' . $logFileName;
        $streamHandler = new StreamHandler($logFilePath);
        $streamHandler->setFormatter(new LineFormatter(
            null, // Format of message in log, default [%datetime%] %channel%.%level_name%: %message% %context% %extra%\n
            null, // Datetime format
            true, // allowInlineLineBreaks option, default false
            false  // ignoreEmptyContextAndExtra option, default false
        ));
        $this->handlers[] = $streamHandler;
    }

    public function addRotatingFileHandler(string $logFileName)
    {
        $logFilePath = Logger::$LOGS_DIR . '/' . $logFileName;
        $rotatingFileHandler = new RotatingFileHandler($logFilePath);
        $rotatingFileHandler->setFormatter(new LineFormatter(
            null, // Format of message in log, default [%datetime%] %channel%.%level_name%: %message% %context% %extra%\n
            null, // Datetime format
            true, // allowInlineLineBreaks option, default false
            false  // ignoreEmptyContextAndExtra option, default false
        ));
        $this->handlers[] = $rotatingFileHandler;
    }

    public function addErrorLogHandler()
    {
        $errorLogHandler = new ErrorLogHandler();
        $errorLogHandler->setFormatter(new LineFormatter(
            "%channel%.%level_name%: %message% %context% %extra%", // Format of message in log, default [%datetime%] %channel%.%level_name%: %message% %context% %extra%\n
            null, // Datetime format
            true, // allowInlineLineBreaks option, default false
            true  // ignoreEmptyContextAndExtra option, default false
        ));
        $this->handlers[] = $errorLogHandler;
    }

    public function addConsoleChannel()
    {
        $streamHandler = new StreamHandler('php://stdout');
        $streamHandler->setFormatter(new LineFormatter(
            null, // Format of message in log, default [%datetime%] %channel%.%level_name%: %message% %context% %extra%\n
            null, // Datetime format
            true, // allowInlineLineBreaks option, default false
            false  // ignoreEmptyContextAndExtra option, default false
        ));
        $this->handlers[] = $streamHandler;
    }

    public function build(string $channelName): StackedChannel
    {
        return new StackedChannel($channelName, $this);
    }
}