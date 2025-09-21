<?php
// 代码生成时间: 2025-09-21 09:55:00
// 引入Slim框架
use Slim\Factory\AppFactory;

// 定义购物车类
class ShoppingCart {
    private $items = [];

    // 添加商品到购物车
    public function add($productId, $quantity) {
        if (!array_key_exists($productId, $this->items)) {
            $this->items[$productId] = ['quantity' => 0, 'product' => $productId];
        }
        $this->items[$productId]['quantity'] += $quantity;
    }

    // 从购物车移除商品
    public function remove($productId, $quantity) {
        if (array_key_exists($productId, $this->items)) {
            $this->items[$productId]['quantity'] -= $quantity;
            if ($this->items[$productId]['quantity'] <= 0) {
                unset($this->items[$productId]);
            }
        }
    }

    // 获取购物车中的商品
    public function getItems() {
        return $this->items;
    }
}

// 创建Slim应用
$app = AppFactory::create();

// 定义添加商品到购物车的路由
$app->post('/add-to-cart', function ($request, $response, $args) {
    $body = $request->getParsedBody();
    $productId = $body['productId'] ?? null;
    $quantity = $body['quantity'] ?? 0;

    if (!$productId || $quantity <= 0) {
        return $response->withJson(['error' => 'Invalid product ID or quantity'], 400);
    }

    $cart = new ShoppingCart();
    $cart->add($productId, $quantity);
    return $response->withJson(['message' => 'Product added to cart', 'cart' => $cart->getItems()], 200);
});

// 定义从购物车移除商品的路由
$app->post('/remove-from-cart', function ($request, $response, $args) {
    $body = $request->getParsedBody();
    $productId = $body['productId'] ?? null;
    $quantity = $body['quantity'] ?? 0;

    if (!$productId || $quantity <= 0) {
        return $response->withJson(['error' => 'Invalid product ID or quantity'], 400);
    }

    $cart = new ShoppingCart();
    $cart->remove($productId, $quantity);
    return $response->withJson(['message' => 'Product removed from cart', 'cart' => $cart->getItems()], 200);
});

// 定义获取购物车内容的路由
$app->get('/cart', function ($request, $response, $args) {
    $cart = new ShoppingCart();
    return $response->withJson($cart->getItems(), 200);
});

// 运行Slim应用
$app->run();
