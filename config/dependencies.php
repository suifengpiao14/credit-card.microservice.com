<?php

// DIC configuration

// -----------------------------------------------------------------------------
// Service providers
// -----------------------------------------------------------------------------

// Api View.
$container['view'] = function ($c) {
    // Simple Content Negotiation (json and xml).
    $defaultMediaType = 'application/json';
    $outputParam = 'output';
    $checkHeader = true;

    return new \App\Renders\ApiView($defaultMediaType, $outputParam, $checkHeader);
};

// Database.
$container['db'] = function ($c) {
    $config = $c->get('settings')['db'];
    $capsule = new \Illuminate\Database\Capsule\Manager;
    $capsule->addConnection($config);

    $capsule->setAsGlobal();
    $capsule->bootEloquent();

    return $capsule;
};
$container->get('db');//实例化db

$container['cache'] = function ($c) {
    if (APP_ENV_DEV) {//开发环境使用虚拟缓存
        return new \Symfony\Component\Cache\Adapter\NullAdapter();
    }
    $config = $c->get('settings')['redis'];
    $redis = new \Redis();
    $redis->connect($config['host'], $config['port'], 2); //2秒连接不上就报错
    $config['password'] and $redis->auth($config['password']);

    return new Symfony\Component\Cache\Adapter\RedisAdapter($redis);
};

// -----------------------------------------------------------------------------
// Service factories
// -----------------------------------------------------------------------------

// Monolog.
$container['logger'] = function ($c) {
    $config = $c->get('settings')['logger'];
    $logger = new \Monolog\Logger($config['name']);
    $formatter = new \Monolog\Formatter\LineFormatter(
        "[%datetime%] [%level_name%]: %message% %context%\n",
        null,
        true,
        true
    );
    /* Log to timestamped files */
    $rotating = new \Monolog\Handler\RotatingFileHandler($config['path'], 0, \Monolog\Logger::DEBUG);
    $rotating->setFormatter($formatter);
    $logger->pushHandler($rotating);

    return $logger;
};

// -----------------------------------------------------------------------------
// Error Handlers
// -----------------------------------------------------------------------------

// Override the default Error Handler. To trap PHP Exceptions.
$container['errorHandler'] = function ($c) {
    return new \App\Handlers\ApiError($c['view'], $c['logger'], $c->get('settings')['displayErrorDetails']);
};

// Override the default error handler for PHP 7+ Throwables.
$container['phpErrorHandler'] = function ($c) {
    return new \App\Handlers\ApiPhpError($c['view'], $c['logger'], $c->get('settings')['displayErrorDetails']);
};

// Override the default 404 Not Found Handler.
$container['notFoundHandler'] = function ($c) {
    return new \App\Handlers\ApiNotFound($c['view']);
};

// Override the default 405 Not Allowed Handler
$container['notAllowedHandler'] = function ($c) {
    return new \App\Handlers\ApiNotAllowed($c['view']);
};
// add 400
$container['invalidArgumentHandler'] = function ($c) {
    return new \App\Handlers\ApiInvalidArgument($c['view']);
};

