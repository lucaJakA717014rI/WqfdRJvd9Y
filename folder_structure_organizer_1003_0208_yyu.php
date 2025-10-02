<?php
// 代码生成时间: 2025-10-03 02:08:23
// 文件夹结构整理器使用Slim框架
require 'vendor/autoload.php';

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

$app = new \.slim\App();

// 定义路由处理函数
$app->get('/organize/{directory:[\\w\\/\\.]+}', function (Request \$request, Response \$response, array \$args) {
    \$directory = \$args['directory'];
    
    try {
        // 检查目录是否存在
        if (!is_dir(\$directory)) {
            \$error = "Directory \"\$directory\