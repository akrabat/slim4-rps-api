<?php

return [
    'table_storage' => [
        'table_name' => 'doctrine_migration_versions',
        'version_column_name' => 'version',
        'version_column_length' => 191,
        'executed_at_column_name' => 'executed_at',
        'execution_time_column_name' => 'execution_time',
    ],

    'migrations_paths' => [
        'Migrations' => 'db/migrations',
    ],

    'all_or_nothing' => true,
    'transactional' => true,
    'check_database_platform' => true,
    'organize_migrations' => 'none',
    'connection' => null,
    'em' => null,
];

//return [
//    'name' => 'Slim4 RPS API Project Migrations',
//    'migrations_namespace' => 'Migrations',
//    'table_name' => 'migration_versions',
//    'column_name' => 'version',
//    'column_length' => 14,
//    'executed_at_column_name' => 'executed_at',
//    'migrations_directory' => 'db/migrations',
//    'all_or_nothing' => true,
//    'check_database_platform' => true,
//];
