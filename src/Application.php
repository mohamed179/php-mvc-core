<?php

namespace Mohamed179\Core;

use Mohamed179\Core\Logger\Logger;
use Mohamed179\Core\Database\Database;
use Mohamed179\Core\Exceptions\Exception;
use Mohamed179\Core\Logger\StreamChannel;
use Mohamed179\Core\Logger\ConsoleChannel;
use Mohamed179\Core\Controllers\Controller;
use Mohamed179\Core\Logger\ErrorLogChannel;
use Mohamed179\Core\Logger\RotatingFileChannel;

class Application
{
    public static $ROOT_DIR;
    public static $app;

    public array $config;
    public Logger $logger;
    public Session $session;
    public Request $request;
    public Response $response;
    public Router $router;
    public Controller $controller;
    public Authentication $auth;
    public Database $db;

    public function __construct(string $rootDir, array $config)
    {
        Application::$ROOT_DIR = $rootDir;
        Application::$app = $this;

        // Setting config and session
        $this->config = $config;
        $this->session = new Session();

        // Setting logger
        $this->logger = new Logger(self::$ROOT_DIR.'/runtime/logs');
        $this->logger->addChannel(new StreamChannel('default', 'default.log'));
        $this->logger->addChannel(new ConsoleChannel('console'));
        $this->logger->addChannel(new RotatingFileChannel('daily', 'daily.log'));
        $this->logger->addChannel(new ErrorLogChannel('errors'));

        // Setting request, response, router, database connnection and authentication
        $this->request = new Request();
        $this->response = new Response();
        $this->router = new Router($this->request, $this->response);
        $this->db = new Database($config['db']);
        $this->auth = new Authentication();
    }

    public function run()
    {
        try {
            echo $this->router->resolve();
        } catch(Exception $ex) {
            $this->response->setResponseCode($ex->getResponseCode());
            echo new View('errors/_error', [
                'message' => $ex->getMessage(),
                'trace' => $ex->getTraceAsString()
            ]);
            $this->logger->log('errors', Logger::LEVEL_ERROR, $ex);
        }
    }
}
