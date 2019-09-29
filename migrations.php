<?php

return [
    'name' => 'Slim4 RPS API Project Migrations',
    'migrations_namespace' => 'Migrations',
    'table_name' => 'migration_versions',
    'column_name' => 'version',
    'column_length' => 14,
    'executed_at_column_name' => 'executed_at',
    'migrations_directory' => 'db/migrations',
    'all_or_nothing' => true,
    'check_database_platform' => true,
];
