<?php
// 代码生成时间: 2025-09-16 22:47:09
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Factory\AppFactory;

// 定义购物车类
class ShoppingCart {
    private $items = [];

    public function addItem($productId, $quantity) {
        if (isset($this->items[$productId])) {
            $this->items[$productId]['quantity'] += $quantity;
        } else {
            $this->items[$productId] = ['quantity' => $quantity];
        }
    }

    public function removeItem($productId) {
        if (isset($this->items[$productId])) {
            unset($this->items[$productId]);
        }
    }

    public function getItems() {
        return $this->items;
    }
}

// 创建Slim应用
$app = AppFactory::create();

// 添加购物车商品路由
$app->post('/cart/add/{productId}', function (Request $request, Response $response, $args) {
    $productId = $args['productId'];
    $quantity = $request->getParsedBody()['quantity'] ?? 0;
    $cart = new ShoppingCart();
    try {
        $cart->addItem($productId, $quantity);
        $response->getBody()->write('Item added to cart successfully');
    } catch (Exception $e) {
        $response->getBody()->write('Error adding item to cart');
    }
    return $response->withStatus(200);
});

// 移除购物车商品路由
$app->delete('/cart/remove/{productId}', function (Request $request, Response $response, $args) {
    $productId = $args['productId'];
    $cart = new ShoppingCart();
    try {
        $cart->removeItem($productId);
        $response->getBody()->write('Item removed from cart successfully');
    } catch (Exception $e) {
        $response->getBody()->write('Error removing item from cart');
    }
    return $response->withStatus(200);
});

// 获取购物车商品路由
$app->get('/cart', function (Request $request, Response $response) {
    $cart = new ShoppingCart();
    $items = $cart->getItems();
    $response->getBody()->write(json_encode($items));
    return $response->withStatus(200);
});

// 运行Slim应用
$app->run();