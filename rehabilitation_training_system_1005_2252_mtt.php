<?php
// 代码生成时间: 2025-10-05 22:52:52
// 使用Slim框架创建康复训练系统
use Slim\App;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Exception\NotFoundException;
use DI\Container;
use Slim\Handlers\PhpError;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

// 配置容器和服务提供者
$container = new Container();

// 设置日志服务
$container["logger"] = function ($c) {
    $settings = $c->get('settings')['logger'];
    $logger = new Logger($settings['name']);
    $logger->pushHandler(new StreamHandler(\$settings['path'], $settings['level']));
    return $logger;
};

// 定义数据库服务
$container["db"] = function ($c) {
    $settings = $c->get('settings')['db'];
    $pdo = new PDO(\$settings['dsn'], \$settings['user'], \$settings['pass']);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    return \$pdo;
};

// 应用程序设置
$settings = [
    'settings' => [
        'displayErrorDetails' => true,
        'addContentLengthHeader' => false,
        'db' => [
            'dsn' => 'mysql:host=localhost;dbname=rehabilitation_training_system',
            'user' => 'root',
            'pass' => '',
        ],
        'logger' => [
            'name' => 'appLogger',
            'path' => __DIR__ . '/../../logs/app.log',
            'level' => Logger::DEBUG,
        ],
    ],
];

// 创建Slim应用程序
$app = new App($settings);
$app->add(new PhpError($container));

// 定义路由和处理函数
$app->get('/rehabilitation/session/{id}', function (Request \$request, Response \$response, array \$args) use ($container) {
    \$id = \$args['id'];
    try {
        // 从数据库获取康复训练会话信息
        \$db = \$container->get('db');
        \$stmt = \$db->prepare('SELECT * FROM rehabilitation_sessions WHERE id = ?');
        \$stmt->execute([ \$id ]);
        \$session = \$stmt->fetch();
        if (!\$session) {
            throw new NotFoundException(\$request, 'Rehabilitation session not found');
        }
        \$response->getBody()->write(json_encode(\$session));
        return \$response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(200);
    } catch (Exception \$e) {
        \$container->get('logger')->error("Error retrieving rehabilitation session: " . \$e->getMessage());
        return \$response
            ->withStatus(500)
            ->withHeader('Content-Type', 'application/json')
            ->write(json_encode(['error' => \$e->getMessage()]));
    }
});

// 运行应用程序
$app->run();