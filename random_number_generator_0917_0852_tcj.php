<?php
// 代码生成时间: 2025-09-17 08:52:06
// 引入Slim框架
require 'vendor/autoload.php';

// 设置随机数生成器类
class RandomNumberGenerator {
    // 构造函数
    public function __construct() {
        // 这里可以初始化一些配置
    }

    // 生成随机数的方法
    public function generate(int $min, int $max): int {
        // 检查边界条件
        if ($min > $max) {
            throw new InvalidArgumentException('The minimum value cannot be greater than the maximum value.');
        }

        // 返回生成的随机数
        return rand($min, $max);
    }
}

// 实例化Slim应用
$app = \Slim\Factory\AppFactory::create();

// 定义GET路由，用于获取随机数
$app->get('/random/{min}/{max}', function ($request, $handler, $args) {
    $randomNumberGenerator = new RandomNumberGenerator();
    $min = $args['min'];
    $max = $args['max'];
    try {
        // 调用生成器生成随机数并返回响应
        $randomNumber = $randomNumberGenerator->generate($min, $max);
        $response = \Slim\Psr7\Response::create();
        $response->getBody()->write(json_encode(['randomNumber' => $randomNumber]));
        return $response->withHeader('Content-Type', 'application/json');
    } catch (InvalidArgumentException $e) {
        // 发生错误时返回错误信息
        $response = \Slim\Psr7\Response::create();
        $response->getBody()->write(json_encode(['error' => $e->getMessage()]));
        return $response->withStatus(400)->withHeader('Content-Type', 'application/json');
    }
});

// 运行应用
$app->run();