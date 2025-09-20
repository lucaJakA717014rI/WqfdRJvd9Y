<?php
// 代码生成时间: 2025-09-20 23:26:11
require 'vendor/autoload.php';

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Factory\AppFactory;

// 创建网络连接状态检查器
class NetworkConnectionChecker {
    public function checkConnection($host, $port) {
        $connection = @fsockopen($host, $port, $errno, $errstr, 10);
        if (is_resource($connection)) {
            fclose($connection);
            return true;
        } else {
            return false;
        }
    }
}

// 定义路由和逻辑
$app = AppFactory::create();

// 网络连接状态检查器路由
$app->get('/check-connection', function (Request $request, Response $response, $args) {
    $host = $request->getQueryParams()['host'] ?? '127.0.0.1';
    $port = $request->getQueryParams()['port'] ?? 80;

    try {
        $checker = new NetworkConnectionChecker();
        $connected = $checker->checkConnection($host, $port);

        if ($connected) {
            $response->getBody()->write('Connected to host: ' . $host . ' on port: ' . $port);
        } else {
            $response->getBody()->write('Failed to connect to host: ' . $host . ' on port: ' . $port);
            $response->withStatus(503);
        }
    } catch (Exception $e) {
        $response->getBody()->write('Error: ' . $e->getMessage());
        $response->withStatus(500);
    }

    return $response;
});

// 运行应用
$app->run();