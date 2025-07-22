<?php

require_once(__DIR__ . '/system/vendor/autoload.php');

if (!file_exists(__DIR__ . '/.env')) {
    throw new Exception('Arquivo .env não encontrado no projeto!');
}

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$dotenv->required([
    'DB_HOST',
    'DB_NAME',
    'DB_USER',
    'DB_PASSWORD'
])->notEmpty();

return
[
    'paths' => [
        'migrations' => '%%PHINX_CONFIG_DIR%%/system/phinx/migrations',
        'seeds' => '%%PHINX_CONFIG_DIR%%/system/phinx/seeds'
    ],
    'environments' => [
        'default_migration_table' => 'phinxlog',
        'default_environment' => 'classificadosfatec',
        'classificadosfatec' => [
            'adapter' => 'mysql',
            'host' => $_ENV['DB_HOST'],
            'name' => $_ENV['DB_NAME'],
            'user' => $_ENV['DB_USER'],
            'pass' => $_ENV['DB_PASSWORD'],
            'port' => '3306',
            'charset' => 'utf8'
        ]
    ],
    'version_order' => 'creation'
];
