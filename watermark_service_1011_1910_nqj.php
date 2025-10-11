<?php
// 代码生成时间: 2025-10-11 19:10:26
require 'vendor/autoload.php';

use Slim\Factory\AppFactory;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class WatermarkService {
    // 添加数字水印到图片上
    public function addWatermark(string $imagePath, string $watermarkText, string $outputPath): bool {
        try {
            $image = imagecreatefromjpeg($imagePath);
            if ($image === false) {
                throw new Exception("Failed to create image from file: {$imagePath}");
            }

            $textColor = imagecolorallocate($image, 255, 255, 255);
            $fontSize = 5;
            imagettftext($image, $fontSize, 0, 10, 10, $textColor, 'arial.ttf', $watermarkText);

            imagejpeg($image, $outputPath);
            imagedestroy($image);

            return true;
        } catch (Exception $e) {
            // 错误处理
            error_log($e->getMessage());
            return false;
        }
    }
}

// 设置基础路径和依赖注入
$app = AppFactory::create();

// 添加水印的路由
$app->get('/add-watermark', function (Request $request, Response $response, $args) {
    $watermarkService = new WatermarkService();
    $imagePath = $args['imagePath'];
    $watermarkText = $args['watermarkText'];
    $outputPath = $args['outputPath'];

    $result = $watermarkService->addWatermark($imagePath, $watermarkText, $outputPath);

    if ($result) {
        $response->getBody()->write('Watermark added successfully');
    } else {
        $response->getBody()->write('Failed to add watermark');
    }

    return $response;
});

// 运行应用
$app->run();
