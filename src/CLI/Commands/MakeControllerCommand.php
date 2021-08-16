<?php

namespace Mohamed179\Core\CLI\Commands;

use Mohamed179\Core\Application;
use Mohamed179\Core\Logger\Logger;

class MakeControllerCommand extends Command
{
    protected static function getName(): string
    {
        return 'make:controller';
    }

    protected static function getDescription(): string
    {
        return 'Make a controller file';
    }

    protected static function getArguments(): array
    {
        return [
            ['raw' => '<name>', 'description' => 'Controller name']
        ];
    }

    protected static function getOptions(): array
    {
        return [
            ['raw' => '-m --model [model]', 'description' => 'With model file']
        ];
    }

    protected static function getUsage(): string
    {
        return '<bold> make:controller</end> PostController -m Post<eol/>' .
               '<bold> make:controller</end> DashboardController --quiet';
    }

    protected function run(): int
    {
        if (isset($this->values()['name']) && $this->values()['name'] !== '<name>') {
            $this->createControllerFile();
        } else {
            $this->log('console', Logger::LEVEL_ALERT, 'Please provide controller name');
            return 0;
        }

        if (isset($this->values()['model'])) {
            if (is_string($this->values()['model'])) {
                $this->createModelFile();
            } else {
                $this->log('console', Logger::LEVEL_ALERT, "Can't create model because model name not provided");
            }
        }
        return 0;
    }

    private function createControllerFile()
    {
        try {
            if (!is_dir(Application::$ROOT_DIR.'/Controllers')) {
                mkdir(Application::$ROOT_DIR.'/Controllers');
            }

            $controllerFilePath = Application::$ROOT_DIR.'/Controllers/'.$this->values()['name'].'.php';
            if (!file_exists($controllerFilePath)) {
                $file = fopen($controllerFilePath, 'w+');
                fputs($file, sprintf(
"<?php

namespace App\Controllers;

use Mohamed179\Core\Controllers\Controller;

class %s extends Controller
{
    //
}",
                    $this->values()['name']
                ));
                $this->log('console', Logger::LEVEL_INFO, 'Controller created successfully');
            } else {
                $this->log('console', Logger::LEVEL_ALERT, 'Controller already exists');
            }
        } catch (\Exception $ex) {
            $this->log('console', Logger::LEVEL_ERROR, $ex);
            $this->log('errors', Logger::LEVEL_ERROR, $ex);
        }
    }

    private function createModelFile()
    {
        try {
            if (!is_dir(Application::$ROOT_DIR.'/Models')) {
                mkdir(Application::$ROOT_DIR.'/Models');
            }

            $modelFilePath = Application::$ROOT_DIR.'/Models/'.$this->values()['model'].'.php';
            if (!file_exists($modelFilePath)) {
                $file = fopen($modelFilePath, 'w+');
                fputs($file, sprintf(
"<?php

namespace App\Controllers;

use Mohamed179\Core\Database\DbModel;

class %s extends DbModel
{
    protected static string \$tableName = '';
    protected static array \$attributes = [];

    protected function labels(): array
    {
        return [];
    }

    protected function rules(): array
    {
        return [];
    }
}",
                    $this->values()['model']
                ));
                $this->log('console', Logger::LEVEL_INFO, 'Model created successfully');
            } else {
                $this->log('console', Logger::LEVEL_ALERT, 'Model already exists');
            }
        } catch (\Exception $ex) {
            $this->log('console', Logger::LEVEL_ERROR, $ex);
            $this->log('errors', Logger::LEVEL_ERROR, $ex);
        }
    }
}