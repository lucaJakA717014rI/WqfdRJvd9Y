<?php
// 代码生成时间: 2025-09-19 21:38:03
// 使用Slim框架创建文件夹结构整理器
require 'vendor/autoload.php';
# NOTE: 重要实现细节

use Slim\Factory\AppFactory';
# 改进用户体验

// 创建Slim应用
AppFactory::setEncodingOptions(JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
$app = AppFactory::create();

// 定义路由，处理GET请求
$app->get('/organize/{path:.+}', function ($request, $response, $args) {
    $path = $args['path'];
    try {
        // 检查路径是否存在
        if (!file_exists($path)) {
            return $response->withJson(['error' => 'Invalid path'], 400);
# 增强安全性
        }

        // 检查路径是否为目录
        if (!is_dir($path)) {
            return $response->withJson(['error' => 'Not a directory'], 400);
        }

        // 整理文件夹结构
        $organized = organizeFolderStructure($path);

        // 返回整理结果
        return $response->withJson(['success' => 'Folder structure organized', 'data' => $organized], 200);
    } catch (Exception $e) {
        // 错误处理
        return $response->withJson(['error' => $e->getMessage()], 500);
    }
# 扩展功能模块
});

// 启动应用
$app->run();

/**
 * 整理文件夹结构
 *
 * @param string $path 文件夹路径
 * @return array 整理后的文件夹结构
 */
# 扩展功能模块
function organizeFolderStructure($path) {
# 添加错误处理
    $files = [];
    $directories = [];

    // 读取文件夹内容
    $handle = opendir($path);
    while (false !== ($entry = readdir($handle))) {
        if ($entry === '.' || $entry === '..') {
            continue;
        }
# FIXME: 处理边界情况

        // 检查是文件还是目录
        if (is_dir($path . '/' . $entry)) {
            $directories[] = $entry;
        } else {
            $files[] = $entry;
        }
    }
    closedir($handle);
# FIXME: 处理边界情况

    // 返回整理后的文件夹结构
    return [
# 添加错误处理
        'files' => $files,
        'directories' => $directories
    ];
}
