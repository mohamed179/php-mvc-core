<?php

namespace Mohamed179\Core\CLI\Commands;

use Mohamed179\Core\Application;
use Mohamed179\Core\Logger\Logger;

class MakeCommandCommand extends Command
{
    protected static function getName(): string
    {
        return 'make:command';
    }

    protected static function getDescription(): string
    {
        return 'Make a command file';
    }

    protected static function getArguments(): array
    {
        return [
            ['raw' => '<name>', 'description' => 'Command name']
        ];
    }

    protected static function getOptions(): array
    {
        return [];
    }

    protected static function getUsage(): string
    {
        return '<bold> make:command</end> MyCommand<eol/>' .
               '<bold> make:command</end> MySecondCommand --quiet';
    }

    protected function run(): int
    {
        if (isset($this->values()['name']) && $this->values()['name'] !== '<name>') {
            $this->createCommandFile();
        } else {
            $this->log('console', Logger::LEVEL_ALERT, 'Please provide command name');
        }
        return 0;
    }

    private function createCommandFile()
    {
        try {
            if (!is_dir(Application::$ROOT_DIR.'/Commands')) {
                mkdir(Application::$ROOT_DIR.'/Commands');
            }

            $commandFilePath = Application::$ROOT_DIR.'/Commands/'.$this->values()['name'].'.php';
            if (!file_exists($commandFilePath)) {
                $file = fopen($commandFilePath, 'w+');
                fputs($file, sprintf(
"<?php

namespace App\Commands;

use Mohamed179\Core\CLI\Commands\Command;

class %s extends Command
{
    protected static function getName(): string
    {
        return '';
    }

    protected static function getDescription(): string
    {
        return '';
    }

    protected static function getArguments(): array
    {
        return [];
    }

    protected static function getOptions(): array
    {
        return [];
    }

    protected static function getUsage(): string
    {
        return '';
    }

    protected function run(): int
    {
        return 0;
    }
}",
                    $this->values()['name']
                ));
                $this->log('console', Logger::LEVEL_INFO, 'Command created successfully');
            } else {
                $this->log('console', Logger::LEVEL_ALERT, 'Command already exists');
            }
        } catch (\Exception $ex) {
            $this->log('console', Logger::LEVEL_ERROR, $ex);
            $this->log('errors', Logger::LEVEL_ERROR, $ex);
        }
    }
}