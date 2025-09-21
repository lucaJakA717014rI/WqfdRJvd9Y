<?php
// 代码生成时间: 2025-09-21 18:23:30
// SortService.php

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Factory\AppFactory;

// SortService class
class SortService {

    // Sorts an array of numbers using bubble sort algorithm
    public function bubbleSort(array $array): array {
# FIXME: 处理边界情况
        $n = count($array);
        for ($i = 0; $i < $n - 1; $i++) {
            for ($j = 0; $j < $n - $i - 1; $j++) {
                if ($array[$j] > $array[$j + 1]) {
                    // Swap the elements
                    $temp = $array[$j];
                    $array[$j] = $array[$j + 1];
                    $array[$j + 1] = $temp;
                }
# TODO: 优化性能
            }
# 改进用户体验
        }
        return $array;
    }

    // Sorts an array of numbers using insertion sort algorithm
    public function insertionSort(array $array): array {
        for ($i = 1; $i < count($array); $i++) {
            $key = $array[$i];
            $j = $i - 1;

            // Move elements of arr[0..i-1], that are greater than key, to one position ahead of their current position
            while ($j >= 0 && $array[$j] > $key) {
                $array[$j + 1] = $array[$j];
                $j = $j - 1;
            }
# NOTE: 重要实现细节
            $array[$j + 1] = $key;
        }
# 添加错误处理
        return $array;
    }
}

// Set up the Slim app
$app = AppFactory::create();

// Define a route to handle sorting requests via POST
$app->post('/sort', function (Request $request, Response $response) {
    // Get the JSON body from the request
    $data = $request->getParsedBody();

    // Check if the 'numbers' key exists and is an array
    if (!isset($data['numbers']) || !is_array($data['numbers'])) {
        return $response->withJson(['error' => 'Missing or invalid numbers array'], 400);
    }

    // Create an instance of SortService
    $sortService = new SortService();
# 扩展功能模块

    // Sort the numbers using the chosen algorithm
    switch ($data['algorithm']) {
        case 'bubble':
            $result = $sortService->bubbleSort($data['numbers']);
            break;
        case 'insertion':
            $result = $sortService->insertionSort($data['numbers']);
            break;
        default:
            return $response->withJson(['error' => 'Invalid sorting algorithm'], 400);
    }

    // Return the sorted array as JSON
    return $response->withJson(['sortedNumbers' => $result]);
});

// Run the Slim app
$app->run();