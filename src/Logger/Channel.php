<?php

namespace Mohamed179\Core\Logger;

use Monolog\Logger as MonologLogger;

abstract class Channel
{
    protected string $channelName;
    protected MonologLogger $monologLogger;
    protected bool $enabled;

    public function __construct(string $channelName)
    {
        $this->channelName = $channelName;
        $this->monologLogger = new MonologLogger($channelName);
        $this->setMonologLoggerHandlers();
        $this->enabled = true;
    }

    public abstract function setMonologLoggerHandlers();

    public function getChannelName()
    {
        return $this->channelName;
    }

    public function enable(): bool
    {
        $this->enabled = true;
        return true;
    }

    public function disable(): bool
    {
        $this->enabled = false;
        return true;
    }

    public function log(string $level, string $message): bool
    {
        if (!$this->enabled) {
            return false;
        }

        switch($level) {
            case Logger::LEVEL_DEBUG:
                $this->monologLogger->debug($message);
                return true;
            case Logger::LEVEL_INFO:
                $this->monologLogger->info($message);
                return true;
            case Logger::LEVEL_NOTICE:
                $this->monologLogger->notice($message);
                return true;
            case Logger::LEVEL_WARNING:
                $this->monologLogger->warning($message);
                return true;
            case Logger::LEVEL_ERROR:
                $this->monologLogger->error($message);
                return true;
            case Logger::LEVEL_CRITICAL:
                $this->monologLogger->critical($message);
                return true;
            case Logger::LEVEL_ALERT:
                $this->monologLogger->alert($message);
                return true;
            case Logger::LEVEL_EMERGENCY:
                $this->monologLogger->emergency($message);
                return true;
            default:
                return false;
        }
    }
}
