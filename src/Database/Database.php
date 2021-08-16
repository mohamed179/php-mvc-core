<?php

namespace Mohamed179\Core\Database;

use Exception;
use Mohamed179\Core\View;
use Mohamed179\Core\Application;
use Mohamed179\Core\Exceptions\DatabaseException;
use Mohamed179\Core\Logger\Logger;
use PDOException;

class Database
{
    private \PDO $pdo;
    private \PDOStatement $stmt;

    public function __construct(array $config)
    {
        $host = $config['host'];
        $port = $config['port'];
        $user = $config['user'];
        $password = $config['password'];
        $database = $config['database'];
        $options = $config['options'] ?? null;

        $dsn = "mysql:host=$host;port=$port;dbname=$database";
        $this->pdo = new \PDO($dsn, $user, $password, $options);
        $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
    }

    public function exec(string $query)
    {
        try {
            return $this->pdo->exec($query);
        } catch (PDOException $ex) {
            throw new DatabaseException($ex->getMessage(), null, $ex);
        }
    }

    public function prepare($query, array $options = [])
    {
        $this->stmt = $this->pdo->prepare($query, $options);
    }

    public function execute($params = null)
    {
        try {
            return $this->stmt->execute($params);
        } catch (PDOException $ex) {
            throw new DatabaseException($ex->getMessage(), null, $ex);
        }
    }

    public function bindParam(
        $param,
        &$var,
        $type = \PDO::PARAM_STR,
        $maxLength = null,
        $driverOptions = null
    )
    {
        return $this->stmt->bindParam($param, $var, $type, $maxLength, $driverOptions);
    }

    public function fetch(
        $mode = \PDO::FETCH_BOTH,
        $cursorOrientation = \PDO::FETCH_ORI_NEXT,
        $cursorOffset = 0
    )
    {
        return $this->stmt->fetch($mode, $cursorOrientation, $cursorOffset);
    }

    public function fetchAll($mode = \PDO::FETCH_BOTH, ...$args)
    {
        return $this->stmt->fetchAll($mode, ...$args);
    }

    public function fetchColumn($column = 0)
    {
        return $this->stmt->fetchColumn($column);
    }

    public function fetchObject($class = "stdClass", array $ctorArgs = [])
    {
        return $this->stmt->fetchObject($class, $ctorArgs);
    }

    public function lastInsertId($name = null)
    {
        return $this->pdo->lastInsertId();
    }

    protected function createMigrationsTable()
    {
        $this->exec("CREATE TABLE IF NOT EXISTS migrations (
            id INT AUTO_INCREMENT PRIMARY KEY,
            migration VARCHAR(255) NOT NULL,
            migrated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        );");
    }

    protected function getMigratedFiles()
    {
        $this->prepare("SELECT migration FROM migrations;");
        $this->execute();
        return $this->fetchAll(\PDO::FETCH_COLUMN);
    }

    protected function saveMigrations(array $migrations)
    {
        $migrations = implode(', ', array_map(function ($migration) {
            return "('$migration')";
        }, $migrations));
        $this->exec("INSERT INTO migrations (migration) VALUES $migrations;");
    }

    public function runMigrations()
    {
        // Create migrations table if not exists
        $this->createMigrationsTable();

        // Get migrated files
        $migrated = $this->getMigratedFiles();

        // Get migrations files
        $files = scandir(Application::$ROOT_DIR.'/migrations');
        $files = array_diff($files, ['.', '..']);

        // Remove migrated files from all files
        $files = array_diff($files, $migrated);

        // Run not migrated migrations
        $newMigrations = [];
        foreach ($files as $file) {
            // Check if the file is a .php file
            $pathParts = pathinfo($file);
            if ($pathParts['extension'] === 'php') {
                try {
                    include_once Application::$ROOT_DIR.'/migrations/'.$file;
                    $className = $pathParts['filename'];
                    Application::$app->logger->log('console', Logger::LEVEL_INFO, 'Applying migration: ' . $file);
                    (new $className())->up();
                    Application::$app->logger->log('console', Logger::LEVEL_INFO, 'Applied migration: ' . $file);
                    $newMigrations[] = $file;
                } catch (\Exception $ex) {
                    // Log the exception
                    Application::$app->logger->log('console', Logger::LEVEL_ERROR, $ex);
                    Application::$app->logger->log('errors', Logger::LEVEL_ERROR, $ex);
                }
            }
        }

        // Add the new migrated files to database
        if (!empty($newMigrations)) {
            $this->saveMigrations($newMigrations);
        } else {
            // Log that all migrations have been migrated
            Application::$app->logger->log('console', Logger::LEVEL_INFO, 'All migrations have been applied');
        }
    }
}
