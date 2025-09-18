<?php
// 代码生成时间: 2025-09-19 02:13:48
// 使用 Slim 框架创建一个简单的网络连接状态检查器
use Slim\Factory\AppFactory;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

require __DIR__ . '/../vendor/autoload.php';

// 定义检查网络连接的函数
function checkNetworkConnection($host) {
    try {
        // 使用 fsockopen 检查网络连接
        $connection = fsockopen($host, 80, $errno, $errstr, 30);
        if (is_resource($connection)) {
            fclose($connection);
            return true;
        } else {
            return false;
        }
    } catch (Exception $e) {
        // 记录异常并返回错误信息
        error_log($e->getMessage());
        return false;
    }
}

// 创建 Slim 应用程序
$app = AppFactory::create();

// 添加路由以检查网络连接
$app->get('/check-connection/{host}', function (Request $request, Response $response, $args) {
    $host = $args['host'];
    $connectionStatus = checkNetworkConnection($host);

    if ($connectionStatus) {
        $response->getBody()->write('Connection successful to ' . $host);
    } else {
        $response->getBody()->write('Failed to connect to ' . $host);
        $response->withStatus(500);
    }

    return $response;
});

// 运行 Slim 应用程序
$app->run();

/*
 * 使用说明：
 * 通过 GET 请求访问 /check-connection/{host} 来检查指定主机的网络连接状态。
 * 例如： http://localhost:3000/check-connection/example.com
 * 如果连接成功，将返回 Connection successful to example.com
 * 如果连接失败，将返回 Failed to connect to example.com 和 HTTP 500 错误。
 */