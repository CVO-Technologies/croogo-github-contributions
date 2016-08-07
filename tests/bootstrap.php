<?php
// @codingStandardsIgnoreFile

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
//define('APP_DIR', 'App');
//define('WEBROOT_DIR', 'webroot');
//define('APP', ROOT . '/tests/App/');
//define('CONFIG', ROOT . '/tests/config/');
//define('WWW_ROOT', ROOT . DS . WEBROOT_DIR . DS);
//define('TESTS', ROOT . DS . 'tests' . DS);
//define('TMP', ROOT . DS . 'tmp' . DS);
//define('LOGS', TMP . 'logs' . DS);
//define('CACHE', TMP . 'cache' . DS);
//define('CAKE_CORE_INCLUDE_PATH', ROOT . '/vendor/cakephp/cakephp');
//define('CROOGO_INCLUDE_PATH', ROOT . '/vendor/croogo/croogo');
//define('CORE_PATH', CAKE_CORE_INCLUDE_PATH . DS);
//define('CAKE', CORE_PATH . 'src' . DS);

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
if (!getenv('db_dsn')) {
    putenv('db_dsn=sqlite:///:memory:');
}

Cake\Datasource\ConnectionManager::config('test', [
    'url' => getenv('db_dsn'),
    'timezone' => 'UTC'
]);
Cake\Datasource\ConnectionManager::alias('test', 'default');

Cake\Datasource\ConnectionManager::config('test_git_hub', [
    'className' => 'Muffin\Webservice\Connection',
    'service' => 'CvoTechnologies/GitHub.GitHub',
]);

$settingsFixture = new \Croogo\Core\Test\Fixture\SettingsFixture();
$settingsFixture->create(\Cake\Datasource\ConnectionManager::get('default'));
$settingsFixture->insert(\Cake\Datasource\ConnectionManager::get('default'));

Cake\Core\Plugin::load('CvoTechnologies/GitHubContributions', ['path' => ROOT . DS, 'autoload' => true]);
Cake\Core\Plugin::load('Croogo/Core', ['path' => CROOGO_INCLUDE_PATH . DS . 'Core' . DS, 'bootstrap' => true]);
