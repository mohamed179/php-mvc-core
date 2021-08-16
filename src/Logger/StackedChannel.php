<?php

namespace Mohamed179\Core\Logger;

use Monolog\Handler\Handler;

class StackedChannel extends Channel
{
    private StackedChannelBuilder $builder;

    public function __construct(string $channelName, StackedChannelBuilder $builder)
    {
        $this->builder = $builder;
        parent::__construct($channelName);
    }

    public function setMonologLoggerHandlers()
    {
        foreach ($this->builder->getHandlers() as $handler) {
            $this->monologLogger->pushHandler($handler);
        }
    }
}