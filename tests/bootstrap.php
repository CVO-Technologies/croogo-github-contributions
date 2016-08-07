<?php
// @codingStandardsIgnoreFile

use Cake\Datasource\ConnectionManager;

$findRoot = function () {
    $root = dirname(__DIR__);
    if (is_dir($root . '/vendor/cakephp/cakephp')) {
        return $root;
    }

    $root = dirname(dirname(__DIR__));
    if (is_dir($root . '/vendor/cakephp/cakephp')) {
        return $root;
    }

    $root = dirname(dirname(dirname(__DIR__)));
    if (is_dir($root . '/vendor/cakephp/cakephp')) {
        return $root;
    }
};

if (!defined('DS')) {
    define('DS', DIRECTORY_SEPARATOR);
}
define('ROOT', $findRoot());
define('VENDOR', ROOT . '/vendor/');

require ROOT . '/vendor/autoload.php';
require ROOT . '/vendor/croogo/croogo/tests/test_app/config/paths.php';
require CORE_PATH . 'config/bootstrap.php';

Cake\Core\Configure::write('App', ['namespace' => 'App']);
Cake\Core\Configure::write('debug', true);

$TMP = new \Cake\Filesystem\Folder(TMP);
$TMP->create(TMP . 'cache/models', 0777);
$TMP->create(TMP . 'cache/persistent', 0777);
$TMP->create(TMP . 'cache/views', 0777);

$cache = [
    'default' => [
        'engine' => 'File'
    ],
    '_cake_core_' => [
        'className' => 'File',
        'prefix' => 'plugin_cake_core_',
        'path' => CACHE . 'persistent/',
        'serialize' => true,
        'duration' => '+10 seconds'
    ],
    '_cake_model_' => [
        'className' => 'File',
        'prefix' => 'plugin_cake_model_',
        'path' => CACHE . 'models/',
        'serialize' => 'File',
        'duration' => '+10 seconds'
    ]
];

Cake\Cache\Cache::config($cache);
Cake\Core\Configure::write('Session', [
    'defaults' => 'php'
]);

Cake\Routing\DispatcherFactory::add('Routing');
Cake\Routing\DispatcherFactory::add('ControllerFactory');

// Ensure default test connection is defined
if (!getenv('db_class')) {
    putenv('db_class=Cake\Database\Driver\Sqlite');
    putenv('db_dsn=sqlite::memory:');
}
ConnectionManager::config('test', [
    'className' => 'Cake\Database\Connection',
    'driver' => getenv('db_class'),
    'dsn' => getenv('db_dsn'),
    'database' => getenv('db_database'),
    'username' => getenv('db_login'),
    'password' => getenv('db_password'),
    'timezone' => 'UTC'
]);
ConnectionManager::alias('test', 'default');

ConnectionManager::config('test_git_hub', [
    'className' => 'Muffin\Webservice\Connection',
    'service' => 'CvoTechnologies/GitHub.GitHub',
]);

$settingsFixture = new \Croogo\Core\Test\Fixture\SettingsFixture();
$settingsFixture->create(ConnectionManager::get('default'));
$settingsFixture->insert(ConnectionManager::get('default'));

Cake\Core\Plugin::load('CvoTechnologies/GitHubContributions', ['path' => ROOT . DS, 'autoload' => true]);
Cake\Core\Plugin::load('Croogo/Core', ['path' => CROOGO_INCLUDE_PATH . DS . 'Core' . DS, 'bootstrap' => true]);
