<?php

namespace Mohamed179\Core\CLI\Commands;

use Ahc\Cli\Input\Command as BaseCommand;
use Mohamed179\Core\Application;

abstract class Command extends BaseCommand
{
    protected static abstract function getName(): string;
    protected static abstract function getDescription(): string;
    protected static abstract function getArguments(): array;
    protected static abstract function getOptions(): array;
    protected static abstract function getUsage(): string;

    public function __construct()
    {
        parent::__construct(static::getName(), static::getDescription());

        foreach (static::getArguments() as $argument) {
            $raw = $argument['raw'];
            $description = $argument['description'] ?? '';
            $default = $argument['raw'] ?? null;
            $this->argument($raw, $description, $default);
        }

        $options = static::getOptions();
        $options[] = [
            'raw' => '-q --quiet',
            'description' => 'Do not output any message',
            'filter' => 'boolval',
            'default' => false
        ];
        foreach ($options as $option) {
            $raw = $option['raw'];
            $description = $option['description'] ?? '';
            $filter = $option['filter'] ?? null;
            $default = $option['default'] ?? null;
            $this->option($raw, $description, $filter, $default);
        }

        $this->usage(static::getUsage());
    }

    protected abstract function run(): int;

    public function execute()
    {
        if (isset($this->values()['quiet']) && $this->values()['quiet']) {
            Application::$app->logger->disableChannel('console');
        }
        return $this->run();
    }

    protected function log(string $channelName, string $level, string $message)
    {
        Application::$app->logger->log($channelName, $level, $message);
    }
}