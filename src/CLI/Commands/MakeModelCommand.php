<?php

namespace Mohamed179\Core\CLI\Commands;

use Mohamed179\Core\Application;
use Mohamed179\Core\Logger\Logger;

class MakeModelCommand extends Command
{
    protected static function getName(): string
    {
        return 'make:model';
    }

    protected static function getDescription(): string
    {
        return 'Make a model file';
    }

    protected static function getArguments(): array
    {
        return [
            ['raw' => '<name>', 'description' => 'Model name']
        ];
    }

    protected static function getOptions(): array
    {
        return [
            ['raw' => '-m --migration [migration]', 'description' => 'With migration file']
        ];
    }

    protected static function getUsage(): string
    {
        return '<bold> make:model</end> Post -m create_posts_table<eol/>' .
               '<bold> make:model</end> Comment --quiet';
    }

    protected function run(): int
    {
        if (isset($this->values()['name']) && $this->values()['name'] !== '<name>') {
            $this->createModelFile();
        } else {
            $this->log('console', Logger::LEVEL_ALERT, 'Please provide model name');
            return 0;
        }

        if (isset($this->values()['migration'])) {
            if (is_string($this->values()['migration'])) {
                $this->createMigrationFile();
            } else {
                $this->log('console', Logger::LEVEL_ALERT, "Can't create migration because migration name not provided");
            }
        }
        return 0;
    }

    private function createModelFile()
    {
        try {
            if (!is_dir(Application::$ROOT_DIR.'/Models')) {
                mkdir(Application::$ROOT_DIR.'/Models');
            }

            $modelFilePath = Application::$ROOT_DIR.'/Models/'.$this->values()['name'].'.php';
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
                    $this->values()['name']
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

    private function createMigrationFile()
    {
        try {
            if (!is_dir(Application::$ROOT_DIR.'/migrations')) {
                mkdir(Application::$ROOT_DIR.'/migrations');
            }

            $migrationName = 'm_'.date('Y_m_d_U').'_'.$this->values()['migration'];
            $migrationFilePath = Application::$ROOT_DIR.'/migrations/'.$migrationName.'.php';
            if (!file_exists($migrationFilePath)) {
                $file = fopen($migrationFilePath, 'w+');
                fputs($file, sprintf(
"<?php

class %s
{
    public function up()
    {
        //
    }

    public function down()
    {
        //
    }
}",
                    $migrationName
                ));
                $this->log('console', Logger::LEVEL_INFO, 'Migration created successfully');
            } else {
                $this->log('console', Logger::LEVEL_ALERT, 'Migration already exists');
            }
        } catch (\Exception $ex) {
            $this->log('console', Logger::LEVEL_ERROR, $ex);
            $this->log('errors', Logger::LEVEL_ERROR, $ex);
        }
    }
}