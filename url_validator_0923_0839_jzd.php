<?php
// 代码生成时间: 2025-09-23 08:39:14
// url_validator.php
// 使用PHP和SLIM框架实现URL链接有效性验证

require 'vendor/autoload.php';

// 创建一个新的Slim应用
$app = new \Slim\App();

// 添加GET路由，用于验证URL链接
$app->get('/validate-url', function ($request, \$response, \$args) {
    // 获取请求参数中的URL
    $url = $request->getParam('url');

    // 检查URL参数是否被传递
    if (!isset($url)) {
        $response->getBody()->write('Error: URL parameter is missing.');
        return $response->withStatus(400);
    }

    // 尝试解析URL以验证其是否有效
    $parsedUrl = parse_url($url);
    if (!$parsedUrl || !isset($parsedUrl['scheme']) || !isset($parsedUrl['host'])) {
        $response->getBody()->write('Error: Invalid URL format.');
        return $response->withStatus(400);
    }

    // 可选：检查URL是否可访问，如果需要
    // $isReachable = checkUrlReachability($url);
    // if (!$isReachable) {
    //     $response->getBody()->write('Error: URL is not reachable.');
    //     return $response->withStatus(503);
    // }

    // 返回验证成功的信息
    $response->getBody()->write('Success: URL is valid and reachable.');
    return $response;
});

// 可选：实现一个函数来检查URL的可达性
// function checkUrlReachability($url) {
//     // 使用cURL来检查URL是否可达
//     $ch = curl_init($url);
//     curl_setopt($ch, CURLOPT_NOBODY, true);
//     curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
//     curl_exec($ch);
//     $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
//     curl_close($ch);

//     // 如果HTTP状态码为200，则认为URL可达
//     return $statusCode === 200;
// }

// 运行Slim应用
$app->run();
