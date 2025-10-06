<?php
// 代码生成时间: 2025-10-07 02:39:25
// 数据脱敏工具
require 'vendor/autoload.php';

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Factory\AppFactory;

// 创建应用实例
AppFactory::setCodingStylePsr4();
AppFactory::define(App::class);
$app = App::create();

// 脱敏类型定义
const PHONE_MASK = '/^1[34578]\d{9}$/';
const EMAIL_MASK = '/^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/';

// 数据脱敏处理函数
function maskData($data, $type) {
    if ($type === 'phone' && preg_match(PHONE_MASK, $data)) {
        return preg_replace('/(\d{3})\d{4}(\d{4})/', '$1****$2', $data);
    } elseif ($type === 'email' && preg_match(EMAIL_MASK, $data)) {
        return preg_replace('/(\w{1,2})\w+([-+.]\w+)*@(\w+)([-.]\w+)*\.\w+([-.]\w+)*/', '$1****@$3.****', $data);
    } else {
        return $data;
    }
}

// POST路由处理数据脱敏
$app->post('/mask', function (Request $request, Response $response, array $args) {
    $data = $request->getParsedBody();
    $type = $data['type'] ?? '';
    $toMask = $data['data'] ?? '';

    if (empty($toMask) || empty($type)) {
        $response->getBody()->write(json_encode(['error' => 'Missing data or type']));
        return $response->withStatus(400);
    }

    try {
        $maskedData = maskData($toMask, $type);
        $response->getBody()->write(json_encode(['masked_data' => $maskedData]));
        return $response->withStatus(200);
    } catch (Exception $e) {
        $response->getBody()->write(json_encode(['error' => $e->getMessage()]));
        return $response->withStatus(500);
    }
});

// 运行应用
$app->run();
