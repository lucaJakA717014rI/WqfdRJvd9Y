<?php
// 代码生成时间: 2025-09-23 00:53:42
// database_migration_tool.php
require 'vendor/autoload.php';

use Slim\Factory\AppFactory;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use DI\Container;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

// 初始化日志
$logger = new Logger('migration_tool');
$logger->pushHandler(new StreamHandler('php://stderr', Logger::WARNING));

// 创建容器
$container = new Container();

// 设置数据库配置
$container->set('settings', function () {
    return [
        'db' => [
            'host' => 'localhost',
            'user' => 'root',
            'pass' => '',
            'dbname' => 'your_database_name',
            'charset' => 'utf8',
            'driver' => 'pdo_mysql',
        ],
    ];
});

// 设置数据库连接
$container->set('db', function (Container $c) {
    $dbSettings = $c->get('settings')['db'];
    $dsn = $dbSettings['driver'] . ':host=' . $dbSettings['host'] . ';dbname=' . $dbSettings['dbname'] . ';charset=' . $dbSettings['charset'];
    $username = $dbSettings['user'];
    $password = $dbSettings['pass'];
    try {
        $pdo = new PDO($dsn, $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    } catch (PDOException $e) {
        $logger->error($e->getMessage());
        exit;
    }
});

// 创建Slim应用
AppFactory::setContainer($container);
$app = AppFactory::create();

// 迁移数据库
$app->get('/migrate', function (Request $request, Response $response) use ($container) {
    $db = $container->get('db');
    $logger = $container->get('logger');
    $migrationsPath = 'migrations/'; // 迁移文件所在目录
    
    try {
        // 获取所有迁移文件
        $migrations = glob($migrationsPath . '*.up.sql');
        
        if (!$migrations) {
            $logger->warning('No migrations found.');
            return $response->getBody()->write('No migrations found.');
        }
        
        foreach ($migrations as $migration) {
            $logger->info('Running migration: ' . basename($migration));
            $query = file_get_contents($migration);
            $db->exec($query);
        }
        
        $response->getBody()->write('Migration completed successfully.');
    } catch (Exception $e) {
        $logger->error($e->getMessage());
        $response->getBody()->write('Migration failed: ' . $e->getMessage());
    }
    return $response;
});

// 运行应用
$app->run();
