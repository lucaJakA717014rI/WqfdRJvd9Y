<?php
// 代码生成时间: 2025-09-18 13:36:20
// 使用Slim框架实现网络连接状态检查器
// 网络连接状态检查器用于检测给定的URL是否可以成功连接

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Factory\AppFactory;

// 定义一个函数用于检查网络连接状态
function checkNetworkStatus($url) {
    try {
        // 初始化cURL会话
        $ch = curl_init($url);

        // 设置cURL选项
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);

        // 执行cURL会话
        $response = curl_exec($ch);

        // 关闭cURL会话
        curl_close($ch);

        // 检查cURL执行结果
        if ($response === false) {
            throw new Exception(curl_error($ch));
        }

        // 检查HTTP响应码
        if (strpos($response, '200 OK') === false) {
            throw new Exception('Failed to connect to ' . $url);
        }

        // 返回成功信息
        return ['success' => true, 'message' => 'Successfully connected to ' . $url];
    } catch (Exception $e) {
        // 返回错误信息
        return ['success' => false, 'message' => $e->getMessage()];
    }
}

// 创建Slim应用
$app = AppFactory::create();

// 定义路由和处理函数
$app->get('/status', function (Request $request, Response $response, array $args) {
    // 获取URL参数
    $url = $request->getQueryParams()['url'] ?? '';

    // 检查URL是否为空
    if (empty($url)) {
        $response->getBody()->write('URL is required');
        return $response->withStatus(400);
    }

    // 检查网络连接状态
    $result = checkNetworkStatus($url);

    // 返回响应
    $response->getBody()->write(json_encode($result));
    return $response->withHeader('Content-Type', 'application/json');
});

// 运行Slim应用
$app->run();