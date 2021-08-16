<?php

namespace Mohamed179\Core\Logger;

class Logger
{
    public const LEVEL_DEBUG = 'debug';
    public const LEVEL_INFO = 'info';
    public const LEVEL_NOTICE = 'notice';
    public const LEVEL_WARNING = 'warning';
    public const LEVEL_ERROR = 'error';
    public const LEVEL_CRITICAL = 'critical';
    public const LEVEL_ALERT = 'alert';
    public const LEVEL_EMERGENCY = 'emergency';

    public static string $LOGS_DIR;

    private array $channels = [];

    public function __construct(string $logsDir)
    {
        self::$LOGS_DIR = $logsDir;
    }

    public function addChannel(Channel $channel): bool
    {
        $name = $channel->getChannelName();

        if (array_key_exists($name, $this->channels)) {
            return false;
        }

        $this->channels[$name] = $channel;
        return true;
    }

    public function enableChannel(string $channelName): bool
    {
        $channel = $this->channels[$channelName] ?? null;

        if (is_null($channel)) {
            return false;
        }

        return $channel->enable();
    }

    public function disableChannel(string $channelName): bool
    {
        $channel = $this->channels[$channelName] ?? null;

        if (is_null($channel)) {
            return false;
        }

        return $channel->disable();
    }

    public function log(string $channelName, string $level, string $message): bool
    {
        $channel = $this->channels[$channelName] ?? null;

        if (is_null($channel)) {
            return false;
        }

        return $channel->log($level, $message);
    }
}
