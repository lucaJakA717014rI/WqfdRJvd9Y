<?php
// 代码生成时间: 2025-10-10 03:18:21
// PriceCalculatorService.php
// 用于计算商品价格的服务类

use Psr\Container\ContainerInterface;
use Slim\Psr7\Request;
use Slim\Psr7\Response;
use Slim\Http\StatusCode;
use Psr\Http\Message\ResponseInterface;

class PriceCalculatorService {
    // 构造函数，依赖注入容器
    public function __construct(ContainerInterface $container) {
        $this->container = $container;
    }

    // 计算商品价格
    public function calculatePrice(array $items): float {
        // 确保items是一个数组
        if (!is_array($items)) {
            throw new InvalidArgumentException('Items must be an array');
        }

        $totalPrice = 0.0;

        // 遍历商品数组，计算总价
        foreach ($items as $item) {
            // 确保每个商品都有'price'和'quantity'键
            if (!isset($item['price']) || !isset($item['quantity'])) {
                throw new InvalidArgumentException('Each item must have price and quantity');
            }

            // 计算单个商品的价格并累加到总价中
            $totalPrice += $item['price'] * $item['quantity'];
        }

        // 返回计算后的总价
        return $totalPrice;
    }
}
