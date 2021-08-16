<?php

namespace Mohamed179\Core\CLI\Commands;

use Mohamed179\Core\Application;
use Mohamed179\Core\Logger\Logger;

class MakeMigrationCommand extends Command
{
    protected static function getName(): string
    {
        return 'make:migration';
    }

    protected static function getDescription(): string
    {
        return 'Make a migration file';
    }

    protected static function getArguments(): array
    {
        return [
            ['raw' => '<name>', 'description' => 'Migration name']
        ];
    }

    protected static function getOptions(): array
    {
        return [];
    }

    protected static function getUsage(): string
    {
        return '<bold> make:migration</end> create_posts_table</eol>' .
               '<bold> make:migration</end> add_created_at_column_to_posts_table -q';
    }

    protected function run(): int
    {
        if (isset($this->values()['name']) && $this->values()['name'] !== '<name>') {
            $this->createMigrationFile();
        } else {
            $this->log('console', Logger::LEVEL_ALERT, 'Please provide migration name');
        }
        return 0;
    }

    private function createMigrationFile()
    {
        try {
            if (!is_dir(Application::$ROOT_DIR.'/migrations')) {
                mkdir(Application::$ROOT_DIR.'/migrations');
            }

            $migrationName = 'm_'.date('Y_m_d_U').'_'.$this->values()['name'];
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