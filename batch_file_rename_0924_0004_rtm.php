<?php
// 代码生成时间: 2025-09-24 00:04:54
// batch_file_rename.php
// 批量文件重命名工具使用Slim框架实现

require 'vendor/autoload.php';

$app = new \Slim\App();

// POST请求处理批量文件重命名
$app->post('/rename', function ($request, $response, $args) {
    // 获取请求体中的数据
    $data = $request->getParsedBody();
    $sourceDirectory = $data['sourceDirectory'] ?? '';
    $renamePattern = $data['renamePattern'] ?? '';
    $fileList = $data['fileList'] ?? [];

    // 错误处理
    if (empty($sourceDirectory) || empty($renamePattern) || empty($fileList)) {
        return $response->withJson(['error' => 'Missing parameters'], 400);
    }

    // 执行批量文件重命名操作
    $renameSuccess = renameFiles($sourceDirectory, $renamePattern, $fileList);

    // 响应结果
    if ($renameSuccess) {
        return $response->withJson(['message' => 'Files renamed successfully'], 200);
    } else {
        return $response->withJson(['error' => 'Failed to rename files'], 500);
    }
});

// 批量文件重命名函数
function renameFiles($sourceDirectory, $renamePattern, $fileList) {
    // 检查目录存在
    if (!file_exists($sourceDirectory) || !is_dir($sourceDirectory)) {
        error_log('Source directory does not exist.');
        return false;
    }

    // 遍历文件列表进行重命名
    foreach ($fileList as $file) {
        $oldPath = realpath($sourceDirectory) . '/' . $file['oldName'];
        $newPath = realpath($sourceDirectory) . '/' . $renamePattern;

        // 检查旧文件是否存在
        if (!file_exists($oldPath)) {
            error_log('File ' . $file['oldName'] . ' does not exist.');
            continue;
        }

        // 检查文件是否可写
        if (!is_writable($oldPath)) {
            error_log('File ' . $file['oldName'] . ' is not writable.');
            continue;
        }

        // 重命名文件
        if (!rename($oldPath, $newPath)) {
            error_log('Failed to rename file ' . $file['oldName'] . ' to ' . $renamePattern);
            return false;
        }
    }

    return true;
}

// 运行应用
$app->run();