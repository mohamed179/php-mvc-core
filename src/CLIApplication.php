<?php

namespace Mohamed179\Core;

use Mohamed179\Core\Logger\Logger;
use Mohamed179\Core\Database\Database;
use Mohamed179\Core\Logger\StreamChannel;
use Mohamed179\Core\Logger\ConsoleChannel;
use Mohamed179\Core\Logger\ErrorLogChannel;
use Mohamed179\Core\Logger\RotatingFileChannel;

class CLIApplication extends Application
{
    public function __construct(string $rootDir, array $config)
    {
        Application::$ROOT_DIR = $rootDir;
        Application::$app = $this;

        // Setting config
        $this->config = $config;

        // Setting logger
        $this->logger = new Logger(self::$ROOT_DIR.'/runtime/logs');
        $this->logger->addChannel(new StreamChannel('default', 'default.log'));
        $this->logger->addChannel(new ConsoleChannel('console'));
        $this->logger->addChannel(new RotatingFileChannel('daily', 'daily.log'));
        $this->logger->addChannel(new ErrorLogChannel('errors'));

        // Setting database connnection
        $this->db = new Database($config['db']);
    }
}