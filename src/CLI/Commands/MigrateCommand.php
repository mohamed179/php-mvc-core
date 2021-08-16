<?php

namespace Mohamed179\Core\CLI\Commands;

use Mohamed179\Core\Application;

class MigrateCommand extends Command
{
    protected static function getName(): string
    {
        return 'migrate';
    }

    protected static function getDescription(): string
    {
        return 'Run migrations';
    }

    protected static function getArguments(): array
    {
        return [];
    }

    protected static function getOptions(): array
    {
        return [
            ['raw' => '--fresh', 'description' => 'Migrate all migartion on fresh database'],
            ['raw' => '-r --rollback [number]', 'description' => 'Rollback some migrations', 'intval', 1],
        ];
    }

    protected static function getUsage(): string
    {
        return '<bold> migrate</end> --fresh --quiet';
    }

    protected function run(): int
    {
        Application::$app->db->runMigrations();
        return 0;
    }
}