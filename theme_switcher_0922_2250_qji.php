<?php
// 代码生成时间: 2025-09-22 22:50:51
// 使用Composer引入Slim框架
require 'vendor/autoload.php';

// 创建一个新的Slim应用
$app = new \Slim\App();

// 定义路由，用于获取当前主题和切换主题
$app->get('/theme', 'getTheme');
$app->post('/theme', 'switchTheme');

// 定义中间件来处理请求
$app->add(function ($request, $response, $next) {
    $response->getBody()->write("Hello, World!");
    return $next($request, $response);
});

// 定义函数来获取当前主题
function getTheme($request, $response, $args) {
    // 从会话中获取主题
    $theme = isset($_SESSION['theme']) ? $_SESSION['theme'] : 'default';
    // 返回当前主题
    $response->getBody()->write("Current theme: " . $theme);
    return $response;
}

// 定义函数来切换主题
function switchTheme($request, $response, $args) {
    try {
        // 获取请求体中的新主题
        $body = json_decode($request->getBody(), true);
        if (!isset($body['theme'])) {
            throw new \Exception('Theme not provided');
        }
        // 验证新主题
        $newTheme = $body['theme'];
        if (!in_array($newTheme, ['default', 'dark', 'light'])) {
            throw new \Exception('Invalid theme');
        }
        // 将新主题保存到会话中
        $_SESSION['theme'] = $newTheme;
        // 返回成功消息
        $response->getBody()->write("Theme switched to: " . $newTheme);
    } catch (\Exception $e) {
        // 处理错误并返回错误消息
        $response->getBody()->write("Error: " . $e->getMessage());
    }
    return $response;
}

// 运行应用
$app->run();