<?php
// 代码生成时间: 2025-09-19 11:59:00
// SortingService.php
// 这个类包含了一个排序服务，用于实现排序算法。

require 'vendor/autoload.php';

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class SortingService {

    // 冒泡排序算法实现
    public function bubbleSort(array $arr): array {
        for ($i = 0; $i < count($arr); $i++) {
            for ($j = 0; $j < count($arr) - $i - 1; $j++) {
                if ($arr[$j] > $arr[$j + 1]) {
                    // 交换两个元素的位置
                    $temp = $arr[$j];
                    $arr[$j] = $arr[$j + 1];
                    $arr[$j + 1] = $temp;
                }
            }
        }
        return $arr;
    }

    // 快速排序算法实现
    public function quickSort(array $arr): array {
        if (count($arr) < 2) {
            return $arr;
        }

        // 选择第一个元素作为基准
        $pivot = $arr[0];
        $left = array();
        $right = array();

        for ($i = 1; $i < count($arr); $i++) {
            if ($arr[$i] < $pivot) {
                $left[] = $arr[$i];
            } else {
                $right[] = $arr[$i];
            }
        }

        // 递归排序
        return array_merge($this->quickSort($left), array($pivot), $this->quickSort($right));
    }
}

$app = new \Slim\App();

// 定义一个路由处理排序请求
$app->post('/sort', function (Request $request, Response $response, $args) {
    $body = $request->getParsedBody();
    $arr = $body['arr'] ?? [];
    $sortType = $body['sortType'] ?? 'bubble';

    if (!is_array($arr)) {
        return $response->withStatus(400)
            ->write(json_encode(['error' => 'Invalid input. Array is required.']));
    }

    $sortingService = new SortingService();

    switch ($sortType) {
        case 'bubble':
            $sortedArr = $sortingService->bubbleSort($arr);
            break;
        case 'quick':
            $sortedArr = $sortingService->quickSort($arr);
            break;
        default:
            return $response->withStatus(400)
                ->write(json_encode(['error' => 'Invalid sort type.']));
    }

    return $response->withJson(['sorted' => $sortedArr]);
});

$app->run();