<?php
// 代码生成时间: 2025-10-13 20:33:37
// 使用Slim框架创建RESTful API服务
require 'vendor/autoload.php';

// 初始化Slim应用
$app = new \Slim\Slim();

// 用户行为数据存储结构
// 假设我们有一个用户行为数据数组
$userBehaviorData = [];

// 获取用户行为数据的API
$app->get('/user-behavior', function () use ($app, &$userBehaviorData) {
    // 返回用户行为数据
    $app->response()->header('Content-Type', 'application/json');
    echo json_encode($userBehaviorData);
});

// 记录用户行为的API
$app->post('/user-behavior', function () use ($app, &$userBehaviorData) {
    // 获取请求体中的数据
    $userBehavior = $app->request()->getBody();
    $userBehaviorData = json_decode($userBehavior, true);
    
    // 验证数据
    if (empty($userBehaviorData)) {
        $app->response()->status(400); // Bad Request
        echo json_encode(['error' => 'Invalid data provided']);
        return;
    }
    
    // 将用户行为数据添加到数组中
    array_push($userBehaviorData, $userBehaviorData);
    
    // 返回成功响应
    $app->response()->status(201); // Created
    echo json_encode(['message' => 'User behavior recorded successfully']);
});

// 运行Slim应用
$app->run();
