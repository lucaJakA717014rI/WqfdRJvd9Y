<?php
// 代码生成时间: 2025-09-17 20:13:05
// config_manager.php
use Psr\Container\ContainerInterface as Container;
use Slim\Factory\AppFactory;
use Slim\Factory\ServerRequestCreatorFactory;
use Slim\Psr7\Response;
use Exception;

// 配置文件管理器类
class ConfigManager {
    private $container;

    public function __construct(Container $container) {
        $this->container = $container;
    }

    // 获取配置值
    public function get($key) {
        if (!isset($this->container[$key])) {
            throw new Exception('Config key not found: ' . $key);
        }
        return $this->container[$key];
    }

    // 设置配置值
    public function set($key, $value) {
        $this->container[$key] = $value;
    }
}

// 创建Slim应用
$app = AppFactory::create();

// 配置容器
$container = $app->getContainer();
$container['config'] = function ($c) {
    return new ConfigManager($c);
};

// 路由：获取配置项
$app->get('/config/{key}', function ($request, $response, $args) {
    try {
        $config = $request->getAttribute('config');
        $configValue = $config->get($args['key']);
        return $response->getBody()->write(json_encode(['config' => $configValue]));
    } catch (Exception $e) {
        return $response->withStatus(400)->getBody()->write(json_encode(['error' => $e->getMessage()]));
    }
});

// 路由：设置配置项
$app->post('/config/{key}', function ($request, $response, $args) {
    try {
        $config = $request->getAttribute('config');
        $config->set($args['key'], $request->getParsedBody()['value']);
        return $response->getBody()->write(json_encode(['message' => 'Config updated']));
    } catch (Exception $e) {
        return $response->withStatus(400)->getBody()->write(json_encode(['error' => $e->getMessage()]));
    }
});

$app->run();
